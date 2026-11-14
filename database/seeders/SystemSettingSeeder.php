<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeds everything behind the "System Settings" entry of the administration menu.
 *
 * The structure that the "Menu Manage" screen renders is:
 *
 *   nav_parent_menus  (1 level)
 *     └── nav_menus            (2 level, linked through parent_menu_id)
 *           └── nav_submenus   (3 level, linked through menu_id)
 *                 └── nav_buttons (4 level, linked through submenu_id)
 *
 * The "Roles", "Manage Permissions" and "Menu Manage" sub menus all live under
 * the "System Settings" menu (key: settings), which itself lives under the
 * "System" parent menu:
 *
 *   System (parent menu, type: admin)
 *     └── System Settings (menu, key: settings, to: null -> it is a group)
 *           ├── Setting            (/settings)
 *           ├── Roles              (/roles)
 *           │     ├── Create           (role_create)
 *           │     ├── Edit             (role_edit)
 *           │     ├── Delete           (role_delete)
 *           │     ├── Assign Permission (assign_permission)
 *           │     └── View Permission   (view_permission)
 *           ├── Manage Permissions (/manage-permissions)
 *           └── Menu Manage        (/menus)
 *
 * The three screens read their data from different tables:
 *
 *   Roles              -> auth.roles, auth.permissions, auth.role_has_permissions
 *   Manage Permissions -> auth.role_nav_permissions (per role) and
 *                         auth.user_nav_permissions (per user)
 *   Menu Manage        -> the four nav_* tables above
 *
 * This seeder writes all of it. It only uses the query builder, so it does not
 * depend on any Eloquent model, and every write is idempotent, so the seeder
 * can safely be executed again on an existing database.
 */
