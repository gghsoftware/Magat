<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Wooden Caskets',
                'description' => 'Classic wooden designs.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Metal Caskets',
                'description' => 'Durable metal options.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Urns',
                'description' => 'Urns for ashes in various designs.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Accessories',
                'description' => 'Funeral accessories like flowers, cards, etc.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
