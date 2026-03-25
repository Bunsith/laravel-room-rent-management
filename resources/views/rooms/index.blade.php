@extends('layouts.app')

@section('title', 'Rooms')

@section('content')
    <div class="rr-section-head mb-4">
        <div>
            <h2 class="page-title mb-1">Room List</h2>
            <p class="text-muted mb-0">Manage room inventory and facilities.</p>
        </div>
        <div class="rr-toolbar-actions">
            @can('rooms.manage')
                <a href="{{ route('rooms.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add Room
                </a>
            @endcan
            @can('floors.manage')
                <a href="{{ route('floors.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-layers me-1"></i>
                    Floors
                </a>
            @endcan
            @can('room_types.manage')
                <a href="{{ route('room-types.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-grid-1x2 me-1"></i>
                    Room Types
                </a>
            @endcan
            @can('facilities.manage')
                <a href="{{ route('facilities.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-boxes me-1"></i>
                    Facilities
                </a>
            @endcan
        </div>
    </div>

    <div class="card rr-data-card">
        <div class="card-header">
            <div class="rr-card-header-grid">
                <div>
                    <h5 class="mb-0">Rooms</h5>
                </div>
                <div>
                    <form method="get" class="d-flex justify-content-md-end mt-2 mt-md-0">
                        <div class="rr-search-wrap">
                            <i class="bi bi-search"></i>
                            <input type="text" name="search" value="{{ $search }}" class="form-control rr-search-input" placeholder="Search room">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Floor</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Fee</th>
                            <th>Stay Type</th>
                            <th>Note</th>
                            <th>Facilities</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rooms as $index => $room)
                            <tr>
                                <td>{{ $rooms->firstItem() + $index }}</td>
                                <td>{{ $room->floor->name ?? '-' }}</td>
                                <td>{{ $room->name }}</td>
                                <td>{{ $room->roomType->name ?? '-' }}</td>
                                <td>{{ number_format($room->price, 2) }} {{ $room->currency }}</td>
                                <td><span class="badge badge-soft">{{ $room->stay_type }}</span></td>
                                <td>{{ \Illuminate\Support\Str::limit($room->note, 30) }}</td>
                                <td>
                                    @foreach ($room->facilities as $facility)
                                        <span class="badge badge-soft">{{ $facility->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @can('rooms.manage')
                                        <a href="{{ route('rooms.edit', $room) }}" class="btn btn-sm btn-primary action-btn">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="post" action="{{ route('rooms.destroy', $room) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger action-btn" type="submit">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">No rooms found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $rooms->links() }}
        </div>
    </div>
@endsection
