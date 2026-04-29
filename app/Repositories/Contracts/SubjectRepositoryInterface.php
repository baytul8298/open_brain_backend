<?php

namespace App\Repositories\Contracts;

use App\Models\Core\Subject;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface SubjectRepositoryInterface
{
    public function paginate(int $perPage = 15, ?string $search = null, ?string $status = null): LengthAwarePaginator;
    public function all(): Collection;
    public function stats(): array;
    public function findById(int $id): ?Subject;
    public function create(array $data): Subject;
    public function update(int $id, array $data): Subject;
    public function delete(int $id): bool;
}
