<?php

namespace App\Repositories\Contracts;

use App\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;

interface RoleRepositoryInterface
{
    /**
     * Get all roles with pagination
     *
     * @param int $perPage
     * @param string|null $search
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, ?string $search = null, array $filters = []): LengthAwarePaginator;

    /**
     * Get all roles
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Find role by ID
     *
     * @param int $id
     * @return Role|null
     */
    public function findById(int $id): ?Role;

    /**
     * Create a new role
     *
     * @param array $data
     * @return Role
     */
    public function create(array $data): Role;

    /**
     * Update role
     *
     * @param int $id
     * @param array $data
     * @return Role
     */
    public function update(int $id, array $data): Role;

    /**
     * Delete role
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Check if role exists by name
     *
     * @param string $name
     * @param int|null $excludeId
     * @return bool
     */
    public function existsByName(string $name, ?int $excludeId = null): bool;

    /**
     * Sync permissions to role
     *
     * @param int $roleId
     * @param array $permissionIds
     * @return void
     */
    public function syncPermissions(int $roleId, array $permissionIds): void;
}
