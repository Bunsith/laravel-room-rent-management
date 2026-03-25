<div class="row g-3">
    <div class="col-md-6">
        <p class="rr-form-kicker">Classification</p>
        <p class="rr-form-subtitle">Create a clear category name used across room records and reporting.</p>
        <label class="form-label">Room Type Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $roomType->name) }}" required>
        <x-input-error for="name" />
    </div>
    <div class="col-12 d-flex flex-wrap gap-2 rr-form-actions">
        <button class="btn btn-primary" type="submit">Save</button>
        <a href="{{ route('room-types.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>
