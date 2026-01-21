<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Facility Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $facility->name) }}" required>
        <x-input-error for="name" />
    </div>
    <div class="col-12 d-flex gap-2">
        <button class="btn btn-primary" type="submit">Save</button>
        <a href="{{ route('facilities.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>
