@extends('layouts.app')

@section('title', 'Add Facility')

@section('content')
    <div class="form-section">
        <h2 class="page-title mb-3">Add Facility</h2>
        <form method="post" action="{{ route('facilities.store') }}">
            @csrf
            @include('facilities._form')
        </form>
    </div>
@endsection
