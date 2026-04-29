<?php

namespace App\Repositories\Admin\Contracts;

use App\Models\Module;
use Illuminate\Pagination\LengthAwarePaginator;

interface ModuleRepositoryInterface
{
    /**
     * Get all modules with pagination
     *
     * @param int $perPage
     * @param string|null $search
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator;

    /**
     * Get all modules
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Find module by ID
     *
     * @param int $id
     * @return Module|null
     */
    public function findById(int $id): ?Module;

    /**
     * Create a new module
     *
     * @param array $data
     * @return Module
     */
    public function create(array $data): Module;

    /**
     * Update module
     *
     * @param int $id
     * @param array $data
     * @return Module
     */
    public function update(int $id, array $data): Module;

    /**
     * Delete module
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Check if module exists by name
     *
     * @param string $name
     * @param int|null $excludeId
     * @return bool
     */
    public function existsByName(string $name, ?int $excludeId = null): bool;
}
