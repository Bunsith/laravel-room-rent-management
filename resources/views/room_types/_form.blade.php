<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Room Type Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $roomType->name) }}" required>
        <x-input-error for="name" />
    </div>
    <div class="col-12 d-flex gap-2">
        <button class="btn btn-primary" type="submit">Save</button>
        <a href="{{ route('room-types.index') }}" class="btn btn-link">Back</a>
    </div>
</div>
