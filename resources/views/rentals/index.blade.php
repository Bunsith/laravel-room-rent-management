@extends('layouts.app')

@section('title', 'Rentals')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="page-title mb-1">Rental Management</h2>
            <p class="text-muted">Track availability, rentals, collections, and journal entries.</p>
        </div>
    </div>

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'available' ? 'active' : '' }}" href="{{ route('rentals.index', ['tab' => 'available']) }}">Available Room</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'rented' ? 'active' : '' }}" href="{{ route('rentals.index', ['tab' => 'rented']) }}">Rented Room</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'collection' ? 'active' : '' }}" href="{{ route('rentals.index', ['tab' => 'collection']) }}">Rental Collection</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'journal' ? 'active' : '' }}" href="{{ route('rentals.index', ['tab' => 'journal']) }}">Journal Entries</a>
        </li>
    </ul>

    <div class="tab-content">
        @if ($tab === 'available')
            @include('rentals.partials.available')
        @elseif ($tab === 'rented')
            @include('rentals.partials.rented')
        @elseif ($tab === 'collection')
            @include('rentals.partials.collection')
        @elseif ($tab === 'journal')
            @include('rentals.partials.journal')
        @endif
    </div>
@endsection
