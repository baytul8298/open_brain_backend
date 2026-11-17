<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Core\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure teacher types exist for teacher_profiles FK
        if (DB::table('core.teacher_type')->count() === 0) {
            $this->call(TeacherTypeSeeder::class);
        }
        $defaultTeacherTypeId = DB::table('core.teacher_type')->orderBy('id')->value('id') ?? 1;

        $defaultPasswordHash = Hash::make('password');

        $users = [
            // ── 1. Super Admin ────────────────────────────────────────────
            [
                'id'                => 'e1dd49ad-fec6-42b7-b870-3b7232b2fbed',
                'email'             => 'leo.baytul@gmail.com',
                'phone'             => '+8801700000001',
                'username'          => 'superadmin',
                'password_hash'     => '$2y$12$1YiJLYeQfDgMGA2Hkq2f7utB.6L4vTDQQDD1ERDt63lMNkaw7wfLK',
                'role'              => UserRole::SuperAdmin->value,
                'status'            => UserStatus::Active->value,
                'user_type'         => 'super-admin',
                'language'          => 'en',
                'email_verified'    => true,
                'phone_verified'    => true,
                'email_verified_at' => now(),
                'spatie_role'       => 'super-admin',
                'profile'           => [
                    'first_name'   => 'Super',
                    'last_name'    => 'Admin',
                    'display_name' => 'Super Admin',
                    'country'      => 'BD',
                    'city'         => 'Dhaka',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
            ],

            // ── 2. Teachers ───────────────────────────────────────────────
            [
                'id'                => '4f9e6836-7d9b-42b3-b78e-8de7e228d531',
                'email'             => 'Wahiduzzaman@mailinator.com',
                'phone'             => '01730910979',
                'username'          => 'wahid',
                'password_hash'     => '$2y$12$T0H7mNSM3nUoQUwGFX2wMe7Pg9F2addDR6cCS8knMgP/3qXE8u/4C',
                'salt'              => 'MTIzNDU2',
                'role'              => UserRole::Teacher->value,
                'status'            => UserStatus::Active->value,
                'user_type'         => 'teacher',
                'language'          => 'en',
                'email_verified'    => true,
                'phone_verified'    => true,
                'email_verified_at' => now(),
                'spatie_role'       => 'wb-teacher',
                'profile'           => [
                    'first_name'   => 'Wahiduzzaman',
                    'last_name'    => '',
                    'display_name' => 'Wahiduzzaman',
                    'address'      => 'Esse quo officia dol',
                    'contact_no'   => '01730910979',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
                'teacher_profile'   => [
                    'headline' => null,
                ],
            ],
            [
                'id'                => '01a19886-4290-7041-9ccf-692b92f872fd',
                'email'             => 'navibaytul3322@gmail.com',
                'phone'             => '+8801765513668',
                'username'          => 'baytul hossen talukder',
                'password_hash'     => '$2y$12$iT.RXRw17kHKmhjSbRhz8eSpGGeb2YNLMuEGCZajb6oFcLtQqhnEq',
                'role'              => UserRole::Teacher->value,
                'status'            => UserStatus::Active->value,
                'user_type'         => 'teacher',
                'language'          => 'en',
                'email_verified'    => false,
                'phone_verified'    => true,
                'spatie_role'       => 'wb-teacher',
                'profile'           => [
                    'first_name'   => 'Md.',
                    'last_name'    => 'Baytul Hossen Talukder',
                    'display_name' => 'Md.',
                    'contact_no'   => '+8801765513668',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
                'teacher_profile'   => [
                    'headline' => null,
                ],
            ],
            [
                'id'                => '01a1989a-664c-71cc-8dfb-d8964f31349e',
                'email'             => 'Ashraful@gmail.com',
                'phone'             => '+8801765513669',
                'username'          => 'islam',
                'password_hash'     => '$2y$12$iMj3v.MlgedzXUmg1qWG/ewv1E1leO2s0Q0pzIPSmaXwWSqJAHYzC',
                'role'              => UserRole::Teacher->value,
                'status'            => UserStatus::Active->value,
                'user_type'         => 'teacher',
                'language'          => 'en',
                'email_verified'    => false,
                'phone_verified'    => true,
                'spatie_role'       => 'wb-teacher',
                'profile'           => [
                    'first_name'   => 'Ashraful',
                    'last_name'    => 'Islam',
                    'display_name' => 'Ashraful',
                    'contact_no'   => '+8801765513669',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
                'teacher_profile'   => [
                    'headline' => null,
                ],
            ],
            [
                'id'                => '019dcb0f-19eb-7016-b364-ddcbec735680',
                'email'             => 'jane@gmail.com',
                'phone'             => '+101765512558',
                'username'          => 'smith',
                'password_hash'     => '$2y$12$KTyuKdfeEJCQCmdeGKfkhudwLVHuRP60BjMPsiZQKEAt14/PQVz..',
                'role'              => UserRole::Teacher->value,
                'status'            => UserStatus::Active->value,
                'user_type'         => 'teacher',
                'language'          => 'en',
                'email_verified'    => false,
                'phone_verified'    => true,
                'spatie_role'       => 'wb-teacher',
                'profile'           => [
                    'first_name'   => 'Jane',
                    'last_name'    => 'Smith',
                    'display_name' => 'Jane',
                    'contact_no'   => '+101765512558',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
                'teacher_profile'   => [
                    'headline' => null,
                ],
            ],
            [
                'id'                => '019dcb4f-d5e0-73ac-95d9-6b279883a08c',
                'email'             => 'kelly@gmail.com',
                'phone'             => '+8801826351216',
                'username'          => 'jo',
                'password_hash'     => '$2y$12$pJD.X9QYtHAB.PVIlQ3dxucyid3SCJbqy3bAW5UH84n1t.gIRQ93u',
                'role'              => UserRole::Teacher->value,
                'status'            => UserStatus::PendingVerification->value,
                'user_type'         => 'teacher',
                'language'          => 'en',
                'email_verified'    => false,
                'phone_verified'    => false,
                'spatie_role'       => 'wb-teacher',
                'profile'           => [
                    'first_name'   => 'Kelly',
                    'last_name'    => 'Jo',
                    'display_name' => 'Kelly',
                    'contact_no'   => '+8801826351216',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
                'teacher_profile'   => [
                    'headline' => null,
                ],
            ],
            [
                'id'                => '019dcb5b-1d59-702a-9b98-cf1dd5c6c169',
                'email'             => 'harry@gmail.com',
                'phone'             => '+88001765513668',
                'username'          => 'brook',
                'password_hash'     => '$2y$12$Srux2zdq.SyrvzMFiB/FoOxhntJ4rZJiCy5zT7lSlphnjCVMgV/Ae',
                'role'              => UserRole::Teacher->value,
                'status'            => UserStatus::Active->value,
                'user_type'         => 'teacher',
                'language'          => 'en',
                'email_verified'    => false,
                'phone_verified'    => true,
                'spatie_role'       => 'wb-teacher',
                'profile'           => [
                    'first_name'   => 'Harry',
                    'last_name'    => 'Brook',
                    'display_name' => 'Harry',
                    'bio'          => 'My updated bio text.',
                    'contact_no'   => '+88001765513668',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
                'teacher_profile'   => [
                    'headline' => 'Senior Math Teacher',
                ],
            ],
            [
                'id'                => '01a1a20d-a0e9-72b2-a517-b3938efb4969',
                'email'             => 'jamal@gmail',
                'phone'             => '+8801826021031',
                'username'          => 'jamal',
                'password_hash'     => '$2y$12$nvDrxMEL1zcP5hG03HF2i.mbibynLXQR0IK4voyGoWgbc6zfLDxRy',
                'role'              => UserRole::Teacher->value,
                'status'            => UserStatus::Active->value,
                'user_type'         => 'teacher',
                'language'          => 'en',
                'email_verified'    => false,
                'phone_verified'    => true,
                'spatie_role'       => 'wb-teacher',
                'profile'           => [
                    'first_name'   => 'jamal',
                    'last_name'    => '',
                    'display_name' => 'Jamal',
                    'contact_no'   => '+8801826021031',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
                'teacher_profile'   => [
                    'headline' => null,
                ],
            ],

            // ── 3. Students ───────────────────────────────────────────────
            [
                'id'                => '019d9223-0c77-7322-b869-0c7d0744f35d',
                'email'             => 'hossen15@gmail.com',
                'phone'             => '01826028031',
                'username'          => 'hossen',
                'password_hash'     => '$2y$12$VzXQ9/E6AqHniGZ6v4x8n.WOv.KHQqSmYuGVVk7QC5N87hl7dXHrK',
                'salt'              => 'QmF5dHVsQDEyMw==',
                'role'              => UserRole::Student->value,
                'status'            => UserStatus::Active->value,
                'user_type'         => 'student',
                'language'          => 'en',
                'email_verified'    => true,
                'phone_verified'    => true,
                'spatie_role'       => 'student',
                'profile'           => [
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
                'student_profile'   => [
                    'school_name' => 'Daffodil International University',
                ],
            ],
            [
                'id'                => '019dc506-ae03-7104-bd88-fe4ab57fa0a4',
                'email'             => 'rahim.uddin@gmail.com',
                'phone'             => '01715586520',
                'username'          => 'uddin',
                'password_hash'     => '$2y$12$HcHccj9RbSab7hf50vGQv.BjTtMidNXq4i3xPb5dKU9KfZIWuFyKq',
                'role'              => UserRole::Student->value,
                'status'            => UserStatus::Active->value,
                'user_type'         => 'student',
                'language'          => 'en',
                'email_verified'    => false,
                'phone_verified'    => true,
                'spatie_role'       => 'student',
                'profile'           => [
                    'first_name'   => 'Rahim',
                    'last_name'    => 'Uddin',
                    'display_name' => 'Rahim',
                    'contact_no'   => '01715586520',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
                'student_profile'   => [],
            ],
            [
                'id'                => '019dcaf1-a301-73a0-943d-09cd1e872d7c',
                'email'             => 'karim@gmail.com',
                'phone'             => '01765512669',
                'username'          => 'jannat',
                'password_hash'     => '$2y$12$k2I1GQAa.yL7G/EOG0Wu/.a8YTGvieYGDCuvochLKksF6UlsUGggO',
                'role'              => UserRole::Student->value,
                'status'            => UserStatus::Active->value,
                'user_type'         => 'student',
                'language'          => 'en',
                'email_verified'    => false,
                'phone_verified'    => true,
                'spatie_role'       => 'student',
                'profile'           => [
                    'first_name'   => 'Karim',
                    'last_name'    => 'Jannat',
                    'display_name' => 'Karim',
                    'contact_no'   => '01765512669',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
                'student_profile'   => [],
            ],
            [
                'id'                => '019dcb4e-b8c4-7216-b083-d39bdd8ea9c6',
                'email'             => 'milon@gmail.com',
                'phone'             => '01826023064',
                'username'          => 'molla',
                'password_hash'     => '$2y$12$MRhBVsXwx.fgzwHvySL8zOzzvYpWu9nb39glMzQpLHRtEe0Oy6v.m',
                'role'              => UserRole::Student->value,
                'status'            => UserStatus::PendingVerification->value,
                'user_type'         => 'student',
                'language'          => 'en',
                'email_verified'    => false,
                'phone_verified'    => false,
                'spatie_role'       => 'student',
                'profile'           => [
                    'first_name'   => 'Milon',
                    'last_name'    => 'Molla',
                    'display_name' => 'Milon',
                    'contact_no'   => '01826023064',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
                'student_profile'   => [],
            ],
            [
                'id'                => '019dcb58-a854-706d-ad81-387cffb229a9',
                'email'             => 'malini@gmail.com',
                'phone'             => '01826021063',
                'username'          => 'rekha',
                'password_hash'     => '$2y$12$BaSPqgo2toXZ.MmW5Ma2EuiGIOXCBpDSFQYIIVgFvqFFplC/3cfqe',
                'role'              => UserRole::Student->value,
                'status'            => UserStatus::Active->value,
                'user_type'         => 'student',
                'language'          => 'en',
                'email_verified'    => false,
                'phone_verified'    => true,
                'spatie_role'       => 'student',
                'profile'           => [
                    'first_name'   => 'Malini',
                    'last_name'    => 'Rekha',
                    'display_name' => 'Malini',
                    'contact_no'   => '01826021063',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
                'student_profile'   => [],
            ],
            [
                'id'                => '0a047f48-92fd-4691-949e-9a8b8ea5ea43',
                'email'             => 'testauth@example.com',
                'phone'             => null,
                'username'          => 'testauth',
                'password_hash'     => '$2y$12$1YiJLYeQfDgMGA2Hkq2f7utB.6L4vTDQQDD1ERDt63lMNkaw7wfLK',
                'role'              => UserRole::Student->value,
                'status'            => UserStatus::Active->value,
                'user_type'         => 'student',
                'language'          => 'en',
                'email_verified'    => true,
                'phone_verified'    => true,
                'spatie_role'       => 'student',
                'profile'           => [
                    'first_name'   => 'Test',
                    'last_name'    => 'Student',
                    'display_name' => 'Test Student',
                    'country'      => 'BD',
                    'timezone'     => 'Asia/Dhaka',
                    'language'     => 'en',
                ],
            ],
        ];

        foreach ($users as $uData) {
            $user = User::where('email', $uData['email'])->first();

            $attributes = [
                'email'             => $uData['email'],
                'phone'             => $uData['phone'] ?? null,
                'username'          => $uData['username'] ?? null,
                'role'              => $uData['role'],
                'status'            => $uData['status'],
                'user_type'         => $uData['user_type'] ?? null,
                'salt'              => $uData['salt'] ?? null,
                'language'          => $uData['language'] ?? 'en',
                'email_verified'    => $uData['email_verified'] ?? false,
                'phone_verified'    => $uData['phone_verified'] ?? false,
                'email_verified_at' => $uData['email_verified_at'] ?? null,
            ];

            if (!$user) {
                $user = new User();
                $user->id = $uData['id'];
                // Set raw password_hash directly so already-hashed passwords aren't double-hashed
                $user->setRawAttributes(array_merge($attributes, [
                    'id'            => $uData['id'],
                    'password_hash' => $uData['password_hash'] ?? $defaultPasswordHash,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]));
                $user->save();
            } else {
                // Avoid phone or username collision if updating
                $user->fill($attributes);
                $user->save();
            }

            // Seed core.profiles
            if (!empty($uData['profile'])) {
                Profile::updateOrCreate(
                    ['id' => $user->id],
                    $uData['profile']
                );
            }

            // Seed core.teacher_profiles if applicable
            if (isset($uData['teacher_profile'])) {
                DB::table('core.teacher_profiles')->updateOrInsert(
                    ['user_id' => $user->id],
                    [
                        'teacher_type_id' => $defaultTeacherTypeId,
                        'headline'        => $uData['teacher_profile']['headline'] ?? null,
                        'updated_at'      => now(),
                    ]
                );
            }

            // Seed core.student_profiles if applicable
            if (isset($uData['student_profile'])) {
                DB::table('core.student_profiles')->updateOrInsert(
                    ['user_id' => $user->id],
                    [
                        'school_name' => $uData['student_profile']['school_name'] ?? null,
                        'updated_at'  => now(),
                    ]
                );
            }

            // Assign Spatie Role (model_has_roles)
            if (!empty($uData['spatie_role'])) {
                $role = Role::where('name', $uData['spatie_role'])->first();
                if ($role) {
                    $user->syncRoles([$role]);
                }
            }
        }

        $this->command?->info('Seeded ' . User::count() . ' users and their profiles/roles.');
    }
}
