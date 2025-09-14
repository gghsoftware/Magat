<?php

// database/seeders/PackageSeeder.php
namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'name' => 'OMS (Wood)',
                'price' => 25000,
                'thumbnail' => 'images/OMS-Wood.jpg',
                'inclusions' => ['Ordinary flowers', 'Tent ×1', 'Chairs ×20', 'Tables ×3', 'Hearse ×1'],
                'gallery' => ['images/pkg/oms-1.jpg', 'images/pkg/oms-2.jpg', 'images/pkg/oms-3.jpg'],
            ],
            [
                'name' => 'OMV (Metal)',
                'price' => 45000,
                'thumbnail' => 'images/OMB-metal.jpg', // filename provided
                'inclusions' => ['Ordinary flowers', 'Tent ×1', 'Chairs ×30', 'Tables ×4', 'Water dispenser', 'White balloons ×20', 'Hearse ×1'],
                'gallery' => ['images/pkg/omv-1.jpg', 'images/pkg/omv-2.jpg'],
            ],
            [
                'name' => 'Junior Metal',
                'price' => 70000,
                'thumbnail' => 'images/Junior-Metal.jpg',
                'inclusions' => ['Full garden', 'Tent ×1', 'Chairs ×40', 'Tables ×6', 'Balloons ×25', 'Water dispenser', 'Cards ×6', 'Hearse + carriage attached'],
                'gallery' => ['images/pkg/junior-1.jpg', 'images/pkg/junior-2.jpg'],
            ],
            [
                'name' => 'Senior Metal (Flexi)',
                'price' => 90000,
                'thumbnail' => 'images/Senior-Metal-(Flexi).jpg',
                'inclusions' => ['Full garden + landscape', 'Tent ×2', 'Chairs ×50', 'Tables ×8', 'Balloons ×30', 'Water dispenser', 'Cards ×6', 'Hearse + carriage + flower car (pickup)'],
                'gallery' => ['images/pkg/senior-1.jpg', 'images/pkg/senior-2.jpg'],
            ],
        ];

        foreach ($data as $p) {
            Package::updateOrCreate(
                ['slug' => Str::slug($p['name'])],
                $p
            );
        }
    }
}
