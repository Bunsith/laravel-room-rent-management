<div class="row g-4">
    @can('journal.manage')
        <div class="col-12">
            <div class="card rr-data-card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <h5 class="mb-1">New Journal Entry</h5>
                        <small class="text-muted">Record cash flow, adjustments, and supporting references.</small>
                    </div>
                    <div class="d-flex align-items-center gap-2 text-muted">
                        <i class="bi bi-calendar3"></i>
                        <span class="fw-semibold">{{ now()->format('M d, Y') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('journal-entries.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Date</label>
                                <input type="date" name="date" class="form-control" value="{{ now()->toDateString() }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Amount</label>
                                <input type="number" step="0.01" name="amount" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Currency</label>
                                <select name="currency" class="form-select">
                                    <option value="USD">USD</option>
                                    <option value="KHR">KHR</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Method</label>
                                <select name="method" class="form-select">
                                    <option value="CASH">CASH</option>
                                    <option value="ABA">ABA</option>
                                    <option value="BANK">BANK</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Account Type</label>
                                <select name="account_type_id" class="form-select">
                                    <option value="">Select</option>
                                    @foreach ($accountTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Resources Budget</label>
                                <select name="resource_budget_id" class="form-select">
                                    <option value="">Select</option>
                                    @foreach ($resourceBudgets as $budget)
                                        <option value="{{ $budget->id }}">{{ $budget->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Floor</label>
                                <select name="floor_id" class="form-select">
                                    <option value="">Select</option>
                                    @foreach ($floors as $floor)
                                        <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Note</label>
                                <textarea name="note" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ref Files</label>
                                <input type="file" name="attachment" class="form-control">
                            </div>
                            <div class="col-12 d-flex flex-wrap justify-content-end gap-2">
                                <button class="btn btn-primary" type="submit">Save Entry</button>
                                <button class="btn btn-outline-secondary" type="reset">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="col-12">
            <div class="card">
                <div class="card-body text-muted">
                    You do not have permission to create journal entries.
                </div>
            </div>
        </div>
    @endcan
    <div class="col-12">
        <div class="card rr-data-card">
            <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <h5 class="mb-1">Entries</h5>
                        <small class="text-muted">
                            Showing {{ $journalEntries->firstItem() ?? 0 }}-{{ $journalEntries->lastItem() ?? 0 }} of {{ $journalEntries->total() }}
                        </small>
                    </div>
                    <span class="badge badge-soft">{{ $journalEntries->total() }} Records</span>
                    <form method="get" class="d-flex flex-wrap align-items-center gap-2">
                        <input type="hidden" name="tab" value="journal">
                        <div class="rr-search-wrap">
                            <i class="bi bi-search"></i>
                            <input type="text" name="search" class="form-control rr-search-input" placeholder="Search note" value="{{ request('search') }}">
                        </div>
                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                        <a class="btn btn-light" href="{{ route('rentals.index', ['tab' => 'journal']) }}">Reset</a>
                    </form>
                </div>
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
                            @forelse ($journalEntries as $entry)
                                <tr>
                                    <td>{{ $entry->date?->format('Y-m-d') }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($entry->note, 24) }}</td>
                                    <td class="fw-semibold">{{ number_format($entry->amount, 2) }} <span class="text-muted">{{ $entry->currency }}</span></td>
                                    <td>
                                        <span class="badge badge-soft text-uppercase">{{ $entry->method }}</span>
                                    </td>
                                    <td>{{ $entry->accountType->name ?? '-' }}</td>
                                    <td>{{ $entry->floor->name ?? '-' }}</td>
                                    <td>{{ $entry->resourceBudget->name ?? '-' }}</td>
                                    <td>
                                        @if ($entry->attachment)
                                            <a class="btn btn-sm btn-outline-secondary action-btn" href="{{ \Illuminate\Support\Facades\Storage::url($entry->attachment) }}" target="_blank">
                                                <i class="bi bi-paperclip me-1"></i>
                                                View
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @can('journal.manage')
                                            <div class="rr-inline-actions">
                                                <form method="post" action="{{ route('journal-entries.destroy', $entry) }}">
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
                                    <td colspan="9" class="text-center text-muted py-4">No journal entries.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white">
                {{ $journalEntries->links() }}
            </div>
        </div>
    </div>
</div>
