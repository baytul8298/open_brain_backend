<?php

namespace Database\Seeders;

use App\Models\NavButton;
use App\Models\NavMenu;
use App\Models\NavParentMenu;
use App\Models\NavSubmenu;
use Illuminate\Database\Seeder;

class NavMenuSeeder extends Seeder
{
    public function run(): void
    {
        // ══════════════════════════════════════════════════════════════════
        // 1. PARENT MENUS (nav_parent_menus)
        // ══════════════════════════════════════════════════════════════════
        $parentDefinitions = [
            // Admin Parent Menus
            'admin_overview'  => ['name' => 'Overview', 'type' => 'admin',   'guard' => 'web', 'sort_order' => 0, 'is_active' => true],
            'admin_people'    => ['name' => 'People',   'type' => 'admin',   'guard' => 'web', 'sort_order' => 1, 'is_active' => true],
            'admin_content'   => ['name' => 'Content',  'type' => 'admin',   'guard' => 'web', 'sort_order' => 2, 'is_active' => true],
            'admin_commerce'  => ['name' => 'Commerce', 'type' => 'admin',   'guard' => 'web', 'sort_order' => 3, 'is_active' => true],
            'admin_safety'    => ['name' => 'Safety',   'type' => 'admin',   'guard' => 'web', 'sort_order' => 4, 'is_active' => true],
            'admin_system'    => ['name' => 'System',   'type' => 'admin',   'guard' => 'web', 'sort_order' => 5, 'is_active' => true],

            // Student Parent Menus
            'student_main'    => ['name' => 'Main',     'type' => 'student', 'guard' => 'web', 'sort_order' => 6, 'is_active' => true],

            // Teacher Parent Menus
            'teacher_main'    => ['name' => 'Main',     'type' => 'teacher', 'guard' => 'web', 'sort_order' => 7, 'is_active' => true],
            'teacher_content' => ['name' => 'Content',  'type' => 'teacher', 'guard' => 'web', 'sort_order' => 8, 'is_active' => true],
            'teacher_account' => ['name' => 'Account',  'type' => 'teacher', 'guard' => 'web', 'sort_order' => 9, 'is_active' => true],
        ];

        $parents = [];
        foreach ($parentDefinitions as $alias => $def) {
            $parents[$alias] = NavParentMenu::updateOrCreate(
                ['name' => $def['name'], 'type' => $def['type']],
                [
                    'guard'      => $def['guard'],
                    'sort_order' => $def['sort_order'],
                    'is_active'  => $def['is_active'],
                ]
            );
        }

        // ══════════════════════════════════════════════════════════════════
        // 2. MENUS (nav_menus)
        // ══════════════════════════════════════════════════════════════════
        $menuDefinitions = [
            // ── Admin: Overview ───────────────────────────────────────────
            [
                'parent'     => 'admin_overview',
                'name'       => 'Dashboard',
                'type'       => 'admin',
                'key'        => 'admin_dashboard',
                'icon_key'   => 'fas fa-th-large',
                'to'         => 'dashboard',
                'search_key' => 'dashboard',
                'guard'      => 'web',
                'sort_order' => 0,
                'is_active'  => true,
            ],
            [
                'parent'     => 'admin_overview',
                'name'       => 'Analytics',
                'type'       => 'admin',
                'key'        => 'admin_analytics',
                'icon_key'   => 'fas fa-chart-line',
                'to'         => 'analytics',
                'search_key' => 'analytics',
                'guard'      => 'web',
                'sort_order' => 1,
                'is_active'  => true,
            ],

            // ── Admin: People ─────────────────────────────────────────────
            [
                'parent'     => 'admin_people',
                'name'       => 'All Users',
                'type'       => 'admin',
                'key'        => 'users',
                'icon_key'   => 'fas fa-users',
                'to'         => '/users',
                'search_key' => 'users',
                'guard'      => 'web',
                'sort_order' => 2,
                'is_active'  => true,
            ],
            [
                'parent'     => 'admin_people',
                'name'       => 'Teachers',
                'type'       => 'admin',
                'key'        => 'teachers',
                'icon_key'   => 'fas fa-user-tie',
                'to'         => '/teachers',
                'search_key' => 'teachers',
                'guard'      => 'web',
                'sort_order' => 3,
                'is_active'  => true,
            ],

            // ── Admin: Content ────────────────────────────────────────────
            [
                'parent'     => 'admin_content',
                'name'       => 'Courses',
                'type'       => 'admin',
                'key'        => 'Courses',
                'icon_key'   => 'fas fa-book',
                'to'         => '/courses',
                'search_key' => 'Courses',
                'guard'      => 'web',
                'sort_order' => 4,
                'is_active'  => true,
            ],
            [
                'parent'     => 'admin_content',
                'name'       => 'Enrollments',
                'type'       => 'admin',
                'key'        => 'Enrollments',
                'icon_key'   => 'fas fa-user-plus',
                'to'         => '/enrollments',
                'search_key' => 'Enrollments',
                'guard'      => 'web',
                'sort_order' => 5,
                'is_active'  => true,
            ],
            [
                'parent'     => 'admin_content',
                'name'       => 'Assignments',
                'type'       => 'admin',
                'key'        => 'Assignments',
                'icon_key'   => 'fas fa-file-alt',
                'to'         => '/assignments',
                'search_key' => 'Assignments',
                'guard'      => 'web',
                'sort_order' => 6,
                'is_active'  => true,
            ],
            [
                'parent'     => 'admin_content',
                'name'       => 'Live Sessions',
                'type'       => 'admin',
                'key'        => 'Live Sessions',
                'icon_key'   => 'fas fa-video',
                'to'         => '/live-sessions',
                'search_key' => 'Live Sessions',
                'guard'      => 'web',
                'sort_order' => 7,
                'is_active'  => true,
            ],
            [
                'parent'     => 'admin_content',
                'name'       => 'Reviews',
                'type'       => 'admin',
                'key'        => 'reviews',
                'icon_key'   => 'fas fa-star',
                'to'         => '/reviews',
                'search_key' => 'Reviews',
                'guard'      => 'web',
                'sort_order' => 8,
                'is_active'  => true,
            ],
            [
                'parent'     => 'admin_content',
                'name'       => 'Subjects',
                'type'       => 'admin',
                'key'        => 'subjects',
                'icon_key'   => 'fas fa-globe',
                'to'         => '/subjects',
                'search_key' => 'Subjects',
                'guard'      => 'web',
                'sort_order' => 9,
                'is_active'  => true,
            ],
            [
                'parent'     => 'admin_content',
                'name'       => 'Classes',
                'type'       => 'admin',
                'key'        => 'admin_class',
                'icon_key'   => 'fa fa-laptop',
                'to'         => '/class-name',
                'search_key' => 'Class Name',
                'guard'      => 'web',
                'sort_order' => 21,
                'is_active'  => false,
            ],
            [
                'parent'     => 'admin_content',
                'name'       => 'Grade Level',
                'type'       => 'admin',
                'key'        => 'grade_level',
                'icon_key'   => 'fa fa-laptop',
                'to'         => '/grade-level',
                'search_key' => 'Grade Level',
                'guard'      => 'web',
                'sort_order' => 22,
                'is_active'  => true,
            ],

            // ── Admin: Commerce ───────────────────────────────────────────
            [
                'parent'     => 'admin_commerce',
                'name'       => 'Payments',
                'type'       => 'admin',
                'key'        => 'payments',
                'icon_key'   => 'fas fa-credit-card',
                'to'         => '/payments',
                'search_key' => 'Payments',
                'guard'      => 'web',
                'sort_order' => 10,
                'is_active'  => true,
            ],
            [
                'parent'     => 'admin_commerce',
                'name'       => 'Payouts',
                'type'       => 'admin',
                'key'        => 'payouts',
                'icon_key'   => 'fas fa-dollar-sign',
                'to'         => '/payouts',
                'search_key' => 'Payouts',
                'guard'      => 'web',
                'sort_order' => 11,
                'is_active'  => true,
            ],
            [
                'parent'     => 'admin_commerce',
                'name'       => 'Coupons',
                'type'       => 'admin',
                'key'        => 'coupons',
                'icon_key'   => 'fas fa-tags',
                'to'         => '/coupons',
                'search_key' => 'Coupons',
                'guard'      => 'web',
                'sort_order' => 12,
                'is_active'  => true,
            ],

            // ── Admin: Safety ─────────────────────────────────────────────
            [
                'parent'     => 'admin_safety',
                'name'       => 'Moderation',
                'type'       => 'admin',
                'key'        => 'moderation',
                'icon_key'   => 'fas fa-exclamation-triangle',
                'to'         => '/moderation',
                'search_key' => 'Moderation',
                'guard'      => 'web',
                'sort_order' => 13,
                'is_active'  => true,
            ],

            // ── Admin: System ─────────────────────────────────────────────
            [
                'parent'     => 'admin_system',
                'name'       => 'Notifications',
                'type'       => 'admin',
                'key'        => 'notifications',
                'icon_key'   => 'fas fa-bell',
                'to'         => '/notifications',
                'search_key' => 'Notifications',
                'guard'      => 'web',
                'sort_order' => 14,
                'is_active'  => true,
            ],
            [
                'parent'     => 'admin_system',
                'name'       => 'System Settings',
                'type'       => 'admin',
                'key'        => 'settings',
                'icon_key'   => 'fas fa-cog',
                'to'         => null,
                'search_key' => 'Settings',
                'guard'      => 'web',
                'sort_order' => 15,
                'is_active'  => true,
            ],
            [
                'parent'     => 'admin_system',
                'name'       => 'Feature Flags',
                'type'       => 'admin',
                'key'        => 'feature-flags',
                'icon_key'   => 'fas fa-flag',
                'to'         => '/feature-flags',
                'search_key' => null,
                'guard'      => 'web',
                'sort_order' => 16,
                'is_active'  => true,
            ],
            [
                'parent'     => 'admin_system',
                'name'       => 'Audit Log',
                'type'       => 'admin',
                'key'        => 'audit-log',
                'icon_key'   => 'fas fa-clipboard-list',
                'to'         => '/audit-log',
                'search_key' => 'Audit Log',
                'guard'      => 'web',
                'sort_order' => 17,
                'is_active'  => true,
            ],

            // ── Student: Main ─────────────────────────────────────────────
            [
                'parent'     => 'student_main',
                'name'       => 'Dashboard',
                'type'       => 'student',
                'key'        => 'student_dashboard',
                'icon_key'   => null,
                'to'         => '/student/dashboard',
                'search_key' => 'Dashboard',
                'guard'      => 'web',
                'sort_order' => 18,
                'is_active'  => true,
            ],
            [
                'parent'     => 'student_main',
                'name'       => 'Profile',
                'type'       => 'student',
                'key'        => 'student_profile',
                'icon_key'   => null,
                'to'         => '/student/profile',
                'search_key' => 'Profile',
                'guard'      => 'web',
                'sort_order' => 23,
                'is_active'  => true,
            ],

            // ── Teacher: Main ─────────────────────────────────────────────
            [
                'parent'     => 'teacher_main',
                'name'       => 'Dashboard',
                'type'       => 'teacher',
                'key'        => 'teacher_dashboard',
                'icon_key'   => null,
                'to'         => '/teacher/dashboard',
                'search_key' => 'Dashboard',
                'guard'      => 'web',
                'sort_order' => 19,
                'is_active'  => true,
            ],
            [
                'parent'     => 'teacher_main',
                'name'       => 'My Students',
                'type'       => 'teacher',
                'key'        => 'my_students',
                'icon_key'   => null,
                'to'         => '/teacher/students',
                'search_key' => 'My Students',
                'guard'      => 'web',
                'sort_order' => 24,
                'is_active'  => true,
            ],
            [
                'parent'     => 'teacher_main',
                'name'       => 'Assignments',
                'type'       => 'teacher',
                'key'        => 'teacher_give_asignment',
                'icon_key'   => null,
                'to'         => '/teacher/assignments',
                'search_key' => 'Assignments',
                'guard'      => 'web',
                'sort_order' => 25,
                'is_active'  => true,
            ],

            // ── Teacher: Account ──────────────────────────────────────────
            [
                'parent'     => 'teacher_account',
                'name'       => 'Profile',
                'type'       => 'teacher',
                'key'        => 'teacher_profile',
                'icon_key'   => null,
                'to'         => '/teacher/profile',
                'search_key' => 'profile',
                'guard'      => 'web',
                'sort_order' => 20,
                'is_active'  => true,
            ],

            // ── Teacher: Content ──────────────────────────────────────────
            [
                'parent'     => 'teacher_content',
                'name'       => 'My Course',
                'type'       => 'teacher',
                'key'        => 'teacher_course',
                'icon_key'   => null,
                'to'         => '/teacher/courses',
                'search_key' => 'My Course',
                'guard'      => 'web',
                'sort_order' => 26,
                'is_active'  => true,
            ],
            [
                'parent'     => 'teacher_content',
                'name'       => 'Create Course',
                'type'       => 'teacher',
                'key'        => 'teacher_create_course',
                'icon_key'   => null,
                'to'         => '/teacher/courses/create',
                'search_key' => 'Create Course',
                'guard'      => 'web',
                'sort_order' => 27,
                'is_active'  => true,
            ],
            [
                'parent'     => 'teacher_content',
                'name'       => 'Analytics',
                'type'       => 'teacher',
                'key'        => 'teacher_analytics',
                'icon_key'   => null,
                'to'         => '/teacher/analytics',
                'search_key' => 'Analytics',
                'guard'      => 'web',
                'sort_order' => 28,
                'is_active'  => true,
            ],
            [
                'parent'     => 'teacher_content',
                'name'       => 'Accademy',
                'type'       => 'teacher',
                'key'        => 'Accademy',
                'icon_key'   => null,
                'to'         => 'accademy',
                'search_key' => 'Accademy',
                'guard'      => 'web',
                'sort_order' => 29,
                'is_active'  => true,
            ],
        ];

        $menus = [];
        foreach ($menuDefinitions as $m) {
            $menus[$m['key']] = NavMenu::updateOrCreate(
                ['key' => $m['key']],
                [
                    'parent_menu_id' => $parents[$m['parent']]->id,
                    'name'           => $m['name'],
                    'type'           => $m['type'],
                    'icon_key'       => $m['icon_key'],
                    'to'             => $m['to'],
                    'search_key'     => $m['search_key'],
                    'guard'          => $m['guard'],
                    'sort_order'     => $m['sort_order'],
                    'is_active'      => $m['is_active'],
                ]
            );
        }

        // ══════════════════════════════════════════════════════════════════
        // 3. SUBMENUS (nav_submenus)
        //    Including "System Settings" -> "Setting", "Roles",
        //    "Manage Permissions", "Menu Manage"
        // ══════════════════════════════════════════════════════════════════
        $systemSettingsMenu = $menus['settings'];
        $accademyMenu       = $menus['Accademy'];

        $settingSubmenu = NavSubmenu::updateOrCreate(
            ['menu_id' => $systemSettingsMenu->id, 'name' => 'Setting'],
            [
                'icon_key'   => null,
                'search_key' => 'Setting',
                'to'         => '/settings',
                'sort_order' => 0,
                'is_active'  => true,
            ]
        );

        $rolesSubmenu = NavSubmenu::updateOrCreate(
            ['menu_id' => $systemSettingsMenu->id, 'name' => 'Roles'],
            [
                'icon_key'   => null,
                'search_key' => 'Roles',
                'to'         => '/roles',
                'sort_order' => 1,
                'is_active'  => true,
            ]
        );

        $managePermissionsSubmenu = NavSubmenu::updateOrCreate(
            ['menu_id' => $systemSettingsMenu->id, 'name' => 'Manage Permissions'],
            [
                'icon_key'   => null,
                'search_key' => 'Manage Permissions',
                'to'         => '/manage-permissions',
                'sort_order' => 2,
                'is_active'  => true,
            ]
        );

        $menuManageSubmenu = NavSubmenu::updateOrCreate(
            ['menu_id' => $systemSettingsMenu->id, 'name' => 'Menu Manage'],
            [
                'icon_key'   => null,
                'search_key' => 'Menu Manage',
                'to'         => '/menus',
                'sort_order' => 3,
                'is_active'  => true,
            ]
        );

        $schoolSubmenu = NavSubmenu::updateOrCreate(
            ['menu_id' => $accademyMenu->id, 'name' => 'School'],
            [
                'icon_key'   => null,
                'search_key' => 'School',
                'to'         => 'School',
                'sort_order' => 0,
                'is_active'  => true,
            ]
        );

        // ══════════════════════════════════════════════════════════════════
        // 4. BUTTONS (nav_buttons)
        //    Submenu-wise buttons managed inside Menu Manage
        // ══════════════════════════════════════════════════════════════════
        $roleButtons = [
            ['name' => 'Create',            'key_value' => 'role_create',       'sort_order' => 0, 'is_active' => true],
            ['name' => 'Edit',              'key_value' => 'role_edit',         'sort_order' => 1, 'is_active' => true],
            ['name' => 'Delete',            'key_value' => 'role_delete',       'sort_order' => 2, 'is_active' => true],
            ['name' => 'Assign Permission', 'key_value' => 'assign_permission', 'sort_order' => 3, 'is_active' => true],
            ['name' => 'View Permission',   'key_value' => 'view_permission',   'sort_order' => 4, 'is_active' => true],
        ];

        foreach ($roleButtons as $btn) {
            NavButton::updateOrCreate(
                [
                    'submenu_id' => $rolesSubmenu->id,
                    'key_value'  => $btn['key_value'],
                ],
                [
                    'name'       => $btn['name'],
                    'icon_key'   => null,
                    'to'         => null,
                    'sort_order' => $btn['sort_order'],
                    'is_active'  => $btn['is_active'],
                ]
            );
        }

        NavButton::updateOrCreate(
            [
                'submenu_id' => $schoolSubmenu->id,
                'key_value'  => 'Students',
            ],
            [
                'name'       => 'Students',
                'icon_key'   => null,
                'to'         => null,
                'sort_order' => 0,
                'is_active'  => true,
            ]
        );

        $this->command?->info(
            'Seeded ' . NavParentMenu::count() . ' parent menus, ' .
            NavMenu::count() . ' menus, ' .
            NavSubmenu::count() . ' submenus, ' .
            NavButton::count() . ' buttons.'
        );
    }
}
