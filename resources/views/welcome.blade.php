@extends('layouts.auth')

@section('title', 'Welcome')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card rr-data-card">
                <div class="card-body p-4 text-center">
                    <div class="rr-auth-mark mb-3">RR</div>
                    <h2 class="mb-2">Room Rental Management</h2>
                    <p class="text-muted mb-3">Sign in to manage rooms, customers, rentals, and collections in one place.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary">Go to Sign In</a>
                </div>
            </div>
        </div>
    </div>
@endsection
