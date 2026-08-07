<?php

namespace App\Repositories;

use App\Models\StockTransaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function __construct(
        protected StockTransaction $model
    ) {}

    public function getAll(): Collection
    {
        return $this->model->latest()->get();
    }

    public function getAllWithRelations(array $relations = []): Collection
    {
        return $this->model->with($relations)->latest()->get();
    }

    public function findById(int $id): StockTransaction
    {
        return $this->model->findOrFail($id);
    }

    public function getRecentActivities(int $limit = 5): Collection
    {
        return $this->model
            ->with(['user', 'product'])
            ->latest()
            ->limit($limit)
            ->get();
    }
}
