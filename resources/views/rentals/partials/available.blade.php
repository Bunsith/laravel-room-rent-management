<div class="card rr-data-card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center gap-2">
            <div>
                <h5 class="mb-0">Available Rooms</h5>
                <small class="text-muted">Create new rental contracts from currently open units.</small>
            </div>
            <span class="badge badge-soft">{{ $availableRooms->count() }} Rooms</span>
        </div>
    </div>
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Room</th>
                        <th>Fee</th>
                        <th>Guest</th>
                        <th>People</th>
                        <th>Deposit</th>
                        <th>Note</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($availableRooms as $room)
                        <tr>
                            <td colspan="8" class="p-0">
                                <form method="post" action="{{ route('rentals.store') }}" class="d-flex align-items-center gap-2 p-3 flex-wrap">
                                    @csrf
                                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                                    <input type="hidden" name="room_fee" value="{{ $room->price }}">
                                    <div style="min-width:140px;">
                                        <input type="date" name="rent_date" class="form-control" value="{{ now()->toDateString() }}">
                                    </div>
                                    <div style="min-width:180px;">
                                        <div class="fw-semibold">{{ $room->name }}</div>
                                        <div class="text-muted small">{{ $room->floor->name ?? '' }} - {{ $room->roomType->name ?? '' }}</div>
                                    </div>
                                    <div>
                                        <span class="badge badge-soft">{{ number_format($room->price, 2) }} {{ $room->currency }} / {{ $room->stay_type }}</span>
                                    </div>
                                    <div style="min-width:200px;">
                                        <div class="input-group">
                                            <select name="customer_id" class="form-select" required>
                                                <option value="">Select customer</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->full_name }}</option>
                                                @endforeach
                                            </select>
                                            @can('customers.manage')
                                                <a href="{{ route('customers.create') }}" class="btn btn-outline-secondary">
                                                    <i class="bi bi-plus"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                    <div style="min-width:90px;">
                                        <input type="number" name="people" class="form-control" value="1" min="1">
                                    </div>
                                    <div class="d-flex flex-column gap-2" style="min-width:180px;">
                                        <input type="number" step="0.01" name="deposit_fee" class="form-control" placeholder="Deposit fee">
                                        <input type="number" step="0.01" name="partial_pay" class="form-control" placeholder="Partial pay">
                                    </div>
                                    <div style="min-width:140px;">
                                        <input type="date" name="expected_check_out" class="form-control" value="{{ now()->endOfMonth()->toDateString() }}">
                                    </div>
                                    <div style="min-width:160px;">
                                        <input type="text" name="note" class="form-control" placeholder="Note">
                                    </div>
                                    <div class="rr-inline-actions">
                                        @can('rentals.manage')
                                            <button class="btn btn-outline-primary" type="submit" name="print" value="1">Save + Print</button>
                                            <button class="btn btn-primary" type="submit">Rent</button>
                                        @endcan
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No available rooms.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
