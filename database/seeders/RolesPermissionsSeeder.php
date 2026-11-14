<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use App\Models\SubModule;
use Illuminate\Database\Seeder;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear Spatie permission cache first
        app()['cache']
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));

        // ── MODULES, SUB-MODULES & PERMISSIONS ─────────────────────────────
        $structure = [
            'Academics' => [
                'Section'               => ['Add', 'Edit', 'List'],
                'Class'                 => ['Add', 'Edit', 'List'],
                'School Class Mapping'  => ['Add', 'View'],
                'Class Version Mapping' => ['Add', 'Edit', 'List'],
                'Academic Year'         => ['Add', 'Edit', 'List'],
            ],
            'Dashboard' => [
                'Overview'       => ['Number Of Student', 'Number Of Teacher', 'Number Of Course'],
                'Test Dashboard' => ['View', 'Hello', 'Hello Two'],
            ],
            'Role & Permission' => [
                'Role'  => ['Add', 'Edit', 'Assign Permission', 'Assign WB Permission'],
                'Users' => ['Add', 'Edit', 'Quick Update', 'List'],
            ],
            'School' => [
                'School Info'  => ['Add', 'Edit', 'List', 'View'],
                'Bulk School'  => ['Upload', 'Download'],
                'School Admin' => ['Add', 'Edit', 'List', 'View'],
            ],
            'My Calendar' => [
                'Calendar' => ['View', 'Add Event', 'Edit Event', 'Delete Event'],
            ],
            'Courses' => [
                'Course Management' => ['Add', 'Edit', 'List', 'Delete', 'Publish'],
                'Course Content'    => ['Add', 'Edit', 'View', 'Delete'],
                'Enrollments'       => ['View', 'List', 'Export'],
            ],
            'Users Management' => [
                'Students' => ['Add', 'Edit', 'List', 'Delete', 'View'],
                'Teachers' => ['Add', 'Edit', 'List', 'Delete', 'View'],
                'Admins'   => ['Add', 'Edit', 'List', 'Delete'],
            ],
            'Reports' => [
                'Analytics'      => ['View', 'Export'],
                'Audit Log'      => ['View', 'Export'],
                'Student Report' => ['View', 'Export'],
            ],
        ];

        $allPermissions = [];

        foreach ($structure as $moduleName => $subModules) {
            $module = Module::updateOrCreate(
                ['module_name' => $moduleName],
                ['module_name' => $moduleName]
            );

            foreach ($subModules as $subName => $actions) {
                $subModule = SubModule::updateOrCreate(
                    [
                        'module_id' => $module->id,
                        'name'      => $subName,
                    ],
                    [
                        'module_id' => $module->id,
                        'name'      => $subName,
                    ]
                );

                foreach ($actions as $action) {
                    $base = strtolower($moduleName . ' ' . $subName . ' ' . $action);
                    $slug = trim(preg_replace('/[^a-z0-9]+/', '-', $base), '-');

                    $perm = Permission::updateOrCreate(
                        [
                            'name'       => $slug,
                            'guard_name' => 'web',
                        ],
                        [
                            'display_name'  => $action,
                            'description'   => $action . ' in ' . $subName,
                            'module_id'     => $module->id,
                            'sub_module'    => $subName,
                            'sub_module_id' => $subModule->id,
                        ]
                    );

                    $allPermissions[$moduleName][$subName][] = $perm;
                }
            }
        }

        // ── STANDALONE PERMISSIONS ───────────────────────────────────────
        // These permissions do not belong to a sub module, they were created
        // directly on the "Manage Permissions" screen and are rendered as
        // top level checkboxes of the Dashboard module.
        $dashboardModule = Module::where('module_name', 'Dashboard')->first();
        $testDashboard   = SubModule::where('name', 'Test Dashboard')->first();

        $standalonePermissions = [
            ['name' => 'view',      'display_name' => 'View',     'sub_module_id' => null],
            ['name' => 'hello',     'display_name' => 'Hello',    'sub_module_id' => $testDashboard?->id],
            ['name' => 'jello-two', 'display_name' => 'Hello Two', 'sub_module_id' => $testDashboard?->id],
        ];

        $standalone = [];

        if ($dashboardModule) {
            foreach ($standalonePermissions as $standalonePermission) {
                $perm = Permission::updateOrCreate(
                    [
                        'name'       => $standalonePermission['name'],
                        'guard_name' => 'web',
                    ],
                    [
                        'display_name'  => $standalonePermission['display_name'],
                        'description'   => '',
                        'module_id'     => $dashboardModule->id,
                        'sub_module'    => '',
                        'sub_module_id' => $standalonePermission['sub_module_id'],
                    ]
                );

                $standalone[] = $perm;
            }
        }

        // ── ROLES (Matches RoleController validation: role_type in admin|teacher|student) ──
        $roles = [
            [
                'name'         => 'super-admin',
                'display_name' => 'Super Admin',
                'guard_name'   => 'web',
                'role_type'    => 'admin',
                'description'  => 'Super Administrator with full system access',
            ],
            [
                'name'         => 'wb-admin',
                'display_name' => 'WB-Admin',
                'guard_name'   => 'web',
                'role_type'    => 'admin',
                'description'  => 'OpenBrain Administrator',
            ],
            [
                'name'         => 'school-admin',
                'display_name' => 'School Admin',
                'guard_name'   => 'web',
                'role_type'    => 'admin',
                'description'  => 'School Administrator',
            ],
            [
                'name'         => 'wb-teacher',
                'display_name' => 'WB-Teacher',
                'guard_name'   => 'web',
                'role_type'    => 'teacher',
                'description'  => 'OpenBrain Teacher',
            ],
            [
                'name'         => 'student',
                'display_name' => 'Student',
                'guard_name'   => 'web',
                'role_type'    => 'student',
                'description'  => 'Student Role',
            ],
        ];

        foreach ($roles as $r) {
            Role::updateOrCreate(
                [
                    'name'       => $r['name'],
                    'guard_name' => $r['guard_name'],
                ],
                [
                    'display_name' => $r['display_name'],
                    'role_type'    => $r['role_type'],
                    'description'  => $r['description'],
                ]
            );
        }

        // ── ASSIGN PERMISSIONS TO ROLES ────────────────────────────────────
        // 1. Super Admin -> all permissions
        $superAdmin = Role::where('name', 'super-admin')->first();
        if ($superAdmin) {
            $superAdmin->syncPermissions(Permission::pluck('id')->toArray());
        }

        // 2. Student -> Academics, Dashboard, My Calendar
        $student = Role::where('name', 'student')->first();
        if ($student) {
            $studentPerms = [];
            foreach (['Academics', 'Dashboard', 'My Calendar'] as $mod) {
                if (isset($allPermissions[$mod])) {
                    foreach ($allPermissions[$mod] as $sub) {
                        foreach ($sub as $perm) {
                            $studentPerms[] = $perm->id;
                        }
                    }
                }
            }
            $student->syncPermissions($studentPerms);
        }

        // 3. WB-Teacher -> Academics, Dashboard, Courses, Reports
        $teacher = Role::where('name', 'wb-teacher')->first();
        if ($teacher) {
            $teacherPerms = Permission::whereIn(
                'module_id',
                Module::whereIn('module_name', ['Academics', 'Dashboard', 'Courses', 'Reports'])->pluck('id')
            )->pluck('id')->toArray();
            $teacher->syncPermissions($teacherPerms);
        }

        // 4. School Admin -> School, Users Management, Courses, Reports
        $schoolAdmin = Role::where('name', 'school-admin')->first();
        if ($schoolAdmin) {
            $adminPerms = Permission::whereIn(
                'module_id',
                Module::whereIn('module_name', ['School', 'Users Management', 'Courses', 'Reports'])->pluck('id')
            )->pluck('id')->toArray();
            $schoolAdmin->syncPermissions($adminPerms);
        }

        // 5. WB-Admin -> All modules except Role & Permission
        $wbAdmin = Role::where('name', 'wb-admin')->first();
        if ($wbAdmin) {
            $wbAdminPerms = Permission::whereIn(
                'module_id',
                Module::whereIn('module_name', ['Academics', 'Dashboard', 'School', 'My Calendar', 'Courses', 'Users Management', 'Reports'])->pluck('id')
            )->pluck('id')->toArray();
            $wbAdmin->syncPermissions($wbAdminPerms);
        }

        $this->command?->info(
            'Seeded ' . Role::count() . ' roles, ' .
            Permission::count() . ' permissions, ' .
            Module::count() . ' modules, ' .
            SubModule::count() . ' sub-modules.'
        );
    }
}
