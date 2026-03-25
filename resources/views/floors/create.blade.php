@extends('layouts.app')

@section('title', 'Add Floor')

@section('content')
    <div class="form-section">
        <h2 class="page-title mb-3">Add Floor</h2>
        <p class="rr-form-subtitle">Create a new floor to keep room assignments and occupancy tracking organized.</p>
        <form method="post" action="{{ route('floors.store') }}">
            @csrf
            @include('floors._form')
        </form>
    </div>
@endsection
