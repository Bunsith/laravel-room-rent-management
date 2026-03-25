<div class="card rr-data-card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center gap-2">
            <div>
                <h5 class="mb-0">Rental Collection</h5>
                <small class="text-muted">Track utility-based billing and payment status per invoice.</small>
            </div>
            <span class="badge badge-soft">{{ $invoices->total() }} Invoices</span>
        </div>
    </div>
    @php
        $waterRate = (float) ($setting?->water_rate ?? 0.75);
        $electricRate = (float) ($setting?->electric_rate ?? 0.25);
    @endphp
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Room</th>
                        <th>Guest</th>
                        <th>Room Paid</th>
                        <th>Deposit Paid</th>
                        <th>
                            Water Paid
                            <span class="badge badge-soft">{{ number_format($waterRate, 2) }} $</span>
                        </th>
                        <th>
                            Electric Paid
                            <span class="badge badge-soft">{{ number_format($electricRate, 2) }} $</span>
                        </th>
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
                            $waterPaid = $items->get('water')?->sum('amount') ?? 0;
                            $electricPaid = $items->get('electric')?->sum('amount') ?? 0;
                            $waterUnits = $waterRate > 0 ? $waterPaid / $waterRate : 0;
                            $electricUnits = $electricRate > 0 ? $electricPaid / $electricRate : 0;
                        @endphp
                        <tr data-utility-row
                            data-room-paid="{{ number_format($roomPaid, 2, '.', '') }}"
                            data-deposit-paid="{{ number_format($depositPaid, 2, '.', '') }}"
                            data-total-paid="{{ number_format($invoice->total_paid, 2, '.', '') }}"
                            data-water-rate="{{ number_format($waterRate, 2, '.', '') }}"
                            data-electric-rate="{{ number_format($electricRate, 2, '.', '') }}">
                            <td>{{ $invoice->invoice_date?->format('Y-m-d') }}</td>
                            <td>{{ $invoice->rental->room->name ?? '-' }}</td>
                            <td>{{ $invoice->rental->customer->full_name ?? '-' }}</td>
                            <td>{{ number_format($roomPaid, 2) }}</td>
                            <td>{{ number_format($depositPaid, 2) }}</td>
                            <td>
                                @can('collections.manage')
                                    <input type="number" step="0.01" min="0" name="water_units" class="form-control form-control-sm"
                                        form="utility-form-{{ $invoice->id }}" value="{{ $waterUnits > 0 ? number_format($waterUnits, 2, '.', '') : '' }}" data-utility-units="water">
                                    <span class="small text-muted">= $<span data-utility-amount="water">{{ $waterPaid > 0 ? number_format($waterPaid, 2) : '' }}</span></span>
                                    <span class="small text-muted" data-utility-status></span>
                                @else
                                    {{ $waterPaid > 0 ? number_format($waterPaid, 2) : '' }}
                                @endcan
                            </td>
                            <td>
                                @can('collections.manage')
                                    <form method="post" action="{{ route('invoices.utilities.update', $invoice) }}" class="d-flex align-items-center gap-2 flex-wrap"
                                        id="utility-form-{{ $invoice->id }}" data-utility-form>
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" step="0.01" min="0" name="electric_units" class="form-control form-control-sm"
                                            value="{{ $electricUnits > 0 ? number_format($electricUnits, 2, '.', '') : '' }}" data-utility-units="electric">
                                        <span class="small text-muted">= $<span data-utility-amount="electric">{{ $electricPaid > 0 ? number_format($electricPaid, 2) : '' }}</span></span>
                                        <span class="small text-muted" data-utility-status></span>
                                    </form>
                                @else
                                    {{ $electricPaid > 0 ? number_format($electricPaid, 2) : '' }}
                                @endcan
                            </td>
                            <td>{{ number_format($invoice->total_paid, 2) }}</td>
                            <td><span data-utility-total-amount>{{ number_format($invoice->total_amount, 2) }}</span></td>
                            <td>
                                <i class="bi bi-check-circle-fill text-success {{ $invoice->due_amount <= 0 ? '' : 'd-none' }}" data-utility-due-icon></i>
                                <span class="text-danger {{ $invoice->due_amount <= 0 ? 'd-none' : '' }}" data-utility-due>
                                    {{ number_format($invoice->due_amount, 2) }}
                                </span>
                            </td>
                            <td>
                                @can('collections.manage')
                                    <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-primary action-btn">Record Payment</a>
                                @endcan
                                <div class="rr-inline-actions">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-outline-secondary action-btn">Detail</a>
                                    @can('collections.manage')
                                        <form method="post" action="{{ route('invoices.destroy', $invoice) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger action-btn" type="submit">Delete</button>
                                        </form>
                                    @endcan
                                    <a href="{{ route('invoices.print', $invoice) }}" class="btn btn-sm btn-outline-primary action-btn">Print</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">No collections available.</td>
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

