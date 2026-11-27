<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User; // TAMBAHAN: Import Model User

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // ========================================================
        // LOGIKA STRICT DEVICE LOCK (TAMBAHAN BARU)
        // ========================================================
        $user = User::where('name', $this->input('name'))->first();

        if ($user && $user->login_token !== null) {
            throw ValidationException::withMessages([
                'name' => 'Akun ini sedang aktif di perangkat lain. Silakan Logout dari perangkat tersebut terlebih dahulu.',
            ]);
        }
        // ========================================================

        // Login menggunakan 'name' dan mengecek apakah akun aktif
        if (!Auth::attempt([
            'name' => $this->input('name'),
            'password' => $this->input('password'),
            'is_active' => true,
        ], $this->boolean('remember'))) {

            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'name' => 'Nama atau password tidak sesuai, atau akun Anda dinonaktifkan.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'name' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->string('name')) . '|' . $this->ip()
        );
    }
}