<?php

namespace App\Http\Controllers\Admin;

use App\Models\Core\GradeLevel;
use App\Repositories\Contracts\GradeLevelRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class GradeLevelController extends Controller
{
    public function __construct(
        private GradeLevelRepositoryInterface $gradeLevelRepository
    ) {}

    public function index(Request $request): Response
    {
        $perPage = $request->integer('per_page', 15);
        $search  = $request->get('search');
        $status  = $request->get('status');

        return Inertia::render('Admin/GradeLevel/Index', [
            'gradeLevels' => $this->gradeLevelRepository->paginate($perPage, $search, $status),
            'stats'       => $this->gradeLevelRepository->stats(),
            'filters'     => compact('search', 'status', 'perPage'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:120', Rule::unique(GradeLevel::class, 'name')],
            'slug'       => ['nullable', 'string', 'max:255', Rule::unique(GradeLevel::class, 'slug')],
            'name_bn'    => ['nullable', 'string', 'max:120'],
            'icon'       => ['nullable', 'string', 'max:255'],
            'color'      => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active'  => ['required', 'boolean'],
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $this->gradeLevelRepository->create($validated);

        return redirect()->back()->with('success', 'Grade level created successfully.');
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:120', Rule::unique(GradeLevel::class, 'name')->ignore($id)],
            'slug'       => ['nullable', 'string', 'max:255', Rule::unique(GradeLevel::class, 'slug')->ignore($id)],
            'name_bn'    => ['nullable', 'string', 'max:120'],
            'icon'       => ['nullable', 'string', 'max:255'],
            'color'      => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active'  => ['required', 'boolean'],
        ]);

        $this->gradeLevelRepository->update($id, $validated);

        return redirect()->back()->with('success', 'Grade level updated successfully.');
    }

    public function destroy(int $id)
    {
        $gradeLevel = $this->gradeLevelRepository->findById($id);

        if (!$gradeLevel) {
            return redirect()->back()->with('error', 'Grade level not found.');
        }

        $this->gradeLevelRepository->delete($id);

        return redirect()->back()->with('success', 'Grade level deleted successfully.');
    }
}
