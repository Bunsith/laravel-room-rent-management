@extends('layouts.app')

@section('title', 'Add Customer')

@section('content')
    <div class="form-section">
        <h2 class="page-title mb-3">Add Customer</h2>
        <p class="rr-form-subtitle">Register a new resident profile with identity documents, contact details, and occupancy metadata.</p>
        <form method="post" action="{{ route('customers.store') }}" enctype="multipart/form-data">
            @csrf
            @include('customers._form')
        </form>
    </div>
@endsection
