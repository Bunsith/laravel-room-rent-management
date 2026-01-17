@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title mb-1">Dashboard</h2>
            <p class="text-muted">Overview of room availability and document status.</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card card-metric p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Available Room</h6>
                        <h3 class="mb-0">{{ $availableRooms }}</h3>
                    </div>
                    <div class="fs-2 text-success">
                        <i class="bi bi-door-open"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-metric p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Rented Room</h6>
                        <h3 class="mb-0">{{ $rentedRooms }}</h3>
                    </div>
                    <div class="fs-2 text-primary">
                        <i class="bi bi-door-closed"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-metric p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Check-Out Room</h6>
                        <h3 class="mb-0">{{ $checkedOutRooms }}</h3>
                    </div>
                    <div class="fs-2 text-danger">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Missing Document</h5>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Room</th>
                                    <th>Floor</th>
                                    <th>Missing</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($missingDocuments as $index => $rental)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $rental->customer->full_name ?? '-' }}</td>
                                        <td>{{ $rental->room->name ?? '-' }}</td>
                                        <td>{{ $rental->room->floor->name ?? '-' }}</td>
                                        <td>
                                            @foreach ($rental->customer->missingDocuments() as $missing)
                                                <span class="badge bg-warning text-dark">{{ $missing }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="{{ route('customers.edit', $rental->customer) }}" class="btn btn-sm btn-primary action-btn">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No missing documents.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Expired Document</h5>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Room</th>
                                    <th>Floor</th>
                                    <th>Expired</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($expiredDocuments as $index => $rental)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $rental->customer->full_name ?? '-' }}</td>
                                        <td>{{ $rental->room->name ?? '-' }}</td>
                                        <td>{{ $rental->room->floor->name ?? '-' }}</td>
                                        <td>
                                            @foreach ($rental->customer->expiredDocuments() as $expired)
                                                <span class="badge bg-danger">{{ $expired }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="{{ route('customers.edit', $rental->customer) }}" class="btn btn-sm btn-primary action-btn">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No expired documents.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
