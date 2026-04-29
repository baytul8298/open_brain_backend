<?php

namespace App\Repositories\Admin\Contracts;

use App\Models\Permission;
use Illuminate\Pagination\LengthAwarePaginator;

interface PermissionRepositoryInterface
{
    /**
     * Get all permissions with pagination
     *
     * @param int $perPage
     * @param string|null $search
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, ?string $search = null, array $filters = []): LengthAwarePaginator;

    /**
     * Get all permissions
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Find permission by ID
     *
     * @param int $id
     * @return Permission|null
     */
    public function findById(int $id): ?Permission;

    /**
     * Create a new permission
     *
     * @param array $data
     * @return Permission
     */
    public function create(array $data): Permission;

    /**
     * Update permission
     *
     * @param int $id
     * @param array $data
     * @return Permission
     */
    public function update(int $id, array $data): Permission;

    /**
     * Delete permission
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Check if permission exists by name
     *
     * @param string $name
     * @param int|null $excludeId
     * @return bool
     */
    public function existsByName(string $name, ?int $excludeId = null): bool;
}
