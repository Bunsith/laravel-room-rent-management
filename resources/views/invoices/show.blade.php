@extends('layouts.app')

@section('title', 'Invoice Detail')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="page-title mb-1">Invoice Detail</h2>
            <p class="text-muted">Invoice {{ $invoice->invoice_no }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('rentals.index', ['tab' => 'collection']) }}" class="btn btn-outline-secondary">Back</a>
            <a href="{{ route('invoices.print', $invoice) }}" class="btn btn-primary">Invoice</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Payment Detail</h5>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Invoice ID</th>
                                    <th>Date</th>
                                    <th>Room Paid</th>
                                    <th>Deposit Paid</th>
                                    <th>Service Paid</th>
                                    <th>Water Paid</th>
                                    <th>Electric Paid</th>
                                    <th>Total Paid</th>
                                    <th>Total Amount</th>
                                    <th>Due</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $items = $invoice->items->groupBy('type');
                                    $roomPaid = $items->get('room')?->sum('amount') ?? 0;
                                    $depositPaid = $items->get('deposit')?->sum('amount') ?? 0;
                                    $servicePaid = $items->get('service')?->sum('amount') ?? 0;
                                    $waterPaid = $items->get('water')?->sum('amount') ?? 0;
                                    $electricPaid = $items->get('electric')?->sum('amount') ?? 0;
                                @endphp
                                <tr>
                                    <td>{{ $invoice->invoice_no }}</td>
                                    <td>{{ $invoice->invoice_date?->format('Y-m-d') }}</td>
                                    <td>{{ number_format($roomPaid, 2) }}</td>
                                    <td>{{ number_format($depositPaid, 2) }}</td>
                                    <td>{{ number_format($servicePaid, 2) }}</td>
                                    <td>{{ number_format($waterPaid, 2) }}</td>
                                    <td>{{ number_format($electricPaid, 2) }}</td>
                                    <td>{{ number_format($invoice->total_paid, 2) }}</td>
                                    <td>{{ number_format($invoice->total_amount, 2) }}</td>
                                    <td>
                                        @if ($invoice->due_amount <= 0)
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                        @else
                                            <span class="text-danger">{{ number_format($invoice->due_amount, 2) }}</span>
                                        @endif
                                    </td>
                                    <td class="d-flex gap-1">
                                        <a href="{{ route('invoices.print', $invoice) }}" class="btn btn-sm btn-outline-primary action-btn">Invoice</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Record Payment</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('invoices.pay', $invoice) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" step="0.01" name="amount" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Method</label>
                            <select name="method" class="form-select">
                                <option value="CASH">CASH</option>
                                <option value="ABA">ABA</option>
                                <option value="BANK">BANK</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Paid At</label>
                            <input type="datetime-local" name="paid_at" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Note</label>
                            <textarea name="note" class="form-control" rows="2"></textarea>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Pay</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
