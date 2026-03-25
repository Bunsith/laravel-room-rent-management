@extends('layouts.app')

@section('title', 'Reports')

@section('content')
    @php
        $invoiced = (float) ($totals['invoiced'] ?? 0);
        $paid = (float) ($totals['paid'] ?? 0);
        $due = (float) ($totals['due'] ?? 0);
        $collectionRate = $invoiced > 0 ? round(($paid / $invoiced) * 100, 1) : 0;
    @endphp

    <div class="rr-section-head mb-4">
        <div>
            <div class="rr-form-kicker">Financial Overview</div>
            <h2 class="page-title mb-1">Reports</h2>
            <p class="text-muted mb-0">Revenue, occupancy, and collections at a glance.</p>
        </div>
        <form method="get" class="rr-toolbar-actions align-items-end">
            <div>
                <label class="form-label small text-muted mb-1">Start Date</label>
                <input type="date" name="start" class="form-control" value="{{ $start }}">
            </div>
            <div>
                <label class="form-label small text-muted mb-1">End Date</label>
                <input type="date" name="end" class="form-control" value="{{ $end }}">
            </div>
            <div>
                <label class="form-label small text-muted mb-1">Quick Range</label>
                <select name="range" class="form-select">
                    <option value="" @selected(request('start') && request('end'))>Custom range</option>
                    @foreach ([7, 30, 90, 180] as $value)
                        <option value="{{ $value }}" @selected(!request('start') && !request('end') && request('range', 30) == $value)>
                            Last {{ $value }} days
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button class="btn btn-primary" type="submit">Apply</button>
            </div>
            <div>
                <a class="btn btn-outline-secondary" href="{{ route('reports.index') }}">Reset</a>
            </div>
        </form>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-metric p-3 h-100">
                <div class="text-muted text-uppercase small">Total Invoiced</div>
                <div class="fs-4 fw-bold">{{ number_format($totals['invoiced'], 2) }}</div>
                <div class="text-muted small">Range {{ $start }} to {{ $end }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-metric p-3 h-100">
                <div class="text-muted text-uppercase small">Total Paid</div>
                <div class="fs-4 fw-bold">{{ number_format($totals['paid'], 2) }}</div>
                <div class="text-muted small">Payments received</div>
                <div class="mt-2">
                    <span class="badge badge-soft-success">Collection Rate {{ number_format($collectionRate, 1) }}%</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-metric p-3 h-100">
                <div class="text-muted text-uppercase small">Total Due</div>
                <div class="fs-4 fw-bold {{ $totals['due'] > 0 ? 'text-danger' : 'text-success' }}">
                    {{ number_format($totals['due'], 2) }}
                </div>
                <div class="text-muted small">Outstanding balance</div>
                <div class="mt-2">
                    @if ($due > 0)
                        <span class="badge badge-soft-warning">Action Required</span>
                    @else
                        <span class="badge badge-soft-success">Cleared</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-metric p-3 h-100">
                <div class="text-muted text-uppercase small">Occupancy</div>
                <div class="fs-4 fw-bold">{{ $occupancy['rate'] }}%</div>
                <div class="text-muted small">
                    {{ $occupancy['rented'] }} rented · {{ $occupancy['available'] }} available
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card rr-data-card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center gap-2">
                        <h5 class="mb-0">Daily Revenue</h5>
                        <span class="badge badge-soft">{{ count($dailyRevenue) }} Days</span>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th class="text-end">Total Paid</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dailyRevenue as $row)
                                    <tr>
                                        <td>{{ $row['date'] }}</td>
                                        <td class="text-end">{{ number_format($row['total'], 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-3">No payments in this range.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card rr-data-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center gap-2">
                        <h5 class="mb-0">Monthly Revenue</h5>
                        <span class="badge badge-soft">{{ count($monthlyRevenue) }} Months</span>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th class="text-end">Total Paid</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($monthlyRevenue as $row)
                                    <tr>
                                        <td>{{ $row['month'] }}</td>
                                        <td class="text-end">{{ number_format($row['total'], 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-3">No payments in this range.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card rr-data-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Collections Status</h5>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th class="text-end">Invoices</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Due</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($statusBreakdown as $row)
                                    <tr>
                                        <td>
                                            @php($status = strtolower($row['status']))
                                            <span class="badge {{ $status === 'paid' ? 'badge-soft-success' : ($status === 'partial' ? 'badge-soft-warning' : 'badge-soft-danger') }} text-capitalize">
                                                {{ $row['status'] }}
                                            </span>
                                        </td>
                                        <td class="text-end">{{ $row['count'] }}</td>
                                        <td class="text-end">{{ number_format($row['total'], 2) }}</td>
                                        <td class="text-end">{{ number_format($row['due'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card rr-data-card">
                <div class="card-header">
                    <h5 class="mb-0">Occupancy Snapshot</h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-muted">Total Active Rooms</div>
                        <div class="fw-semibold">{{ $occupancy['total'] }}</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-muted">Rented Rooms</div>
                        <div class="fw-semibold">{{ $occupancy['rented'] }}</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-muted">Available Rooms</div>
                        <div class="fw-semibold">{{ $occupancy['available'] }}</div>
                    </div>
                    <div class="text-muted small">Occupancy Rate</div>
                    <div class="fs-5 fw-bold">{{ $occupancy['rate'] }}%</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const startInput = document.querySelector('input[name="start"]');
            const endInput = document.querySelector('input[name="end"]');
            const rangeSelect = document.querySelector('select[name="range"]');

            if (!startInput || !endInput || !rangeSelect) {
                return;
            }

            rangeSelect.addEventListener('change', () => {
                if (rangeSelect.value) {
                    startInput.value = '';
                    endInput.value = '';
                }
            });

            const handleCustomRange = () => {
                if (startInput.value || endInput.value) {
                    rangeSelect.value = '';
                }
            };

            startInput.addEventListener('change', handleCustomRange);
            endInput.addEventListener('change', handleCustomRange);
        });
    </script>
@endpush
