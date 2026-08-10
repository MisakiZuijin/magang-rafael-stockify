<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function index(): View
    {
        $users = $this->userService->getAllUsers();
        return view('pages.admin.adminpengguna', compact('users'));
    }

    public function create(): View
    {
        return view('pages.admin.form.adminpengguna-form');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role'     => ['required', 'in:Admin,Manager Gudang,Staff Gudang'],
            'remember_token' => ['nullable', 'string', 'max:100'],
        ]);

        $this->userService->createUser($validated);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $user = $this->userService->getUserById($id);
        return view('pages.admin.form.adminpengguna-form', compact('user'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,' . $id],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'role'     => ['required', 'in:Admin,Manager Gudang,Staff Gudang'],
        ]);

        $this->userService->updateUser($id, $validated);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        // Cegah admin menghapus diri sendiri
        if ($id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $this->userService->deleteUser($id);
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function full(Request $request): View
    {
        $query = \App\Models\User::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }
        $users = $query->orderBy('id')->paginate(25)->withQueryString();
        return view('pages.admin.adminuser-full', compact('users'));
    }
}
