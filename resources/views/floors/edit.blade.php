@extends('layouts.app')

@section('title', 'Edit Floor')

@section('content')
    <div class="form-section">
        <h2 class="page-title mb-3">Edit Floor</h2>
        <form method="post" action="{{ route('floors.update', $floor) }}">
            @csrf
            @method('PUT')
            @include('floors._form')
        </form>
    </div>
@endsection
