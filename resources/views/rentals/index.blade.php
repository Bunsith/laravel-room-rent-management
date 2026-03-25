@extends('layouts.app')

@section('title', 'Rentals')

@section('content')
    <div class="rr-section-head mb-4">
        <div>
            <h2 class="page-title mb-1">Rental Management</h2>
            <p class="text-muted mb-0">Track availability, rentals, collections, and journal entries.</p>
        </div>
    </div>

    <ul class="nav nav-pills rr-tabs mb-4">
        @can('rentals.view')
            <li class="nav-item">
                <a class="nav-link {{ $tab === 'available' ? 'active' : '' }}" href="{{ route('rentals.index', ['tab' => 'available']) }}">
                    <i class="bi bi-house-door"></i>
                    <span>Available Room</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tab === 'rented' ? 'active' : '' }}" href="{{ route('rentals.index', ['tab' => 'rented']) }}">
                    <i class="bi bi-door-closed"></i>
                    <span>Rented Room</span>
                </a>
            </li>
        @endcan
        @can('collections.view')
            <li class="nav-item">
                <a class="nav-link {{ $tab === 'collection' ? 'active' : '' }}" href="{{ route('rentals.index', ['tab' => 'collection']) }}">
                    <i class="bi bi-cash-stack"></i>
                    <span>Rental Collection</span>
                </a>
            </li>
        @endcan
        @can('journal.view')
            <li class="nav-item">
                <a class="nav-link {{ $tab === 'journal' ? 'active' : '' }}" href="{{ route('rentals.index', ['tab' => 'journal']) }}">
                    <i class="bi bi-journal-text"></i>
                    <span>Journal Entries</span>
                </a>
            </li>
        @endcan
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
