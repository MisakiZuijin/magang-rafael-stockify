<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $repository // ← pakai interface
    ) {}

    public function getAllProducts(): Collection
    {
        return $this->repository->getAll();
    }

    public function getProductById(int $id): Product
    {
        return $this->repository->findById($id);
    }
}
