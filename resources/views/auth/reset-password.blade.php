@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card rr-data-card">
                <div class="card-body p-4">
                    <div class="text-center mb-3">
                        <div class="rr-auth-mark">RR</div>
                    </div>
                    <h4 class="mb-2">Reset Password</h4>
                    <p class="text-muted mb-3">Choose a strong new password for your account.</p>
                    <form method="post" action="{{ route('password.store') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $email) }}" required>
                            <x-input-error for="email" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" required>
                            <x-input-error for="password" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                            <x-input-error for="password_confirmation" />
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
