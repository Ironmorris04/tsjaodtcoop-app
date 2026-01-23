<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\BackupLog;
use App\Models\User;

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
        /**
         * ------------------------------------------------------
         * Create default officer users (ONE-TIME, NO SEEDER)
         * ------------------------------------------------------
         */
        if (!app()->runningInConsole()) {

            $officerRoles = ['admin', 'president', 'treasurer'];

            // If any officer role already exists, do nothing
            if (!User::whereIn('role', $officerRoles)->exists()) {

                $officers = [
                    [
                        'name'  => 'Admin',
                        'email' => 'admin@transport.com',
                        'role'  => 'admin',
                    ],
                    [
                        'name'  => 'President Officer',
                        'email' => 'president@transport.com',
                        'role'  => 'president',
                    ],
                    [
                        'name'  => 'Treasurer Officer',
                        'email' => 'treasurer@transport.com',
                        'role'  => 'treasurer',
                    ],
                ];

                foreach ($officers as $officer) {
                    $userId = User::generateUserId($officer['role']);

                    User::create([
                        'name'     => $officer['name'],
                        'email'    => $officer['email'],
                        'role'     => $officer['role'],
                        'user_id'  => $userId,
                        'password' => Hash::make($userId),
                    ]);
                }
            }
        }

        /**
         * ------------------------------------------------------
         * Force HTTPS for tunnels / proxies
         * ------------------------------------------------------
         */
        $host = request()->getHost();
        $forwardedProto = request()->header('X-Forwarded-Proto');

        if (
            str_contains($host, 'loca.lt') ||
            str_contains($host, 'trycloudflare.com') ||
            str_contains($host, 'ngrok') ||
            str_contains($host, 'tunnel.') ||
            $forwardedProto === 'https'
        ) {
            \URL::forceScheme('https');
        }

        /**
         * ------------------------------------------------------
         * Admin dashboard data (View Composer)
         * ------------------------------------------------------
         */
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
