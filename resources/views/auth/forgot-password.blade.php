@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card">
                <div class="card-body p-4">
                    <div class="text-center mb-3">
                        <div class="rr-auth-mark">RR</div>
                    </div>
                    <h4 class="mb-2">Forgot your password?</h4>
                    <p class="text-muted">Enter your email and we will send a reset link.</p>
                    <form method="post" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                            <x-input-error for="email" />
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Send Reset Link</button>
                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}">Back to login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
