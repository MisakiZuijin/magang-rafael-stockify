<?php

namespace App\Services;

use App\Models\StockTransaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TransactionService
{
    public function __construct(
        protected TransactionRepositoryInterface $repository // ← pakai interface
    ) {}

    public function getAllTransaction(): Collection
    {
        return $this->repository->getAllWithRelations(['user', 'product']);
    }

    public function getRecentActivities(int $limit = 5): Collection
    {
        return $this->repository->getRecentActivities($limit);
    }

    public function getTransactionById(int $id): StockTransaction
    {
        return $this->repository->findById($id);
    }

    public function createTransaction(array $data): StockTransaction
    {
        return $this->repository->create($data);
    }
}
