<?php

namespace App\Repositories\Contracts;

use App\Models\Core\ClassName;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ClassNameRepositoryInterface
{
    public function paginate(int $perPage = 15, ?string $search = null, ?string $status = null): LengthAwarePaginator;
    public function all(): Collection;
    public function stats(): array;
    public function findById(int $id): ?ClassName;
    public function create(array $data): ClassName;
    public function update(int $id, array $data): ClassName;
    public function delete(int $id): bool;
}
