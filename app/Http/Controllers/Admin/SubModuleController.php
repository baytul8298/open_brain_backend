<?php

namespace App\Http\Controllers\Admin;

use App\Models\Module;
use App\Repositories\Contracts\SubModuleRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubModuleController extends Controller
{
    public function __construct(
        private SubModuleRepositoryInterface $subModuleRepository
    ) {}

    /**
     * Display a listing of the sub-modules.
     */
    public function index(Request $request): Response
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');
        $moduleId = $request->get('module_id');

        $filters = [];
        if ($moduleId) {
            $filters['module_id'] = $moduleId;
        }

        $subModules = $this->subModuleRepository->paginate($perPage, $search, $filters);

        return Inertia::render('Admin/SubModules/Index', [
            'subModules' => $subModules,
            'filters' => [
                'search'    => $search,
                'per_page'  => $perPage,
                'module_id' => $moduleId,
            ],
            'modules' => Module::orderBy('module_name')->get(),
        ]);
    }

    /**
     * Store a newly created sub-module.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'module_id' => ['required', 'integer', 'exists:modules,id'],
        ]);

        if ($this->subModuleRepository->existsByName($validated['name'], $validated['module_id'])) {
            return redirect()->back()->withErrors(['name' => 'A sub-module with this name already exists under the selected module.'])->withInput();
        }

        try {
            $this->subModuleRepository->create($validated);

            return redirect()->back()->with('success', 'Sub-module created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create sub-module: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified sub-module.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'module_id' => ['required', 'integer', 'exists:modules,id'],
        ]);

        if ($this->subModuleRepository->existsByName($validated['name'], $validated['module_id'], $id)) {
            return redirect()->back()->withErrors(['name' => 'A sub-module with this name already exists under the selected module.'])->withInput();
        }

        try {
            $this->subModuleRepository->update($id, $validated);

            return redirect()->back()->with('success', 'Sub-module updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update sub-module: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified sub-module.
     */
    public function destroy(int $id)
    {
        try {
            $subModule = $this->subModuleRepository->findById($id);

            if (!$subModule) {
                return redirect()->back()->with('error', 'Sub-module not found.');
            }

            if ($subModule->permissions()->count() > 0) {
                return redirect()->back()->with('error', 'Cannot delete sub-module: it has associated permissions.');
            }

            $this->subModuleRepository->delete($id);

            return redirect()->back()->with('success', 'Sub-module deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete sub-module: ' . $e->getMessage());
        }
    }

    /**
     * Get all sub-modules (for select dropdowns, etc.)
     */
    public function all(Request $request)
    {
        $moduleId = $request->get('module_id') ? (int) $request->get('module_id') : null;

        $subModules = $this->subModuleRepository->all($moduleId);

        return response()->json([
            'data' => $subModules,
        ]);
    }
}
