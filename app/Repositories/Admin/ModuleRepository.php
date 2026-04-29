<?php

namespace App\Repositories\Admin;

use App\Models\Module;
use App\Repositories\Contracts\ModuleRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ModuleRepository implements ModuleRepositoryInterface
{
    /**
     * Get all modules with pagination
     *
     * @param int $perPage
     * @param string|null $search
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = Module::query();

        if ($search) {
            $query->where('module_name', 'like', "%{$search}%");
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get all modules
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return Module::orderBy('module_name')->get();
    }

    /**
     * Find module by ID
     *
     * @param int $id
     * @return Module|null
     */
    public function findById(int $id): ?Module
    {
        return Module::find($id);
    }

    /**
     * Create a new module
     *
     * @param array $data
     * @return Module
     */
    public function create(array $data): Module
    {
        return Module::create([
            'module_name' => $data['module_name'],
        ]);
    }

    /**
     * Update module
     *
     * @param int $id
     * @param array $data
     * @return Module
     */
    public function update(int $id, array $data): Module
    {
        $module = $this->findById($id);

        $module->update([
            'module_name' => $data['module_name'] ?? $module->module_name,
        ]);

        return $module->fresh();
    }

    /**
     * Delete module
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $module = $this->findById($id);

        if (!$module) {
            return false;
        }

        return $module->delete();
    }

    /**
     * Check if module exists by name
     *
     * @param string $name
     * @param int|null $excludeId
     * @return bool
     */
    public function existsByName(string $name, ?int $excludeId = null): bool
    {
        $query = Module::where('module_name', $name);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
