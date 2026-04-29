<?php

namespace App\Repositories\Admin;

use App\Models\Core\GradeLevel;
use App\Repositories\Contracts\GradeLevelRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class GradeLevelRepository implements GradeLevelRepositoryInterface
{
    public function paginate(int $perPage = 15, ?string $search = null, ?string $status = null): LengthAwarePaginator
    {
        $query = GradeLevel::query();

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
        return GradeLevel::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'name_bn', 'color', 'icon']);
    }

    public function stats(): array
    {
        return [
            'total'    => GradeLevel::count(),
            'active'   => GradeLevel::where('is_active', true)->count(),
            'inactive' => GradeLevel::where('is_active', false)->count(),
        ];
    }

    public function findById(int $id): ?GradeLevel
    {
        return GradeLevel::find($id);
    }

    public function create(array $data): GradeLevel
    {
        return GradeLevel::create($data);
    }

    public function update(int $id, array $data): GradeLevel
    {
        $gradeLevel = $this->findById($id);
        $gradeLevel->update($data);
        return $gradeLevel->fresh();
    }

    public function delete(int $id): bool
    {
        $gradeLevel = $this->findById($id);
        if (!$gradeLevel) return false;
        return $gradeLevel->delete();
    }
}
