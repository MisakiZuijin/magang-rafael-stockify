<?php

namespace App\Repositories;

use App\Models\Supplier;
use App\Repositories\Contracts\SupplierRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function __construct(protected Supplier $model) {}

    public function getAll(): Collection
    {
        return $this->model->withCount('products')->latest()->get();
    }

    public function findById(int $id): Supplier
    {
        return $this->model->withCount('products')->findOrFail($id);
    }

    public function create(array $data): Supplier
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Supplier
    {
        $supplier = $this->model->findOrFail($id);
        $supplier->update($data);
        return $supplier->fresh();
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
