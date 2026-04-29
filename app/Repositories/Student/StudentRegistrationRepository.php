<?php

namespace App\Repositories\Student;

use App\Models\User;
use App\Repositories\Student\Contracts\StudentRegistrationRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentRegistrationRepository implements StudentRegistrationRepositoryInterface
{
    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'email'         => $data['email'],
                'phone'         => $data['phone'] ?? null,
                'password_hash' => Hash::make($data['password']),
                'role'          => 'student',
                'status'        => 'pending_verification',
                'salt'          => base64_encode((string)$data['password']),
                'username'      => mb_strtolower($data['last_name']),
                'user_type'     => 'student',
                'language'      => 'en',
                'email_verified'=> true,
                'phone_verified'=> true,
            ]);

            $user->assignRole('student');

            $user->profile()->create([
                'id'         => $user->id,
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'display_name' => Str::title($data['first_name']),
                'language'   => 'en',
                'contact_no' => $data['phone'] ?? null,
            ]);

            $user->studentProfile()->create([
                'user_id'             => $user->id,
                'grade_level_id'      => $data['class_level'] ?? null,
                'interested_subjects' => $data['subjects'] ?? [],
            ]);

            return $user;
        });
    }
}
