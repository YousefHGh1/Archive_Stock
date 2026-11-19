<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

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
                throw new \RuntimeException('Database connection settings are incomplete.');
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
            } catch (\Throwable $e) {
                // If db:dump is not available, try mysqldump
            }

            // Symfony Process works on hosted PHP where exec() is disabled.
            if ($this->backupUsingMysqldump($database, $username, $password, $host, $port, $outputFile)) {
                return redirect()->route('backup.index')->with('success', 'تم إنشاء النسخة الاحتياطية بنجاح');
            }

            // Final fallback: Use PHP to create backup
            if ($this->backupUsingPHP($outputFile)) {
                return redirect()->route('backup.index')->with('success', 'تم إنشاء النسخة الاحتياطية بنجاح (باستخدام PHP)');
            }

            throw new \Exception('فشل جميع طرق إنشاء النسخة الاحتياطية');
        } catch (\Throwable $e) {
            if (isset($outputFile) && is_file($outputFile)) {
                @unlink($outputFile);
            }
            Log::error('Backup creation failed', ['exception' => $e]);
            return redirect()->route('backup.index')->with('error', 'فشل إنشاء النسخة الاحتياطية. يرجى مراجعة سجل التطبيق.');
        }
    }

    private function findMysqldump()
    {
        // Check common Linux/macOS locations and local Windows installations.
        $possiblePaths = [
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            '/usr/local/mysql/bin/mysqldump',
            '/opt/homebrew/bin/mysqldump',
            'C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe',
            'C:\laragon\bin\mysql\mysql-5.7.35-winx64\bin\mysqldump.exe',
            'C:\xampp\mysql\bin\mysqldump.exe',
            'C:\wamp64\bin\mysql\mysql8.0.31\bin\mysqldump.exe',
            'C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe',
            'C:\Program Files\MySQL\MySQL Server 5.7\bin\mysqldump.exe',
        ];

        $pathCandidate = (new ExecutableFinder())->find('mysqldump');
        if ($pathCandidate) {
            array_unshift($possiblePaths, $pathCandidate);
        }

        foreach ($possiblePaths as $path) {
            if (is_file($path) && (DIRECTORY_SEPARATOR === '\\' || is_executable($path))) {
                return $path;
            }
        }

        return null;
    }

    private function backupUsingMysqldump($database, $username, $password, $host, $port, $outputFile): bool
    {
        $mysqldumpPath = $this->findMysqldump();
        if (!$mysqldumpPath) {
            return false;
        }

        $handle = @fopen($outputFile, 'wb');
        if ($handle === false) {
            return false;
        }

        $process = new Process([
            $mysqldumpPath,
            '--host=' . $host,
            '--port=' . $port,
            '--user=' . $username,
            '--default-character-set=utf8mb4',
            '--single-transaction',
            $database,
        ], null, ['MYSQL_PWD' => $password ?: null]);
        $process->setTimeout(null);
        $writeFailed = false;

        try {
            $process->run(function ($type, $buffer) use ($handle, &$writeFailed) {
                if ($type === Process::OUT && !$writeFailed) {
                    if (!$this->writeBackupChunk($handle, $buffer)) {
                        $writeFailed = true;
                    }
                }
            });
        } catch (\Throwable $exception) {
            fclose($handle);
            @unlink($outputFile);
            return false;
        }

        fclose($handle);

        if (!$writeFailed && $process->isSuccessful() && is_file($outputFile) && filesize($outputFile) > 0) {
            return true;
        }

        @unlink($outputFile);
        return false;
    }

    private function backupUsingPHP($outputFile)
    {
        $handle = @fopen($outputFile, 'wb');
        if ($handle === false) {
            return false;
        }

        $pdo = null;
        try {
            $pdo = \Illuminate\Support\Facades\DB::connection('mysql')->getPdo();
            $pdo->exec('SET NAMES utf8mb4');
            $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');

            if (!$this->writeBackupChunk($handle, "SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS = 0;\n\n")) {
                throw new \RuntimeException('Unable to write backup file.');
            }

            $tables = $pdo->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);
            foreach ($tables as $table) {
                $quotedTable = '`' . str_replace('`', '``', $table) . '`';
                $create = $pdo->query('SHOW CREATE TABLE ' . $quotedTable)->fetch(\PDO::FETCH_NUM);
                if (!$create || !isset($create[1])) {
                    throw new \RuntimeException('Unable to read table definition.');
                }

                if (!$this->writeBackupChunk($handle, "DROP TABLE IF EXISTS {$quotedTable};\n{$create[1]};\n\n")) {
                    throw new \RuntimeException('Unable to write backup file.');
                }

                $rows = $pdo->query('SELECT * FROM ' . $quotedTable);
                while ($row = $rows->fetch(\PDO::FETCH_NUM)) {
                    $values = [];
                    foreach ($row as $value) {
                        if ($value === null) {
                            $values[] = 'NULL';
                            continue;
                        }

                        $quotedValue = $pdo->quote((string) $value);
                        if ($quotedValue === false) {
                            throw new \RuntimeException('Unable to encode a database value.');
                        }
                        $values[] = $quotedValue;
                    }

                    if (!$this->writeBackupChunk($handle, 'INSERT INTO ' . $quotedTable . ' VALUES (' . implode(', ', $values) . ");\n")) {
                        throw new \RuntimeException('Unable to write backup file.');
                    }
                }

                if (!$this->writeBackupChunk($handle, "\n")) {
                    throw new \RuntimeException('Unable to write backup file.');
                }
            }

            if (!$this->writeBackupChunk($handle, "SET FOREIGN_KEY_CHECKS = 1;\n")) {
                throw new \RuntimeException('Unable to write backup file.');
            }
            fclose($handle);
            $handle = null;

            return is_file($outputFile) && filesize($outputFile) > 0;
        } catch (\Throwable $e) {
            Log::warning('PHP database backup failed', ['exception' => $e]);
            return false;
        } finally {
            if ($pdo instanceof \PDO) {
                try {
                    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
                } catch (\Throwable $ignored) {
                    // The connection will be discarded if the server is no longer available.
                }
            }

            if (is_resource($handle)) {
                fclose($handle);
            }
        }
    }

    private function writeBackupChunk($handle, string $contents): bool
    {
        $length = strlen($contents);
        $offset = 0;

        while ($offset < $length) {
            $written = fwrite($handle, substr($contents, $offset));
            if ($written === false || $written === 0) {
                return false;
            }
            $offset += $written;
        }

        return true;
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
