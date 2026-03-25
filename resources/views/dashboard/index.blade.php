@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="dashboard-shell">
        <section class="dashboard-hero">
            <div class="row g-4 align-items-stretch">
                <div class="col-xl-8">
                    <div class="dashboard-hero-panel h-100">
                        <span class="dashboard-kicker">Daily overview</span>
                        <div class="dashboard-hero-head">
                            <div>
                                <h2 class="page-title mb-2">Dashboard</h2>
                                <p class="dashboard-subtitle mb-0">
                                    Track occupancy, monitor document compliance, and keep follow-up work visible across the property.
                                </p>
                            </div>
                            <div class="dashboard-hero-badge">
                                <span class="dashboard-hero-badge-label">Items requiring review</span>
                                <strong>{{ count($missingDocuments) + count($expiredDocuments) }}</strong>
                            </div>
                        </div>
                        <div class="dashboard-overview-grid">
                            <div class="dashboard-overview-item">
                                <span class="dashboard-overview-label">Total units</span>
                                <strong>{{ $availableRooms + $rentedRooms + $checkedOutRooms }}</strong>
                                <small>Current inventory across all tracked rooms.</small>
                            </div>
                            <div class="dashboard-overview-item">
                                <span class="dashboard-overview-label">Missing files</span>
                                <strong>{{ count($missingDocuments) }}</strong>
                                <small>Residents with incomplete document records.</small>
                            </div>
                            <div class="dashboard-overview-item">
                                <span class="dashboard-overview-label">Expired files</span>
                                <strong>{{ count($expiredDocuments) }}</strong>
                                <small>Documents that require renewal or replacement.</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="dashboard-insight-card h-100">
                        <div>
                            <div class="dashboard-insight-header">
                                <span class="dashboard-kicker mb-0">Occupancy mix</span>
                                <i class="bi bi-buildings"></i>
                            </div>
                            <div class="dashboard-insight-list">
                                <div class="dashboard-insight-row">
                                    <span>Available</span>
                                    <strong>{{ $availableRooms }}</strong>
                                </div>
                                <div class="dashboard-insight-row">
                                    <span>Rented</span>
                                    <strong>{{ $rentedRooms }}</strong>
                                </div>
                                <div class="dashboard-insight-row">
                                    <span>Check-out</span>
                                    <strong>{{ $checkedOutRooms }}</strong>
                                </div>
                            </div>
                        </div>
                        <p class="dashboard-insight-note mb-0">
                            This view is intended to work as a concise morning brief for room status and compliance.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="row g-3">
            <div class="col-md-4">
                <div class="card card-metric dashboard-metric-card h-100">
                    <div class="dashboard-metric-top">
                        <span class="dashboard-metric-label">Available room</span>
                        <span class="dashboard-metric-icon text-success">
                            <i class="bi bi-door-open"></i>
                        </span>
                    </div>
                    <div class="dashboard-metric-value">{{ $availableRooms }}</div>
                    <p class="dashboard-metric-note mb-0">Ready to allocate for new occupancy.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-metric dashboard-metric-card h-100">
                    <div class="dashboard-metric-top">
                        <span class="dashboard-metric-label">Rented room</span>
                        <span class="dashboard-metric-icon text-primary">
                            <i class="bi bi-door-closed"></i>
                        </span>
                    </div>
                    <div class="dashboard-metric-value">{{ $rentedRooms }}</div>
                    <p class="dashboard-metric-note mb-0">Units with active rental occupancy.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-metric dashboard-metric-card h-100">
                    <div class="dashboard-metric-top">
                        <span class="dashboard-metric-label">Check-out room</span>
                        <span class="dashboard-metric-icon text-danger">
                            <i class="bi bi-clipboard-check"></i>
                        </span>
                    </div>
                    <div class="dashboard-metric-value">{{ $checkedOutRooms }}</div>
                    <p class="dashboard-metric-note mb-0">Rooms in turnover or awaiting closure.</p>
                </div>
            </div>
        </section>

        <section class="row g-4">
            <div class="col-lg-6">
                <div class="card dashboard-table-card">
                    <div class="card-header dashboard-table-header">
                        <div>
                            <span class="dashboard-kicker">Compliance queue</span>
                            <h5 class="mb-0">Missing Document</h5>
                        </div>
                        <span class="dashboard-table-count">{{ count($missingDocuments) }}</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table dashboard-table mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Room</th>
                                        <th>Floor</th>
                                        <th>Missing</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($missingDocuments as $index => $rental)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="dashboard-person-cell">
                                                    <strong>{{ $rental->customer->full_name ?? '-' }}</strong>
                                                    <span>Tenant record</span>
                                                </div>
                                            </td>
                                            <td>{{ $rental->room->name ?? '-' }}</td>
                                            <td>{{ $rental->room->floor->name ?? '-' }}</td>
                                            <td>
                                                <div class="dashboard-tag-group">
                                                    @foreach ($rental->customer->missingDocuments() as $missing)
                                                        <span class="badge badge-soft">{{ $missing }}</span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('customers.edit', $rental->customer) }}" class="btn btn-sm btn-primary action-btn">
                                                    <i class="bi bi-pencil-square me-1"></i>Review
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-0">
                                                <div class="dashboard-empty-state">
                                                    <i class="bi bi-check2-circle"></i>
                                                    <strong>No missing documents</strong>
                                                    <span>All current tenant files are complete.</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card dashboard-table-card">
                    <div class="card-header dashboard-table-header">
                        <div>
                            <span class="dashboard-kicker">Renewal queue</span>
                            <h5 class="mb-0">Expired Document</h5>
                        </div>
                        <span class="dashboard-table-count dashboard-table-count-danger">{{ count($expiredDocuments) }}</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table dashboard-table mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Room</th>
                                        <th>Floor</th>
                                        <th>Expired</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($expiredDocuments as $index => $rental)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="dashboard-person-cell">
                                                    <strong>{{ $rental->customer->full_name ?? '-' }}</strong>
                                                    <span>Tenant record</span>
                                                </div>
                                            </td>
                                            <td>{{ $rental->room->name ?? '-' }}</td>
                                            <td>{{ $rental->room->floor->name ?? '-' }}</td>
                                            <td>
                                                <div class="dashboard-tag-group">
                                                    @foreach ($rental->customer->expiredDocuments() as $expired)
                                                        <span class="badge badge-soft-danger">{{ $expired }}</span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('customers.edit', $rental->customer) }}" class="btn btn-sm btn-primary action-btn">
                                                    <i class="bi bi-pencil-square me-1"></i>Review
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-0">
                                                <div class="dashboard-empty-state dashboard-empty-state-danger">
                                                    <i class="bi bi-shield-check"></i>
                                                    <strong>No expired documents</strong>
                                                    <span>There are no document renewals pending.</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
