<?php

use App\Models\Customer;
use App\Models\CustomerDocument;
use App\Models\Floor;
use App\Models\Rental;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('marks rooms with active rentals as unavailable', function () {
    $floor = Floor::create(['name' => 'Floor 1']);
    $type = RoomType::create(['name' => 'Standard']);
    $room = Room::create([
        'floor_id' => $floor->id,
        'room_type_id' => $type->id,
        'name' => 'A101',
        'price' => 100,
        'currency' => 'USD',
        'stay_type' => 'Month',
    ]);

    $customer = Customer::create([
        'first_name' => 'Test',
        'last_name' => 'Guest',
        'member_count' => 1,
    ]);

    Rental::create([
        'room_id' => $room->id,
        'customer_id' => $customer->id,
        'people' => 1,
        'rent_date' => now()->toDateString(),
        'check_in' => now()->toDateString(),
        'expected_check_out' => now()->addMonth()->toDateString(),
        'room_fee' => 100,
        'status' => 'rented',
    ]);

    expect(Room::available()->pluck('id'))->not()->toContain($room->id);
});

it('detects expired customer documents', function () {
    $customer = Customer::create([
        'first_name' => 'Expired',
        'last_name' => 'Doc',
        'member_count' => 1,
    ]);

    CustomerDocument::create([
        'customer_id' => $customer->id,
        'national_id' => 'NAT-001',
        'national_valid_until' => now()->subDay()->toDateString(),
    ]);

    expect($customer->expiredDocuments())->toContain('National');
});
