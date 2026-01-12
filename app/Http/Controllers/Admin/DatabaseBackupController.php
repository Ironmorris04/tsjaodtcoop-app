<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BackupLog;
use App\Services\DatabaseBackupService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DatabaseBackupController extends Controller
{
    const MANUAL_BACKUP_LIMIT = 3;

    /**
     * Show backup info in admin panel
     */
    public function index()
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $lastBackup = BackupLog::latest()->first();
        $recentBackups = BackupLog::latest()->take(5)->get();

        $manualBackupsToday = BackupLog::where('admin_id', Auth::id())
            ->whereDate('created_at', today())
            ->count();

        return view('admin.database.index', [
            'lastBackupAt'        => $lastBackup?->created_at?->format('Y-m-d H:i:s') ?? 'Not yet available',
            'backupStatus'        => ucfirst($lastBackup?->status ?? 'Pending'),
            'recentBackups'       => $recentBackups,
            'manualBackupsToday'  => $manualBackupsToday,
        ]);
    }

    /**
     * Run a manual database backup with S3 upload
     */
    public function runBackup(DatabaseBackupService $backupService)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        // ✅ SERVER-SIDE DAILY LIMIT (important)
        $countToday = BackupLog::where('admin_id', Auth::id())
            ->whereDate('created_at', today())
            ->count();

        if ($countToday >= self::MANUAL_BACKUP_LIMIT) {
            return response()->json([
                'success' => false,
                'message' => 'Daily manual backup limit reached.',
            ], 429);
        }

        $filename = 'manual_backup_' . now()->format('Y-m-d_H-i-s');

        $log = BackupLog::create([
            'filename'  => $filename . '.sql',
            'status'    => 'pending',
            'admin_id'  => Auth::id(),
            's3_path'   => null,
        ]);

        try {
            $result = $backupService->run($filename);
            // expected: ['s3_path' => 'db/xxx.sql.gz', 'file_size' => 12345]

            $log->update([
                'status'     => 'success',
                's3_path'    => $result['s3_path'],
                'file_size'  => $result['file_size'] ?? null,
            ]);

            // ✅ Pre-signed URL (expires in 15 minutes)
            $temporaryUrl = Storage::disk('s3')->temporaryUrl(
                $result['s3_path'],
                now()->addMinutes(15)
            );

            return response()->json([
                'success' => true,
                'message' => 'Backup completed and uploaded to S3 successfully.',
                's3' => [
                    'bucket' => config('filesystems.disks.s3.bucket'),
                    'key'    => $result['s3_path'],
                    'url'    => $temporaryUrl,
                ],
            ]);

        } catch (\Throwable $e) {
            $log->update([
                'status' => 'failed',
                'notes'  => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Download a backup from S3 (fallback / legacy)
     */
    public function download(BackupLog $backup)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        abort_if(! $backup->s3_path, 404, 'Backup file not found.');

        return Storage::disk('s3')->download(
            $backup->s3_path,
            $backup->filename
        );
    }
}
