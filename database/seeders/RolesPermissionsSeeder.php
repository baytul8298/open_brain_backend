<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use App\Models\SubModule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear Spatie permission cache first
        app()['cache']
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));

        // Clear existing data (pivot first, then permissions/roles/modules)
        DB::table('role_has_permissions')->delete();
        DB::table('model_has_permissions')->delete();
        DB::table('model_has_roles')->delete();
        Permission::query()->delete();
        Role::query()->delete();
        SubModule::query()->delete();
        Module::query()->delete();

        // ── MODULES & PERMISSIONS ──────────────────────────────────────────
        $structure = [
            'Academics' => [
                'Section'               => ['Add', 'Edit', 'List'],
                'Class'                 => ['Add', 'Edit', 'List'],
                'School Class Mapping'  => ['Add', 'View'],
                'Class Version Mapping' => ['Add', 'Edit', 'List'],
                'Academic Year'         => ['Add', 'Edit', 'List'],
            ],
            'Dashboard' => [
                'Overview' => ['Number Of Student', 'Number Of Teacher', 'Number Of Course'],
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
                'Students'  => ['Add', 'Edit', 'List', 'Delete', 'View'],
                'Teachers'  => ['Add', 'Edit', 'List', 'Delete', 'View'],
                'Admins'    => ['Add', 'Edit', 'List', 'Delete'],
            ],
            'Reports' => [
                'Analytics'      => ['View', 'Export'],
                'Audit Log'      => ['View', 'Export'],
                'Student Report' => ['View', 'Export'],
            ],
        ];

        $allPermissions = [];

        foreach ($structure as $moduleName => $subModules) {
            $module = Module::create(['module_name' => $moduleName]);

            foreach ($subModules as $subName => $actions) {
                $subModule = SubModule::create([
                    'module_id' => $module->id,
                    'name'      => $subName,
                ]);

                foreach ($actions as $action) {
                    // Slug: "academics.section.add"
                    $base = strtolower($moduleName . ' ' . $subName . ' ' . $action);
                    $slug = trim(preg_replace('/[^a-z0-9]+/', '-', $base), '-');

                    $perm = Permission::create([
                        'name'          => $slug,
                        'display_name'  => $action,
                        'description'   => $action . ' in ' . $subName,
                        'guard_name'    => 'web',
                        'module_id'     => $module->id,
                        'sub_module'    => $subName,
                        'sub_module_id' => $subModule->id,
                    ]);

                    $allPermissions[$moduleName][$subName][] = $perm;
                }
            }
        }

        // ── ROLES ──────────────────────────────────────────────────────────
        $roles = [
            ['name' => 'student',      'display_name' => 'Student',      'role_type' => 'User Defined'],
            ['name' => 'wb-teacher',   'display_name' => 'WB-Teacher',   'role_type' => 'User Defined'],
            ['name' => 'wb-admin',     'display_name' => 'WB-Admin',     'role_type' => 'User Defined'],
            ['name' => 'school-admin', 'display_name' => 'School Admin', 'role_type' => 'User Defined'],
        ];

        foreach ($roles as $r) {
            Role::create([
                'name'         => $r['name'],
                'display_name' => $r['display_name'],
                'guard_name'   => 'web',
                'role_type'    => $r['role_type'],
            ]);
        }

        // ── ASSIGN SAMPLE PERMISSIONS TO STUDENT ROLE ─────────────────────
        $student = Role::where('name', 'student')->first();
        if ($student) {
            // Give student: Academics fully + Dashboard view + Calendar view
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

        // Give school-admin most permissions
        $schoolAdmin = Role::where('name', 'school-admin')->first();
        if ($schoolAdmin) {
            $adminPerms = Permission::whereIn('module_id',
                Module::whereIn('module_name', ['School', 'Users Management', 'Courses', 'Reports'])->pluck('id')
            )->pluck('id')->toArray();
            $schoolAdmin->syncPermissions($adminPerms);
        }

        $this->command->info('Seeded ' . Role::count() . ' roles, ' . Permission::count() . ' permissions, ' . Module::count() . ' modules, ' . SubModule::count() . ' sub-modules.');
    }
}
