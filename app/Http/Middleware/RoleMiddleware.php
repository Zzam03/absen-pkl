<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->is_active) {
            auth()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'name' => 'Akun Anda sudah dinonaktifkan. Silakan hubungi administrator.',
                ]);
        }

        if (!in_array($user->role, $roles)) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin.');
        }

        return $next($request);
    }
}
