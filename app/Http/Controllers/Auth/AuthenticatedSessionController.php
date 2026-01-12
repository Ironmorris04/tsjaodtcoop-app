<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\AuditTrail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        \Log::info('Login attempt started for user_id: ' . $request->user_id);

        try {
            $request->authenticate();
            \Log::info('Authentication successful');
        } catch (\Exception $e) {
            \Log::error('Authentication failed: ' . $e->getMessage());
            throw $e;
        }

        $request->session()->regenerate();
        \Log::info('Session regenerated');

        // Check if user is an operator and their approval status
        $user = Auth::user();
        \Log::info('User loaded: ' . $user->name . ' (Role: ' . $user->role . ')');

        if ($user->role === 'operator') {
            $operator = $user->operator;

            if ($operator && $operator->approval_status === 'pending') {
                Auth::logout();
                return back()->withErrors([
                    'user_id' => 'Your registration is still pending approval by the administrator. Please wait for confirmation.',
                ]);
            }

            if ($operator && $operator->approval_status === 'rejected') {
                Auth::logout();
                return back()->withErrors([
                    'user_id' => 'Your registration has been rejected. Please contact the administrator for more information.',
                ]);
            }
        }

        // Log successful login
        try {
            AuditTrail::log(
                'login',
                'User logged in to the system',
                'User',
                $user->id
            );
            \Log::info('Audit trail logged');
        } catch (\Exception $e) {
            \Log::error('Audit trail error: ' . $e->getMessage());
            // Don't fail login if audit fails
        }

        \Log::info('Redirecting to /dashboard');

        return redirect('/dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Log logout before destroying session
        if (Auth::check()) {
            AuditTrail::log(
                'logout',
                'User logged out from the system',
                'User',
                Auth::id()
            );
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
