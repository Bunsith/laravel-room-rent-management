@extends('layouts.app')

@section('title', 'Edit Facility')

@section('content')
    <div class="form-section">
        <h2 class="page-title mb-3">Edit Facility</h2>
        <form method="post" action="{{ route('facilities.update', $facility) }}">
            @csrf
            @method('PUT')
            @include('facilities._form')
        </form>
    </div>
@endsection
