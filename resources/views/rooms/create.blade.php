@extends('layouts.app')

@section('title', 'Add Room')

@section('content')
    <div class="form-section">
        <h2 class="page-title mb-3">Add Room</h2>
        <p class="rr-form-subtitle">Create a room profile with floor assignment, pricing, facilities, and occupancy type.</p>
        <form method="post" action="{{ route('rooms.store') }}" enctype="multipart/form-data">
            @csrf
            @include('rooms._form')
        </form>
    </div>
@endsection
