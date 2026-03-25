@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="rr-section-head mb-4">
        <div>
            <div class="rr-form-kicker">Configuration Center</div>
            <h2 class="page-title mb-1">App Settings</h2>
            <p class="text-muted mb-0">Configure profile, billing defaults, and utility settings used across operations.</p>
        </div>
    </div>

    <form method="post" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="d-grid gap-4">
        @csrf

        <div class="card rr-data-card">
            <div class="card-header">
                <h5 class="mb-0">Company Profile</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $setting->company_name) }}">
                        <x-input-error for="company_name" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Default Currency</label>
                        <select name="default_currency" class="form-select">
                            @foreach (['USD', 'KHR'] as $currency)
                                <option value="{{ $currency }}" @selected(old('default_currency', $setting->default_currency ?? 'USD') === $currency)>{{ $currency }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="default_currency" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Company Logo</label>
                        <input type="file" name="logo" class="form-control">
                        <x-input-error for="logo" />
                        <div class="form-text">Used for invoices and printable documents.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Current Logo</label>
                        @if ($setting->logo)
                            <div class="rr-check-grid d-flex align-items-center gap-3">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($setting->logo) }}" alt="Logo" height="52">
                                <span class="text-muted small">Active logo is displayed on generated invoices.</span>
                            </div>
                        @else
                            <div class="rr-check-grid text-muted small">No logo uploaded yet.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card rr-data-card">
            <div class="card-header">
                <h5 class="mb-0">Billing Defaults</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Deposit Default</label>
                        <input type="number" step="0.01" name="deposit_default" class="form-control" value="{{ old('deposit_default', $setting->deposit_default) }}">
                        <x-input-error for="deposit_default" />
                        <div class="form-text">Applied when creating new rentals.</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Water Rate (per unit)</label>
                        <input type="number" step="0.01" name="water_rate" class="form-control" value="{{ old('water_rate', $setting->water_rate ?? 0.75) }}">
                        <x-input-error for="water_rate" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Electric Rate (per unit)</label>
                        <input type="number" step="0.01" name="electric_rate" class="form-control" value="{{ old('electric_rate', $setting->electric_rate ?? 0.25) }}">
                        <x-input-error for="electric_rate" />
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 rr-form-actions">
            <button class="btn btn-primary" type="submit">Save Settings</button>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Back to Dashboard</a>
        </div>
    </form>
@endsection
