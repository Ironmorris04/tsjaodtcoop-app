<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BackupLog;
use App\Services\DatabaseBackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        $recentBackups = BackupLog::with('admin')
            ->latest()
            ->take(5)
            ->get();

        $manualBackupsToday = BackupLog::where('admin_id', Auth::id())
            ->whereDate('created_at', today())
            ->count();

        return view('admin.database.index', [
            'lastBackupAt'       => $lastBackup?->created_at?->format('Y-m-d H:i:s'),
            'backupStatus'       => $lastBackup ? ucfirst($lastBackup->status) : 'Pending',
            'recentBackups'      => $recentBackups,
            'manualBackupsToday' => $manualBackupsToday,
        ]);
    }

    /**
     * Run a manual database backup with S3 upload
     */
    public function runBackup(Request $request, DatabaseBackupService $backupService)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        // ✅ SERVER-SIDE DAILY LIMIT
        $countToday = BackupLog::where('admin_id', Auth::id())
            ->whereDate('created_at', today())
            ->count();

        if ($countToday >= self::MANUAL_BACKUP_LIMIT) {
            Log::warning('Manual backup limit exceeded', [
                'admin_id' => Auth::id(),
                'count' => $countToday,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Daily manual backup limit reached.',
            ], 429);
        }

        $filename = 'manual_backup_' . now()->format('Y-m-d_H-i-s');

        // ✅ Create log entry immediately
        $log = BackupLog::create([
            'filename' => $filename . '.sql.gz',
            'status'   => 'pending',
            'admin_id' => Auth::id(),
            's3_path'  => null,
        ]);

        try {
            // ✅ Run backup service
            $result = $backupService->run($filename);

            if (!isset($result['s3_path']) || !isset($result['file_size'])) {
                throw new \Exception('Invalid backup service response');
            }

            // ✅ Verify upload
            if (!Storage::disk('s3')->exists($result['s3_path'])) {
                throw new \Exception('S3 upload verification failed');
            }

            // ✅ Mark success
            $log->update([
                'status'    => 'success',
                's3_path'   => $result['s3_path'],
                'file_size' => $result['file_size'],
                'notes'     => 'Uploaded to S3 successfully',
            ]);

            $temporaryUrl = Storage::disk('s3')->temporaryUrl(
                $result['s3_path'],
                now()->addMinutes(15)
            );

            Log::info('Manual backup completed successfully', [
                'admin_id' => Auth::id(),
                's3_path'  => $result['s3_path'],
                'size'     => $result['file_size'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Backup completed and uploaded to S3 successfully.',
                's3' => [
                    'bucket' => config('filesystems.disks.s3.bucket'),
                    'region' => config('filesystems.disks.s3.region'),
                    'key'    => $result['s3_path'],
                    'url'    => $temporaryUrl,
                    'size'   => $result['file_size'],
                ],
            ]);

        } catch (\Throwable $e) {
            // ✅ Mark failure
            $log->update([
                'status' => 'failed',
                'notes'  => substr($e->getMessage(), 0, 500),
            ]);

            Log::error('Manual backup failed', [
                'admin_id' => Auth::id(),
                'error'    => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Download a backup from S3
     */
    public function download(BackupLog $backup)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        if (!$backup->s3_path) {
            abort(404, 'Backup file path not found in database.');
        }

        if (!Storage::disk('s3')->exists($backup->s3_path)) {
            abort(404, 'Backup file not found in S3 storage.');
        }

        Log::info('Backup download initiated', [
            'admin_id' => Auth::id(),
            'backup_id' => $backup->id,
        ]);

        return Storage::disk('s3')->download(
            $backup->s3_path,
            $backup->filename
        );
    }

    /**
     * List all backups
     */
    public function list()
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $backups = BackupLog::with('admin')
            ->latest()
            ->paginate(20);

        return view('admin.database.list', compact('backups'));
    }

    /**
     * Delete a backup from S3 and database
     */
    public function delete(BackupLog $backup)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        try {
            if ($backup->s3_path && Storage::disk('s3')->exists($backup->s3_path)) {
                Storage::disk('s3')->delete($backup->s3_path);
            }

            $backup->delete();

            Log::info('Backup deleted', [
                'admin_id' => Auth::id(),
                'backup_id' => $backup->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Backup deleted successfully.',
            ]);

        } catch (\Throwable $e) {
            Log::error('Backup deletion failed', [
                'admin_id' => Auth::id(),
                'backup_id' => $backup->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete backup: ' . $e->getMessage(),
            ], 500);
        }
    }
}
