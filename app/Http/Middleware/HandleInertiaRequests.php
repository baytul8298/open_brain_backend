<?php

namespace App\Http\Middleware;

use App\Models\NavParentMenu;
use App\Models\NavMenu;
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
        $role   = $user?->roles->first();
        $roleId = $role?->id;

        $effectiveMenuIds    = null;
        $effectiveSubmenuIds = null;

        if ($roleId) {
            $rolePerms = RoleNavPermission::where('role_id', $roleId)->get();

            if ($rolePerms->isNotEmpty()) {
                $roleMenuIds    = $rolePerms->where('nav_item_type', 'menu')->pluck('nav_item_id')->toArray();
                $roleSubmenuIds = $rolePerms->where('nav_item_type', 'submenu')->pluck('nav_item_id')->toArray();

                // Apply per-user overrides (grant/revoke on top of role)
                $overrides    = UserNavPermission::where('user_id', $user->id)->get();
                $addMenus     = $overrides->where('nav_item_type', 'menu')->where('is_granted', true)->pluck('nav_item_id')->toArray();
                $rmMenus      = $overrides->where('nav_item_type', 'menu')->where('is_granted', false)->pluck('nav_item_id')->toArray();
                $addSubmenus  = $overrides->where('nav_item_type', 'submenu')->where('is_granted', true)->pluck('nav_item_id')->toArray();
                $rmSubmenus   = $overrides->where('nav_item_type', 'submenu')->where('is_granted', false)->pluck('nav_item_id')->toArray();

                $effectiveMenuIds    = array_values(array_unique(array_merge(array_diff($roleMenuIds, $rmMenus), $addMenus)));
                $effectiveSubmenuIds = array_values(array_unique(array_merge(array_diff($roleSubmenuIds, $rmSubmenus), $addSubmenus)));
            }
        }

        return [
            'role_type'    => $role?->role_type,
            'menu_ids'     => $effectiveMenuIds,
            'submenu_ids'  => $effectiveSubmenuIds,
        ];
    }

    private function buildNavParentMenus($user)
    {
        if (! $user) {
            return [];
        }

        ['role_type' => $roleType, 'menu_ids' => $menuIds, 'submenu_ids' => $submenuIds] = $this->resolveNavAccess($user);

        return NavParentMenu::with(['menus' => function ($q) use ($menuIds, $submenuIds) {
            $q->where('is_active', true)
              ->when($menuIds !== null, fn ($q2) => $q2->whereIn('id', $menuIds ?: [0]))
              ->with(['submenus' => function ($q2) use ($submenuIds) {
                  $q2->where('is_active', true)
                     ->when($submenuIds !== null, fn ($q3) => $q3->whereIn('id', $submenuIds ?: [0]))
                     ->orderBy('sort_order');
              }])
              ->orderBy('sort_order');
        }])
            ->where('is_active', true)
            ->when($roleType, fn ($q) => $q->where('type', $roleType))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    private function buildNavMenus($user)
    {
        if (! $user) {
            return [];
        }

        ['role_type' => $roleType, 'menu_ids' => $menuIds, 'submenu_ids' => $submenuIds] = $this->resolveNavAccess($user);

        return NavMenu::with(['submenus' => function ($q) use ($submenuIds) {
            $q->where('is_active', true)
              ->when($submenuIds !== null, fn ($q2) => $q2->whereIn('id', $submenuIds ?: [0]))
              ->orderBy('sort_order');
        }])
            ->where('is_active', true)
            ->whereNull('parent_menu_id')
            ->when($menuIds !== null, fn ($q) => $q->whereIn('id', $menuIds ?: [0]))
            ->when($roleType, fn ($q) => $q->where('type', $roleType))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }
}
