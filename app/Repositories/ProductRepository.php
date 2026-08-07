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
        return $this->model->with(['categori', 'supplier'])->latest()->get();
    }

    public function findById(int $id): Product
    {
        return $this->model->with(['categori', 'supplier', 'productAttributs'])->findOrFail($id);
    }

    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Product
    {
        $product = $this->model->findOrFail($id);
        $product->update($data);
        return $product->fresh();
    }

    public function delete(int $id): bool
    {
        $product = $this->model->findOrFail($id);
        return $product->delete();
    }
}
