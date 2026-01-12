<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Operator;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // 🔍 Find user
        $user = User::where('user_id', $this->user_id)->first();

        if (! $user) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'user_id' => 'User ID does not exist.',
            ]);
        }

        /**
         * 🔒 CHECK IF OPERATOR IS SOFT-DELETED / UNREGISTERED
         */
        $operator = Operator::withTrashed()
            ->where('user_id', $user->id)
            ->first();

        if ($operator && $operator->trashed()) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'user_id' => 'This user is unregistered or has been deactivated.',
            ]);
        }

        /**
         * 🔐 Attempt authentication
         */
        if (! Auth::attempt(
            ['user_id' => $this->user_id, 'password' => $this->password],
            $this->boolean('remember')
        )) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'password' => 'The password you entered is incorrect.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'user_id' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->string('user_id')) . '|' . $this->ip()
        );
    }
}
