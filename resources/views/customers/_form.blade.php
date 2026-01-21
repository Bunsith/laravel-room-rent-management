<div class="row g-4">
    <div class="col-lg-4">
        <div class="upload-preview" id="customer-photo-preview">
            @if ($customer->photo)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($customer->photo) }}" alt="Customer photo">
            @else
                <div class="text-center">
                    <i class="bi bi-person-bounding-box fs-1"></i>
                    <div class="mt-2">Profile Photo</div>
                </div>
            @endif
        </div>
        <input type="file" name="photo" class="form-control mt-3" accept="image/*" data-preview="#customer-photo-preview">
        <x-input-error for="photo" />
        <div class="mt-3">
            <label class="form-label">Attachments</label>
            <input type="file" name="attachments[]" class="form-control" multiple>
            <x-input-error for="attachments" />
        </div>
    </div>
    <div class="col-lg-8">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $customer->first_name) }}" required>
                <x-input-error for="first_name" />
            </div>
            <div class="col-md-6">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $customer->last_name) }}">
                <x-input-error for="last_name" />
            </div>
            <div class="col-md-6">
                <label class="form-label">National ID</label>
                <input type="text" name="national_id" class="form-control" value="{{ old('national_id', $customer->document->national_id ?? '') }}">
                <x-input-error for="national_id" />
            </div>
            <div class="col-md-6">
                <label class="form-label">Valid Nation ID</label>
                <input type="date" name="national_valid_until" class="form-control" value="{{ old('national_valid_until', optional($customer->document)->national_valid_until?->format('Y-m-d')) }}">
                <x-input-error for="national_valid_until" />
            </div>
            <div class="col-md-6">
                <label class="form-label">Passport ID</label>
                <input type="text" name="passport_id" class="form-control" value="{{ old('passport_id', $customer->document->passport_id ?? '') }}">
                <x-input-error for="passport_id" />
            </div>
            <div class="col-md-6">
                <label class="form-label">Valid Passport</label>
                <input type="date" name="passport_valid_until" class="form-control" value="{{ old('passport_valid_until', optional($customer->document)->passport_valid_until?->format('Y-m-d')) }}">
                <x-input-error for="passport_valid_until" />
            </div>
            <div class="col-md-6">
                <label class="form-label">VISA ID</label>
                <input type="text" name="visa_id" class="form-control" value="{{ old('visa_id', $customer->document->visa_id ?? '') }}">
                <x-input-error for="visa_id" />
            </div>
            <div class="col-md-6">
                <label class="form-label">Valid VISA</label>
                <input type="date" name="visa_valid_until" class="form-control" value="{{ old('visa_valid_until', optional($customer->document)->visa_valid_until?->format('Y-m-d')) }}">
                <x-input-error for="visa_valid_until" />
            </div>
            <div class="col-md-6">
                <label class="form-label">Date of Birth</label>
                <input type="date" name="dob" class="form-control" value="{{ old('dob', $customer->dob?->format('Y-m-d')) }}">
                <x-input-error for="dob" />
            </div>
            <div class="col-md-6">
                <label class="form-label d-block">Gender</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" value="Male" @checked(old('gender', $customer->gender) === 'Male')>
                    <label class="form-check-label">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" value="Female" @checked(old('gender', $customer->gender) === 'Female')>
                    <label class="form-check-label">Female</label>
                </div>
                <x-input-error for="gender" />
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone</label>
                <div id="phone-fields">
                    @php($phones = old('phones', $customer->phones?->pluck('phone')->toArray() ?? ['']))
                    @foreach ($phones as $phone)
                        <input type="text" name="phones[]" class="form-control mb-2" value="{{ $phone }}">
                    @endforeach
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-add-phone>Add Phone</button>
                <x-input-error for="phones" />
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}">
                <x-input-error for="email" />
            </div>
            <div class="col-md-6">
                <label class="form-label">Country</label>
                <div class="dropdown">
                    <button class="form-select text-start" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" data-country-toggle>
                        <span data-country-label>
                            {{ old('country', $customer->country) ?: 'Select country' }}
                        </span>
                    </button>
                    <div class="dropdown-menu w-100 p-2">
                        <input type="text" class="form-control mb-2" placeholder="Search country" data-country-filter>
                        <div class="d-flex flex-column gap-1" style="max-height: 240px; overflow-y: auto;">
                            @foreach ($countries as $country)
                                <button type="button" class="dropdown-item" data-country-option="{{ $country }}">{{ $country }}</button>
                            @endforeach
                        </div>
                    </div>
                    <input type="hidden" name="country" value="{{ old('country', $customer->country) }}" data-country-value>
                </div>
                <x-input-error for="country" />
            </div>
            <div class="col-md-6">
                <label class="form-label">Member</label>
                <input type="number" name="member_count" class="form-control" value="{{ old('member_count', $customer->member_count ?? 1) }}">
                <x-input-error for="member_count" />
            </div>
            <div class="col-12">
                <label class="form-label">Address</label>
                <textarea name="address1" class="form-control" rows="2">{{ old('address1', $customer->address1) }}</textarea>
                <x-input-error for="address1" />
            </div>
            <div class="col-12">
                <label class="form-label">Address 2</label>
                <textarea name="address2" class="form-control" rows="2">{{ old('address2', $customer->address2) }}</textarea>
                <x-input-error for="address2" />
            </div>
            <div class="col-12">
                <label class="form-label">Note</label>
                <textarea name="note" class="form-control" rows="2">{{ old('note', $customer->note) }}</textarea>
                <x-input-error for="note" />
            </div>
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Save</button>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Cancel</a>
                <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Back</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('[data-add-phone]').forEach(function (button) {
        button.addEventListener('click', function () {
            var container = document.getElementById('phone-fields');
            var input = document.createElement('input');
            input.type = 'text';
            input.name = 'phones[]';
            input.className = 'form-control mb-2';
            container.appendChild(input);
        });
    });

    document.querySelectorAll('[data-country-toggle]').forEach(function (toggle) {
        var dropdown = toggle.closest('.dropdown');
        if (!dropdown) {
            return;
        }

        var label = dropdown.querySelector('[data-country-label]');
        var hiddenInput = dropdown.querySelector('[data-country-value]');
        var filterInput = dropdown.querySelector('[data-country-filter]');
        var options = Array.from(dropdown.querySelectorAll('[data-country-option]'));

        options.forEach(function (option) {
            option.addEventListener('click', function () {
                var value = option.getAttribute('data-country-option') || '';
                hiddenInput.value = value;
                label.textContent = value || 'Select country';
                filterInput.value = '';
                options.forEach(function (item) {
                    item.classList.remove('d-none');
                });
            });
        });

        filterInput.addEventListener('input', function () {
            var query = filterInput.value.toLowerCase();
            options.forEach(function (option) {
                var text = option.textContent.toLowerCase();
                option.classList.toggle('d-none', query && !text.includes(query));
            });
        });

        toggle.addEventListener('click', function () {
            setTimeout(function () {
                filterInput.focus();
            }, 0);
        });
    });
</script>
@endpush
