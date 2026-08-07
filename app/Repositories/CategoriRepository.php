<?php

namespace App\Repositories;

use App\Models\Categori;
use App\Repositories\Contracts\CategoriRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoriRepository implements CategoriRepositoryInterface
{
    public function __construct(
        protected Categori $model
    ) {}

    public function getAll(): Collection
    {
        return $this->model->latest()->get();
    }

    public function findById(int $id): Categori
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Categori
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Categori
    {
        $category = $this->model->findOrFail($id);
        $category->update($data);
        return $category->fresh();
    }

    public function delete(int $id): bool
    {
        $category = $this->model->findOrFail($id);
        return $category->delete();
    }
}
