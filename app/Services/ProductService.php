<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $repository
    ) {}

    public function getAllProducts(): Collection
    {
        return $this->repository->getAll();
    }

    public function getProductById(int $id): Product
    {
        return $this->repository->findById($id);
    }

    public function createProduct(array $data): Product
    {
        return $this->repository->create($data);
    }

    public function updateProduct(int $id, array $data): Product
    {
        return $this->repository->update($id, $data);
    }

    public function deleteProduct(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
