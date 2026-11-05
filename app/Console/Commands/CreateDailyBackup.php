<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateDailyBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'إنشاء نسخة احتياطية يومية من قاعدة البيانات';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->info('بدء إنشاء النسخة الاحتياطية...');

            // Read database credentials from .env
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST', '127.0.0.1');
            $port = env('DB_PORT', '3306');

            $filename = 'daily_backup_' . date('Y-m-d_H-i-s') . '.sql';
            $backupPath = storage_path('app/backups');

            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            $outputFile = $backupPath . '/' . $filename;

            // Find mysqldump
            $mysqldumpPath = $this->findMysqldump();

            if ($mysqldumpPath) {
                // Build command with or without password
                if (empty($password)) {
                    $command = sprintf(
                        '"%s" --host=%s --port=%s --user=%s %s > "%s"',
                        $mysqldumpPath,
                        $host,
                        $port,
                        $username,
                        $database,
                        $outputFile
                    );
                } else {
                    $command = sprintf(
                        '"%s" --host=%s --port=%s --user=%s --password=%s %s > "%s"',
                        $mysqldumpPath,
                        $host,
                        $port,
                        $username,
                        $password,
                        $database,
                        $outputFile
                    );
                }

                exec($command, $output, $returnCode);

                if ($returnCode === 0 && file_exists($outputFile) && filesize($outputFile) > 0) {
                    $this->info('تم إنشاء النسخة الاحتياطية بنجاح: ' . $filename);
                    Log::info('Daily backup created successfully: ' . $filename);

                    // Keep only last 7 backups to save space
                    $this->cleanupOldBackups($backupPath, 7);

                    return Command::SUCCESS;
                }
            }

            // Fallback: Use PHP to create backup
            if ($this->backupUsingPHP($database, $username, $password, $host, $port, $outputFile)) {
                $this->info('تم إنشاء النسخة الاحتياطية بنجاح (باستخدام PHP): ' . $filename);
                Log::info('Daily backup created successfully using PHP: ' . $filename);

                // Keep only last 7 backups
                $this->cleanupOldBackups($backupPath, 7);

                return Command::SUCCESS;
            }

            $this->error('فشل إنشاء النسخة الاحتياطية');
            Log::error('Failed to create daily backup');
            return Command::FAILURE;

        } catch (\Exception $e) {
            $this->error('حدث خطأ: ' . $e->getMessage());
            Log::error('Daily backup error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function findMysqldump()
    {
        $possiblePaths = [
            'C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe',
            'C:\laragon\bin\mysql\mysql-5.7.35-winx64\bin\mysqldump.exe',
            'C:\xampp\mysql\bin\mysqldump.exe',
            'C:\wamp64\bin\mysql\mysql8.0.31\bin\mysqldump.exe',
            'C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe',
            'C:\Program Files\MySQL\MySQL Server 5.7\bin\mysqldump.exe',
        ];

        $possiblePaths[] = 'mysqldump';

        foreach ($possiblePaths as $path) {
            if (file_exists($path) || $path === 'mysqldump') {
                if ($path === 'mysqldump') {
                    exec('where mysqldump', $output, $returnCode);
                    if ($returnCode === 0 && !empty($output)) {
                        return trim($output[0]);
                    }
                } else {
                    return $path;
                }
            }
        }

        return null;
    }

    private function backupUsingPHP($database, $username, $password, $host, $port, $outputFile)
    {
        try {
            $mysqli = new \mysqli($host, $username, $password, $database, $port);

            if ($mysqli->connect_error) {
                throw new \Exception('Connection failed: ' . $mysqli->connect_error);
            }

            $mysqli->query('SET NAMES utf8mb4');
            $mysqli->query('SET FOREIGN_KEY_CHECKS = 0');

            $tables = [];
            $result = $mysqli->query('SHOW TABLES');
            while ($row = $result->fetch_row()) {
                $tables[] = $row[0];
            }

            $sql = '';
            foreach ($tables as $table) {
                $result = $mysqli->query('SHOW CREATE TABLE ' . $table);
                $tableData = $result->fetch_row();
                $sql .= "\n\n" . $tableData[1] . ";\n\n";

                $result = $mysqli->query('SELECT * FROM ' . $table);
                $numColumns = $result->field_count;

                while ($row = $result->fetch_row()) {
                    $sql .= 'INSERT INTO ' . $table . ' VALUES(';
                    for ($i = 0; $i < $numColumns; $i++) {
                        $row[$i] = addslashes($row[$i]);
                        $row[$i] = preg_replace("/\n/", "\\n", $row[$i]);
                        if (isset($row[$i])) {
                            $sql .= '"' . $row[$i] . '"';
                        } else {
                            $sql .= '""';
                        }
                        if ($i < $numColumns - 1) {
                            $sql .= ',';
                        }
                    }
                    $sql .= ");\n";
                }

                $sql .= "\n\n\n";
            }

            $mysqli->query('SET FOREIGN_KEY_CHECKS = 1');
            $mysqli->close();

            if (file_put_contents($outputFile, $sql)) {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            throw new \Exception('PHP backup failed: ' . $e->getMessage());
        }
    }

    private function cleanupOldBackups($backupPath, $keepDays)
    {
        $files = glob($backupPath . '/daily_backup_*.sql');
        usort($files, function ($a, $b) {
            return filemtime($a) - filemtime($b);
        });

        $filesToDelete = count($files) - $keepDays;
        if ($filesToDelete > 0) {
            for ($i = 0; $i < $filesToDelete; $i++) {
                if (file_exists($files[$i])) {
                    unlink($files[$i]);
                    $this->info('تم حذف النسخة القديمة: ' . basename($files[$i]));
                }
            }
        }
    }
}
