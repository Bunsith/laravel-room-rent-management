<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\Rental;

class InvoiceService
{
    public function createForRental(Rental $rental, array $items, float $partialPay = 0, string $method = 'CASH'): Invoice
    {
        $invoice = Invoice::create([
            'rental_id' => $rental->id,
            'invoice_no' => $this->generateInvoiceNo(),
            'invoice_date' => now()->toDateString(),
            'status' => 'unpaid',
        ]);

        foreach ($items as $type => $amount) {
            if ($amount <= 0) {
                continue;
            }

            $invoice->items()->create([
                'type' => $type,
                'amount' => $amount,
            ]);
        }

        if ($partialPay > 0) {
            $invoice->payments()->create([
                'amount' => $partialPay,
                'method' => $method,
                'paid_at' => now(),
            ]);
        }

        $invoice->recalculateTotals();

        return $invoice->fresh(['items', 'payments']);
    }

    public function addPayment(Invoice $invoice, array $payload): Payment
    {
        $payment = $invoice->payments()->create([
            'amount' => $payload['amount'],
            'method' => $payload['method'],
            'paid_at' => $payload['paid_at'] ?? now(),
            'note' => $payload['note'] ?? null,
        ]);

        $invoice->recalculateTotals();

        return $payment;
    }

    public function generateInvoiceNo(): string
    {
        $prefix = 'INV-'.now()->format('Ymd');
        $sequence = Invoice::whereDate('invoice_date', now()->toDateString())->count() + 1;

        return $prefix.'-'.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }
}
