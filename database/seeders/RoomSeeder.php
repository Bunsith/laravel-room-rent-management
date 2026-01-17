<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Floor;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $floor = Floor::first();
        $type = RoomType::first();
        $facilities = Facility::take(3)->pluck('id');

        if (!$floor || !$type) {
            return;
        }

        $rooms = [
            ['name' => 'A101', 'price' => 120, 'stay_type' => 'Month'],
            ['name' => 'A102', 'price' => 140, 'stay_type' => 'Month'],
            ['name' => 'B201', 'price' => 80, 'stay_type' => 'Day'],
        ];

        foreach ($rooms as $roomData) {
            $room = Room::firstOrCreate([
                'floor_id' => $floor->id,
                'room_type_id' => $type->id,
                'name' => $roomData['name'],
            ], [
                'price' => $roomData['price'],
                'currency' => 'USD',
                'stay_type' => $roomData['stay_type'],
                'note' => 'Sample room',
            ]);

            $room->facilities()->sync($facilities);
        }
    }
}
