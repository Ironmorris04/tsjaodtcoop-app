<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
    }
}
