<?php

namespace App\Services;

use App\Models\Rental;
use Carbon\Carbon;

class RentalService
{
    public function __construct(private InvoiceService $invoiceService)
    {
    }

    public function create(array $payload): Rental
    {
        $rentDate = isset($payload['rent_date'])
            ? Carbon::parse($payload['rent_date'])
            : now();

        $checkIn = isset($payload['check_in'])
            ? Carbon::parse($payload['check_in'])
            : $rentDate;

        $expectedCheckOut = isset($payload['expected_check_out']) && $payload['expected_check_out']
            ? Carbon::parse($payload['expected_check_out'])
            : $rentDate->copy()->endOfMonth();

        $rental = Rental::create([
            'room_id' => $payload['room_id'],
            'customer_id' => $payload['customer_id'],
            'people' => $payload['people'],
            'rent_date' => $rentDate->toDateString(),
            'check_in' => $checkIn->toDateString(),
            'expected_check_out' => $expectedCheckOut->toDateString(),
            'room_fee' => $payload['room_fee'],
            'deposit_fee' => $payload['deposit_fee'] ?? 0,
            'partial_pay' => $payload['partial_pay'] ?? 0,
            'note' => $payload['note'] ?? null,
            'status' => 'rented',
        ]);

        $items = [
            'room' => $rental->room_fee,
            'deposit' => $rental->deposit_fee,
        ];

        $this->invoiceService->createForRental(
            $rental,
            $items,
            (float) $rental->partial_pay,
            $payload['payment_method'] ?? 'CASH'
        );

        return $rental->fresh(['room', 'customer', 'invoice']);
    }

    public function checkOut(Rental $rental, ?string $date = null): Rental
    {
        $rental->update([
            'status' => 'checked_out',
            'check_out' => $date ? Carbon::parse($date)->toDateString() : now()->toDateString(),
        ]);

        return $rental;
    }
}
