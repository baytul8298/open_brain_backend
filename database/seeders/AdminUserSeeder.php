<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds the super administrator of the application.
 *
 * This seeder is deliberately separate from UsersSeeder: it creates one admin
 * account and nothing else, so it can be run on its own whenever an admin
 * account is needed.
 *
 * It writes into:
 *
 *   users            -> the account itself (uuid primary key, role and status
 *                       are PostgreSQL enum columns)
 *   core.profiles    -> the public profile of the account
 *   roles            -> the Spatie role of the account
 *   permissions      -> the Spatie permissions
 *   role_has_permissions    -> which permissions the role carries
 *   model_has_roles         -> which role the account has
 *   model_has_permissions   -> the permissions of the account itself
 *
 * The schema of the permission tables is not the plain Spatie default in this
 * project: a permission carries a module_id that points to a module, and a few
 * extra descriptive columns. The seeder therefore reads the real columns of
 * every table it writes to and only fills the columns that really exist. A
 * column that the table requires but that cannot be filled is reported in the
 * output instead of aborting the whole seed.
 *
 * The seeder only uses the query builder, so it does not depend on any Eloquent
 * model. It is idempotent: the account is matched by its e-mail address, the
 * role by its name, and every permission by its name, so running the seeder
 * twice does not create duplicates.
 */
class AdminUserSeeder extends Seeder
{
    /** The uuid of the admin account. It is only used on the first insert. */
    private const ADMIN_ID = '01a1c9a1-5cc6-7204-bc93-48153eff57d8';

    private const ADMIN_EMAIL = 'sudhumatro@gmail.com';

    private const ADMIN_PHONE = '+8801700000002';

    private const ADMIN_USERNAME = 'sudhumatro';

    /** The plain password of the seeded account. */
    private const ADMIN_PASSWORD = '123456';

    /** The Spatie role that is assigned to the account. */
    private const SPATIE_ROLE = 'super-admin';

    private const GUARD = 'web';

    /** The model that the Spatie pivot tables point to. */
    private const MODEL_TYPE = 'App\\Models\\User';

    /** The tables of the Spatie permission tables, without the schema prefix. */
    private const ROLES_TABLE = 'roles';

    private const PERMISSIONS_TABLE = 'permissions';

    private const ROLE_HAS_PERMISSIONS_TABLE = 'role_has_permissions';

    private const MODEL_HAS_ROLES_TABLE = 'model_has_roles';

    private const MODEL_HAS_PERMISSIONS_TABLE = 'model_has_permissions';

    /**
     * The columns of the tables that were already read, so the schema is only
     * looked up once per table.
     *
     * @var array<string, array<string, array{nullable: bool, default: mixed, type: string}>>
     */
    private array $columns = [];

    /**
     * The module of every permission that was written, so the pivot tables can
     * be filled with the same module.
     *
     * @var array<int, int|null>
     */
    private array $moduleIds = [];

    /** The rows that could not be written, with the reason why. */
    private array $skipped = [];

    public function run(): void
    {
        $userId = $this->seedUser();
        $this->seedProfile($userId);

        $roleId = $this->ensureRole();
        $permissionIds = $this->ensurePermissions();

        // A super administrator gets every permission of the guard, the ones
        // that already exist in the project and the ones created above.
        $allPermissionIds = $this->allPermissionIds();

        $this->assignPermissionsToRole($roleId, $allPermissionIds);
        $this->assignRoleToUser($roleId, $userId);
        $this->assignPermissionsToUser($userId, $allPermissionIds);

        $this->command?->info('Seeded the super administrator ' . self::ADMIN_EMAIL
            . ' with the ' . self::SPATIE_ROLE . ' role and ' . count($allPermissionIds) . ' permissions.');

        foreach (array_keys($this->skipped) as $skip) {
            $this->command?->warn('Skipped: ' . $skip);
        }    }

