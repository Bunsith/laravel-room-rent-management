<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Rental;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RentalSeeder extends Seeder
{
    public function run(): void
    {
        $room = Room::first();
        $customer = Customer::first();

        if (!$room || !$customer) {
            return;
        }

        $rental = Rental::firstOrCreate([
            'room_id' => $room->id,
            'customer_id' => $customer->id,
            'status' => 'rented',
        ], [
            'people' => 2,
            'rent_date' => now()->toDateString(),
            'check_in' => now()->toDateString(),
            'expected_check_out' => now()->addMonth()->toDateString(),
            'room_fee' => $room->price,
            'deposit_fee' => 50,
            'partial_pay' => 20,
            'note' => 'Seeded rental',
        ]);

        $invoice = Invoice::firstOrCreate([
            'rental_id' => $rental->id,
        ], [
            'invoice_no' => 'INV-'.now()->format('Ymd').'-0001',
            'invoice_date' => now()->toDateString(),
        ]);

        if ($invoice->items()->count() === 0) {
            $invoice->items()->createMany([
                ['type' => 'room', 'amount' => $rental->room_fee],
                ['type' => 'deposit', 'amount' => $rental->deposit_fee],
            ]);
        }

        if ($invoice->payments()->count() === 0) {
            $invoice->payments()->create([
                'amount' => $rental->partial_pay,
                'method' => 'CASH',
                'paid_at' => now(),
            ]);
        }

        $invoice->recalculateTotals();
    }
}
