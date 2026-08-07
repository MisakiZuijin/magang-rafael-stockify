<?php

namespace App\Repositories\Contracts;

use App\Models\Categori;
use Illuminate\Database\Eloquent\Collection;

interface CategoriRepositoryInterface
{
    public function getAll(): Collection;
    public function findById(int $id): Categori;
    public function create(array $data): Categori;
    public function update(int $id, array $data): Categori;
    public function delete(int $id): bool;
}
