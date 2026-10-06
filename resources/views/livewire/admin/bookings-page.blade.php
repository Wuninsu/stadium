<div>
    <div class="page-header d-flex flex-wrap justify-content-between align-items-end gap-3">
        <div>
            <div class="crumb mb-1">Admin / Bookings</div>
            <h3 class="fw-bold mb-0">Bookings</h3>
            <p class="text-secondary mb-0 small">Review, approve and manage all facility reservation requests.</p>
        </div>
        <a href="{{ route('booking') }}" class="btn btn-flood rounded-3"><i class="bi bi-plus-lg me-1"></i>New Booking</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div><div class="stat-value">{{ $counts['Pending'] }}</div><div class="stat-label">Pending review</div></div>
                <div class="stat-icon" style="background:#FFF3D6;color:var(--flood-600);"><i class="bi bi-hourglass-split"></i></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div><div class="stat-value">{{ $counts['Confirmed'] }}</div><div class="stat-label">Confirmed</div></div>
                <div class="stat-icon" style="background:var(--pitch-100);color:var(--pitch-700);"><i class="bi bi-check-circle"></i></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div><div class="stat-value">{{ $counts['Cancelled'] }}</div><div class="stat-label">Cancelled</div></div>
                <div class="stat-icon" style="background:#FBE7E7;color:var(--danger);"><i class="bi bi-x-circle"></i></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div><div class="stat-value">GH&#8373;{{ number_format($revenueBooked / 1000, 1) }}k</div><div class="stat-label">Revenue booked</div></div>
                <div class="stat-icon" style="background:#FFF3D6;color:var(--flood-600);"><i class="bi bi-receipt"></i></div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <ul class="nav nav-pills-soft gap-1">
                @foreach (['All', 'Pending', 'Confirmed', 'Cancelled'] as $option)
                    <li class="nav-item">
                        <button type="button" wire:click="setStatus('{{ $option }}')" class="nav-link {{ $status === $option ? 'active' : '' }}">{{ $option }} ({{ $counts[$option] }})</button>
                    </li>
                @endforeach
            </ul>
            <div class="d-flex gap-2">
                <div class="input-group input-group-sm" style="max-width:220px;">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input class="form-control" wire:model.live.debounce.400ms="search" placeholder="Search booking, org...">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-modern align-middle">
                <thead>
                    <tr>
                        <th><input type="checkbox" class="form-check-input"></th>
                        <th>Booking ID</th><th>Organisation</th><th>Facility</th><th>Date &amp; Time</th><th>Purpose</th><th>Amount</th><th>Status</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        <tr>
                            <td><input type="checkbox" class="form-check-input"></td>
                            <td class="font-mono small">{{ $booking->booking_ref }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img class="row-avatar" src="https://ui-avatars.com/api/?name={{ urlencode($booking->organisation ?? $booking->contact_name) }}&background=0B6E4F&color=fff">
                                    {{ $booking->organisation ?? $booking->contact_name }}
                                </div>
                            </td>
                            <td>{{ $booking->facility->name }}</td>
                            <td>{{ $booking->date->format('M d') }}, {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }}</td>
                            <td>{{ $booking->purpose_label }}</td>
                            <td class="font-mono">GH&#8373;{{ number_format($booking->amount) }}</td>
                            <td><span class="badge {{ $booking->status_badge_class }}">{{ ucfirst($booking->status) }}</span></td>
                            <td>
                                <div class="d-flex gap-1">
                                    @if ($booking->status === 'pending')
                                        <button type="button" wire:click="approve({{ $booking->id }})" class="btn btn-sm btn-pitch" title="Approve"><i class="bi bi-check-lg"></i></button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-pitch" title="View"><i class="bi bi-eye"></i></button>
                                    @endif
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if ($booking->status !== 'cancelled')
                                                <li><button type="button" class="dropdown-item text-danger" wire:click="cancel({{ $booking->id }})">Cancel booking</button></li>
                                            @endif
                                            @if ($booking->status !== 'confirmed')
                                                <li><button type="button" class="dropdown-item" wire:click="approve({{ $booking->id }})">Mark confirmed</button></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-secondary py-4">No bookings match that filter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <span class="small text-secondary">Showing {{ $bookings->count() }} of {{ $counts['All'] }} bookings</span>
            {{ $bookings->links() }}
        </div>
    </div>
</div>
