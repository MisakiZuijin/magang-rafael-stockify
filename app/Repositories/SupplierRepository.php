<?php

namespace App\Repositories;

use App\Models\Supplier;
use App\Repositories\Contracts\SupplierRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function __construct(
        protected Supplier $model
    ) {}

    public function getAll(): Collection
    {
        return $this->model->latest()->get();
    }

    public function findById(int $id): Supplier
    {
        return $this->model->findOrFail($id);
    }
}
