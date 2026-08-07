<?php

namespace App\Services;

use App\Models\Categori;
use App\Repositories\Contracts\CategoriRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoriService
{
    public function __construct(
        protected CategoriRepositoryInterface $repository
    ) {}

    public function getAllCategories(): Collection
    {
        return $this->repository->getAll();
    }

    public function getCategoryById(int $id): Categori
    {
        return $this->repository->findById($id);
    }

    public function createCategory(array $data): Categori
    {
        return $this->repository->create($data);
    }

    public function updateCategory(int $id, array $data): Categori
    {
        return $this->repository->update($id, $data);
    }

    public function deleteCategory(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
