<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure the "customer" role exists
        $roleId = Role::firstOrCreate(
            ['role_name' => 'customer'],
            ['description' => 'Customer role for frontend users']
        )->id;

        // Sample customers
        $customers = [
            [
                'name'     => 'Juan Dela Cruz',
                'email'    => 'juan@example.com',
                'password' => 'password123',
                'phone'    => '09171234567',
                'address'  => '123 Rizal St., Manila',
                'status'   => 'active',
            ],
            [
                'name'     => 'Maria Santos',
                'email'    => 'maria@example.com',
                'password' => 'password123',
                'phone'    => '09981234567',
                'address'  => '456 Mabini Ave., Quezon City',
                'status'   => 'inactive',
            ],
            [
                'name'     => 'Pedro Pascual',
                'email'    => 'pedro@example.com',
                'password' => 'password123',
                'phone'    => '09221234567',
                'address'  => '789 Bonifacio Rd., Cebu',
                'status'   => 'active',
            ],
        ];

        foreach ($customers as $c) {
            User::updateOrCreate(
                ['email' => $c['email']], // prevent duplicates
                array_merge($c, ['role_id' => $roleId])
            );
        }
    }
}