class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedParentMenus();
        $this->seedMenus();
        $this->seedSubMenus();
        $this->seedButtons();
        $this->seedRoles();
        $this->seedRoleNavPermissions();

        $this->command?->info(
            'Seeded ' . DB::table('nav_parent_menus')->where('name', 'System')->count() . ' parent menu, ' .
            DB::table('nav_menus')->where('key', 'settings')->count() . ' settings menu, ' .
            DB::table('nav_submenus')->count() . ' sub menus and ' .
            DB::table('nav_buttons')->count() . ' buttons.'
        );
    }

    /**
     * The parent menu that holds every system entry (Settings, Notifications,
     * Feature Flags, Audit Log).
     */
    private function seedParentMenus(): void
    {
        $this->upsert(
            'nav_parent_menus',
            ['name' => 'System', 'type' => 'admin'],
            ['guard' => 'web', 'sort_order' => 5, 'is_active' => true]
        );
    }

    /**
     * The "System Settings" menu itself. `to` is null because it is a group
     * menu - the sub menus below it carry the routes.
     */
    private function seedMenus(): void
    {
        $parentId = DB::table('nav_parent_menus')
            ->where('name', 'System')
            ->where('type', 'admin')
            ->value('id');

        $this->upsert(
            'nav_menus',
            ['key' => 'settings'],
            [
                'parent_menu_id' => $parentId,
                'name'           => 'System Settings',
                'type'           => 'admin',
                'icon_key'       => 'fas fa-cog',
                'to'             => null,
                'search_key'     => 'Settings',
                'guard'          => 'web',
                'sort_order'     => 15,
                'is_active'      => true,
            ]
        );
    }

    /**
     * The four sub menus of "System Settings".
     */
    private function seedSubMenus(): void
    {
        $menuId = DB::table('nav_menus')->where('key', 'settings')->value('id');

        $subMenus = [
            ['name' => 'Setting',            'to' => '/settings',            'sort_order' => 0],
            ['name' => 'Roles',              'to' => '/roles',              'sort_order' => 1],
            ['name' => 'Manage Permissions', 'to' => '/manage-permissions', 'sort_order' => 2],
            ['name' => 'Menu Manage',        'to' => '/menus',              'sort_order' => 3],
        ];

        foreach ($subMenus as $subMenu) {
            $this->upsert(
                'nav_submenus',
                ['menu_id' => $menuId, 'name' => $subMenu['name']],
                [
                    'icon_key'   => null,
                    'search_key' => $subMenu['name'],
                    'to'         => $subMenu['to'],
                    'sort_order' => $subMenu['sort_order'],
                    'is_active'  => true,
                ]
            );
        }
    }

    /**
     * The action buttons that belong to the "Roles" sub menu. They are the
     * smallest unit of the navigation tree and are used to gate the individual
     * actions of the roles screen.
     */
    private function seedButtons(): void
    {
        $rolesSubMenuId = DB::table('nav_submenus')
            ->where('name', 'Roles')
            ->value('id');

        $buttons = [
            ['name' => 'Create',            'key_value' => 'role_create',       'sort_order' => 0],
            ['name' => 'Edit',              'key_value' => 'role_edit',         'sort_order' => 1],
            ['name' => 'Delete',            'key_value' => 'role_delete',       'sort_order' => 2],
            ['name' => 'Assign Permission', 'key_value' => 'assign_permission', 'sort_order' => 3],
            ['name' => 'View Permission',   'key_value' => 'view_permission',   'sort_order' => 4],
        ];

        foreach ($buttons as $button) {
            $this->upsert(
                'nav_buttons',
                ['submenu_id' => $rolesSubMenuId, 'key_value' => $button['key_value']],
                [
                    'name'       => $button['name'],
                    'icon_key'   => null,
                    'to'         => null,
                    'sort_order' => $button['sort_order'],
                    'is_active'  => true,
                ]
            );
        }
    }

    /**
     * The five application roles. They are shown on the "Roles" screen with
     * their display name, their type and a description.
     */
    private function seedRoles(): void
    {
        $roles = [
            ['name' => 'super-admin',  'display_name' => 'Super Admin',  'role_type' => 'admin',   'description' => 'Super Administrator with full system access'],
            ['name' => 'wb-admin',     'display_name' => 'WB-Admin',     'role_type' => 'admin',   'description' => 'OpenBrain Administrator'],
            ['name' => 'school-admin', 'display_name' => 'School Admin', 'role_type' => 'admin',   'description' => 'School Administrator'],
            ['name' => 'wb-teacher',   'display_name' => 'WB-Teacher',   'role_type' => 'teacher', 'description' => 'OpenBrain Teacher'],
            ['name' => 'student',      'display_name' => 'Student',      'role_type' => 'student', 'description' => 'Student Role'],
        ];

        foreach ($roles as $role) {
            $this->upsert(
                'roles',
                ['name' => $role['name'], 'guard_name' => 'web'],
                [
                    'display_name' => $role['display_name'],
                    'description'  => $role['description'],
                    'role_type'    => $role['role_type'],
                ]
            );
        }
    }

    /**
     * Grants the super administrator role access to the complete "System
     * Settings" branch: the menu, all of its sub menus and every button of the
     * "Roles" sub menu. These rows are what the "Manage Permissions" screen
     * renders as the checked boxes.
     */
    private function seedRoleNavPermissions(): void
    {
        $roleId = DB::table('roles')
            ->where('name', 'super-admin')
            ->where('guard_name', 'web')
            ->value('id');

        if (! $roleId) {
            return;
        }

        $menuId = DB::table('nav_menus')->where('key', 'settings')->value('id');

        $this->grant($roleId, 'menu', $menuId);

        $subMenuIds = DB::table('nav_submenus')
            ->where('menu_id', $menuId)
            ->pluck('id')
            ->all();

        foreach ($subMenuIds as $subMenuId) {
            $this->grant($roleId, 'submenu', $subMenuId);
        }

        $buttonIds = DB::table('nav_buttons')
            ->whereIn('submenu_id', $subMenuIds)
            ->pluck('id')
            ->all();

        foreach ($buttonIds as $buttonId) {
            $this->grant($roleId, 'button', $buttonId);
        }
    }

    /**
     * Writes a single row into role_nav_permissions.
     */
    private function grant(int $roleId, string $navItemType, int $navItemId): void
    {
        $exists = DB::table('role_nav_permissions')
            ->where('role_id', $roleId)
            ->where('nav_item_type', $navItemType)
            ->where('nav_item_id', $navItemId)
            ->exists();

        if ($exists) {
            return;
        }

        DB::table('role_nav_permissions')->insert([
            'role_id'       => $roleId,
            'nav_item_type' => $navItemType,
            'nav_item_id'   => $navItemId,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }

    /**
     * Insert or update a single row, matched on the given attributes.
     */
    private function upsert(string $table, array $attributes, array $values): void
    {
        $row = DB::table($table);

        foreach ($attributes as $column => $value) {
            $row->where($column, $value);
        }

        $existing = $row->first();

        if ($existing) {
            DB::table($table)
                ->where('id', $existing->id)
                ->update($values + ['updated_at' => now()]);

            return;
        }

        DB::table($table)->insert($attributes + $values + [
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
