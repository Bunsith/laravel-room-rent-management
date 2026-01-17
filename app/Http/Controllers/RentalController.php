<?php

namespace App\Http\Controllers;

use App\Http\Requests\RentalRequest;
use App\Http\Requests\RentalUpdateRequest;
use App\Models\AccountType;
use App\Models\Customer;
use App\Models\Floor;
use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Models\Rental;
use App\Models\ResourceBudget;
use App\Models\Room;
use App\Services\RentalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RentalController extends Controller
{
    public function __construct(private RentalService $rentalService)
    {
    }

    public function index(Request $request): View
    {
        $tab = $request->string('tab')->toString() ?: 'available';

        $availableRooms = collect();
        $customers = collect();
        $rentals = collect();
        $invoices = collect();
        $journalEntries = collect();
        $accountTypes = collect();
        $resourceBudgets = collect();
        $floors = collect();

        if ($tab === 'available') {
            $availableRooms = Room::available()
                ->with(['floor', 'roomType', 'facilities'])
                ->orderBy('name')
                ->get();
            $customers = Customer::orderBy('first_name')->get();
        }

        if ($tab === 'rented') {
            $rentals = Rental::with(['room.floor', 'customer'])
                ->active()
                ->orderByDesc('rent_date')
                ->paginate(10)
                ->withQueryString();
        }

        if ($tab === 'collection') {
            $invoices = Invoice::with(['rental.room', 'rental.customer'])
                ->with(['items', 'payments'])
                ->orderByDesc('invoice_date')
                ->paginate(10)
                ->withQueryString();
        }

        if ($tab === 'journal') {
            $journalEntries = JournalEntry::with(['accountType', 'resourceBudget', 'floor'])
                ->orderByDesc('date')
                ->paginate(10)
                ->withQueryString();
            $accountTypes = AccountType::orderBy('name')->get();
            $resourceBudgets = ResourceBudget::orderBy('name')->get();
            $floors = Floor::orderBy('name')->get();
        }

        return view('rentals.index', [
            'tab' => $tab,
            'availableRooms' => $availableRooms,
            'customers' => $customers,
            'rentals' => $rentals,
            'invoices' => $invoices,
            'journalEntries' => $journalEntries,
            'accountTypes' => $accountTypes,
            'resourceBudgets' => $resourceBudgets,
            'floors' => $floors,
        ]);
    }

    public function store(RentalRequest $request): RedirectResponse
    {
        $payload = $request->validated();

        $isAvailable = Room::available()->where('id', $payload['room_id'])->exists();
        if (!$isAvailable) {
            return back()->withErrors(['room_id' => 'Room is not available.'])->withInput();
        }

        $rental = $this->rentalService->create($payload);

        if ($request->boolean('print') && $rental->invoice) {
            return redirect()->route('invoices.print', $rental->invoice);
        }

        return redirect()->route('rentals.index', ['tab' => 'rented'])
            ->with('status', 'Rental created successfully.');
    }

    public function update(RentalUpdateRequest $request, Rental $rental): RedirectResponse
    {
        $rental->update($request->validated());

        return back()->with('status', 'Rental updated successfully.');
    }

    public function checkOut(Request $request, Rental $rental): RedirectResponse
    {
        $this->rentalService->checkOut($rental, $request->input('check_out'));

        return back()->with('status', 'Rental checked out successfully.');
    }
}
