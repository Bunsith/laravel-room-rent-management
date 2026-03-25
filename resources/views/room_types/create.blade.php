@extends('layouts.app')

@section('title', 'Add Room Type')

@section('content')
    <div class="form-section">
        <h2 class="page-title mb-3">Add Room Type</h2>
        <p class="rr-form-subtitle">Create a room classification used for inventory grouping and reporting.</p>
        <form method="post" action="{{ route('room-types.store') }}">
            @csrf
            @include('room_types._form')
        </form>
    </div>
@endsection
