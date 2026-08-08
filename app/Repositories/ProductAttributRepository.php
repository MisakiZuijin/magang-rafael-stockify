<?php

namespace App\Repositories;

use App\Models\ProductAttribut;
use App\Repositories\Contracts\ProductAttributRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductAttributRepository implements ProductAttributRepositoryInterface
{
    public function __construct(
        protected ProductAttribut $model
    ) {}

    public function getAll(): Collection
    {
        return $this->model->with(['product'])->latest()->get();
    }

    public function findById(int $id): ProductAttribut
    {
        return $this->model->with(['product'])->findOrFail($id);
    }

    public function create(array $data): ProductAttribut
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): ProductAttribut
    {
        $attribut = $this->model->findOrFail($id);
        $attribut->update($data);
        return $attribut->fresh();
    }

    public function delete(int $id): bool
    {
        $attribut = $this->model->findOrFail($id);
        return $attribut->delete();
    }
}
