<div class="row g-4">
    <div class="col-lg-4">
        <div class="upload-preview" id="room-photo-preview">
            @if ($room->photo)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($room->photo) }}" alt="Room photo">
            @else
                <div class="text-center">
                    <i class="bi bi-image fs-1"></i>
                    <div class="mt-2">Room Photo</div>
                </div>
            @endif
        </div>
        <input type="file" name="photo" class="form-control mt-3" accept="image/*" data-preview="#room-photo-preview">
        <x-input-error for="photo" />
    </div>
    <div class="col-lg-8">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Floor</label>
                <select name="floor_id" class="form-select" required>
                    <option value="">Select floor</option>
                    @foreach ($floors as $floor)
                        <option value="{{ $floor->id }}" @selected(old('floor_id', $room->floor_id) == $floor->id)>{{ $floor->name }}</option>
                    @endforeach
                </select>
                <x-input-error for="floor_id" />
            </div>
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $room->name) }}" required>
                <x-input-error for="name" />
            </div>
            <div class="col-md-6">
                <label class="form-label">Type</label>
                <select name="room_type_id" class="form-select" required>
                    <option value="">Select type</option>
                    @foreach ($roomTypes as $type)
                        <option value="{{ $type->id }}" @selected(old('room_type_id', $room->room_type_id) == $type->id)>{{ $type->name }}</option>
                    @endforeach
                </select>
                <x-input-error for="room_type_id" />
            </div>
            <div class="col-md-6">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $room->price) }}" required>
                <x-input-error for="price" />
            </div>
            <div class="col-md-6">
                <label class="form-label">Currency</label>
                <select name="currency" class="form-select">
                    @foreach (['USD', 'KHR'] as $currency)
                        <option value="{{ $currency }}" @selected(old('currency', $room->currency ?? 'USD') === $currency)>{{ $currency }}</option>
                    @endforeach
                </select>
                <x-input-error for="currency" />
            </div>
            <div class="col-md-6">
                <label class="form-label">Stay Type</label>
                <select name="stay_type" class="form-select">
                    @foreach (['Month', 'Day', 'Year'] as $stayType)
                        <option value="{{ $stayType }}" @selected(old('stay_type', $room->stay_type ?? 'Month') === $stayType)>{{ $stayType }}</option>
                    @endforeach
                </select>
                <x-input-error for="stay_type" />
            </div>
            <div class="col-12">
                <label class="form-label">Note</label>
                <textarea name="note" class="form-control" rows="3">{{ old('note', $room->note) }}</textarea>
                <x-input-error for="note" />
            </div>
            <div class="col-12">
                <label class="form-label">Facilities</label>
                <div class="row g-2">
                    @foreach ($facilities as $facility)
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="facilities[]" value="{{ $facility->id }}"
                                    @checked(in_array($facility->id, old('facilities', $room->facilities?->pluck('id')->toArray() ?? [])))>
                                <label class="form-check-label">{{ $facility->name }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
                <x-input-error for="facilities" />
            </div>
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Save changes</button>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Cancel</a>
                <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary">Back</a>
            </div>
        </div>
    </div>
</div>
