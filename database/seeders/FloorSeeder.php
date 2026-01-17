<?php

namespace Database\Seeders;

use App\Models\Floor;
use Illuminate\Database\Seeder;

class FloorSeeder extends Seeder
{
    public function run(): void
    {
        $floors = ['Floor 1', 'Floor 2', 'Floor 3'];

        foreach ($floors as $floor) {
            Floor::firstOrCreate(['name' => $floor]);
        }
    }
}
