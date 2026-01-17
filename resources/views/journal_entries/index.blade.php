@extends('layouts.app')

@section('title', 'Journal Entries')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="page-title mb-1">Journal Entries</h2>
            <p class="text-muted">Track account movements and expenses.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">New Entry</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('journal-entries.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" value="{{ now()->toDateString() }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Note</label>
                            <textarea name="note" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Account Type</label>
                            <select name="account_type_id" class="form-select">
                                <option value="">Select</option>
                                @foreach ($accountTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Resources Budget</label>
                            <select name="resource_budget_id" class="form-select">
                                <option value="">Select</option>
                                @foreach ($resourceBudgets as $budget)
                                    <option value="{{ $budget->id }}">{{ $budget->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" step="0.01" name="amount" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Currency</label>
                            <select name="currency" class="form-select">
                                <option value="USD">USD</option>
                                <option value="KHR">KHR</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Method</label>
                            <select name="method" class="form-select">
                                <option value="CASH">CASH</option>
                                <option value="ABA">ABA</option>
                                <option value="BANK">BANK</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Floor</label>
                            <select name="floor_id" class="form-select">
                                <option value="">Select</option>
                                @foreach ($floors as $floor)
                                    <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ref Files</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit">Save</button>
                            <button class="btn btn-outline-secondary" type="reset">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Entries</h5>
                    <form method="get" class="w-50">
                        <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Search note">
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Note</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Account Type</th>
                                    <th>Floor</th>
                                    <th>Resources</th>
                                    <th>Attachment</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($entries as $entry)
                                    <tr>
                                        <td>{{ $entry->date?->format('Y-m-d') }}</td>
                                        <td>{{ \Illuminate\Support\Str::limit($entry->note, 20) }}</td>
                                        <td>{{ number_format($entry->amount, 2) }} {{ $entry->currency }}</td>
                                        <td>{{ $entry->method }}</td>
                                        <td>{{ $entry->accountType->name ?? '-' }}</td>
                                        <td>{{ $entry->floor->name ?? '-' }}</td>
                                        <td>{{ $entry->resourceBudget->name ?? '-' }}</td>
                                        <td>
                                            @if ($entry->attachment)
                                                <a href="{{ \Illuminate\Support\Facades\Storage::url($entry->attachment) }}" target="_blank">View</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="d-flex gap-1">
                                            <button class="btn btn-sm btn-outline-secondary action-btn" data-bs-toggle="collapse" data-bs-target="#edit-entry-{{ $entry->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form method="post" action="{{ route('journal-entries.destroy', $entry) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger action-btn" type="submit">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="edit-entry-{{ $entry->id }}">
                                        <td colspan="9">
                                            <form method="post" action="{{ route('journal-entries.update', $entry) }}" enctype="multipart/form-data" class="row g-2">
                                                @csrf
                                                @method('PUT')
                                                <div class="col-md-2">
                                                    <input type="date" name="date" class="form-control" value="{{ $entry->date?->format('Y-m-d') }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" name="note" class="form-control" value="{{ $entry->note }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="number" step="0.01" name="amount" class="form-control" value="{{ $entry->amount }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <select name="method" class="form-select">
                                                        @foreach (['CASH', 'ABA', 'BANK'] as $method)
                                                            <option value="{{ $method }}" @selected($entry->method === $method)>{{ $method }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <select name="currency" class="form-select">
                                                        @foreach (['USD', 'KHR'] as $currency)
                                                            <option value="{{ $currency }}" @selected($entry->currency === $currency)>{{ $currency }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <button class="btn btn-primary w-100" type="submit">Update</button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">No journal entries.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    {{ $entries->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
