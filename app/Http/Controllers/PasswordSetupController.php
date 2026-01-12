<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class PasswordSetupController extends Controller
{
    /**
     * Show the password setup form
     */
    public function show($token)
    {
        $user = User::where('setup_token', $token)
            ->where('setup_token_expires_at', '>', now())
            ->first();

        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['token' => 'Invalid or expired password setup link.']);
        }

        return view('auth.password-setup', compact('token', 'user'));
    }

    /**
     * Handle password setup submission
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('setup_token', $request->token)
            ->where('setup_token_expires_at', '>', now())
            ->first();

        if (!$user) {
            return back()->withErrors(['token' => 'Invalid or expired password setup link.']);
        }

        // Set the password
        $user->password = Hash::make($request->password);
        $user->setup_token = null;
        $user->setup_token_expires_at = null;
        $user->save();

        // If user is an operator, activate their account
        if ($user->role === 'operator') {
            $operator = Operator::where('user_id', $user->id)->first();
            if ($operator && $operator->approval_status === 'approved') {
                $operator->update(['status' => 'active']);
            }
        }

        return redirect()->route('login')
            ->with('success', 'Password set up successfully! You can now log in with your User ID: ' . $user->user_id);
    }
}
