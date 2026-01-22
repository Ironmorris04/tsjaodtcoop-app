<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\BackupLog;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Automatically detect tunnel/proxy and force HTTPS
        // Supports: Cloudflare Tunnel, loca.lt, ngrok, and other tunnels
        $host = request()->getHost();
        $forwardedProto = request()->header('X-Forwarded-Proto');

        if (str_contains($host, 'loca.lt') ||
            str_contains($host, 'trycloudflare.com') ||
            str_contains($host, 'ngrok') ||
            str_contains($host, 'tunnel.') ||
            $forwardedProto === 'https') {
            \URL::forceScheme('https');
        }

        View::composer('layouts.app', function ($view) {
            if (Auth::check() && Auth::user()->isAdmin()) {
                $lastBackup = BackupLog::latest()->first();

                $recentBackups = BackupLog::with('admin')
                    ->latest()
                    ->take(5)
                    ->get();

                $manualBackupsToday = BackupLog::where('admin_id', Auth::id())
                    ->whereDate('created_at', today())
                    ->count();

                $view->with([
                    'lastBackupAt'       => $lastBackup?->created_at?->format('Y-m-d H:i:s'),
                    'backupStatus'       => $lastBackup ? ucfirst($lastBackup->status) : 'Pending',
                    'recentBackups'      => $recentBackups,
                    'manualBackupsToday' => $manualBackupsToday,
                ]);
            }
        });

    }
    
}
