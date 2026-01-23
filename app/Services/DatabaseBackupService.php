<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DatabaseBackupService
{
    public function run(string $filename): array
    {
        $config = config('database.connections.mysql');

        $backupDir = storage_path('app/db-backups');
        $sqlPath   = "{$backupDir}/{$filename}.sql";
        $gzipPath  = "{$backupDir}/{$filename}.sql.gz";

        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $mysqldump = env('MYSQL_DUMP_PATH', '/usr/bin/mysqldump');

        if (!is_executable($mysqldump)) {
            throw new \Exception("mysqldump binary not executable: {$mysqldump}");
        }

        try {
            /**
             * Use MYSQL_PWD to avoid leaking password
             * Escape EVERYTHING
             */
            $command = sprintf(
                'MYSQL_PWD=%s "%s" ' .
                '--single-transaction --quick --skip-lock-tables ' .
                '--protocol=TCP --host=%s --port=%d --user=%s %s > "%s" 2>&1',
                escapeshellarg($config['password']),
                $mysqldump,
                escapeshellarg($config['host']),
                (int) $config['port'],
                escapeshellarg($config['username']),
                escapeshellarg($config['database']),
                $sqlPath
            );

            exec($command, $output, $exitCode);

            if ($exitCode !== 0 || !file_exists($sqlPath) || filesize($sqlPath) === 0) {
                Log::error('mysqldump failed', [
                    'exit_code' => $exitCode,
                    'output' => $output,
                ]);

                throw new \Exception('mysqldump failed');
            }

            // Compress using PHP only (Render-safe)
            $this->gzipPhp($sqlPath, $gzipPath);

            // Upload to S3
            $s3Path = "db/{$filename}.sql.gz";

            Storage::disk('s3')->put(
                $s3Path,
                file_get_contents($gzipPath),
                ['visibility' => 'private']
            );

            $fileSize = filesize($gzipPath);

            Log::info('Database backup completed', [
                's3_path' => $s3Path,
                'file_size' => $fileSize,
            ]);

            // Clean up local files (IMPORTANT on Render)
            @unlink($sqlPath);
            @unlink($gzipPath);

            return [
                's3_path' => $s3Path,
                'file_size' => $fileSize,
            ];

        } catch (\Throwable $e) {
            @unlink($sqlPath);
            @unlink($gzipPath);

            Log::error('Database backup failed', [
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Backup failed: ' . $e->getMessage());
        }
    }

    /**
     * Pure PHP gzip (no shell, no PATH issues)
     */
    protected function gzipPhp(string $input, string $output): void
    {
        $data = file_get_contents($input);
        if ($data === false) {
            throw new \Exception('Failed to read SQL file');
        }

        $compressed = gzencode($data, 9);
        if ($compressed === false) {
            throw new \Exception('gzip compression failed');
        }

        if (file_put_contents($output, $compressed) === false) {
            throw new \Exception('Failed to write gzip file');
        }
    }
}