    /**
     * Creates the admin account, or updates it when it already exists.
     *
     * @return string the uuid of the account
     */
    private function seedUser(): string
    {
        $now = now();

        $attributes = [
            'email'             => self::ADMIN_EMAIL,
            'phone'             => self::ADMIN_PHONE,
            'username'          => self::ADMIN_USERNAME,
            'role'              => 'super_admin',
            'status'            => 'active',
            'user_type'         => 'admin',
            'language'          => 'en',
            'email_verified'    => true,
            'phone_verified'    => true,
            'email_verified_at' => $now,
            'updated_at'        => $now,
        ];

        $user = DB::table('users')->where('email', self::ADMIN_EMAIL)->first();

        if ($user) {
            // The moment a mail was verified is a historical fact, so it is not
            // overwritten when the seeder runs again on an existing account.
            unset($attributes['email_verified_at']);

            // The password is only reset when the account does not use the
            // password of the seeder yet, so a password that was changed by
            // hand is not overwritten on every run.
            if (! password_verify(self::ADMIN_PASSWORD, $user->password_hash ?? '')) {
                $attributes['password_hash'] = Hash::make(self::ADMIN_PASSWORD);
            }

            DB::table('users')->where('id', $user->id)->update($attributes);

            return $user->id;
        }

        DB::table('users')->insert($attributes + [
            'id'            => self::ADMIN_ID,
            'password_hash' => Hash::make(self::ADMIN_PASSWORD),
            'created_at'    => $now,
        ]);

        return self::ADMIN_ID;
    }

    /**
     * Creates the profile of the account, or updates it when it already exists.
     */
    private function seedProfile(string $userId): void
    {
        $now = now();

        $profile = [
            'first_name'   => 'Sudhumatro',
            'last_name'    => '',
            'display_name' => 'Sudhumatro',
            'avatar_url'   => null,
            'cover_url'    => null,
            'bio'          => null,
            'date_of_birth' => null,
            'gender'       => null,
            'country'      => 'BD',
            'city'         => 'Dhaka',
            'timezone'     => 'Asia/Dhaka',
            'language'     => 'en',
            'address'      => null,
            'contact_no'   => self::ADMIN_PHONE,
            'location'     => null,
            'updated_at'   => $now,
        ];

        $existing = DB::table('core.profiles')->where('id', $userId)->first();

        if ($existing) {
            DB::table('core.profiles')->where('id', $userId)->update($profile);

            return;
        }

        DB::table('core.profiles')->insert($profile + [
            'id'         => $userId,
            'created_at' => $now,
        ]);
    }

