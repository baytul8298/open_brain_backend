<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

/**
 * Seeds the users of the application.
 *
 * A user is spread over four tables:
 *
 *   auth.users            -> the account itself (uuid primary key, role and
 *                            status are PostgreSQL enum columns)
 *   core.profiles         -> the public profile of the account; the primary key
 *                            is the same uuid and it cascades on user delete
 *   core.teacher_profiles -> only for teachers
 *   core.student_profiles -> only for students
 *
 * The Spatie role of the user is stored in auth.model_has_roles
 * (role_id / model_type / model_id).
 *
 * The seeder only uses the query builder, so it does not depend on any
 * Eloquent model, and it is idempotent: every user is matched by its e-mail
 * address, so running the seeder twice does not create duplicates.
 */
class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // The teacher profile references the teacher type, so make sure it exists.
        /*if (DB::table('core.teacher_type')->count() === 0) {
            $this->call(TeacherTypeSeeder::class);
        }*/

        $teacherTypeId = DB::table('core.teacher_type')->orderBy('id')->value('id');

        foreach ($this->users() as $data) {
            $this->seedUser($data, $teacherTypeId);
        }

        $this->command?->info('Seeded ' . DB::table('users')->count() . ' users with their profiles.');
    }

    /**
     * The users that are created. The password hashes are the ones from the
     * production database, so the seeded accounts keep working with the
     * credentials that are already in use.
     */
    private function users(): array
    {
        return [
            // ── Super administrator ────────────────────────────────────────
            [
                'id'              => 'e1dd49ad-fec6-42b7-b870-3b7232b2fbed',
                'email'           => 'leo.baytul@gmail.com',
                'phone'           => '+8801700000001',
                'username'        => 'superadmin',
                'password_hash'   => '$2y$12$1YiJLYeQfDgMGA2Hkq2f7utB.6L4vTDQQDD1ERDt63lMNkaw7wfLK',
                'role'            => 'super_admin',
                'status'          => 'active',
                'user_type'       => 'admin',
                'language'        => 'en',
                'email_verified'  => true,
                'phone_verified'  => true,
                'email_verified_at' => '2026-11-10 09:38:10+00',
                'spatie_role'     => 'super-admin',
                'profile'         => [
                    'first_name'   => 'Super',
                    'last_name'    => 'Admin',
                    'display_name' => 'Super Admin',
                    'country'      => 'BD',
                    'city'         => 'Dhaka',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
            ],

            // ── Teachers ───────────────────────────────────────────────────
            [
                'id'              => '4f9e6836-7d9b-42b3-b78e-8de7e228d531',
                'email'           => 'Wahiduzzaman@mailinator.com',
                'phone'           => '01730910979',
                'username'        => 'wahid',
                'password_hash'   => '$2y$12$T0H7mNSM3nUoQUwGFX2wMe7Pg9F2addDR6cCS8knMgP/3qXE8u/4C',
                'salt'            => 'MTIzNDU2',
                'role'            => 'teacher',
                'status'          => 'active',
                'user_type'       => 'waiter',
                'language'        => 'en',
                'email_verified'  => true,
                'phone_verified'  => true,
                'email_verified_at' => '2026-11-10 09:38:10+00',
                'spatie_role'     => 'wb-teacher',
                'profile'         => [
                    'first_name'   => 'Wahiduzzaman',
                    'last_name'    => '',
                    'display_name' => 'Wahiduzzaman',
                    'address'      => 'Esse quo officia dol',
                    'contact_no'   => '01730910979',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
                'teacher_profile' => ['headline' => null],
            ],
            [
                'id'              => '01a19886-4290-7041-9ccf-692b92f872fd',
                'email'           => 'navibaytul3322@gmail.com',
                'phone'           => '+8801765513668',
                'username'        => 'baytul hossen talukder',
                'password_hash'   => '$2y$12$iT.RXRw17kHKmhjSbRhz8eSpGGeb2YNLMuEGCZajb6oFcLtQqhnEq',
                'role'            => 'teacher',
                'status'          => 'active',
                'user_type'       => 'teacher',
                'language'        => 'en',
                'email_verified'  => false,
                'phone_verified'  => true,
                'email_verified_at' => null,
                'spatie_role'     => 'wb-teacher',
                'profile'         => [
                    'first_name'   => 'Md.',
                    'last_name'    => 'Baytul Hossen Talukder',
                    'display_name' => 'Md.',
                    'contact_no'   => '+8801765513668',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
                'teacher_profile' => ['headline' => null],
            ],
            [
                'id'              => '01a1989a-664c-71cc-8dfb-d8964f31349e',
                'email'           => 'Ashraful@gmail.com',
                'phone'           => '+8801765513669',
                'username'        => 'islam',
                'password_hash'   => '$2y$12$iMj3v.MlgedzXUmg1qWG/ewv1E1leO2s0Q0pzIPSmaXwWSqJAHYzC',
                'role'            => 'teacher',
                'status'          => 'active',
                'user_type'       => 'teacher',
                'language'        => 'en',
                'email_verified'  => false,
                'phone_verified'  => true,
                'email_verified_at' => null,
                'spatie_role'     => 'wb-teacher',
                'profile'         => [
                    'first_name'   => 'Ashraful',
                    'last_name'    => 'Islam',
                    'display_name' => 'Ashraful',
                    'contact_no'   => '+8801765513669',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
                'teacher_profile' => ['headline' => null],
            ],
            [
                'id'              => '019dcb0f-19eb-7016-b364-ddcbec735680',
                'email'           => 'jane@gmail.com',
                'phone'           => '+101765512558',
                'username'        => 'smith',
                'password_hash'   => '$2y$12$KTyuKdfeEJCQCmdeGKfkhudwLVHuRP60BjMPsiZQKEAt14/PQVz..',
                'role'            => 'teacher',
                'status'          => 'active',
                'user_type'       => 'teacher',
                'language'        => 'en',
                'email_verified'  => false,
                'phone_verified'  => true,
                'email_verified_at' => null,
                'spatie_role'     => 'wb-teacher',
                'profile'         => [
                    'first_name'   => 'Jane',
                    'last_name'    => 'Smith',
                    'display_name' => 'Jane',
                    'contact_no'   => '+101765512558',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
                'teacher_profile' => ['headline' => null],
            ],
            [
                'id'              => '019dcb4f-d5e0-73ac-95d9-6b279883a08c',
                'email'           => 'kelly@gmail.com',
                'phone'           => '+8801826351216',
                'username'        => 'jo',
                'password_hash'   => '$2y$12$pJD.X9QYtHAB.PVIlQ3dxucyid3SCJbqy3bAW5UH84n1t.gIRQ93u',
                'role'            => 'teacher',
                'status'          => 'pending_verification',
                'user_type'       => 'teacher',
                'language'        => 'en',
                'email_verified'  => false,
                'phone_verified'  => false,
                'email_verified_at' => null,
                'spatie_role'     => 'wb-teacher',
                'profile'         => [
                    'first_name'   => 'Kelly',
                    'last_name'    => 'Jo',
                    'display_name' => 'Kelly',
                    'contact_no'   => '+8801826351216',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
                'teacher_profile' => ['headline' => null],
            ],
            [
                'id'              => '019dcb5b-1d59-702a-9b98-cf1dd5c6c169',
                'email'           => 'harry@gmail.com',
                'phone'           => '+88001765513668',
                'username'        => 'brook',
                'password_hash'   => '$2y$12$Srux2zdq.SyrvzMFiB/FoOxhntJ4rZJiCy5zT7lSlphnjCVMgV/Ae',
                'role'            => 'teacher',
                'status'          => 'active',
                'user_type'       => 'teacher',
                'language'        => 'en',
                'email_verified'  => false,
                'phone_verified'  => true,
                'email_verified_at' => null,
                'spatie_role'     => 'wb-teacher',
                'profile'         => [
                    'first_name'   => 'Harry',
                    'last_name'    => 'Brook',
                    'display_name' => 'Harry',
                    'bio'          => 'My updated bio text.',
                    'contact_no'   => '+88001765513668',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
                'teacher_profile' => ['headline' => 'Senior Math Teacher'],
            ],
            [
                'id'              => '01a1a20d-a0e9-72b2-a517-b3938efb4969',
                'email'           => 'jamal@gmail',
                'phone'           => '+8801826021031',
                'username'        => 'jamal',
                'password_hash'   => '$2y$12$nvDrxMEL1zcP5hG03HF2i.mbibynLXQR0IK4voyGoWgbc6zfLDxRy',
                'role'            => 'teacher',
                'status'          => 'active',
                'user_type'       => 'teacher',
                'language'        => 'en',
                'email_verified'  => false,
                'phone_verified'  => true,
                'email_verified_at' => null,
                'spatie_role'     => 'wb-teacher',
                'profile'         => [
                    'first_name'   => 'jamal',
                    'last_name'    => '',
                    'display_name' => 'Jamal',
                    'contact_no'   => '+8801826021031',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
                'teacher_profile' => ['headline' => null],
            ],

            // ── Students ───────────────────────────────────────────────────
            [
                'id'              => '019d9223-0c77-7322-b869-0c7d0744f35d',
                'email'           => 'hossen15@gmail.com',
                'phone'           => '01826028031',
                'username'        => 'hossen',
                'password_hash'   => '$2y$12$VzXQ9/E6AqHniGZ6v4x8n.WOv.KHQqSmYuGVVk7QC5N87hl7dXHrK',
                'salt'            => 'QmF5dHVsQDEyMw==',
                'role'            => 'student',
                'status'          => 'active',
                'user_type'       => 'student',
                'language'        => 'en',
                'email_verified'  => true,
                'phone_verified'  => true,
                'email_verified_at' => null,
                'spatie_role'     => 'student',
                'profile'         => [
                    'first_name'    => 'Baytul',
                    'last_name'     => 'Talukder',
                    'display_name'  => 'Baytul',
                    'bio'           => "Hi, I'm Rahim Uddin, a Class 9 student from Dhaka preparing for my SSC examination in 2027.",
                    'date_of_birth' => '1996-01-01',
                    'city'          => 'Dhaka',
                    'contact_no'    => '01826028031',
                    'country'       => 'BD',
                    'timezone'      => 'Asia/Dhaka',
                    'language'      => 'en',
                ],
                'student_profile' => ['school_name' => 'Daffodil International University'],
            ],
            [
                'id'              => '019dc506-ae03-7104-bd88-fe4ab57fa0a4',
                'email'           => 'rahim.uddin@gmail.com',
                'phone'           => '01715586520',
                'username'        => 'uddin',
                'password_hash'   => '$2y$12$HcHccj9RbSab7hf50vGQv.BjTtMidNXq4i3xPb5dKU9KfZIWuFyKq',
                'role'            => 'student',
                'status'          => 'active',
                'user_type'       => 'student',
                'language'        => 'en',
                'email_verified'  => false,
                'phone_verified'  => true,
                'email_verified_at' => null,
                'spatie_role'     => 'student',
                'profile'         => [
                    'first_name'   => 'Rahim',
                    'last_name'    => 'Uddin',
                    'display_name' => 'Rahim',
                    'contact_no'   => '01715586520',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
                'student_profile' => [],
            ],
            [
                'id'              => '019dcaf1-a301-73a0-943d-09cd1e872d7c',
                'email'           => 'karim@gmail.com',
                'phone'           => '01765512669',
                'username'        => 'jannat',
                'password_hash'   => '$2y$12$k2I1GQAa.yL7G/EOG0Wu/.a8YTGvieYGDCuvochLKksF6UlsUGggO',
                'role'            => 'student',
                'status'          => 'active',
                'user_type'       => 'student',
                'language'        => 'en',
                'email_verified'  => false,
                'phone_verified'  => true,
                'email_verified_at' => null,
                'spatie_role'     => 'student',
                'profile'         => [
                    'first_name'   => 'Karim',
                    'last_name'    => 'Jannat',
                    'display_name' => 'Karim',
                    'contact_no'   => '01765512669',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
                'student_profile' => [],
            ],
            [
                'id'              => '019dcb4e-b8c4-7216-b083-d39bdd8ea9c6',
                'email'           => 'milon@gmail.com',
                'phone'           => '01826023064',
                'username'        => 'molla',
                'password_hash'   => '$2y$12$MRhBVsXwx.fgzwHvySL8zOzzvYpWu9nb39glMzQpLHRtEe0Oy6v.m',
                'role'            => 'student',
                'status'          => 'pending_verification',
                'user_type'       => 'student',
                'language'        => 'en',
                'email_verified'  => false,
                'phone_verified'  => false,
                'email_verified_at' => null,
                'spatie_role'     => 'student',
                'profile'         => [
                    'first_name'   => 'Milon',
                    'last_name'    => 'Molla',
                    'display_name' => 'Milon',
                    'contact_no'   => '01826023064',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
                'student_profile' => [],
            ],
            [
                'id'              => '019dcb58-a854-706d-ad81-387cffb229a9',
                'email'           => 'malini@gmail.com',
                'phone'           => '01826021063',
                'username'        => 'rekha',
                'password_hash'   => '$2y$12$BaSPqgo2toXZ.MmW5Ma2EuiGIOXCBpDSFQYIIVgFvqFFplC/3cfqe',
                'role'            => 'student',
                'status'          => 'active',
                'user_type'       => 'student',
                'language'        => 'en',
                'email_verified'  => false,
                'phone_verified'  => true,
                'email_verified_at' => null,
                'spatie_role'     => 'student',
                'profile'         => [
                    'first_name'   => 'Malini',
                    'last_name'    => 'Rekha',
                    'display_name' => 'Malini',
                    'contact_no'   => '01826021063',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
                'student_profile' => [],
            ],
            [
                'id'              => '0a047f48-92fd-4691-949e-9a8b8ea5ea43',
                'email'           => 'testauth@example.com',
                'phone'           => null,
                'username'        => 'testauth',
                'password_hash'   => '$2y$12$1YiJLYeQfDgMGA2Hkq2f7utB.6L4vTDQQDD1ERDt63lMNkaw7wfLK',
                'role'            => 'student',
                'status'          => 'active',
                'user_type'       => 'student',
                'language'        => 'en',
                'email_verified'  => true,
                'phone_verified'  => true,
                'email_verified_at' => null,
                'spatie_role'     => 'student',
                'profile'         => [
                    'first_name'   => 'Test',
                    'last_name'    => 'Student',
                    'display_name' => 'Test Student',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
            ],
        ];
    }

    /**
     * Writes one user together with its profile, its role specific profile and
     * the Spatie role assignment.
     */
    private function seedUser(array $data, ?int $teacherTypeId): void
    {
        $now = now();

        $userAttributes = [
            'email'              => $data['email'],
            'phone'              => $data['phone'] ?? null,
            'username'           => $data['username'] ?? null,
            'role'               => $data['role'],
            'status'             => $data['status'],
            'user_type'          => $data['user_type'] ?? null,
            'salt'               => $data['salt'] ?? null,
            'language'           => $data['language'] ?? 'en',
            'email_verified'     => $data['email_verified'] ?? false,
            'phone_verified'     => $data['phone_verified'] ?? false,
            'email_verified_at'  => $data['email_verified_at'] ?? null,
        ];

        $user = DB::table('users')->where('email', $data['email'])->first();

        if ($user) {
            // The moment a mail was verified is a historical fact, so it is not
            // overwritten when the seeder runs again on an existing account.
            unset($userAttributes['email_verified_at']);

            DB::table('users')->where('id', $user->id)->update($userAttributes + ['updated_at' => $now]);
            $userId = $user->id;
        } else {
            $userId = $data['id'];

            DB::table('users')->insert($userAttributes + [
                'id'            => $userId,
                'password_hash' => $data['password_hash'] ?? Hash::make('password'),
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }

        // ── core.profiles ────────────────────────────────────────────────
        if (! empty($data['profile'])) {
            $profile = $data['profile'];

            $existing = DB::table('core.profiles')->where('id', $userId)->first();

            $profileValues = [
                'first_name'   => $profile['first_name'] ?? '',
                'last_name'    => $profile['last_name'] ?? '',
                'display_name' => $profile['display_name'] ?? null,
                'avatar_url'   => $profile['avatar_url'] ?? null,
                'cover_url'    => $profile['cover_url'] ?? null,
                'bio'          => $profile['bio'] ?? null,
                'date_of_birth' => $profile['date_of_birth'] ?? null,
                'gender'       => $profile['gender'] ?? null,
                'country'      => $profile['country'] ?? 'BD',
                'city'         => $profile['city'] ?? null,
                'timezone'     => $profile['timezone'] ?? 'Asia/Dhaka',
                'language'     => $profile['language'] ?? 'en',
                'address'      => $profile['address'] ?? null,
                'contact_no'   => $profile['contact_no'] ?? null,
                'location'     => $profile['location'] ?? null,
            ];

            if ($existing) {
                DB::table('core.profiles')->where('id', $userId)->update($profileValues + ['updated_at' => $now]);
            } else {
                DB::table('core.profiles')->insert($profileValues + [
                    'id'         => $userId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // ── core.teacher_profiles ────────────────────────────────────────
        if (isset($data['teacher_profile'])) {
            $this->seedProfileRow(
                'core.teacher_profiles',
                $userId,
                [
                    'teacher_type_id' => $teacherTypeId,
                    'headline'        => $data['teacher_profile']['headline'] ?? null,
                ],
                ['user_id']
            );
        }

        // ── core.student_profiles ────────────────────────────────────────
        if (isset($data['student_profile'])) {
            $this->seedProfileRow(
                'core.student_profiles',
                $userId,
                [
                    'school_name' => $data['student_profile']['school_name'] ?? null,
                ],
                ['user_id']
            );
        }

        // ── auth.model_has_roles ─────────────────────────────────────────
        if (! empty($data['spatie_role'])) {
            $roleId = DB::table('roles')
                ->where('name', $data['spatie_role'])
                ->where('guard_name', 'web')
                ->value('id');

            if ($roleId) {
                $alreadyAssigned = DB::table('model_has_roles')
                    ->where('role_id', $roleId)
                    ->where('model_type', 'App\\Models\\User')
                    ->where('model_id', $userId)
                    ->exists();

                if (! $alreadyAssigned) {
                    DB::table('model_has_roles')->insert([
                        'role_id'    => $roleId,
                        'model_type' => 'App\\Models\\User',
                        'model_id'   => $userId,
                    ]);
                }
            }
        }
    }

    /**
     * Writes a row into one of the profile tables that are linked to the user
     * through a user_id column.
     *
     * core.teacher_profiles and core.student_profiles are keyed by user_id and
     * only carry an updated_at column, so the statement is built from the
     * columns that the table actually has.
     */
    private function seedProfileRow(string $table, string $userId, array $values, array $keyColumns): void
    {
        $existing = DB::table($table);

        foreach ($keyColumns as $column) {
            $existing->where($column, $userId);
        }

        if ($existing->first()) {
            DB::table($table)
                ->where($keyColumns[0], $userId)
                ->update($values + ['updated_at' => now()]);

            return;
        }

        $insert = $values;

        foreach ($keyColumns as $column) {
            $insert[$column] = $userId;
        }

        $insert['updated_at'] = now();

        if (Schema::hasColumn($table, 'created_at')) {
            $insert['created_at'] = now();
        }

        DB::table($table)->insert($insert);
    }
}
