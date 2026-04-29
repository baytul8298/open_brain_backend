<?php

namespace App\Http\Controllers\Admin;

use App\Models\Core\ClassName;
use App\Repositories\Contracts\ClassNameRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ClassNameController extends Controller
{
    public function __construct(
        private ClassNameRepositoryInterface $classNameRepository
    ) {}

    public function index(Request $request): Response
    {
        $perPage = $request->integer('per_page', 15);
        $search  = $request->get('search');
        $status  = $request->get('status');

        return Inertia::render('Admin/ClassName/Index', [
            'classNames'     => $this->classNameRepository->paginate($perPage, $search, $status),
            'all_class_names' => $this->classNameRepository->all(),
            'stats'          => $this->classNameRepository->stats(),
            'filters'        => compact('search', 'status', 'perPage'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:120', Rule::unique(ClassName::class, 'name')],
            'slug'       => ['required', 'string', 'max:255', Rule::unique(ClassName::class, 'slug')],
            'name_bn'    => ['nullable', 'string', 'max:120'],
            'icon'       => ['nullable', 'string', 'max:255'],
            'color'      => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active'  => ['required', 'boolean'],
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $this->classNameRepository->create($validated);

        return redirect()->back()->with('success', 'Class name created successfully.');
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:120', Rule::unique(ClassName::class, 'name')->ignore($id)],
            'slug'       => ['required', 'string', 'max:255', Rule::unique(ClassName::class, 'slug')->ignore($id)],
            'name_bn'    => ['nullable', 'string', 'max:120'],
            'icon'       => ['nullable', 'string', 'max:255'],
            'color'      => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active'  => ['required', 'boolean'],
        ]);

        $this->classNameRepository->update($id, $validated);

        return redirect()->back()->with('success', 'Class name updated successfully.');
    }

    public function destroy(int $id)
    {
        $className = $this->classNameRepository->findById($id);

        if (!$className) {
            return redirect()->back()->with('error', 'Class name not found.');
        }

        $this->classNameRepository->delete($id);

        return redirect()->back()->with('success', 'Class name deleted successfully.');
    }
}
