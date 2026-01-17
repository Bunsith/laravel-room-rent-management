@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="form-section">
        <h2 class="page-title mb-3">Edit User</h2>
        <form method="post" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')
            @include('users._form')
        </form>
    </div>
@endsection
