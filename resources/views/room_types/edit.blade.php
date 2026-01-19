@extends('layouts.app')

@section('title', 'Edit Room Type')

@section('content')
    <div class="form-section">
        <h2 class="page-title mb-3">Edit Room Type</h2>
        <form method="post" action="{{ route('room-types.update', $roomType) }}">
            @csrf
            @method('PUT')
            @include('room_types._form')
        </form>
    </div>
@endsection
