@extends('layouts.app')

@section('title', 'Invoice Detail')

@section('content')
    @php
        $items = $invoice->items->groupBy('type');
        $roomPaid = $items->get('room')?->sum('amount') ?? 0;
        $depositPaid = $items->get('deposit')?->sum('amount') ?? 0;
        $waterPaid = $items->get('water')?->sum('amount') ?? 0;
        $electricPaid = $items->get('electric')?->sum('amount') ?? 0;
        $statusClass = 'secondary';
        if ($invoice->status === 'paid') {
            $statusClass = 'success';
        } elseif ($invoice->status === 'partial') {
            $statusClass = 'warning';
        }
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
        <div>
            <h2 class="page-title mb-1">Invoice Detail</h2>
            <div class="d-flex align-items-center gap-2 flex-wrap text-muted">
                <span class="fw-semibold text-dark">Invoice {{ $invoice->invoice_no }}</span>
                <span class="badge bg-{{ $statusClass }}">{{ strtoupper($invoice->status) }}</span>
                <span class="small">Issued {{ $invoice->invoice_date?->format('Y-m-d') }}</span>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('rentals.index', ['tab' => 'collection']) }}" class="btn btn-outline-secondary">Back</a>
            <a href="{{ route('invoices.print', $invoice) }}" class="btn btn-primary">Print Invoice</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card card-metric p-3 h-100">
                <div class="text-muted text-uppercase small">Total Amount</div>
                <div class="fs-4 fw-bold">{{ number_format($invoice->total_amount, 2) }}</div>
                <div class="text-muted small">Room {{ $invoice->rental->room->name ?? '-' }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-metric p-3 h-100">
                <div class="text-muted text-uppercase small">Total Paid</div>
                <div class="fs-4 fw-bold">{{ number_format($invoice->total_paid, 2) }}</div>
                <div class="text-muted small">Method: {{ $invoice->payments->last()->method ?? 'N/A' }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-metric p-3 h-100">
                <div class="text-muted text-uppercase small">Outstanding</div>
                <div class="fs-4 fw-bold {{ $invoice->due_amount > 0 ? 'text-danger' : 'text-success' }}">
                    {{ number_format($invoice->due_amount, 2) }}
                </div>
                <div class="text-muted small">Guest {{ $invoice->rental->customer->full_name ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Invoice Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted small">Invoice ID</div>
                            <div class="fw-semibold">{{ $invoice->invoice_no }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Invoice Date</div>
                            <div class="fw-semibold">{{ $invoice->invoice_date?->format('Y-m-d') }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Room</div>
                            <div class="fw-semibold">{{ $invoice->rental->room->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Guest</div>
                            <div class="fw-semibold">{{ $invoice->rental->customer->full_name ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Status</div>
                            <div class="fw-semibold text-uppercase">{{ $invoice->status }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Total Due</div>
                            <div class="fw-semibold">{{ number_format($invoice->due_amount, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Charges Breakdown</h5>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted">Room Fee</td>
                                    <td class="text-end fw-semibold">{{ number_format($roomPaid, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Deposit</td>
                                    <td class="text-end fw-semibold">{{ number_format($depositPaid, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Water</td>
                                    <td class="text-end fw-semibold">{{ number_format($waterPaid, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Electric</td>
                                    <td class="text-end fw-semibold">{{ number_format($electricPaid, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Total Amount</td>
                                    <td class="text-end fw-bold">{{ number_format($invoice->total_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Total Paid</td>
                                    <td class="text-end fw-bold">{{ number_format($invoice->total_paid, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Outstanding</td>
                                    <td class="text-end fw-bold {{ $invoice->due_amount > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($invoice->due_amount, 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Payment History</h5>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Method</th>
                                    <th class="text-end">Amount</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($invoice->payments as $payment)
                                    <tr>
                                        <td>{{ $payment->paid_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                        <td>{{ $payment->method }}</td>
                                        <td class="text-end">{{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ $payment->note ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">No payments recorded.</td>
                                    </tr>
                                @endforelse
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
                    @can('collections.manage')
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
                    @else
                        <div class="text-muted">You do not have permission to record payments.</div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
