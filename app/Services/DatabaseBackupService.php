<?php

namespace App\Services;

class DatabaseBackupService
{
    public function run(string $filename): string
    {
        $config = config('database.connections.mysql');

        $dumpPath = storage_path("app/db-backups/{$filename}.sql");
        $mysqldump = env('MYSQL_DUMP_PATH');

        if (! file_exists($mysqldump)) {
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

        if ($resultCode !== 0 || ! file_exists($dumpPath)) {
            throw new \Exception("mysqldump failed:\n" . implode("\n", $output));
        }

        return $dumpPath;
    }
}
