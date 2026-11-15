<?php

namespace App\Http\Middleware;

use App\Models\NavParentMenu;
use App\Models\NavMenu;
use App\Models\NavSubmenu;
use App\Models\RoleNavPermission;
use App\Models\UserNavPermission;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    private ?array $navAccessCache = null;
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
                'info'    => fn () => $request->session()->get('info'),
                'message' => fn () => $request->session()->get('message'),
            ],
            'nav_parent_menus' => fn () => $this->buildNavParentMenus($user),
            'nav_menus'        => fn () => $this->buildNavMenus($user),
        ];
    }

    /**
     * Compute the effective allowed menu/submenu IDs for a user.
     * Returns null if no role-level permissions are configured (show all of the type).
     */
    private function resolveNavAccess($user): array
    {
        if ($this->navAccessCache !== null) {
            return $this->navAccessCache;
        }

        $this->navAccessCache = $this->computeNavAccess($user);
        return $this->navAccessCache;
    }

    private function computeNavAccess($user): array
    {
        if (! $user) {
            return [
                'role_type'   => null,
                'menu_ids'    => [],
                'submenu_ids' => [],
            ];
        }

        $roles    = $user->roles;
        $role     = $roles->first();
        $roleType = $role?->role_type;
        $roleIds  = $roles->pluck('id')->toArray();

        if (! $roleType) {
            $rawRole  = $user->getRawOriginal('role');
            $roleType = match ($rawRole) {
                'super_admin', 'admin' => 'admin',
                'teacher'              => 'teacher',
                'student'              => 'student',
                default                => null,
            };
        }

        $roleMenuIds    = [];
        $roleSubmenuIds = [];

        if (! empty($roleIds)) {
            $rolePerms      = RoleNavPermission::whereIn('role_id', $roleIds)->get();
            $roleMenuIds    = $rolePerms->where('nav_item_type', 'menu')->pluck('nav_item_id')->toArray();
            $roleSubmenuIds = $rolePerms->where('nav_item_type', 'submenu')->pluck('nav_item_id')->toArray();
        }

        // Apply per-user overrides (grant/revoke on top of role)
        $overrides   = UserNavPermission::where('user_id', $user->id)->get();
        $addMenus    = $overrides->where('nav_item_type', 'menu')->where('is_granted', true)->pluck('nav_item_id')->toArray();
        $rmMenus     = $overrides->where('nav_item_type', 'menu')->where('is_granted', false)->pluck('nav_item_id')->toArray();
        $addSubmenus = $overrides->where('nav_item_type', 'submenu')->where('is_granted', true)->pluck('nav_item_id')->toArray();
        $rmSubmenus  = $overrides->where('nav_item_type', 'submenu')->where('is_granted', false)->pluck('nav_item_id')->toArray();

        $effectiveMenuIds    = array_values(array_unique(array_merge(array_diff($roleMenuIds, $rmMenus), $addMenus)));
        $effectiveSubmenuIds = array_values(array_unique(array_merge(array_diff($roleSubmenuIds, $rmSubmenus), $addSubmenus)));

        if (! empty($effectiveSubmenuIds)) {
            $parentMenuIdsFromSubs = NavSubmenu::whereIn('id', $effectiveSubmenuIds)
                ->where('is_active', true)
                ->pluck('menu_id')
                ->toArray();
            $parentMenuIdsFromSubs = array_diff($parentMenuIdsFromSubs, $rmMenus);
            $effectiveMenuIds = array_values(array_unique(array_merge($effectiveMenuIds, $parentMenuIdsFromSubs)));
        }

        return [
            'role_type'   => $roleType,
            'menu_ids'    => $effectiveMenuIds,
            'submenu_ids' => $effectiveSubmenuIds,
        ];
    }

    private function buildNavParentMenus($user)
    {
        if (! $user) {
            return [];
        }

        ['role_type' => $roleType, 'menu_ids' => $menuIds, 'submenu_ids' => $submenuIds] = $this->resolveNavAccess($user);

        if (! $roleType || empty($menuIds)) {
            return [];
        }

        return NavParentMenu::with(['menus' => function ($q) use ($menuIds, $submenuIds, $roleType) {
            $q->where('is_active', true)
              ->where('type', $roleType)
              ->whereIn('id', $menuIds)
              ->with(['submenus' => function ($q2) use ($submenuIds) {
                  $q2->where('is_active', true)
                     ->whereIn('id', $submenuIds ?: [0])
                     ->orderBy('sort_order');
              }])
              ->orderBy('sort_order');
        }])
            ->where('is_active', true)
            ->where('type', $roleType)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->filter(fn ($parent) => $parent->menus->isNotEmpty())
            ->values();
    }

    private function buildNavMenus($user)
    {
        if (! $user) {
            return [];
        }

        ['role_type' => $roleType, 'menu_ids' => $menuIds, 'submenu_ids' => $submenuIds] = $this->resolveNavAccess($user);

        if (! $roleType || empty($menuIds)) {
            return [];
        }

        return NavMenu::with(['submenus' => function ($q) use ($submenuIds) {
            $q->where('is_active', true)
              ->whereIn('id', $submenuIds ?: [0])
              ->orderBy('sort_order');
        }])
            ->where('is_active', true)
            ->whereNull('parent_menu_id')
            ->whereIn('id', $menuIds)
            ->where('type', $roleType)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }
}
