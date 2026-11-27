<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // 1. Buat token unik
        $user = auth()->user();
        $newToken = \Illuminate\Support\Str::random(60);
        
        // 2. Simpan token ke database
        $user->update(['login_token' => $newToken]);
        
        // 3. SIMPAN TOKEN KE SESSION BROWSER (INI YANG TERTINGGAL SEBELUMNYA)
        $request->session()->put('device_token', $newToken);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        // HAPUS TOKEN saat logout agar user bisa login di device lain nanti
        if (Auth::check()) {
            $user = Auth::user();
            $user->update(['login_token' => null]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}