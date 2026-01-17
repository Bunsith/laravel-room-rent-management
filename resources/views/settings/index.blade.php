@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="form-section">
        <h2 class="page-title mb-3">App Settings</h2>
        <form method="post" action="{{ route('settings.update') }}" enctype="multipart/form-data">
            @csrf
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
                    <label class="form-label">Deposit Default</label>
                    <input type="number" step="0.01" name="deposit_default" class="form-control" value="{{ old('deposit_default', $setting->deposit_default) }}">
                    <x-input-error for="deposit_default" />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Company Logo</label>
                    <input type="file" name="logo" class="form-control">
                    <x-input-error for="logo" />
                    @if ($setting->logo)
                        <div class="mt-2">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($setting->logo) }}" alt="Logo" height="48">
                        </div>
                    @endif
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Save Settings</button>
                </div>
            </div>
        </form>
    </div>
@endsection
