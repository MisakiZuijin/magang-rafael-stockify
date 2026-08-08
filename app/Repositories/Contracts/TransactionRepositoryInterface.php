<?php

namespace App\Repositories\Contracts;

use App\Models\StockTransaction;
use Illuminate\Database\Eloquent\Collection;

interface TransactionRepositoryInterface
{
    public function getAll(): Collection;
    public function getAllWithRelations(array $relations = []): Collection;
    public function getRecentActivities(int $limit = 5): Collection;
    public function findById(int $id): StockTransaction;
    public function create(array $data): StockTransaction;
}
