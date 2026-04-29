<?php

namespace App\Repositories\Admin\Contracts;

use App\Models\SubModule;
use Illuminate\Pagination\LengthAwarePaginator;

interface SubModuleRepositoryInterface
{
    /**
     * Get all sub-modules with pagination
     *
     * @param int $perPage
     * @param string|null $search
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, ?string $search = null, array $filters = []): LengthAwarePaginator;

    /**
     * Get all sub-modules
     *
     * @param int|null $moduleId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(?int $moduleId = null);

    /**
     * Find sub-module by ID
     *
     * @param int $id
     * @return SubModule|null
     */
    public function findById(int $id): ?SubModule;

    /**
     * Create a new sub-module
     *
     * @param array $data
     * @return SubModule
     */
    public function create(array $data): SubModule;

    /**
     * Update sub-module
     *
     * @param int $id
     * @param array $data
     * @return SubModule
     */
    public function update(int $id, array $data): SubModule;

    /**
     * Delete sub-module
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Check if sub-module exists by name within the same module
     *
     * @param string $name
     * @param int $moduleId
     * @param int|null $excludeId
     * @return bool
     */
    public function existsByName(string $name, int $moduleId, ?int $excludeId = null): bool;
}
