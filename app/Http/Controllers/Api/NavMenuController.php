<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NavParentMenu;
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

        $role     = $user->roles->first();
        $roleType = $role?->role_type;
        $roleId   = $role?->id;

        $effectiveMenuIds    = null;
        $effectiveSubmenuIds = null;
        $effectiveButtonIds  = null;

        if ($roleId) {
            $rolePerms = RoleNavPermission::where('role_id', $roleId)->get();

            if ($rolePerms->isNotEmpty()) {
                $roleMenuIds    = $rolePerms->where('nav_item_type', 'menu')->pluck('nav_item_id')->toArray();
                $roleSubmenuIds = $rolePerms->where('nav_item_type', 'submenu')->pluck('nav_item_id')->toArray();
                $roleButtonIds  = $rolePerms->where('nav_item_type', 'button')->pluck('nav_item_id')->toArray();

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
            }
        }

        $parentMenus = NavParentMenu::with(['menus' => function ($q) use ($effectiveMenuIds, $effectiveSubmenuIds, $effectiveButtonIds) {
            $q->where('is_active', true)
              ->when($effectiveMenuIds !== null, fn ($q2) => $q2->whereIn('id', $effectiveMenuIds ?: [0]))
              ->orderBy('sort_order')
              ->with(['submenus' => function ($q2) use ($effectiveSubmenuIds, $effectiveButtonIds) {
                  $q2->where('is_active', true)
                     ->when($effectiveSubmenuIds !== null, fn ($q3) => $q3->whereIn('id', $effectiveSubmenuIds ?: [0]))
                     ->orderBy('sort_order')
                     ->with(['buttons' => function ($q3) use ($effectiveButtonIds) {
                         $q3->where('is_active', true)
                            ->when($effectiveButtonIds !== null, fn ($q4) => $q4->whereIn('id', $effectiveButtonIds ?: [0]))
                            ->orderBy('sort_order');
                     }]);
              }]);
        }])
            ->where('is_active', true)
            ->when($roleType, fn ($q) => $q->where('type', $roleType))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $parentMenus,
        ]);
    }
}
