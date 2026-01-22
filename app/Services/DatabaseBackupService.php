<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DatabaseBackupService
{
    /**
     * Run database backup, compress, and upload to S3
     * 
     * @param string $filename Base filename (without extension)
     * @return array ['s3_path' => string, 'file_size' => int, 'local_path' => string]
     * @throws \Exception
     */
    public function run(string $filename): array
    {
        $config = config('database.connections.mysql');

        // Local paths
        $backupDir = storage_path('app/db-backups');
        $sqlPath = "{$backupDir}/{$filename}.sql";
        $gzipPath = "{$backupDir}/{$filename}.sql.gz";

        // Ensure backup directory exists
        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        // Get mysqldump path
        $mysqldump = env('MYSQL_DUMP_PATH', 'mysqldump');

        // Verify mysqldump exists
        if (!empty(env('MYSQL_DUMP_PATH')) && !file_exists($mysqldump)) {
            throw new \Exception("mysqldump not found at: {$mysqldump}");
        }

        try {
            // 1. Create SQL dump
            $command = sprintf(
                '"%s" --protocol=TCP --host=127.0.0.1 --port=%d --user=%s %s > "%s" 2>&1',
                $mysqldump,
                $config['port'],
                $config['username'],
                $config['database'],
                $sqlPath
            );

            exec($command, $output, $resultCode);

            if ($resultCode !== 0 || !file_exists($sqlPath)) {
                throw new \Exception("mysqldump failed:\n" . implode("\n", $output));
            }

            // Verify SQL file has content
            if (filesize($sqlPath) === 0) {
                throw new \Exception("Generated SQL file is empty");
            }

            // 2. Compress the SQL file
            $this->compressFile($sqlPath, $gzipPath);

            // 3. Upload to S3
            $s3Path = "db/{$filename}.sql.gz";
            $uploaded = Storage::disk('s3')->put(
                $s3Path,
                file_get_contents($gzipPath),
                'private' // Keep backups private
            );

            if (!$uploaded) {
                throw new \Exception('Failed to upload backup to S3');
            }

            // Verify S3 upload
            if (!Storage::disk('s3')->exists($s3Path)) {
                throw new \Exception('S3 upload verification failed - file not found in bucket');
            }

            $fileSize = filesize($gzipPath);

            Log::info('Database backup completed', [
                's3_path' => $s3Path,
                'file_size' => $fileSize,
                'local_path' => $gzipPath,
            ]);

            // 4. Optional: Clean up local files (uncomment if you want to save space)
            // @unlink($sqlPath);
            // @unlink($gzipPath);

            return [
                's3_path' => $s3Path,
                'file_size' => $fileSize,
                'local_path' => $gzipPath,
            ];

        } catch (\Throwable $e) {
            // Clean up on failure
            @unlink($sqlPath);
            @unlink($gzipPath);

            Log::error('Database backup failed', [
                'error' => $e->getMessage(),
                'filename' => $filename,
            ]);

            throw new \Exception("Backup failed: " . $e->getMessage());
        }
    }

    /**
     * Compress SQL file using gzip
     * 
     * @param string $inputPath Path to SQL file
     * @param string $outputPath Path for compressed file
     * @throws \Exception
     */
    protected function compressFile(string $inputPath, string $outputPath): void
    {
        // Method 1: Using gzip command (faster)
        if ($this->isCommandAvailable('gzip')) {
            $command = sprintf('gzip -c "%s" > "%s"', $inputPath, $outputPath);
            exec($command, $output, $resultCode);

            if ($resultCode !== 0 || !file_exists($outputPath)) {
                throw new \Exception("gzip compression failed");
            }
            return;
        }

        // Method 2: PHP gzencode (slower but more compatible)
        $sqlContent = file_get_contents($inputPath);
        if ($sqlContent === false) {
            throw new \Exception("Failed to read SQL file for compression");
        }

        $compressed = gzencode($sqlContent, 9); // Maximum compression
        if ($compressed === false) {
            throw new \Exception("Failed to compress SQL file");
        }

        if (file_put_contents($outputPath, $compressed) === false) {
            throw new \Exception("Failed to write compressed file");
        }
    }

    /**
     * Check if a shell command is available
     * 
     * @param string $command Command name
     * @return bool
     */
    protected function isCommandAvailable(string $command): bool
    {
        $whereIsCommand = PHP_OS_FAMILY === 'Windows' ? 'where' : 'which';
        exec("{$whereIsCommand} {$command}", $output, $resultCode);
        return $resultCode === 0;
    }

    /**
     * Legacy method: Return only local path (for backward compatibility)
     * 
     * @param string $filename
     * @return string Local path to SQL file
     * @throws \Exception
     */
    public function runLocal(string $filename): string
    {
        $config = config('database.connections.mysql');
        $backupDir = storage_path('app/db-backups');
        $dumpPath = "{$backupDir}/{$filename}.sql";

        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $mysqldump = env('MYSQL_DUMP_PATH', 'mysqldump');

        if (!empty(env('MYSQL_DUMP_PATH')) && !file_exists($mysqldump)) {
            throw new \Exception("mysqldump not found at: {$mysqldump}");
        }

        $command = sprintf(
            '"%s" --protocol=TCP --host=127.0.0.1 --port=%d --user=%s %s > "%s" 2>&1',
            $mysqldump,
            $config['port'],
            $config['username'],
            $config['database'],
            $dumpPath
        );

        exec($command, $output, $resultCode);

        if ($resultCode !== 0 || !file_exists($dumpPath)) {
            throw new \Exception("mysqldump failed:\n" . implode("\n", $output));
        }

        return $dumpPath;
    }
}
