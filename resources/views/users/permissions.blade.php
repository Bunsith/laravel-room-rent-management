@extends('layouts.app')

@section('title', 'Role Permissions')

@section('content')
    <div class="rr-section-head mb-4">
        <div>
            <h2 class="page-title mb-1">Role Permissions</h2>
            <p class="text-muted mb-0">Control what each role can access in the system.</p>
        </div>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>

    <form method="post" action="{{ route('users.permissions.update') }}">
        @csrf
        @method('PUT')

        @foreach ($roles as $role)
            @php($roleName = $role->name)
            @php($label = $roleLabels[$roleName] ?? ucfirst($roleName))
            <div class="card rr-data-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">{{ $label }}</h5>
                        <small class="text-muted text-uppercase">{{ $roleName }}</small>
                    </div>
                    @if ($roleName === 'admin')
                        <span class="badge badge-soft">Full access</span>
                    @endif
                </div>
                <div class="card-body">
                    @if ($roleName === 'admin')
                        <p class="text-muted mb-0">Admins always have full access to every section.</p>
                    @else
                        <div class="row g-3">
                            @foreach ($permissionGroups as $group)
                                <div class="col-lg-4 col-md-6">
                                    <div class="rr-check-grid h-100">
                                        <h6 class="mb-2">{{ $group['label'] }}</h6>
                                        @foreach ($group['permissions'] as $permission => $permissionLabel)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="permissions[{{ $roleName }}][]" value="{{ $permission }}"
                                                    @checked(in_array($permission, $currentPermissions[$roleName] ?? [], true))>
                                                <label class="form-check-label">{{ $permissionLabel }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        <div class="d-flex justify-content-end rr-form-actions">
            <button class="btn btn-primary" type="submit">Save Permissions</button>
        </div>
    </form>
@endsection
