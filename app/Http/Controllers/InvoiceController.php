<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Http\Requests\InvoiceElectricRequest;
use App\Http\Requests\InvoiceWaterRequest;
use App\Http\Requests\InvoiceUtilitiesRequest;
use App\Models\Invoice;
use App\Models\Setting;
use App\Services\InvoiceService;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function __construct(private InvoiceService $invoiceService)
    {
    }

    public function index(Request $request): View
    {
        Gate::authorize('collections.view');

        $search = $request->string('search')->toString();

        $invoices = Invoice::with(['rental.room', 'rental.customer'])
            ->when($search, function ($query) use ($search) {
                $query->where('invoice_no', 'like', "%{$search}%")
                    ->orWhereHas('rental.customer', function ($sub) use ($search) {
                        $sub->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('rental.room', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderByDesc('invoice_date')
            ->paginate(10)
            ->withQueryString();

        return view('invoices.index', [
            'invoices' => $invoices,
            'search' => $search,
        ]);
    }

    public function show(Invoice $invoice): View
    {
        Gate::authorize('collections.view');

        $invoice->load(['items', 'payments', 'rental.room', 'rental.customer']);

        return view('invoices.show', [
            'invoice' => $invoice,
        ]);
    }

    public function pay(PaymentRequest $request, Invoice $invoice): RedirectResponse
    {
        Gate::authorize('collections.manage');

        $this->invoiceService->addPayment($invoice, $request->validated());

        return back()->with('status', 'Payment recorded successfully.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        Gate::authorize('collections.manage');

        $invoice->delete();

        return back()->with('status', 'Invoice deleted successfully.');
    }

    public function updateElectric(InvoiceElectricRequest $request, Invoice $invoice): RedirectResponse
    {
        Gate::authorize('collections.manage');

        $units = (float) $request->validated()['units'];
        $rates = $this->utilityRates();
        $amount = round($units * $rates['electric'], 2);

        $item = $invoice->items()->where('type', 'electric')->first();

        if ($units <= 0) {
            if ($item) {
                $item->delete();
            }
        } elseif ($item) {
            $item->update(['amount' => $amount]);
        } else {
            $invoice->items()->create([
                'type' => 'electric',
                'amount' => $amount,
            ]);
        }

        $invoice->recalculateTotals();

        return back()->with('status', 'Electric amount updated successfully.');
    }

    public function updateWater(InvoiceWaterRequest $request, Invoice $invoice): RedirectResponse
    {
        Gate::authorize('collections.manage');

        $units = (float) $request->validated()['units'];
        $rates = $this->utilityRates();
        $amount = round($units * $rates['water'], 2);

        $item = $invoice->items()->where('type', 'water')->first();

        if ($units <= 0) {
            if ($item) {
                $item->delete();
            }
        } elseif ($item) {
            $item->update(['amount' => $amount]);
        } else {
            $invoice->items()->create([
                'type' => 'water',
                'amount' => $amount,
            ]);
        }

        $invoice->recalculateTotals();

        return back()->with('status', 'Water amount updated successfully.');
    }

    public function updateUtilities(InvoiceUtilitiesRequest $request, Invoice $invoice): RedirectResponse
    {
        Gate::authorize('collections.manage');

        $data = $request->validated();
        $rates = $this->utilityRates();

        $waterUnits = (float) ($data['water_units'] ?? 0);
        $electricUnits = (float) ($data['electric_units'] ?? 0);

        $this->syncUtilityItem($invoice, 'water', $waterUnits, $rates['water']);
        $this->syncUtilityItem($invoice, 'electric', $electricUnits, $rates['electric']);

        $invoice->recalculateTotals();

        return back()->with('status', 'Utilities updated successfully.');
    }

    private function utilityRates(): array
    {
        $setting = Setting::first();

        return [
            'water' => (float) ($setting->water_rate ?? 0.75),
            'electric' => (float) ($setting->electric_rate ?? 0.25),
        ];
    }

    private function syncUtilityItem(Invoice $invoice, string $type, float $units, float $rate): void
    {
        $item = $invoice->items()->where('type', $type)->first();
        $amount = round($units * $rate, 2);

        if ($units <= 0) {
            if ($item) {
                $item->delete();
            }
            return;
        }

        if ($item) {
            $item->update(['amount' => $amount]);
            return;
        }

        $invoice->items()->create([
            'type' => $type,
            'amount' => $amount,
        ]);
    }

    public function print(Invoice $invoice)
    {
        Gate::authorize('collections.view');

        $invoice->load(['items', 'payments', 'rental.room', 'rental.customer']);

        if (class_exists(Dompdf::class)) {
            $options = new Options();
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml(view('invoices.print', ['invoice' => $invoice])->render());
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$invoice->invoice_no.'.pdf"',
            ]);
        }

        return view('invoices.print', ['invoice' => $invoice]);
    }
}
