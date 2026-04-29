<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RoleRepositoryInterface $roleRepository
    ) {}

    /**
     * Display a listing of the users.
     */
    public function index(Request $request): Response
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        // Get filter parameters
        $filters = [
            'user_type' => $request->get('user_type'),
            'status' => $request->get('status'),
            'created_from' => $request->get('created_from'),
            'created_to' => $request->get('created_to'),
        ];

        $users = $this->userRepository->paginate($perPage, $search, $filters);
        $roles = $this->roleRepository->all();

        // User type options
        $userTypeOptions = [
            ['value' => '', 'label' => 'All User Types'],
            ['value' => 'admin', 'label' => 'Admin'],
            ['value' => 'manager', 'label' => 'Manager'],
            ['value' => 'cashier', 'label' => 'Cashier'],
            ['value' => 'waiter', 'label' => 'Waiter'],
            ['value' => 'user', 'label' => 'User'],
        ];

        // Status options
        $statusOptions = [
            ['value' => '', 'label' => 'All Status'],
            ['value' => '1', 'label' => 'Active'],
            ['value' => '0', 'label' => 'Inactive'],
        ];

        // Language options
        $languageOptions = [
            ['value' => 'en', 'label' => 'English'],
            ['value' => 'es', 'label' => 'Spanish'],
            ['value' => 'fr', 'label' => 'French'],
            ['value' => 'de', 'label' => 'German'],
        ];

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'roles' => $roles,
            'userTypeOptions' => $userTypeOptions,
            'statusOptions' => $statusOptions,
            'languageOptions' => $languageOptions,
            'filters' => [
                'search' => $search,
                'per_page' => $perPage,
                'user_type' => $filters['user_type'],
                'status' => $filters['status'],
                'created_from' => $filters['created_from'],
                'created_to' => $filters['created_to'],
            ],
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'user_type' => 'nullable|string|max:255',
            'contact_no' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'language' => 'nullable|string|max:10',
            'status' => 'nullable|boolean',
            'role_id' => 'nullable|integer|exists:roles,id',
        ]);

        try {
            $user = $this->userRepository->create($validated);

            // Assign role if provided
            if (isset($validated['role_id'])) {
                $this->userRepository->assignRole($user->id, $validated['role_id']);
            }

            return redirect()->back()->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'password' => 'nullable|string|min:6',
            'user_type' => 'required|string|max:255',
            'contact_no' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'language' => 'nullable|string|max:10',
            'status' => 'nullable|boolean',
            'role_id' => 'nullable|integer|exists:roles,id',
        ]);

        try {
            $user = $this->userRepository->update($id, $validated);

            // Assign role if provided
            if (isset($validated['role_id'])) {
                $this->userRepository->assignRole($user->id, $validated['role_id']);
            }

            return redirect()->back()->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user.
     */
    public function destroy(string $id)
    {
        try {
            $user = $this->userRepository->findById($id);

            if (!$user) {
                return redirect()->back()->with('error', 'User not found.');
            }

            // Prevent deleting yourself
            if ($user->id === auth()->id()) {
                return redirect()->back()->with('error', 'You cannot delete your own account.');
            }

            $this->userRepository->delete($id);

            return redirect()->back()->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Get all users (for select dropdowns, etc.)
     */
    public function all()
    {
        $users = $this->userRepository->all();

        return response()->json([
            'data' => $users,
        ]);
    }
}
