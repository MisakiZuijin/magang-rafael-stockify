<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        protected Product $model
    ) {}

    public function getAll(): Collection
    {
        return $this->model->latest()->get();
    }

    public function findById(int $id): Product
    {
        return $this->model->findOrFail($id);
    }
}
