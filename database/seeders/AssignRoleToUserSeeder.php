<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignRoleToUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // user খুঁজে বের করো
        $user = User::where('email', 'testauth@example.com')->first();

        if (!$user) {
            $this->command->error('User not found!');
            return;
        }

        // role ensure করা (না থাকলে create করবে)
        $role = Role::firstOrCreate(['name' => 'student']);

        // role assign
        $user->assignRole($role);

        $this->command->info('Role assigned successfully!');
    }
}
