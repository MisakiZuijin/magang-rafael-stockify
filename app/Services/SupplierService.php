<?php

namespace App\Services;

use App\Models\Supplier;
use App\Repositories\Contracts\SupplierRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SupplierService
{
    public function __construct(
        protected SupplierRepositoryInterface $repository // ← pakai interface
    ) {}

    public function getAllSuppliers(): Collection
    {
        return $this->repository->getAll();
    }

    public function getSupplierById(int $id): Supplier
    {
        return $this->repository->findById($id);
    }
}
