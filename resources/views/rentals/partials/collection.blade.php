<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Rental Collection</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Room</th>
                        <th>Guest</th>
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
                    @forelse ($invoices as $invoice)
                        @php
                            $items = $invoice->items->groupBy('type');
                            $roomPaid = $items->get('room')?->sum('amount') ?? 0;
                            $depositPaid = $items->get('deposit')?->sum('amount') ?? 0;
                            $servicePaid = $items->get('service')?->sum('amount') ?? 0;
                            $waterPaid = $items->get('water')?->sum('amount') ?? 0;
                            $electricPaid = $items->get('electric')?->sum('amount') ?? 0;
                        @endphp
                        <tr>
                            <td>{{ $invoice->invoice_date?->format('Y-m-d') }}</td>
                            <td>{{ $invoice->rental->room->name ?? '-' }}</td>
                            <td>{{ $invoice->rental->customer->full_name ?? '-' }}</td>
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
                                <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-primary action-btn">Pay</a>
                                <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-outline-secondary action-btn">Detail</a>
                                <form method="post" action="{{ route('invoices.destroy', $invoice) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger action-btn" type="submit">Delete</button>
                                </form>
                                <a href="{{ route('invoices.print', $invoice) }}" class="btn btn-sm btn-outline-primary action-btn">Invoice</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted py-4">No collections available.</td>
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
