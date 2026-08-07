<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        protected User $model
    ) {}

    public function getAll(): Collection
    {
        return $this->model->latest()->get();
    }

    public function findById(int $id): User
    {
        return $this->model->findOrFail($id);
    }
}
