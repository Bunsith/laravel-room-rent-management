@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
    <div class="form-section">
        <h2 class="page-title mb-3">Edit Customer</h2>
        <p class="rr-form-subtitle">Update identity records, active documents, and communication details for this customer.</p>
        <form method="post" action="{{ route('customers.update', $customer) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('customers._form')
        </form>
    </div>
@endsection
