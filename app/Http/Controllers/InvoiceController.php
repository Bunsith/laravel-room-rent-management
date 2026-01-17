<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function __construct(private InvoiceService $invoiceService)
    {
    }

    public function index(Request $request): View
    {
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
        $invoice->load(['items', 'payments', 'rental.room', 'rental.customer']);

        return view('invoices.show', [
            'invoice' => $invoice,
        ]);
    }

    public function pay(PaymentRequest $request, Invoice $invoice): RedirectResponse
    {
        $this->invoiceService->addPayment($invoice, $request->validated());

        return back()->with('status', 'Payment recorded successfully.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return back()->with('status', 'Invoice deleted successfully.');
    }

    public function print(Invoice $invoice)
    {
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
