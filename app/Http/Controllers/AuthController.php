<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = $this->userService->authenticate(
            $credentials['email'],
            $credentials['password']
        );

        if (!$user) {
            return back()->with('error', 'Email atau password salah.');
        }

        // Login eksplisit dengan guard web
        Auth::guard('web')->login($user, $request->boolean('remember'));

        // Regenerate session
        $request->session()->regenerate();

        // Pastikan login berhasil
        if (!Auth::check()) {
            return back()->with('error', 'Gagal membuat sesi login.');
        }

        // Redirect berdasarkan role (hardcoded path, bukan intended)
        $redirectPath = match ($user->role) {
            'Admin'          => '/dashboard',
            'Manager Gudang' => '/manager/dashboard',
            'Staff Gudang'   => '/staff/dashboard',
            default          => '/dashboard',
        };

        return redirect($redirectPath);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
