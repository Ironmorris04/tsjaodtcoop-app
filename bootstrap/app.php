<?php
// bootstrap/app.php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'system.admin' => \App\Http\Middleware\SystemAdminMiddleware::class, // ✅ ADDED
        ]);

        // Trust proxies for Cloudflare Tunnel
        $middleware->trustProxies(at: '*');

        // Configure the redirect for unauthenticated users
        $middleware->redirectGuestsTo('/login');
    })
    ->withSchedule(function (Schedule $schedule) {

        // Send expiring document notifications daily at 10:00 AM
        $schedule->command('notify:expiring-documents')
            ->dailyAt('10:00')
            ->timezone('Asia/Manila');

        /*
        |--------------------------------------------------------------------------
        | Automatic Daily Database Backup (CUSTOM – NO SPATIE)
        |--------------------------------------------------------------------------
        */
        $schedule->call(function () {

            $filename = 'auto_backup_' . now()->format('Y-m-d_H-i-s');

            // Log backup attempt
            $log = \App\Models\BackupLog::create([
                'filename' => $filename . '.sql',
                'status'   => 'pending',
                'admin_id' => null, // automatic backup
            ]);

            try {
                // Run custom backup service
                app(\App\Services\DatabaseBackupService::class)->run($filename);

                $log->update([
                    'status' => 'success',
                ]);

            } catch (\Exception $e) {
                $log->update([
                    'status' => 'failed',
                    'notes'  => $e->getMessage(),
                ]);
            }

        })
        ->name('daily-db-backup')
        ->daily()
        ->withoutOverlapping()
        ->onOneServer()
        ->timezone('Asia/Manila');

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
