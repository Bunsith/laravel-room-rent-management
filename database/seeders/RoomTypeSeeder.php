<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['Standard', 'Deluxe', 'Family', 'Standard (Khmer)'];

        foreach ($types as $type) {
            RoomType::firstOrCreate(['name' => $type]);
        }
    }
}
