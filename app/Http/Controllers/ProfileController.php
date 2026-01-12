<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Change password for any authenticated user (admin or operator)
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        if (!$user || !($user->isAdmin() || $user->isOperator())) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validator = \Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'errors' => ['current_password' => ['The current password is incorrect']]
            ], 422);
        }

        // Generate hash once
        $newPasswordHash = Hash::make($request->new_password);

        // Update main user
        $user->password = $newPasswordHash;
        $user->save();

        // Sync officer accounts
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

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully and synced to assigned positions'
        ]);
    }


}
