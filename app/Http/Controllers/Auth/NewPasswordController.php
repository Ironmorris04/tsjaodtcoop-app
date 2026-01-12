<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

   /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {

                // Hash once
                $newPasswordHash = Hash::make($request->password);

                // Update main user
                $user->forceFill([
                    'password' => $newPasswordHash,
                    'remember_token' => Str::random(60),
                ])->save();

                // 🔁 Sync assigned officer accounts if operator
                if ($user->isOperator()) {
                    [$local, $domain] = explode('@', $user->email);

                    $roles = ['president', 'treasurer', 'auditor'];

                    $emails = collect($roles)->map(fn ($role) =>
                        "{$local}.{$role}@{$domain}"
                    );

                    User::whereIn('email', $emails)
                        ->whereIn('role', $roles)
                        ->update(['password' => $newPasswordHash]);
                }

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }

}
