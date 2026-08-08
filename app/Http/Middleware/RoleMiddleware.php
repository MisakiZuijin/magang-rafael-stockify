<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role = null): Response
    {
        // auth middleware sudah dipasang SEBELUM role, 
        // jadi di titik ini user HARUS sudah login.
        // Kalau belum, biarkan auth middleware yang redirect (bukan di sini!)

        if ($role !== null && auth()->user()->role !== $role) {
            abort(403, 'Akses ditolak. Role Anda: ' . auth()->user()->role);
        }

        return $next($request);
    }
}
