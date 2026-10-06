<div>
    @php
        $hour = now()->hour;
        $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
        $displayName = auth()->user()->first_name ?? (auth()->user()->name ?? 'there');
    @endphp

    <div class="page-header d-flex flex-wrap justify-content-between align-items-end gap-3">
        <div>
            <div class="crumb mb-1">Admin / Overview</div>
            <h3 class="fw-bold mb-0">{{ $greeting }}, {{ $displayName }}</h3>
            <p class="text-secondary mb-0 small">Here's what's happening at the stadium today, {{ now()->format('j F Y') }}.</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-pitch rounded-3"><i class="bi bi-download me-1"></i>Export report</button>
            <a href="{{ route('admin.bookings') }}" class="btn btn-flood rounded-3"><i class="bi bi-plus-lg me-1"></i>New Booking</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div>
                    <div class="stat-value"><span data-count="{{ $stats['bookings']['value'] }}">0</span></div>
                    <div class="stat-label">Bookings this month</div>
                    <span class="stat-trend {{ $stats['bookings']['trend']['dir'] }} mt-2"><i class="bi bi-arrow-{{ $stats['bookings']['trend']['dir'] }}-short"></i>{{ $stats['bookings']['trend']['text'] }}</span>
                </div>
                <div class="stat-icon" style="background:var(--pitch-100);color:var(--pitch-700);"><i class="bi bi-calendar-check"></i></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div>
                    <div class="stat-value">GH&#8373;<span data-count="{{ (int) $stats['revenue']['value'] }}">0</span></div>
                    <div class="stat-label">Revenue this month</div>
                    <span class="stat-trend {{ $stats['revenue']['trend']['dir'] }} mt-2"><i class="bi bi-arrow-{{ $stats['revenue']['trend']['dir'] }}-short"></i>{{ $stats['revenue']['trend']['text'] }}</span>
                </div>
                <div class="stat-icon" style="background:#FFF3D6;color:var(--flood-600);"><i class="bi bi-cash-coin"></i></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div>
                    <div class="stat-value"><span data-count="{{ $stats['fixtures']['value'] }}">0</span></div>
                    <div class="stat-label">Upcoming fixtures</div>
                    <span class="stat-trend up mt-2"><i class="bi bi-flag"></i>Scheduled</span>
                </div>
                <div class="stat-icon" style="background:var(--pitch-100);color:var(--pitch-700);"><i class="bi bi-flag"></i></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div>
                    <div class="stat-value"><span data-count="{{ $stats['utilisation']['value'] }}" data-suffix="%">0</span></div>
                    <div class="stat-label">Pitch utilisation</div>
                    <span class="stat-trend up mt-2"><i class="bi bi-graph-up-arrow"></i>Main Pitch</span>
                </div>
                <div class="stat-icon" style="background:#FFF3D6;color:var(--flood-600);"><i class="bi bi-graph-up-arrow"></i></div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="panel h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="panel-title mb-0">Revenue &amp; bookings</h6>
                    <span class="small text-secondary">Last 6 months</span>
                </div>
                <div wire:ignore>
                    <canvas id="revenueChart" height="110"
                        data-labels="{{ $chartLabels->toJson() }}"
                        data-revenue="{{ $revenueSeries->toJson() }}"
                        data-bookings="{{ $bookingsSeries->toJson() }}"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel h-100">
                <h6 class="panel-title mb-3">Facility usage split</h6>
                <div wire:ignore>
                    <canvas id="usageChart" height="180"
                        data-labels="{{ $usageFacilities->pluck('name')->toJson() }}"
                        data-values="{{ $usageFacilities->pluck('bookings_count')->toJson() }}"></canvas>
                </div>
                <div class="d-flex flex-column gap-2 mt-3">
                    @php $dotColors = ['#0B6E4F', '#FFC72C', '#16A075', '#DDE7E0']; @endphp
                    @foreach ($usageFacilities as $i => $facility)
                        <div class="d-flex justify-content-between small">
                            <span><i class="bi bi-circle-fill me-2" style="color:{{ $dotColors[$i % 4] }};"></i>{{ $facility->name }}</span>
                            <span class="fw-semibold">{{ $facility->bookings_count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="panel h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="panel-title mb-0">Recent bookings</h6>
                    <a href="{{ route('admin.bookings') }}" class="small fw-semibold text-pitch">View all</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead><tr><th>Organisation</th><th>Facility</th><th>Date</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                            @forelse ($recentBookings as $booking)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img class="row-avatar" src="https://ui-avatars.com/api/?name={{ urlencode($booking->organisation ?? $booking->contact_name) }}&background=0B6E4F&color=fff">
                                            <span class="fw-semibold">{{ $booking->organisation ?? $booking->contact_name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $booking->facility->name }}</td>
                                    <td>{{ $booking->date->format('M d') }}</td>
                                    <td>
                                        <span class="badge badge-soft-{{ $booking->status === 'confirmed' ? 'green' : ($booking->status === 'pending' ? 'yellow' : 'red') }}">{{ ucfirst($booking->status) }}</span>
                                    </td>
                                    <td><i class="bi bi-three-dots text-secondary"></i></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-secondary py-4">No bookings yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="panel h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="panel-title mb-0">Upcoming fixtures</h6>
                    <a href="{{ route('admin.events') }}" class="small fw-semibold text-pitch">Calendar</a>
                </div>
                <div class="d-flex flex-column gap-3">
                    @forelse ($upcomingEvents as $event)
                        <div class="d-flex gap-3 align-items-center">
                            <div class="event-date"><div class="d">{{ $event->date->format('d') }}</div><div class="m">{{ $event->date->format('M') }}</div></div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold small">{{ $event->title }}</div>
                                <div class="text-secondary" style="font-size:.78rem;">{{ $event->category_label }} &middot; {{ $event->facility->name }}</div>
                            </div>
                            <span class="badge badge-soft-{{ $event->status === 'confirmed' ? 'green' : 'yellow' }}">{{ $event->status === 'confirmed' ? 'Ready' : 'Prep' }}</span>
                        </div>
                    @empty
                        <div class="text-secondary small">No upcoming fixtures scheduled.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                function boot() {
                    var revCanvas = document.getElementById('revenueChart');
                    if (revCanvas && window.Chart && !revCanvas.dataset.rendered) {
                        revCanvas.dataset.rendered = '1';
                        var t = window.StadiumTheme;
                        new Chart(revCanvas, {
                            type: 'bar',
                            data: {
                                labels: JSON.parse(revCanvas.dataset.labels),
                                datasets: [
                                    { label: 'Revenue (GH₵)', data: JSON.parse(revCanvas.dataset.revenue), backgroundColor: t.pitch700, borderRadius: 6, order: 2 },
                                    { label: 'Bookings', data: JSON.parse(revCanvas.dataset.bookings), type: 'line', borderColor: t.flood400, backgroundColor: t.flood400, tension: .4, yAxisID: 'y1', order: 1, pointRadius: 3 }
                                ]
                            },
                            options: {
                                responsive: true,
                                interaction: { mode: 'index', intersect: false },
                                scales: {
                                    y: { grid: { color: t.line } },
                                    y1: { position: 'right', grid: { display: false } }
                                },
                                plugins: { legend: { position: 'top', align: 'end' } }
                            }
                        });
                    }

                    var usageCanvas = document.getElementById('usageChart');
                    if (usageCanvas && window.Chart && !usageCanvas.dataset.rendered) {
                        usageCanvas.dataset.rendered = '1';
                        var t = window.StadiumTheme;
                        new Chart(usageCanvas, {
                            type: 'doughnut',
                            data: {
                                labels: JSON.parse(usageCanvas.dataset.labels),
                                datasets: [{ data: JSON.parse(usageCanvas.dataset.values), backgroundColor: [t.pitch700, t.flood400, t.pitch500, t.line], borderWidth: 0 }]
                            },
                            options: { cutout: '70%', plugins: { legend: { display: false } } }
                        });
                    }
                }

                document.addEventListener('DOMContentLoaded', boot);
                document.addEventListener('livewire:navigated', boot);
                boot();
            })();
        </script>
    @endpush
</div>
