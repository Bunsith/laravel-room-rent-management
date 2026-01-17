<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Rented Rooms</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Room</th>
                        <th>Fee</th>
                        <th>Room Fee</th>
                        <th>Guest</th>
                        <th>People</th>
                        <th>Check-in</th>
                        <th>Check-Out</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rentals as $rental)
                        <tr>
                            <td>{{ $rental->rent_date?->format('Y-m-d') }}</td>
                            <td>{{ $rental->room->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ number_format($rental->room_fee, 2) }} {{ $rental->room->currency ?? 'USD' }} / {{ $rental->room->stay_type ?? 'Month' }}
                                </span>
                            </td>
                            <td style="min-width:140px;">
                                <input type="number" step="0.01" name="room_fee" class="form-control"
                                    value="{{ $rental->room_fee }}" form="rental-form-{{ $rental->id }}">
                            </td>
                            <td>{{ $rental->customer->full_name ?? '-' }}</td>
                            <td style="min-width:90px;">
                                <input type="number" name="people" class="form-control"
                                    value="{{ $rental->people }}" form="rental-form-{{ $rental->id }}">
                            </td>
                            <td style="min-width:140px;">
                                <input type="date" name="check_in" class="form-control"
                                    value="{{ $rental->check_in?->format('Y-m-d') }}" form="rental-form-{{ $rental->id }}">
                            </td>
                            <td style="min-width:140px;">
                                <input type="date" name="expected_check_out" class="form-control"
                                    value="{{ $rental->expected_check_out?->format('Y-m-d') }}" form="rental-form-{{ $rental->id }}">
                            </td>
                            <td class="d-flex gap-2">
                                <form method="post" action="{{ route('rentals.update', $rental) }}" id="rental-form-{{ $rental->id }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-primary action-btn" type="submit">
                                        <i class="bi bi-save"></i>
                                    </button>
                                </form>
                                <form method="post" action="{{ route('rentals.checkout', $rental) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-danger action-btn" type="submit">Check Out</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No rented rooms.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $rentals->links() }}
    </div>
</div>
