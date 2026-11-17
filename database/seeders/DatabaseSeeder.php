<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TeacherTypeSeeder::class,
            RolesPermissionsSeeder::class,
            UserSeeder::class,
            NavMenuSeeder::class,
            ManagePermissionsSeeder::class,
            WebSessionSeeder::class,
            CacheSeeder::class,
        ]);
    }
}
