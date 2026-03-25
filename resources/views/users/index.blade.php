@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="rr-section-head mb-4">
        <div>
            <h2 class="page-title mb-1">Users</h2>
            <p class="text-muted mb-0">Manage admin and staff accounts for secure system access.</p>
        </div>
        <div class="rr-toolbar-actions">
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

    <div class="card rr-data-card">
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
                            @php($roleName = strtolower($user->roles->first()?->name ?? $user->role ?? 'staff'))
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge {{ $roleName === 'admin' ? 'badge-soft-success' : ($roleName === 'manager' ? 'badge-soft-warning' : 'badge-soft') }}">
                                        {{ ucfirst($roleName) }}
                                    </span>
                                </td>
                                <td>
                                    @if (auth()->user()->can('users.manage') || auth()->user()->can('delete', $user))
                                        <div class="rr-inline-actions">
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
                                        </div>
                                    @endif
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
