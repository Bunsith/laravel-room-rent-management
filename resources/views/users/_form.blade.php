<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        <x-input-error for="name" />
    </div>
    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        <x-input-error for="email" />
    </div>
    <div class="col-md-6">
        <label class="form-label">Role</label>
        <select name="role" class="form-select">
            @foreach ($roles as $role)
                @php($roleName = $role->name)
                <option value="{{ $roleName }}" @selected(old('role', $user->roles->first()?->name ?? $user->role ?? 'staff') === $roleName)>
                    {{ ucfirst($roleName) }}
                </option>
            @endforeach
        </select>
        <x-input-error for="role" />
    </div>
    <div class="col-md-6">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" @if(!$user->exists) required @endif>
        @if ($user->exists)
            <small class="text-muted">Leave blank to keep current password.</small>
        @endif
        <x-input-error for="password" />
    </div>
    <div class="col-12 d-flex gap-2">
        <button class="btn btn-primary" type="submit">Save</button>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>
