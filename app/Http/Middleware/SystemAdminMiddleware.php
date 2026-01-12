<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SystemAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isSystemAdmin()) {
            abort(403, 'System access only.');
        }

        return $next($request);
    }
}

