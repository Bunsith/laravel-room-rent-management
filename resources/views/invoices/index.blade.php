@extends('layouts.app')

@section('title', 'Invoices')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="page-title mb-1">Invoices</h2>
            <p class="text-muted">View invoice history and payments.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Invoice List</h5>
            <form method="get" class="w-50">
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Search invoice">
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Date</th>
                            <th>Room</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->invoice_no }}</td>
                                <td>{{ $invoice->invoice_date?->format('Y-m-d') }}</td>
                                <td>{{ $invoice->rental->room->name ?? '-' }}</td>
                                <td>{{ $invoice->rental->customer->full_name ?? '-' }}</td>
                                <td>{{ number_format($invoice->total_amount, 2) }}</td>
                                <td>{{ number_format($invoice->total_paid, 2) }}</td>
                                <td>{{ number_format($invoice->due_amount, 2) }}</td>
                                <td><span class="badge bg-secondary">{{ ucfirst($invoice->status) }}</span></td>
                                <td class="d-flex gap-1">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-primary action-btn">Detail</a>
                                    <a href="{{ route('invoices.print', $invoice) }}" class="btn btn-sm btn-outline-primary action-btn">Invoice</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">No invoices found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $invoices->links() }}
        </div>
    </div>
@endsection
