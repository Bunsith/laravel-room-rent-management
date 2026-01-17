<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Room;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $availableRooms = Room::available()->count();
        $rentedRooms = Rental::active()->count();
        $checkedOutRooms = Rental::where('status', 'checked_out')->count();

        $activeRentals = Rental::with(['room.floor', 'customer.document'])
            ->active()
            ->get();

        $missingDocuments = $activeRentals->filter(function (Rental $rental) {
            return count($rental->customer?->missingDocuments() ?? []) > 0;
        })->take(10);

        $expiredDocuments = $activeRentals->filter(function (Rental $rental) {
            return count($rental->customer?->expiredDocuments() ?? []) > 0;
        })->take(10);

        return view('dashboard.index', [
            'availableRooms' => $availableRooms,
            'rentedRooms' => $rentedRooms,
            'checkedOutRooms' => $checkedOutRooms,
            'missingDocuments' => $missingDocuments,
            'expiredDocuments' => $expiredDocuments,
        ]);
    }
}
