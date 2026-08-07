<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $repository // ← pakai interface
    ) {}

    public function getAllUsers(): Collection
    {
        return $this->repository->getAll();
    }

    public function getUserById(int $id): User
    {
        return $this->repository->findById($id);
    }
}
