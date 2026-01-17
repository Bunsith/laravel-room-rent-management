<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = ['1 x Bed', 'Fridge', 'Air Conditioner', 'WiFi', 'TV'];

        foreach ($facilities as $facility) {
            Facility::firstOrCreate(['name' => $facility]);
        }
    }
}
