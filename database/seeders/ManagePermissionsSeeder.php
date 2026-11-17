<?php

namespace Database\Seeders;

use App\Models\NavButton;
use App\Models\NavMenu;
use App\Models\NavSubmenu;
use App\Models\Role;
use App\Models\RoleNavPermission;
use App\Models\User;
use App\Models\UserNavPermission;
use Illuminate\Database\Seeder;

class ManagePermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // ══════════════════════════════════════════════════════════════════
        // 1. ROLE NAV PERMISSIONS (role_nav_permissions)
        //    Used by "Manage Permissions" (/manage-permissions -> Role tab)
        // ══════════════════════════════════════════════════════════════════

        // ── A. Super Admin Role Permissions ───────────────────────────────
        $superAdminRole = Role::where('name', 'super-admin')->first();
        if ($superAdminRole) {
            $adminMenuKeys = [
                'admin_dashboard',
                'admin_analytics',
                'users',
                'teachers',
                'Courses',
                'Enrollments',
                'Assignments',
                'Live Sessions',
                'reviews',
                'subjects',
                'payments',
                'payouts',
                'coupons',
                'moderation',
                'notifications',
                'settings',
                'feature-flags',
                'audit-log',
                'grade_level',
            ];

            $adminMenus = NavMenu::whereIn('key', $adminMenuKeys)->get();
            foreach ($adminMenus as $menu) {
                RoleNavPermission::updateOrCreate([
                    'role_id'       => $superAdminRole->id,
                    'nav_item_type' => 'menu',
                    'nav_item_id'   => $menu->id,
                ]);
            }

            // Submenus under System Settings ("Setting", "Roles", "Manage Permissions", "Menu Manage")
            $settingsMenu = NavMenu::where('key', 'settings')->first();
            if ($settingsMenu) {
                $settingsSubmenus = NavSubmenu::where('menu_id', $settingsMenu->id)
                    ->whereIn('name', ['Setting', 'Roles', 'Manage Permissions', 'Menu Manage'])
                    ->get();

                foreach ($settingsSubmenus as $sub) {
                    RoleNavPermission::updateOrCreate([
                        'role_id'       => $superAdminRole->id,
                        'nav_item_type' => 'submenu',
                        'nav_item_id'   => $sub->id,
                    ]);

                    // Buttons under Roles submenu
                    $buttons = NavButton::where('submenu_id', $sub->id)->get();
                    foreach ($buttons as $btn) {
                        RoleNavPermission::updateOrCreate([
                            'role_id'       => $superAdminRole->id,
                            'nav_item_type' => 'button',
                            'nav_item_id'   => $btn->id,
                        ]);
                    }
                }
            }
        }

        // ── B. WB-Teacher Role Permissions ────────────────────────────────
        $teacherRole = Role::where('name', 'wb-teacher')->first();
        if ($teacherRole) {
            $teacherMenuKeys = [
                'teacher_dashboard',
                'teacher_profile',
                'my_students',
                'teacher_give_asignment',
                'teacher_course',
                'teacher_create_course',
                'teacher_analytics',
            ];

            $teacherMenus = NavMenu::whereIn('key', $teacherMenuKeys)->get();
            foreach ($teacherMenus as $menu) {
                RoleNavPermission::updateOrCreate([
                    'role_id'       => $teacherRole->id,
                    'nav_item_type' => 'menu',
                    'nav_item_id'   => $menu->id,
                ]);
            }
        }

        // ── C. Student Role Permissions ───────────────────────────────────
        $studentRole = Role::where('name', 'student')->first();
        if ($studentRole) {
            $studentMenuKeys = [
                'student_dashboard',
                'student_profile',
            ];

            $studentMenus = NavMenu::whereIn('key', $studentMenuKeys)->get();
            foreach ($studentMenus as $menu) {
                RoleNavPermission::updateOrCreate([
                    'role_id'       => $studentRole->id,
                    'nav_item_type' => 'menu',
                    'nav_item_id'   => $menu->id,
                ]);
            }
        }

        // ══════════════════════════════════════════════════════════════════
        // 2. USER NAV PERMISSIONS (user_nav_permissions)
        //    Used by "Manage Permissions" (/manage-permissions -> Users tab)
        // ══════════════════════════════════════════════════════════════════
        $wahidUser = User::where('email', 'Wahiduzzaman@mailinator.com')->first();
        if ($wahidUser) {
            $accademyMenu = NavMenu::where('key', 'Accademy')->first();
            if ($accademyMenu) {
                UserNavPermission::updateOrCreate(
                    [
                        'user_id'       => $wahidUser->id,
                        'nav_item_type' => 'menu',
                        'nav_item_id'   => $accademyMenu->id,
                    ],
                    ['is_granted' => true]
                );

                $schoolSubmenu = NavSubmenu::where('menu_id', $accademyMenu->id)
                    ->where('name', 'School')
                    ->first();

                if ($schoolSubmenu) {
                    UserNavPermission::updateOrCreate(
                        [
                            'user_id'       => $wahidUser->id,
                            'nav_item_type' => 'submenu',
                            'nav_item_id'   => $schoolSubmenu->id,
                        ],
                        ['is_granted' => true]
                    );

                    $studentsBtn = NavButton::where('submenu_id', $schoolSubmenu->id)
                        ->where('key_value', 'Students')
                        ->first();

                    if ($studentsBtn) {
                        UserNavPermission::updateOrCreate(
                            [
                                'user_id'       => $wahidUser->id,
                                'nav_item_type' => 'button',
                                'nav_item_id'   => $studentsBtn->id,
                            ],
                            ['is_granted' => true]
                        );
                    }
                }
            }
        }

        $this->command?->info(
            'Seeded ' . RoleNavPermission::count() . ' role nav permissions, ' .
            UserNavPermission::count() . ' user nav permissions.'
        );
    }
}
