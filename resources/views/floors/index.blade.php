@extends('layouts.app')

@section('title', 'Floors')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="page-title mb-1">Floors</h2>
            <p class="text-muted">Manage building floors used for room assignments.</p>
        </div>
        @can('floors.manage')
            <a href="{{ route('floors.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                Add New
            </a>
        @endcan
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">Floor List</h5>
                </div>
                <div class="col-md-6">
                    <form method="get" class="d-flex justify-content-md-end mt-2 mt-md-0">
                        <input type="text" name="search" value="{{ $search }}" class="form-control w-50" placeholder="Search floor">
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
                        @forelse ($floors as $index => $floor)
                            <tr>
                                <td>{{ $floors->firstItem() + $index }}</td>
                                <td>{{ $floor->name }}</td>
                                <td>
                                    @can('floors.manage')
                                        <a href="{{ route('floors.edit', $floor) }}" class="btn btn-sm btn-primary action-btn">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="post" action="{{ route('floors.destroy', $floor) }}" class="d-inline">
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
                                <td colspan="3" class="text-center text-muted py-4">No floors found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $floors->links() }}
        </div>
    </div>
@endsection
