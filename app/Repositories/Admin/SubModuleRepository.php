<?php

namespace App\Repositories\Admin;

use App\Models\SubModule;
use App\Repositories\Contracts\SubModuleRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class SubModuleRepository implements SubModuleRepositoryInterface
{
    /**
     * Get all sub-modules with pagination
     *
     * @param int $perPage
     * @param string|null $search
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, ?string $search = null, array $filters = []): LengthAwarePaginator
    {
        $query = SubModule::with('module');

        if (!empty($filters['module_id'])) {
            $query->where('module_id', $filters['module_id']);
        }

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get all sub-modules
     *
     * @param int|null $moduleId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(?int $moduleId = null)
    {
        $query = SubModule::with('module');

        if ($moduleId !== null) {
            $query->where('module_id', $moduleId);
        }

        return $query->join('modules', 'sub_modules.module_id', '=', 'modules.id')
            ->orderBy('modules.module_name')
            ->orderBy('sub_modules.name')
            ->select('sub_modules.*')
            ->get();
    }

    /**
     * Find sub-module by ID
     *
     * @param int $id
     * @return SubModule|null
     */
    public function findById(int $id): ?SubModule
    {
        return SubModule::with('module')->find($id);
    }

    /**
     * Create a new sub-module
     *
     * @param array $data
     * @return SubModule
     */
    public function create(array $data): SubModule
    {
        return SubModule::create($data);
    }

    /**
     * Update sub-module
     *
     * @param int $id
     * @param array $data
     * @return SubModule
     */
    public function update(int $id, array $data): SubModule
    {
        $subModule = $this->findById($id);

        $subModule->update([
            'module_id' => $data['module_id'] ?? $subModule->module_id,
            'name'      => $data['name'] ?? $subModule->name,
        ]);

        return $subModule->fresh();
    }

    /**
     * Delete sub-module
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $subModule = $this->findById($id);

        if (!$subModule) {
            return false;
        }

        return $subModule->delete();
    }

    /**
     * Check if sub-module exists by name within the same module
     *
     * @param string $name
     * @param int $moduleId
     * @param int|null $excludeId
     * @return bool
     */
    public function existsByName(string $name, int $moduleId, ?int $excludeId = null): bool
    {
        $query = SubModule::where('name', $name)->where('module_id', $moduleId);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
