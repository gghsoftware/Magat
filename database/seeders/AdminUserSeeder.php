<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure the admin role exists
        $adminRole = Role::firstOrCreate(
            ['role_name' => 'admin'],
            ['created_at' => now()]
        );

        // Create the admin user if not exists
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Super Admin',
                'password' => Hash::make('password123'), // change later
                'phone' => '09123456789',
                'address' => 'Head Office',
                'status' => 'active',
            ]
        );
    }
}
