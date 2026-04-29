<?php

namespace App\Repositories\Admin;

use App\Models\Core\Subject;
use App\Repositories\Contracts\SubjectRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class SubjectRepository implements SubjectRepositoryInterface
{
    public function paginate(int $perPage = 15, ?string $search = null, ?string $status = null): LengthAwarePaginator
    {
        $query = Subject::query()
            ->selectRaw('core.subjects.*')
            ->selectSub(
                DB::table('learn.courses')->selectRaw('COUNT(*)')->whereColumn('subject_id', 'core.subjects.id'),
                'courses_count'
            )
            ->selectSub(
                DB::table('learn.courses')->selectRaw('COUNT(DISTINCT teacher_id)')->whereColumn('subject_id', 'core.subjects.id'),
                'teachers_count'
            )
            ->selectSub(function ($q) {
                $q->selectRaw('COUNT(DISTINCT e.student_id)')
                  ->from('commerce.enrollments as e')
                  ->join('learn.courses as c', 'c.id', '=', 'e.course_id')
                  ->whereColumn('c.subject_id', 'core.subjects.id');
            }, 'students_count');

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
        return Subject::orderBy('sort_order')->orderBy('name')->get(['id', 'name', 'color', 'icon']);
    }

    public function stats(): array
    {
        return [
            'total'    => Subject::count(),
            'active'   => Subject::where('is_active', true)->count(),
            'inactive' => Subject::where('is_active', false)->count(),
            'courses'  => DB::table('learn.courses')->whereNull('deleted_at')->count(),
        ];
    }

    public function findById(int $id): ?Subject
    {
        return Subject::find($id);
    }

    public function create(array $data): Subject
    {
        return Subject::create($data);
    }

    public function update(int $id, array $data): Subject
    {
        $subject = $this->findById($id);
        $subject->update($data);
        return $subject->fresh();
    }

    public function delete(int $id): bool
    {
        $subject = $this->findById($id);
        if (!$subject) return false;
        return $subject->delete();
    }
}
