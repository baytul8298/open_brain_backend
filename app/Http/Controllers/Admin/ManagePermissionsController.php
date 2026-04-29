<?php

namespace App\Http\Controllers\Admin;

use App\Models\NavMenu;
use App\Models\Role;
use App\Models\RoleNavPermission;
use App\Models\User;
use App\Models\UserNavPermission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ManagePermissionsController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')
            ->orderBy('name')
            ->get(['id', 'name', 'display_name']);

        $users = User::with('roles:id,name,display_name')
            ->orderBy('email')
            ->get(['id', 'email', 'username'])
            ->map(fn ($u) => [
                'id'    => $u->id,
                'name'  => $u->username ?: $u->email,
                'email' => $u->email,
                'roles' => $u->roles->map(fn ($r) => $r->display_name ?: $r->name)->join(', '),
            ]);

        return Inertia::render('Admin/ManagePermissions/Index', [
            'roles' => $roles,
            'users' => $users,
        ]);
    }

    public function permissionTree(Request $request)
    {
        $roleId = $request->get('role_id');
        $userId = $request->get('user_id');

        // Determine role type for filtering menus shown in the permission tree
        $roleType = null;
        if ($roleId) {
            $roleType = Role::find($roleId)?->role_type;
        } elseif ($userId) {
            $roleType = User::with('roles:id,role_type')->find($userId)?->roles->first()?->role_type;
        }

        // Build nav tree filtered by role/user type
        $menus = NavMenu::with([
            'submenus' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')
                ->with(['buttons' => fn ($q2) => $q2->where('is_active', true)->orderBy('sort_order')]),
        ])
            ->whereNotNull('parent_menu_id')
            ->where('is_active', true)
            ->when($roleType, fn ($q) => $q->where('type', $roleType))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($roleId) {
            $granted      = RoleNavPermission::where('role_id', $roleId)->get();
            $menuIds      = $granted->where('nav_item_type', 'menu')->pluck('nav_item_id')->toArray();
            $submenuIds   = $granted->where('nav_item_type', 'submenu')->pluck('nav_item_id')->toArray();
            $buttonIds    = $granted->where('nav_item_type', 'button')->pluck('nav_item_id')->toArray();

            return response()->json([
                'type'             => 'role',
                'role_menu_ids'    => [],
                'role_submenu_ids' => [],
                'role_button_ids'  => [],
                'menus'            => $this->buildTree($menus, $menuIds, $submenuIds, $buttonIds),
            ]);
        }

        if ($userId) {
            $user = User::with('roles:id,name,display_name')->findOrFail($userId);
            $role = $user->roles->first();

            $roleMenuIds    = [];
            $roleSubmenuIds = [];
            $roleButtonIds  = [];

            if ($role) {
                $roleGranted    = RoleNavPermission::where('role_id', $role->id)->get();
                $roleMenuIds    = $roleGranted->where('nav_item_type', 'menu')->pluck('nav_item_id')->toArray();
                $roleSubmenuIds = $roleGranted->where('nav_item_type', 'submenu')->pluck('nav_item_id')->toArray();
                $roleButtonIds  = $roleGranted->where('nav_item_type', 'button')->pluck('nav_item_id')->toArray();
            }

            // User-level overrides
            $overrides = UserNavPermission::where('user_id', $userId)->get();

            $addMenus    = $overrides->where('nav_item_type', 'menu')->where('is_granted', true)->pluck('nav_item_id')->toArray();
            $addSubmenus = $overrides->where('nav_item_type', 'submenu')->where('is_granted', true)->pluck('nav_item_id')->toArray();
            $addButtons  = $overrides->where('nav_item_type', 'button')->where('is_granted', true)->pluck('nav_item_id')->toArray();
            $rmMenus     = $overrides->where('nav_item_type', 'menu')->where('is_granted', false)->pluck('nav_item_id')->toArray();
            $rmSubmenus  = $overrides->where('nav_item_type', 'submenu')->where('is_granted', false)->pluck('nav_item_id')->toArray();
            $rmButtons   = $overrides->where('nav_item_type', 'button')->where('is_granted', false)->pluck('nav_item_id')->toArray();

            // Effective = role ∪ grants − revokes
            $menuIds    = array_values(array_unique(array_merge(array_diff($roleMenuIds, $rmMenus), $addMenus)));
            $submenuIds = array_values(array_unique(array_merge(array_diff($roleSubmenuIds, $rmSubmenus), $addSubmenus)));
            $buttonIds  = array_values(array_unique(array_merge(array_diff($roleButtonIds, $rmButtons), $addButtons)));

            return response()->json([
                'type'             => 'user',
                'role_name'        => $role ? ($role->display_name ?: $role->name) : null,
                'role_menu_ids'    => $roleMenuIds,
                'role_submenu_ids' => $roleSubmenuIds,
                'role_button_ids'  => $roleButtonIds,
                'menus'            => $this->buildTree($menus, $menuIds, $submenuIds, $buttonIds),
            ]);
        }

        return response()->json([
            'type'             => 'none',
            'role_menu_ids'    => [],
            'role_submenu_ids' => [],
            'role_button_ids'  => [],
            'menus'            => $this->buildTree($menus, [], [], []),
        ]);
    }

    public function syncRole(Request $request)
    {
        $validated = $request->validate([
            'role_id'       => 'required|integer|exists:roles,id',
            'menu_ids'      => 'nullable|array',
            'menu_ids.*'    => 'integer',
            'submenu_ids'   => 'nullable|array',
            'submenu_ids.*' => 'integer',
            'button_ids'    => 'nullable|array',
            'button_ids.*'  => 'integer',
        ]);

        RoleNavPermission::where('role_id', $validated['role_id'])->delete();

        $now     = now();
        $records = [];
        foreach ($validated['menu_ids'] ?? [] as $id) {
            $records[] = ['role_id' => $validated['role_id'], 'nav_item_type' => 'menu', 'nav_item_id' => $id, 'created_at' => $now, 'updated_at' => $now];
        }
        foreach ($validated['submenu_ids'] ?? [] as $id) {
            $records[] = ['role_id' => $validated['role_id'], 'nav_item_type' => 'submenu', 'nav_item_id' => $id, 'created_at' => $now, 'updated_at' => $now];
        }
        foreach ($validated['button_ids'] ?? [] as $id) {
            $records[] = ['role_id' => $validated['role_id'], 'nav_item_type' => 'button', 'nav_item_id' => $id, 'created_at' => $now, 'updated_at' => $now];
        }
        if ($records) {
            RoleNavPermission::insert($records);
        }

        return redirect()->back()->with('success', 'Role permissions saved successfully.');
    }

    public function syncUser(Request $request)
    {
        $validated = $request->validate([
            'user_id'            => 'required|string|exists:users,id',
            'menu_ids'           => 'nullable|array',
            'menu_ids.*'         => 'integer',
            'submenu_ids'        => 'nullable|array',
            'submenu_ids.*'      => 'integer',
            'button_ids'         => 'nullable|array',
            'button_ids.*'       => 'integer',
            'role_menu_ids'      => 'nullable|array',
            'role_menu_ids.*'    => 'integer',
            'role_submenu_ids'   => 'nullable|array',
            'role_submenu_ids.*' => 'integer',
            'role_button_ids'    => 'nullable|array',
            'role_button_ids.*'  => 'integer',
        ]);

        UserNavPermission::where('user_id', $validated['user_id'])->delete();

        $checked = [
            'menu'    => $validated['menu_ids'] ?? [],
            'submenu' => $validated['submenu_ids'] ?? [],
            'button'  => $validated['button_ids'] ?? [],
        ];
        $roleBase = [
            'menu'    => $validated['role_menu_ids'] ?? [],
            'submenu' => $validated['role_submenu_ids'] ?? [],
            'button'  => $validated['role_button_ids'] ?? [],
        ];

        $now     = now();
        $records = [];

        foreach (['menu', 'submenu', 'button'] as $type) {
            $all = array_unique(array_merge($checked[$type], $roleBase[$type]));
            foreach ($all as $id) {
                $isChecked = in_array($id, $checked[$type]);
                $inRole    = in_array($id, $roleBase[$type]);
                if ($isChecked !== $inRole) {
                    // Only store overrides that differ from role
                    $records[] = [
                        'user_id'       => $validated['user_id'],
                        'nav_item_type' => $type,
                        'nav_item_id'   => $id,
                        'is_granted'    => $isChecked,
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ];
                }
            }
        }

        if ($records) {
            UserNavPermission::insert($records);
        }

        return redirect()->back()->with('success', 'User permissions saved successfully.');
    }

    private function buildTree($menus, array $menuIds, array $submenuIds, array $buttonIds): array
    {
        return $menus->map(fn ($menu) => [
            'id'            => $menu->id,
            'name'          => $menu->name,
            'checked'       => in_array($menu->id, $menuIds),
            'submenu_count' => $menu->submenus->count(),
            'submenus'      => $menu->submenus->map(fn ($sub) => [
                'id'           => $sub->id,
                'name'         => $sub->name,
                'checked'      => in_array($sub->id, $submenuIds),
                'button_count' => $sub->buttons->count(),
                'buttons'      => $sub->buttons->map(fn ($btn) => [
                    'id'      => $btn->id,
                    'name'    => $btn->name,
                    'checked' => in_array($btn->id, $buttonIds),
                ])->values()->toArray(),
            ])->values()->toArray(),
        ])->values()->toArray();
    }
}
