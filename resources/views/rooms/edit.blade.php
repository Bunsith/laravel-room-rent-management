@extends('layouts.app')

@section('title', 'Edit Room')

@section('content')
    <div class="form-section">
        <h2 class="page-title mb-3">Edit Room</h2>
        <form method="post" action="{{ route('rooms.update', $room) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('rooms._form')
        </form>
    </div>
@endsection
