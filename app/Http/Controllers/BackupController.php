<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BackupController extends Controller
{
    public function index()
    {
        $this->authorize('stock');

        $backups = [];
        $backupPath = storage_path('app/backups');

        if (is_dir($backupPath)) {
            $files = scandir($backupPath);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                    $filePath = $backupPath . '/' . $file;
                    $backups[] = [
                        'name' => $file,
                        'size' => $this->formatSize(filesize($filePath)),
                        'date' => Carbon::createFromTimestamp(filemtime($filePath))->format('Y-m-d H:i:s'),
                        'download_link' => route('backup.download', $file),
                        'delete_link' => route('backup.delete', $file),
                    ];
                }
            }
        }

        usort($backups, function ($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return view('backup.index', compact('backups'));
    }

    public function create()
    {
        $this->authorize('stock');

        try {
            // Try to read from config first, fallback to env
            $database = config('database.connections.mysql.database') ?: env('DB_DATABASE');
            $username = config('database.connections.mysql.username') ?: env('DB_USERNAME');
            $password = config('database.connections.mysql.password') ?: env('DB_PASSWORD');
            $host = config('database.connections.mysql.host') ?: env('DB_HOST', '127.0.0.1');
            $port = config('database.connections.mysql.port') ?: env('DB_PORT', '3306');

            // If still empty, try to load from database.php config file directly
            if (empty($database) || empty($username)) {
                $config = include base_path('config/database.php');
                $database = $config['connections']['mysql']['database'] ?? $database;
                $username = $config['connections']['mysql']['username'] ?? $username;
                $password = $config['connections']['mysql']['password'] ?? $password;
                $host = $config['connections']['mysql']['host'] ?? $host;
                $port = $config['connections']['mysql']['port'] ?? $port;
            }

            // Validate required fields
            if (empty($database) || empty($username)) {
                throw new \Exception('Database credentials are missing. Database: ' . $database . ', Username: ' . $username);
            }

            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $backupPath = storage_path('app/backups');

            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            $outputFile = $backupPath . '/' . $filename;

            // Try Laravel's built-in backup command first
            try {
                \Illuminate\Support\Facades\Artisan::call('db:dump', [
                    '--database' => 'mysql',
                    '--path' => $outputFile,
                ]);

                if (file_exists($outputFile) && filesize($outputFile) > 0) {
                    return redirect()->route('backup.index')->with('success', 'تم إنشاء النسخة الاحتياطية بنجاح');
                }
            } catch (\Exception $e) {
                // If db:dump is not available, try mysqldump
            }

            // Try mysqldump on Windows
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
                    return redirect()->route('backup.index')->with('success', 'تم إنشاء النسخة الاحتياطية بنجاح');
                }
            }

            // Fallback: Try using environment variable for password
            if ($mysqldumpPath) {
                $command = sprintf(
                    '"%s" --host=%s --port=%s --user=%s %s > "%s"',
                    $mysqldumpPath,
                    $host,
                    $port,
                    $username,
                    $database,
                    $outputFile
                );

                if (!empty($password)) {
                    putenv('MYSQL_PWD=' . $password);
                }
                exec($command, $output, $returnCode);
                if (!empty($password)) {
                    putenv('MYSQL_PWD');
                }

                if ($returnCode === 0 && file_exists($outputFile) && filesize($outputFile) > 0) {
                    return redirect()->route('backup.index')->with('success', 'تم إنشاء النسخة الاحتياطية بنجاح');
                }
            }

            // Final fallback: Use PHP to create backup
            if ($this->backupUsingPHP($database, $username, $password, $host, $port, $outputFile)) {
                return redirect()->route('backup.index')->with('success', 'تم إنشاء النسخة الاحتياطية بنجاح (باستخدام PHP)');
            }

            throw new \Exception('فشل جميع طرق إنشاء النسخة الاحتياطية');
        } catch (\Exception $e) {
            return redirect()->route('backup.index')->with('error', 'فشل إنشاء النسخة الاحتياطية: ' . $e->getMessage());
        }
    }

    private function findMysqldump()
    {
        // Common mysqldump paths on Windows
        $possiblePaths = [
            'C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe',
            'C:\laragon\bin\mysql\mysql-5.7.35-winx64\bin\mysqldump.exe',
            'C:\xampp\mysql\bin\mysqldump.exe',
            'C:\wamp64\bin\mysql\mysql8.0.31\bin\mysqldump.exe',
            'C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe',
            'C:\Program Files\MySQL\MySQL Server 5.7\bin\mysqldump.exe',
        ];

        // Also check PATH
        $possiblePaths[] = 'mysqldump';

        foreach ($possiblePaths as $path) {
            if (file_exists($path) || $path === 'mysqldump') {
                if ($path === 'mysqldump') {
                    // Try to find it in PATH
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

    public function download($filename)
    {
        $this->authorize('admin');

        $backupPath = storage_path('app/backups/' . $filename);

        if (!file_exists($backupPath)) {
            abort(404, 'الملف غير موجود');
        }

        return response()->download($backupPath);
    }

    public function delete($filename)
    {
        $this->authorize('admin');

        $backupPath = storage_path('app/backups/' . $filename);

        if (file_exists($backupPath)) {
            unlink($backupPath);
            return redirect()->route('backup.index')->with('success', 'تم حذف النسخة الاحتياطية بنجاح');
        }

        return redirect()->route('backup.index')->with('error', 'الملف غير موجود');
    }

    private function formatSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 0) {
            return $bytes . ' bytes';
        } else {
            return '0 bytes';
        }
    }
}
