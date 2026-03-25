@extends('layouts.app')

@section('title', 'Customers')

@section('content')
    <div class="rr-section-head mb-4">
        <div>
            <h2 class="page-title mb-1">Customer List</h2>
            <p class="text-muted mb-0">Manage customer profiles and documents.</p>
        </div>
        @can('customers.manage')
            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                Add Customer
            </a>
        @endcan
    </div>

    <div class="card rr-data-card">
        <div class="card-header">
            <div class="rr-card-header-grid">
                <div>
                    <h5 class="mb-0">Customers</h5>
                </div>
                <div>
                    <form method="get" class="d-flex justify-content-md-end mt-2 mt-md-0">
                        <div class="rr-search-wrap">
                            <i class="bi bi-search"></i>
                            <input type="text" name="search" value="{{ $search }}" class="form-control rr-search-input" placeholder="Search customer">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Nation ID</th>
                            <th>Passport</th>
                            <th>VISA</th>
                            <th>Date Birth</th>
                            <th>Ages</th>
                            <th>Phone</th>
                            <th>Country</th>
                            <th>Members</th>
                            <th>Attachment</th>
                            <th>Note</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $index => $customer)
                            <tr>
                                <td>{{ $customers->firstItem() + $index }}</td>
                                <td>
                                    <img src="{{ $customer->photo ? \Illuminate\Support\Facades\Storage::url($customer->photo) : 'https://via.placeholder.com/40' }}"
                                         alt="Photo" class="rr-avatar">
                                </td>
                                <td>{{ $customer->full_name }}</td>
                                <td>{{ $customer->document->national_id ?? '-' }}</td>
                                <td>{{ $customer->document->passport_id ?? '-' }}</td>
                                <td>{{ $customer->document->visa_id ?? '-' }}</td>
                                <td>{{ $customer->dob?->format('Y-m-d') ?? '-' }}</td>
                                <td>{{ $customer->age ?? '-' }}</td>
                                <td>{{ $customer->phones->pluck('phone')->implode(', ') }}</td>
                                <td>{{ $customer->country ?? '-' }}</td>
                                <td>{{ $customer->member_count }}</td>
                                <td>
                                    @php($attachments = $customer->document->attachment_file ?? [])
                                    @if (!empty($attachments))
                                        <a href="{{ \Illuminate\Support\Facades\Storage::url($attachments[0]) }}" target="_blank" class="rr-attachment-link">{{ basename($attachments[0]) }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="rr-cell-note">{{ \Illuminate\Support\Str::limit($customer->note, 20) }}</td>
                                <td>
                                    @can('customers.manage')
                                        <div class="rr-inline-actions">
                                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-primary action-btn">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="post" action="{{ route('customers.destroy', $customer) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger action-btn" type="submit">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center text-muted py-4">No customers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $customers->links() }}
        </div>
    </div>
@endsection
