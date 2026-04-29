<?php

namespace App\Http\Controllers\Admin;

use App\Models\Core\Subject;
use App\Repositories\Contracts\SubjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class SubjectController extends Controller
{
    public function __construct(
        private SubjectRepositoryInterface $subjectRepository
    ) {}

    public function index(Request $request): Response
    {
        $perPage = $request->integer('per_page', 15);
        $search  = $request->get('search');
        $status  = $request->get('status');

        return Inertia::render('Admin/Subjects/Index', [
            'subjects'     => $this->subjectRepository->paginate($perPage, $search, $status),
            'all_subjects' => $this->subjectRepository->all(),
            'stats'        => $this->subjectRepository->stats(),
            'filters'      => compact('search', 'status', 'perPage'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:120', Rule::unique(Subject::class, 'name')],
            'slug'       => ['required', 'string', 'max:120', Rule::unique(Subject::class, 'slug')],
            'name_bn'    => ['nullable', 'string', 'max:120'],
            'color'      => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'icon'       => ['nullable', 'string', 'max:64'],
            'parent_id'  => ['nullable', 'integer', Rule::exists(Subject::class, 'id')],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active'  => ['required', 'boolean'],
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $this->subjectRepository->create($validated);

        return redirect()->back()->with('success', 'Subject created successfully.');
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:120', Rule::unique(Subject::class, 'name')->ignore($id)],
            'slug'       => ['required', 'string', 'max:120', Rule::unique(Subject::class, 'slug')->ignore($id)],
            'name_bn'    => ['nullable', 'string', 'max:120'],
            'color'      => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'icon'       => ['nullable', 'string', 'max:64'],
            'parent_id'  => ['nullable', 'integer', Rule::exists(Subject::class, 'id')],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active'  => ['required', 'boolean'],
        ]);

        $this->subjectRepository->update($id, $validated);

        return redirect()->back()->with('success', 'Subject updated successfully.');
    }

    public function destroy(int $id)
    {
        $subject = $this->subjectRepository->findById($id);

        if (!$subject) {
            return redirect()->back()->with('error', 'Subject not found.');
        }

        $this->subjectRepository->delete($id);

        return redirect()->back()->with('success', 'Subject deleted successfully.');
    }
}
