<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceLock {
    public function handle(Request $request, Closure $next) {
        if (Auth::check()) {
            $user = Auth::user();
            $sessionToken = $request->session()->get('device_token');
            if (!$sessionToken || $user->login_token !== $sessionToken) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect('/login')->with('error', 'Akun Anda telah login di perangkat lain.');
            }
        }
        return $next($request);
    }
}