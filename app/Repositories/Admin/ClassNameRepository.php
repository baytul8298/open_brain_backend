<?php

namespace App\Repositories\Admin;

use App\Models\Core\ClassName;
use App\Repositories\Contracts\ClassNameRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ClassNameRepository implements ClassNameRepositoryInterface
{
    public function paginate(int $perPage = 15, ?string $search = null, ?string $status = null): LengthAwarePaginator
    {
        $query = ClassName::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('name_bn', 'ilike', "%{$search}%");
            });
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        return $query->orderBy('sort_order')->orderBy('name')->paginate($perPage);
    }

    public function all(): Collection
    {
        return ClassName::orderBy('sort_order')->orderBy('name')->get(['id', 'name', 'name_bn', 'color', 'icon']);
    }

    public function stats(): array
    {
        return [
            'total'    => ClassName::count(),
            'active'   => ClassName::where('is_active', true)->count(),
            'inactive' => ClassName::where('is_active', false)->count(),
        ];
    }

    public function findById(int $id): ?ClassName
    {
        return ClassName::find($id);
    }

    public function create(array $data): ClassName
    {
        return ClassName::create($data);
    }

    public function update(int $id, array $data): ClassName
    {
        $className = $this->findById($id);
        $className->update($data);
        return $className->fresh();
    }

    public function delete(int $id): bool
    {
        $className = $this->findById($id);
        if (!$className) return false;
        return $className->delete();
    }
}
