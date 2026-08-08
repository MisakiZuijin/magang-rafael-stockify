<?php

namespace App\Services;

use App\Models\ProductAttribut;
use App\Repositories\Contracts\ProductAttributRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductAttributService
{
    public function __construct(
        protected ProductAttributRepositoryInterface $repository
    ) {}

    public function getAll(): Collection
    {
        return $this->repository->getAll();
    }

    public function getById(int $id): ProductAttribut
    {
        return $this->repository->findById($id);
    }

    public function create(array $data): ProductAttribut
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): ProductAttribut
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