@push('scripts')
<script>
    document.querySelectorAll('[data-utility-row]').forEach(function (row) {
        var waterInput = row.querySelector('[data-utility-units="water"]');
        var electricInput = row.querySelector('[data-utility-units="electric"]');
        var waterAmount = row.querySelector('[data-utility-amount="water"]');
        var electricAmount = row.querySelector('[data-utility-amount="electric"]');
        var totalAmount = row.querySelector('[data-utility-total-amount]');
        var dueAmount = row.querySelector('[data-utility-due]');
        var dueIcon = row.querySelector('[data-utility-due-icon]');
        var form = row.querySelector('[data-utility-form]');
        var statuses = Array.from(row.querySelectorAll('[data-utility-status]'));
        var saveTimeout;
        var statusTimeout;

        var roomPaid = parseFloat(row.dataset.roomPaid) || 0;
        var depositPaid = parseFloat(row.dataset.depositPaid) || 0;
        var totalPaid = parseFloat(row.dataset.totalPaid) || 0;
        var waterRate = parseFloat(row.dataset.waterRate) || 0;
        var electricRate = parseFloat(row.dataset.electricRate) || 0;

        if (!waterInput || !electricInput || !waterAmount || !electricAmount) {
            return;
        }

        var updateTotals = function () {
            var waterUnits = parseFloat(waterInput.value);
            if (Number.isNaN(waterUnits)) {
                waterUnits = 0;
            }
            var electricUnits = parseFloat(electricInput.value);
            if (Number.isNaN(electricUnits)) {
                electricUnits = 0;
            }

            var waterTotal = waterUnits * waterRate;
            var electricTotal = electricUnits * electricRate;
            var total = roomPaid + depositPaid + waterTotal + electricTotal;
            var due = Math.max(total - totalPaid, 0);

            waterAmount.textContent = waterUnits > 0 ? waterTotal.toFixed(2) : '';
            electricAmount.textContent = electricUnits > 0 ? electricTotal.toFixed(2) : '';
            if (totalAmount) {
                totalAmount.textContent = total.toFixed(2);
            }
            if (dueAmount && dueIcon) {
                if (due <= 0) {
                    dueAmount.classList.add('d-none');
                    dueIcon.classList.remove('d-none');
                } else {
                    dueAmount.textContent = due.toFixed(2);
                    dueAmount.classList.remove('d-none');
                    dueIcon.classList.add('d-none');
                }
            }
        };

        var sendSave = function () {
            if (!form) {
                return;
            }

            var tokenInput = form.querySelector('input[name="_token"]');
            if (!tokenInput) {
                return;
            }

            var payload = new URLSearchParams();
            payload.append('_token', tokenInput.value);
            payload.append('_method', 'PATCH');
            payload.append('water_units', waterInput.value || '0');
            payload.append('electric_units', electricInput.value || '0');

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: payload,
            }).then(function (response) {
                if (!statuses.length) {
                    return;
                }
                statuses.forEach(function (item) {
                    item.textContent = response.ok ? 'Saved' : 'Error';
                });
                if (statusTimeout) {
                    clearTimeout(statusTimeout);
                }
                statusTimeout = setTimeout(function () {
                    statuses.forEach(function (item) {
                        item.textContent = '';
                    });
                }, 2000);
            }).catch(function () {
                if (!statuses.length) {
                    return;
                }
                statuses.forEach(function (item) {
                    item.textContent = 'Error';
                });
            });
        };

        var scheduleSave = function () {
            if (!form) {
                return;
            }

            if (saveTimeout) {
                clearTimeout(saveTimeout);
            }

            if (statuses.length) {
                statuses.forEach(function (item) {
                    item.textContent = 'Saving...';
                });
            }
            saveTimeout = setTimeout(sendSave, 500);
        };

        waterInput.addEventListener('input', function () {
            updateTotals();
            scheduleSave();
        });
        electricInput.addEventListener('input', function () {
            updateTotals();
            scheduleSave();
        });

        updateTotals();
    });
</script>
@endpush
