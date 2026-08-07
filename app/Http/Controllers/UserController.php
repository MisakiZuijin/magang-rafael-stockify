<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\View\View;

class UserController extends Controller
{

    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * Tampilkan semua user (dengan pagination)
     */
    public function index(): View
    {
        $users = $this->userService->getAllUsers();

        return view('pages.testing', compact('users'));
    }

    /**
     * Tampilkan detail 1 user
     */
    public function show(int $id): View
    {
        $user = $this->userService->getUserById($id);

        return view('pages.test', compact('user'));
    }
}
