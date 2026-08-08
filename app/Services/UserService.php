<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $repository
    ) {}

    public function getAllUsers(): Collection
    {
        return $this->repository->getAll();
    }

    public function getUserById(int $id): User
    {
        return $this->repository->findById($id);
    }

    public function authenticate(string $email, string $password): ?User
    {
        $user = $this->repository->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        return $user;
    }

    public function createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return $this->repository->create($data);
    }

    public function updateUser(int $id, array $data): User
    {
        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        return $this->repository->update($id, $data);
    }

    public function deleteUser(int $id): bool
    {
        return $this->repository->delete($id);
    }

    // Helper untuk cek role
    public function isAdmin(User $user): bool
    {
        return $user->role === 'Admin';
    }

    public function isManager(User $user): bool
    {
        return $user->role === 'Manager Gudang';
    }

    public function isStaff(User $user): bool
    {
        return $user->role === 'Staff Gudang';
    }
}
