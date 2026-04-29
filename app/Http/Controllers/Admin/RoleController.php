<?php

namespace App\Http\Controllers\Admin;

use App\Models\Module;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{
    public function __construct(
        private RoleRepositoryInterface $roleRepository
    ) {}

    /**
     * Display a listing of the roles.
     */
    public function index(Request $request): Response
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        // Get filter parameters
        $filters = [
            'role_type' => $request->get('role_type'),
            'created_from' => $request->get('created_from'),
            'created_to' => $request->get('created_to'),
        ];

        $roles = $this->roleRepository->paginate($perPage, $search, $filters);

        return Inertia::render('Admin/Roles/Index', [
            'roles' => $roles,
            'filters' => [
                'search' => $search,
                'per_page' => $perPage,
                'role_type' => $filters['role_type'],
                'created_from' => $filters['created_from'],
                'created_to' => $filters['created_to'],
            ],
        ]);
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:roles,name',
                'regex:/^[a-z0-9\-\.]+$/',
            ],
            'display_name' => 'required|string|max:255',
            'guard_name' => 'required|string|max:255',
            'role_type' => 'required|in:admin,teacher,student',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.regex' => 'Role name must only contain lowercase letters, numbers, hyphens, and dots.',
        ]);

        try {
            $role = $this->roleRepository->create($validated);

            return redirect()->back()->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create role: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:roles,name,' . $id,
                'regex:/^[a-z0-9\-\.]+$/',
            ],
            'display_name' => 'required|string|max:255',
            'guard_name' => 'required|string|max:255',
            'role_type' => 'required|in:admin,teacher,student',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.regex' => 'Role name must only contain lowercase letters, numbers, hyphens, and dots.',
        ]);

        try {
            $role = $this->roleRepository->update($id, $validated);

            return redirect()->back()->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update role: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified role.
     */
    public function destroy(int $id)
    {
        try {
            $role = $this->roleRepository->findById($id);

            if (!$role) {
                return redirect()->back()->with('error', 'Role not found.');
            }

            // Check if role is assigned to any users
            if ($role->users()->count() > 0) {
                return redirect()->back()->with('error', 'Cannot delete role that is assigned to users.');
            }

            $this->roleRepository->delete($id);

            return redirect()->back()->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete role: ' . $e->getMessage());
        }
    }

    /**
     * Show the assign permission page for a role.
     */
    public function showAssignPermission(int $id): Response
    {
        $role = $this->roleRepository->findById($id);

        if (!$role) {
            abort(404);
        }

        // All modules with their permissions grouped by sub-module
        $modules = Module::with(['permissions' => function ($query) {
            $query->with('subModuleRelation')->orderBy('sub_module_id')->orderBy('display_name');
        }])->orderBy('module_name')->get();

        $assignedPermissionIds = $role->permissions->pluck('id')->toArray();

        return Inertia::render('Admin/Roles/AssignPermission', [
            'role' => $role,
            'modules' => $modules,
            'assignedPermissionIds' => $assignedPermissionIds,
        ]);
    }

    /**
     * Save assigned permissions for a role.
     */
    public function saveAssignPermission(Request $request, int $id)
    {
        $validated = $request->validate([
            'permission_ids' => 'nullable|array',
            'permission_ids.*' => 'integer|exists:permissions,id',
        ]);

        try {
            $role = $this->roleRepository->findById($id);

            if (!$role) {
                return redirect()->back()->with('error', 'Role not found.');
            }

            $this->roleRepository->syncPermissions($id, $validated['permission_ids'] ?? []);

            return redirect()->route('roles.index')->with('success', 'Permissions assigned to "' . ($role->display_name ?: $role->name) . '" successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to assign permissions: ' . $e->getMessage());
        }
    }

    /**
     * Get all roles (for select dropdowns, etc.)
     */
    public function all()
    {
        $roles = $this->roleRepository->all();

        return response()->json([
            'data' => $roles,
        ]);
    }
}
