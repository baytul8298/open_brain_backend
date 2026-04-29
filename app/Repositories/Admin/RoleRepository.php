<?php

namespace App\Repositories\Admin;

use App\Models\Role;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleRepository implements RoleRepositoryInterface
{
    /**
     * Get all roles with pagination
     *
     * @param int $perPage
     * @param string|null $search
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, ?string $search = null, array $filters = []): LengthAwarePaginator
    {
        $query = Role::query()
            ->with('permissions.module')
            ->withCount('permissions');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('display_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply role type filter
        if (!empty($filters['role_type'])) {
            $query->where('role_type', $filters['role_type']);
        }

        // Apply created from filter
        if (!empty($filters['created_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_from']);
        }

        // Apply created to filter
        if (!empty($filters['created_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_to']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get all roles
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return Role::orderBy('name')->get();
    }

    /**
     * Find role by ID
     *
     * @param int $id
     * @return Role|null
     */
    public function findById(int $id): ?Role
    {
        return Role::with('permissions.module')->find($id);
    }

    /**
     * Create a new role
     *
     * @param array $data
     * @return Role
     */
    public function create(array $data): Role
    {
        return Role::create([
            'name' => $data['name'],
            'display_name' => $data['display_name'] ?? $data['name'],
            'role_type' => $data['role_type'] ?? null,
            'description' => $data['description'] ?? null,
            'guard_name' => $data['guard_name'] ?? 'web',
        ]);
    }

    /**
     * Update role
     *
     * @param int $id
     * @param array $data
     * @return Role
     */
    public function update(int $id, array $data): Role
    {
        $role = $this->findById($id);

        $role->update([
            'name' => $data['name'] ?? $role->name,
            'display_name' => $data['display_name'] ?? $role->display_name,
            'guard_name' => $data['guard_name'] ?? $role->guard_name,
            'role_type' => $data['role_type'] ?? $role->role_type,
            'description' => $data['description'] ?? $role->description,
        ]);

        return $role->fresh(['permissions.module']);
    }

    /**
     * Delete role
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $role = $this->findById($id);

        if (!$role) {
            return false;
        }

        return $role->delete();
    }

    /**
     * Check if role exists by name
     *
     * @param string $name
     * @param int|null $excludeId
     * @return bool
     */
    public function existsByName(string $name, ?int $excludeId = null): bool
    {
        $query = Role::where('name', $name);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Sync permissions to role
     *
     * @param int $roleId
     * @param array $permissionIds
     * @return void
     */
    public function syncPermissions(int $roleId, array $permissionIds): void
    {
        $role = $this->findById($roleId);

        if ($role) {
            $role->syncPermissions($permissionIds);
        }
    }
}
