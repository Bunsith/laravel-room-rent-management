@extends('layouts.app')

@section('title', 'Room Types')

@section('content')
    <div class="rr-section-head mb-4">
        <div>
            <h2 class="page-title mb-1">Room Types</h2>
            <p class="text-muted mb-0">Define the categories used for room listings.</p>
        </div>
        @can('room_types.manage')
            <a href="{{ route('room-types.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                Add Room Type
            </a>
        @endcan
    </div>

    <div class="card rr-data-card">
        <div class="card-header">
            <div class="rr-card-header-grid">
                <div>
                    <h5 class="mb-0">Room Type List</h5>
                </div>
                <div>
                    <form method="get" class="d-flex justify-content-md-end mt-2 mt-md-0">
                        <div class="rr-search-wrap">
                            <i class="bi bi-search"></i>
                            <input type="text" name="search" value="{{ $search }}" class="form-control rr-search-input" placeholder="Search type">
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
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roomTypes as $index => $roomType)
                            <tr>
                                <td>{{ $roomTypes->firstItem() + $index }}</td>
                                <td>{{ $roomType->name }}</td>
                                <td>
                                    @can('room_types.manage')
                                        <a href="{{ route('room-types.edit', $roomType) }}" class="btn btn-sm btn-primary action-btn">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="post" action="{{ route('room-types.destroy', $roomType) }}" class="d-inline">
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
                                <td colspan="3" class="text-center text-muted py-4">No room types found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $roomTypes->links() }}
        </div>
    </div>
@endsection
