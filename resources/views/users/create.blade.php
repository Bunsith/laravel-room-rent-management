@extends('layouts.app')

@section('title', 'Add User')

@section('content')
    <div class="form-section">
        <h2 class="page-title mb-3">Add User</h2>
        <form method="post" action="{{ route('users.store') }}">
            @csrf
            @include('users._form')
        </form>
    </div>
@endsection
