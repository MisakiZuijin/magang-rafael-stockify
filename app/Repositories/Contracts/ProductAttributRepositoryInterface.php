<?php

namespace App\Repositories\Contracts;

use App\Models\ProductAttribut;
use Illuminate\Database\Eloquent\Collection;

interface ProductAttributRepositoryInterface
{
    public function getAll(): Collection;
    public function findById(int $id): ProductAttribut;
    public function create(array $data): ProductAttribut;
    public function update(int $id, array $data): ProductAttribut;
    public function delete(int $id): bool;
}
