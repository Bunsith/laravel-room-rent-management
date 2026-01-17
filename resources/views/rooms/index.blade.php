@extends('layouts.app')

@section('title', 'Rooms')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="page-title mb-1">Room List</h2>
            <p class="text-muted">Manage room inventory and facilities.</p>
        </div>
        <a href="{{ route('rooms.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>
            Add New
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">Rooms</h5>
                </div>
                <div class="col-md-6">
                    <form method="get" class="d-flex justify-content-md-end mt-2 mt-md-0">
                        <input type="text" name="search" value="{{ $search }}" class="form-control w-50" placeholder="Search room">
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
                                <td>{{ $room->stay_type }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($room->note, 30) }}</td>
                                <td>
                                    @foreach ($room->facilities as $facility)
                                        <span class="badge badge-soft">{{ $facility->name }}</span>
                                    @endforeach
                                </td>
                                <td>
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
