<div class="row g-3">
    <div class="col-md-6">
        <p class="rr-form-kicker">Amenities Catalog</p>
        <p class="rr-form-subtitle">Maintain a concise facility label to keep room tagging and filtering consistent.</p>
        <label class="form-label">Facility Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $facility->name) }}" required>
        <x-input-error for="name" />
    </div>
    <div class="col-12 d-flex flex-wrap gap-2 rr-form-actions">
        <button class="btn btn-primary" type="submit">Save</button>
        <a href="{{ route('facilities.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>
