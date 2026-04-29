<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'General',     'description' => 'General purpose teacher type'],
            ['name' => 'Tutor',       'description' => 'Private or one-on-one tutor'],
            ['name' => 'Instructor',  'description' => 'Course instructor'],
            ['name' => 'Mentor',      'description' => 'Student mentor'],
        ];

        foreach ($types as $type) {
            DB::table('core.teacher_type')->insertOrIgnore([
                'name'        => $type['name'],
                'description' => $type['description'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
