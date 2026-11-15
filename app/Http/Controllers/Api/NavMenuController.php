<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NavParentMenu;
use App\Models\NavSubmenu;
use App\Models\RoleNavPermission;
use App\Models\UserNavPermission;
use Illuminate\Http\Request;

class NavMenuController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'User Not Found!',
                'success' => false,
            ], 404);
        }

        $roles    = $user->roles;
        $role     = $roles->first();
        $roleType = $role?->role_type;
        $roleIds  = $roles->pluck('id')->toArray();

        if (!$roleType) {
            $rawRole = $user->getRawOriginal('role');
            $roleType = match ($rawRole) {
                'super_admin', 'admin' => 'admin',
                'teacher'              => 'teacher',
                'student'              => 'student',
                default                => null,
            };
        }

        $roleMenuIds    = [];
        $roleSubmenuIds = [];
        $roleButtonIds  = [];

        if (!empty($roleIds)) {
            $rolePerms      = RoleNavPermission::whereIn('role_id', $roleIds)->get();
            $roleMenuIds    = $rolePerms->where('nav_item_type', 'menu')->pluck('nav_item_id')->toArray();
            $roleSubmenuIds = $rolePerms->where('nav_item_type', 'submenu')->pluck('nav_item_id')->toArray();
            $roleButtonIds  = $rolePerms->where('nav_item_type', 'button')->pluck('nav_item_id')->toArray();
        }

        $overrides   = UserNavPermission::where('user_id', $user->id)->get();
        $addMenus    = $overrides->where('nav_item_type', 'menu')->where('is_granted', true)->pluck('nav_item_id')->toArray();
        $rmMenus     = $overrides->where('nav_item_type', 'menu')->where('is_granted', false)->pluck('nav_item_id')->toArray();
        $addSubmenus = $overrides->where('nav_item_type', 'submenu')->where('is_granted', true)->pluck('nav_item_id')->toArray();
        $rmSubmenus  = $overrides->where('nav_item_type', 'submenu')->where('is_granted', false)->pluck('nav_item_id')->toArray();
        $addButtons  = $overrides->where('nav_item_type', 'button')->where('is_granted', true)->pluck('nav_item_id')->toArray();
        $rmButtons   = $overrides->where('nav_item_type', 'button')->where('is_granted', false)->pluck('nav_item_id')->toArray();

        $effectiveMenuIds    = array_values(array_unique(array_merge(array_diff($roleMenuIds, $rmMenus), $addMenus)));
        $effectiveSubmenuIds = array_values(array_unique(array_merge(array_diff($roleSubmenuIds, $rmSubmenus), $addSubmenus)));
        $effectiveButtonIds  = array_values(array_unique(array_merge(array_diff($roleButtonIds, $rmButtons), $addButtons)));

        if (!empty($effectiveSubmenuIds)) {
            $parentMenuIdsFromSubs = NavSubmenu::whereIn('id', $effectiveSubmenuIds)
                ->where('is_active', true)
                ->pluck('menu_id')
                ->toArray();
            $parentMenuIdsFromSubs = array_diff($parentMenuIdsFromSubs, $rmMenus);
            $effectiveMenuIds = array_values(array_unique(array_merge($effectiveMenuIds, $parentMenuIdsFromSubs)));
        }

        if (!$roleType || empty($effectiveMenuIds)) {
            return response()->json([
                'success' => true,
                'data'    => [],
            ]);
        }

        $parentMenus = NavParentMenu::with(['menus' => function ($q) use ($effectiveMenuIds, $effectiveSubmenuIds, $effectiveButtonIds, $roleType) {
            $q->where('is_active', true)
              ->where('type', $roleType)
              ->whereIn('id', $effectiveMenuIds)
              ->orderBy('sort_order')
              ->with(['submenus' => function ($q2) use ($effectiveSubmenuIds, $effectiveButtonIds) {
                  $q2->where('is_active', true)
                     ->whereIn('id', $effectiveSubmenuIds ?: [0])
                     ->orderBy('sort_order')
                     ->with(['buttons' => function ($q3) use ($effectiveButtonIds) {
                         $q3->where('is_active', true)
                            ->whereIn('id', $effectiveButtonIds ?: [0])
                            ->orderBy('sort_order');
                     }]);
              }]);
        }])
            ->where('is_active', true)
            ->where('type', $roleType)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->filter(fn ($parent) => $parent->menus->isNotEmpty())
            ->values();

        return response()->json([
            'success' => true,
            'data'    => $parentMenus,
        ]);
    }
}
