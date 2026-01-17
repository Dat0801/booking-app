<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['display_name' => 'Administrator']
        );

        $customerRole = Role::firstOrCreate(
            ['name' => 'customer'],
            ['display_name' => 'Customer']
        );

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'is_active' => true,
            ]
        );

        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        User::factory(5)->create()->each(function (User $user) use ($customerRole) {
            $user->roles()->syncWithoutDetaching([$customerRole->id]);
        });
    }
}
