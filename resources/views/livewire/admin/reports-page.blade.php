<div>
    @php
        $purposeLabel = fn ($key) => match ($key) {
            'league_fixture' => 'League fixtures',
            'training' => 'Training',
            'community_event' => 'Community events',
            'private_function' => 'Private functions',
            default => ucfirst(str_replace('_', ' ', $key)),
        };
    @endphp

    <div class="page-header d-flex flex-wrap justify-content-between align-items-end gap-3">
        <div>
            <div class="crumb mb-1">Admin / Reports &amp; Analytics</div>
            <h3 class="fw-bold mb-0">Reports &amp; Analytics</h3>
            <p class="text-secondary mb-0 small">Performance across revenue, attendance and facility utilisation.</p>
        </div>
        <div class="d-flex gap-2">
            <select class="form-select w-auto"><option>Last 6 months</option><option>This year</option><option>Last year</option></select>
            <button type="button" class="btn btn-outline-pitch rounded-3"><i class="bi bi-download me-1"></i>Export PDF</button>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card"><div><div class="stat-value">GH&#8373;{{ number_format($totalRevenue / 1000, 1) }}k</div><div class="stat-label">Total revenue</div></div><div class="stat-icon" style="background:#FFF3D6;color:var(--flood-600);"><i class="bi bi-cash-stack"></i></div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card"><div><div class="stat-value">{{ number_format($totalAttendance) }}</div><div class="stat-label">Total attendance</div></div><div class="stat-icon" style="background:var(--pitch-100);color:var(--pitch-700);"><i class="bi bi-people-fill"></i></div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card"><div><div class="stat-value">{{ $eventsHosted }}</div><div class="stat-label">Events hosted</div></div><div class="stat-icon" style="background:var(--pitch-100);color:var(--pitch-700);"><i class="bi bi-calendar2-week"></i></div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card"><div><div class="stat-value">GH&#8373;{{ number_format($avgBookingValue) }}</div><div class="stat-label">Avg. booking value</div></div><div class="stat-icon" style="background:#FFF3D6;color:var(--flood-600);"><i class="bi bi-graph-up"></i></div></div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-8">
            <div class="panel h-100">
                <h6 class="panel-title mb-3">Revenue by facility</h6>
                <div wire:ignore>
                    <canvas id="revByFacility" height="110"
                        data-labels="{{ $monthLabels->toJson() }}"
                        data-datasets="{{ $facilities->values()->map(fn ($f, $i) => ['label' => $f->name, 'data' => $revenueByFacility[$i]])->toJson() }}"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel h-100">
                <h6 class="panel-title mb-3">Booking purpose split</h6>
                <div wire:ignore>
                    <canvas id="purposeChart" height="180"
                        data-labels="{{ $purposeSplit->keys()->map($purposeLabel)->toJson() }}"
                        data-values="{{ $purposeSplit->values()->toJson() }}"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="panel h-100">
                <h6 class="panel-title mb-3">Attendance trend</h6>
                <div wire:ignore>
                    <canvas id="attendanceChart" height="140"
                        data-labels="{{ $monthLabels->toJson() }}"
                        data-values="{{ $attendanceTrend->toJson() }}"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="panel h-100">
                <h6 class="panel-title mb-3">Top performing events</h6>
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead><tr><th>Event</th><th>Attendance</th><th>Revenue</th></tr></thead>
                        <tbody>
                            @forelse ($topEvents as $event)
                                <tr>
                                    <td class="fw-semibold">{{ $event->title }}</td>
                                    <td>{{ number_format($event->tickets_sold) }}</td>
                                    <td class="font-mono">GH&#8373;{{ number_format($event->estimated_revenue / 1000, 0) }}k</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-secondary py-4">No events recorded yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                function boot() {
                    var t = window.StadiumTheme;
                    var palette = [t.pitch700, t.flood400, t.pitch500, t.line];

                    var revCanvas = document.getElementById('revByFacility');
                    if (revCanvas && window.Chart && !revCanvas.dataset.rendered) {
                        revCanvas.dataset.rendered = '1';
                        var datasets = JSON.parse(revCanvas.dataset.datasets).map(function (d, i) {
                            return Object.assign(d, { backgroundColor: palette[i % palette.length], stack: 's' });
                        });
                        new Chart(revCanvas, {
                            type: 'bar',
                            data: { labels: JSON.parse(revCanvas.dataset.labels), datasets: datasets },
                            options: { responsive: true, scales: { x: { stacked: true, grid: { display: false } }, y: { stacked: true, grid: { color: t.line } } }, plugins: { legend: { position: 'top', align: 'end' } } }
                        });
                    }

                    var purposeCanvas = document.getElementById('purposeChart');
                    if (purposeCanvas && window.Chart && !purposeCanvas.dataset.rendered) {
                        purposeCanvas.dataset.rendered = '1';
                        new Chart(purposeCanvas, {
                            type: 'pie',
                            data: { labels: JSON.parse(purposeCanvas.dataset.labels), datasets: [{ data: JSON.parse(purposeCanvas.dataset.values), backgroundColor: palette, borderWidth: 0 }] },
                            options: { plugins: { legend: { position: 'bottom' } } }
                        });
                    }

                    var attCanvas = document.getElementById('attendanceChart');
                    if (attCanvas && window.Chart && !attCanvas.dataset.rendered) {
                        attCanvas.dataset.rendered = '1';
                        new Chart(attCanvas, {
                            type: 'line',
                            data: { labels: JSON.parse(attCanvas.dataset.labels), datasets: [{ label: 'Attendance', data: JSON.parse(attCanvas.dataset.values), borderColor: t.pitch700, backgroundColor: 'rgba(11,110,79,.12)', fill: true, tension: .4 }] },
                            options: { plugins: { legend: { display: false } }, scales: { y: { grid: { color: t.line } } } }
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
