<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Contracts\PermissionRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PermissionController extends Controller
{
    public function __construct(
        private PermissionRepositoryInterface $permissionRepository
    ) {}

    /**
     * Display a listing of the permissions.
     */
    public function index(Request $request): Response
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        // Get filter parameters
        $filters = [
            'guard_name' => $request->get('guard_name'),
            'module_id' => $request->get('module_id'),
            'created_from' => $request->get('created_from'),
            'created_to' => $request->get('created_to'),
        ];

        $permissions = $this->permissionRepository->paginate($perPage, $search, $filters);

        return Inertia::render('Admin/Permissions/Index', [
            'permissions' => $permissions,
            'filters' => [
                'search' => $search,
                'per_page' => $perPage,
                'guard_name' => $filters['guard_name'],
                'module_id' => $filters['module_id'],
                'created_from' => $filters['created_from'],
                'created_to' => $filters['created_to'],
            ],
        ]);
    }

    /**
     * Store a newly created permission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:permissions,name',
                'regex:/^[a-z0-9\-\.]+$/',
            ],
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'module_id' => 'required|integer|exists:modules,id',
            'sub_module_id' => [
                'nullable',
                'integer',
                'exists:sub_modules,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && $request->module_id) {
                        $subModule = \App\Models\SubModule::find($value);
                        if ($subModule && $subModule->module_id != $request->module_id) {
                            $fail('The selected sub-module does not belong to the selected module.');
                        }
                    }
                },
            ],
        ], [
            'name.regex' => 'Permission name must only contain lowercase letters, numbers, hyphens, and dots.',
            'module_id.required' => 'Module is required.',
            'module_id.exists' => 'Selected module does not exist.',
            'sub_module_id.exists' => 'Selected sub-module does not exist.',
        ]);

        try {
            $permission = $this->permissionRepository->create($validated);

            return redirect()->back()->with('success', 'Permission created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create permission: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified permission.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:permissions,name,' . $id,
                'regex:/^[a-z0-9\-\.]+$/',
            ],
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'module_id' => 'required|integer|exists:modules,id',
            'sub_module_id' => [
                'nullable',
                'integer',
                'exists:sub_modules,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && $request->module_id) {
                        $subModule = \App\Models\SubModule::find($value);
                        if ($subModule && $subModule->module_id != $request->module_id) {
                            $fail('The selected sub-module does not belong to the selected module.');
                        }
                    }
                },
            ],
        ], [
            'name.regex' => 'Permission name must only contain lowercase letters, numbers, hyphens, and dots.',
            'module_id.required' => 'Module is required.',
            'module_id.exists' => 'Selected module does not exist.',
            'sub_module_id.exists' => 'Selected sub-module does not exist.',
        ]);

        try {
            $permission = $this->permissionRepository->update($id, $validated);

            return redirect()->back()->with('success', 'Permission updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update permission: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified permission.
     */
    public function destroy(int $id)
    {
        try {
            $permission = $this->permissionRepository->findById($id);

            if (!$permission) {
                return redirect()->back()->with('error', 'Permission not found.');
            }

            // Check if permission is assigned to any roles
            if ($permission->roles()->count() > 0) {
                return redirect()->back()->with('error', 'Cannot delete permission that is assigned to roles.');
            }

            $this->permissionRepository->delete($id);

            return redirect()->back()->with('success', 'Permission deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete permission: ' . $e->getMessage());
        }
    }

    /**
     * Get all permissions (for select dropdowns, etc.)
     */
    public function all()
    {
        $permissions = $this->permissionRepository->all();

        return response()->json([
            'data' => $permissions,
        ]);
    }
}
