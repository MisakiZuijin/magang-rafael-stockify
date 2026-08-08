<?php

namespace App\Services;

use App\Models\Supplier;
use App\Repositories\Contracts\SupplierRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SupplierService
{
    public function __construct(
        protected SupplierRepositoryInterface $repository
    ) {}

    public function getAllSuppliers(): Collection
    {
        return $this->repository->getAll();
    }

    public function getSupplierById(int $id): Supplier
    {
        return $this->repository->findById($id);
    }

    public function createSupplier(array $data): Supplier
    {
        return $this->repository->create($data);
    }

    public function updateSupplier(int $id, array $data): Supplier
    {
        return $this->repository->update($id, $data);
    }

    public function deleteSupplier(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
