<?php

namespace App\Repositories\Contracts;

use App\Models\Core\GradeLevel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface GradeLevelRepositoryInterface
{
    public function paginate(int $perPage = 15, ?string $search = null, ?string $status = null): LengthAwarePaginator;
    public function all(): Collection;
    public function stats(): array;
    public function findById(int $id): ?GradeLevel;
    public function create(array $data): GradeLevel;
    public function update(int $id, array $data): GradeLevel;
    public function delete(int $id): bool;
}
