<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Contracts\ModuleRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ModuleController extends Controller
{
    public function __construct(
        private ModuleRepositoryInterface $moduleRepository
    ) {}

    /**
     * Display a listing of the modules.
     */
    public function index(Request $request): Response
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        $modules = $this->moduleRepository->paginate($perPage, $search);

        return Inertia::render('Admin/Modules/Index', [
            'modules' => $modules,
            'filters' => [
                'search' => $search,
                'per_page' => $perPage,
            ],
        ]);
    }

    /**
     * Store a newly created module.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'module_name' => [
                'required',
                'string',
                'max:255',
                'unique:modules,module_name',
            ],
        ]);

        try {
            $module = $this->moduleRepository->create($validated);

            return redirect()->back()->with('success', 'Module created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create module: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified module.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'module_name' => [
                'required',
                'string',
                'max:255',
                'unique:modules,module_name,' . $id,
            ],
        ]);

        try {
            $module = $this->moduleRepository->update($id, $validated);

            return redirect()->back()->with('success', 'Module updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update module: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified module.
     */
    public function destroy(int $id)
    {
        try {
            $module = $this->moduleRepository->findById($id);

            if (!$module) {
                return redirect()->back()->with('error', 'Module not found.');
            }

            $this->moduleRepository->delete($id);

            return redirect()->back()->with('success', 'Module deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete module: ' . $e->getMessage());
        }
    }

    /**
     * Get all modules (for select dropdowns, etc.)
     */
    public function all()
    {
        $modules = $this->moduleRepository->all();

        return response()->json([
            'data' => $modules,
        ]);
    }
}
