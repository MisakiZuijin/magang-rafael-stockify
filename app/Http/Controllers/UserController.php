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

    public function index(Request $request): View
    {
        $sortColumn    = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'asc');
        $search        = $request->input('search', '');

        $users = $this->userService->getAllUsers();

        $desc = $sortDirection === 'desc';

        // Search
        if ($search) {
            $s = strtolower($search);
            $users = $users->filter(function ($sup) use ($s) {
                return str_contains(strtolower($sup->name), $s)
                    || str_contains(strtolower($sup->email ?? ''), $s)
                    || str_contains(strtolower($sup->role ?? ''), $s)
                    || str_contains(strtolower((string) $sup->id), $s);
            })->values();
        }

        // Sort
        $users = match ($sortColumn) {
            'name'           => $users->sortBy('name', SORT_REGULAR, $desc),
            'email'          => $users->sortBy('email', SORT_REGULAR, $desc),
            'role'          => $users->sortBy('role', SORT_REGULAR, $desc),
            default          => $users->sortBy('id', SORT_REGULAR, $desc),
        };

        return view('pages.admin.adminpengguna', compact(
            'users',
            'sortColumn',
            'sortDirection',
            'search'
        ));

        // $users = $this->userService->getAllUsers();
        // return view('pages.admin.adminpengguna', compact('users'));
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