    /**
     * Makes sure the Spatie role of the admin exists.
     *
     * @return int the id of the role
     */
    private function ensureRole(): int
    {
        $roleId = DB::table(self::ROLES_TABLE)
            ->where('name', self::SPATIE_ROLE)
            ->where('guard_name', self::GUARD)
            ->value('id');

        if ($roleId) {
            return (int) $roleId;
        }

        $now = now();

        $this->insertRow(self::ROLES_TABLE, [
            'name'       => self::SPATIE_ROLE,
            'guard_name' => self::GUARD,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return (int) DB::table(self::ROLES_TABLE)
            ->where('name', self::SPATIE_ROLE)
            ->where('guard_name', self::GUARD)
            ->value('id');
    }

    /**
     * Makes sure the permissions of the admin exist.
     *
     * A permission that already exists in the project is left alone. Only the
     * missing ones are created, and they are created with the module and the
     * descriptive columns that the table of the project really has.
     *
     * @return array<int> the ids of the created permissions
     */
    private function ensurePermissions(): array
    {
        $now = now();
        $created = [];

        foreach ($this->permissions() as $module => $actions) {
            foreach ($actions as $action) {
                $existing = $this->findPermission($module, $action);

                if ($existing) {
                    $this->moduleIds[(int) $existing->id] = $this->moduleIdOfPermission((int) $existing->id);

                    continue;
                }

                $name = $module . '.' . $action;
                $moduleRow = $this->resolveModule($module);

                $values = [
                    'name'       => $name,
                    'guard_name' => self::GUARD,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                // The descriptive columns are optional, so they are only written
                // when the table of the project has them.
                $values += [
                    'module_name' => $moduleRow['name'],
                    'group_name'  => $moduleRow['name'],
                    'description' => 'Manage ' . $moduleRow['name'],
                    'is_active'   => true,
                ];

                $inserted = $this->insertRow(self::PERMISSIONS_TABLE, $values, [
                    'module_id' => fn () => $moduleRow['id'],
                ]);

                if (! $inserted) {
                    continue;
                }

                $id = (int) DB::table(self::PERMISSIONS_TABLE)
                    ->where('name', $name)
                    ->where('guard_name', self::GUARD)
                    ->value('id');

                $this->moduleIds[$id] = $moduleRow['id'];
                $created[] = $id;
            }
        }

        return $created;
    }

    /**
     * Finds the permission of a module and an action that already exists.
     *
     * The project names its modules in its own way, so the module of the
     * permission is compared without the plural and the singular, and the
     * action is compared without the underscore and the dot. So the permission
     * "users.list" of the project is found for the module "user" and the
     * action "list".
     */
    private function findPermission(string $module, string $action): ?object
    {
        $action = str_replace('_', '.', $action);

        $candidates = DB::table(self::PERMISSIONS_TABLE)
            ->where('guard_name', self::GUARD)
            ->get();

        foreach ($candidates as $candidate) {
            if (! isset($candidate->name) || ! str_contains($candidate->name, '.')) {
                continue;
            }

            [$candidateModule, $candidateAction] = explode('.', $candidate->name, 2);

            if ($this->sameModule($candidateModule, $module)
                && $this->sameAction($candidateAction, $action)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * Two module names are the same when they only differ in the plural.
     */
    private function sameModule(string $left, string $right): bool
    {
        $left = strtolower($left);
        $right = strtolower($right);

        if ($left === $right) {
            return true;
        }

        return rtrim($left, 's') === rtrim($right, 's');
    }

    /**
     * Two action names are the same when they only differ in the separator.
     */
    private function sameAction(string $left, string $right): bool
    {
        return strtolower(str_replace(['.', '_', '-'], '', $left))
            === strtolower(str_replace(['.', '_', '-'], '', $right));
    }

    /**
     * All permission ids of the guard, the ones that already existed and the
     * ones that were created above.
     *
     * @return array<int>
     */
    private function allPermissionIds(): array
    {
        return DB::table(self::PERMISSIONS_TABLE)
            ->where('guard_name', self::GUARD)
            ->orderBy('id')
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /**
     * Gives every permission to the role of the admin.
     *
     * @param  array<int>  $permissionIds
     */
    private function assignPermissionsToRole(int $roleId, array $permissionIds): void
    {
        foreach ($permissionIds as $permissionId) {
            $exists = DB::table(self::ROLE_HAS_PERMISSIONS_TABLE)
                ->where('role_id', $roleId)
                ->where('permission_id', $permissionId)
                ->exists();

            if ($exists) {
                continue;
            }

            $this->insertRow(self::ROLE_HAS_PERMISSIONS_TABLE, [
                'role_id'       => $roleId,
                'permission_id' => $permissionId,
            ], [
                'module_id' => fn () => $this->moduleIds[$permissionId] ?? null,
            ]);
        }
    }

    /**
     * Gives the role of the admin to the admin account.
     */
    private function assignRoleToUser(int $roleId, string $userId): void
    {
        $exists = DB::table(self::MODEL_HAS_ROLES_TABLE)
            ->where('role_id', $roleId)
            ->where('model_type', self::MODEL_TYPE)
            ->where('model_id', $userId)
            ->exists();

        if ($exists) {
            return;
        }

        $this->insertRow(self::MODEL_HAS_ROLES_TABLE, [
            'role_id'    => $roleId,
            'model_type' => self::MODEL_TYPE,
            'model_id'   => $userId,
        ]);
    }

    /**
     * Gives every permission to the admin account itself, so the account keeps
     * the full access even when the role of the account is changed later.
     *
     * @param  array<int>  $permissionIds
     */
    private function assignPermissionsToUser(string $userId, array $permissionIds): void
    {
        foreach ($permissionIds as $permissionId) {
            $exists = DB::table(self::MODEL_HAS_PERMISSIONS_TABLE)
                ->where('permission_id', $permissionId)
                ->where('model_type', self::MODEL_TYPE)
                ->where('model_id', $userId)
                ->exists();

            if ($exists) {
                continue;
            }

            $this->insertRow(self::MODEL_HAS_PERMISSIONS_TABLE, [
                'permission_id' => $permissionId,
                'model_type'    => self::MODEL_TYPE,
                'model_id'      => $userId,
            ], [
                'module_id' => fn () => $this->moduleIds[$permissionId] ?? null,
            ]);
        }
    }

    /**
     * The permissions that the admin needs, as module and action.
     *
     * The permission names of the project are built as "<module>.<action>", so
     * the list is written as modules with their actions. A permission that
     * already exists in the project is left alone, so the names of the project
     * are never changed and no duplicate is created.
     *
     * @return array<string, array<int, string>>
     */
    private function permissions(): array
    {
        return [
            'user' => [
                'view',
                'create',
                'update',
                'delete',
            ],
            'profile' => [
                'view',
                'update',
            ],
            'role' => [
                'view',
                'create',
                'update',
                'delete',
            ],
            'permission' => [
                'view',
                'create',
                'update',
                'delete',
            ],
        ];
    }

    /**
     * Inserts a row, but only into the columns that the table really has.
     *
     * A column that the table requires but that the row does not carry is
     * resolved through the given resolvers. When a required column cannot be
     * resolved, nothing is inserted and false is returned.
     *
     * @param  array<string, callable>  $resolvers
     */
    private function insertRow(string $table, array $values, array $resolvers = []): bool
    {
        $columns = $this->columnsOf($table);

        if ($columns === []) {
            $this->skipped['the table ' . $table . ' does not exist'] = true;

            return false;
        }

        $row = [];

        foreach ($values as $column => $value) {
            if (array_key_exists($column, $columns)) {
                $row[$column] = $value;
            }
        }

        foreach ($this->requiredColumnsOf($table) as $column) {
            if (array_key_exists($column, $row)) {
                continue;
            }

            if (isset($resolvers[$column])) {
                $resolved = $resolvers[$column]();

                if ($resolved !== null) {
                    $row[$column] = $resolved;

                    continue;
                }
            }

            $this->skipped[$this->skipReason($table, $column)] = true;

            return false;
        }

        DB::table($table)->insert($row);

        return true;
    }

    /**
     * The reason why a row was skipped, as a key so the same reason is only
     * reported once.
     */
    private function skipReason(string $table, string $column): string
    {
        return 'the table ' . $table . ' needs the column ' . $column
            . ', which the seeder cannot fill';
    }

    /**
     * The columns of a table, read from the database itself.
     *
     * @return array<string, array{nullable: bool, default: mixed, type: string}>
     */
    private function columnsOf(string $table): array
    {
        if (array_key_exists($table, $this->columns)) {
            return $this->columns[$table];
        }

        [$schema, $name] = $this->splitTable($table);

        $rows = DB::select(
            'select column_name, is_nullable, column_default, data_type
             from information_schema.columns
             where table_schema = ? and table_name = ?',
            [$schema, $name]
        );

        $columns = [];

        foreach ($rows as $row) {
            $columns[$row->column_name] = [
                'nullable' => $row->is_nullable === 'YES',
                'default'  => $row->column_default,
                'type'     => $row->data_type,
            ];
        }

        return $this->columns[$table] = $columns;
    }

    /**
     * The columns of a table that have to be filled in by the seeder.
     *
     * A column that the database fills itself, like a serial id, is not one
     * of them. A column that has a default is still one of them, because the
     * seeder knows the right value and the default is only a fallback.
     *
     * @return array<int, string>
     */
    private function requiredColumnsOf(string $table): array
    {
        $required = [];

        foreach ($this->columnsOf($table) as $name => $column) {
            if ($column['nullable']) {
                continue;
            }

            if ($this->isGeneratedColumn($column)) {
                continue;
            }

            $required[] = $name;
        }

        return $required;
    }

    /**
     * Whether the database fills the column itself, like a serial id.
     *
     * @param  array{nullable: bool, default: mixed, type: string}  $column
     */
    private function isGeneratedColumn(array $column): bool
    {
        $default = $column['default'];

        return is_string($default) && str_starts_with(strtolower($default), 'nextval(');
    }

    /**
     * Splits a table name into its schema and its name. A table without a
     * schema is looked up in the schemas of the search path.
     *
     * @return array{0: string, 1: string}
     */
    private function splitTable(string $table): array
    {
        if (str_contains($table, '.')) {
            [$schema, $name] = explode('.', $table, 2);

            return [$schema, $name];
        }

        $schema = DB::select('select current_schema() as schema')[0]->schema;

        return [$schema, $table];
    }

    /**
     * The module that a permission belongs to.
     *
     * The module is taken from the part of the permission name in front of the
     * dot, so "user.view" belongs to the module "user".
     *
     * @return array{id: int|null, name: string}
     */
    private function resolveModule(string $module): array
    {
        $name = $module;

        $table = $this->moduleTable();

        if ($table === null) {
            // There is no module table, so the module of a permission that
            // already exists in the project is used.
            return ['id' => $this->moduleIdOfPermissionByName($module), 'name' => $name];
        }

        $columns = $this->columnsOf($table);

        $existing = $this->findModule($table, $name);

        if ($existing) {
            return ['id' => (int) $existing->id, 'name' => $name];
        }

        $values = ['name' => $name, 'created_at' => now(), 'updated_at' => now()];

        // The slug is only written when the table has it.
        if (array_key_exists('slug', $columns)) {
            $values['slug'] = $name;
        }

        $resolvers = [];

        foreach ($this->requiredColumnsOf($table) as $column) {
            if (array_key_exists($column, $values)) {
                continue;
            }

            $resolvers[$column] = match ($column) {
                'slug' => fn () => $name,
                'title', 'label', 'key' => fn () => $name,
                'description' => fn () => 'Module ' . $name,
                'is_active', 'active', 'enabled' => fn () => true,
                default => fn () => null,
            };
        }

        if (! $this->insertRow($table, $values, $resolvers)) {
            return ['id' => $this->moduleIdOfPermissionByName($module), 'name' => $name];
        }

        $moduleRow = $this->findModule($table, $name);

        return ['id' => $moduleRow ? (int) $moduleRow->id : null, 'name' => $name];
    }

    /**
     * Finds a module by its name, the plural and the singular are the same.
     */
    private function findModule(string $table, string $name): ?object
    {
        $candidates = DB::table($table)->get();

        foreach ($candidates as $candidate) {
            if (! isset($candidate->name)) {
                continue;
            }

            if ($this->sameModule($candidate->name, $name)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * The table that holds the modules of the permissions.
     */
    private function moduleTable(): ?string
    {
        // The table is found through the foreign key of the module_id, so the
        // real table of the project is used and not a guessed name.
        $row = DB::select(
            "select ccu.table_schema as schema, ccu.table_name as name
             from information_schema.table_constraints tc
             join information_schema.key_column_usage kcu
               on kcu.constraint_name = tc.constraint_name
              and kcu.constraint_schema = tc.constraint_schema
             join information_schema.constraint_column_usage ccu
               on ccu.constraint_name = tc.constraint_name
              and ccu.constraint_schema = tc.constraint_schema
             where tc.constraint_type = 'FOREIGN KEY'
               and kcu.column_name = 'module_id'
               and tc.table_name = ?
             limit 1",
            [$this->splitTable(self::PERMISSIONS_TABLE)[1]]
        );

        if ($row) {
            return $row[0]->schema . '.' . $row[0]->name;
        }

        // Without a foreign key the module table is looked up by its name.
        foreach (['modules', 'module', 'permission_modules'] as $candidate) {
            [$schema, $name] = $this->splitTable($candidate);

            $exists = DB::select(
                'select 1 from information_schema.tables where table_schema = ? and table_name = ?',
                [$schema, $name]
            );

            if ($exists) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * The module id of a permission that already exists.
     */
    private function moduleIdOfPermission(int $permissionId): ?int
    {
        if (! $this->hasColumn(self::PERMISSIONS_TABLE, 'module_id')) {
            return null;
        }

        $row = DB::table(self::PERMISSIONS_TABLE)
            ->where('id', $permissionId)
            ->first();

        if (! $row || ! property_exists($row, 'module_id')) {
            return null;
        }

        return $row->module_id === null ? null : (int) $row->module_id;
    }

    /**
     * The module id that the project already uses for the permissions of the
     * same module, so a permission is attached to the module it belongs to.
     */
    private function moduleIdOfPermissionByName(string $module): ?int
    {
        $candidates = DB::table(self::PERMISSIONS_TABLE)
            ->where('guard_name', self::GUARD)
            ->get();

        $hasModuleId = $this->hasColumn(self::PERMISSIONS_TABLE, 'module_id');

        foreach ($candidates as $candidate) {
            if (! isset($candidate->name) || ! str_contains($candidate->name, '.')) {
                continue;
            }

            [$candidateModule] = explode('.', $candidate->name, 2);

            if ($this->sameModule($candidateModule, $module)
                && $hasModuleId && $candidate->module_id !== null) {
                return (int) $candidate->module_id;
            }
        }

        if (! $hasModuleId) {
            return null;
        }

        $row = DB::table(self::PERMISSIONS_TABLE)
            ->whereNotNull('module_id')
            ->orderBy('id')
            ->first();

        return $row ? (int) $row->module_id : null;
    }

    /**
     * Whether a table has a column.
     */
    private function hasColumn(string $table, string $column): bool
    {
        return array_key_exists($column, $this->columnsOf($table));
    }
}
