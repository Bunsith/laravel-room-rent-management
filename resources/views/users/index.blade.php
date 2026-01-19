@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="page-title mb-1">Users</h2>
            <p class="text-muted">Manage admin and staff accounts.</p>
        </div>
        <div class="d-flex gap-2">
            @can('permissions.manage')
                <a href="{{ route('users.permissions.edit') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-shield-lock me-1"></i>
                    Role Permissions
                </a>
            @endcan
            @can('users.manage')
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add User
                </a>
            @endcan
        </div>
    </div>

    <div class="card">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge bg-secondary">{{ ucfirst($user->role) }}</span></td>
                                <td class="d-flex gap-1">
                                    @can('users.manage')
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-primary action-btn">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $user)
                                        <form method="post" action="{{ route('users.destroy', $user) }}">
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
                                <td colspan="4" class="text-center text-muted py-4">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $users->links() }}
        </div>
    </div>
@endsection
