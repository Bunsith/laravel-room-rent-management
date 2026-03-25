@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card rr-data-card">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="rr-auth-mark">RR</div>
                        <h4 class="mt-3 mb-0">Welcome Back</h4>
                        <small class="text-muted">Sign in to continue managing rentals and operations.</small>
                    </div>
                    <form method="post" action="{{ route('login.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                            <x-input-error for="email" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                            <x-input-error for="password" />
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            <a href="{{ route('password.request') }}" class="small">Forgot password?</a>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Sign In</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
