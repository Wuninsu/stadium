<div>
    <div class="page-header d-flex flex-wrap justify-content-between align-items-end gap-3">
        <div>
            <div class="crumb mb-1">Admin / Facilities</div>
            <h3 class="fw-bold mb-0">Facilities</h3>
            <p class="text-secondary mb-0 small">Monitor condition, maintenance and availability of every bookable space.</p>
        </div>
        <button type="button" class="btn btn-flood rounded-3"><i class="bi bi-plus-lg me-1"></i>Add Facility</button>
    </div>

    <div class="row g-3">
        @foreach ($facilities as $facility)
            <div class="col-md-6 col-xl-3">
                <div class="panel h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-tile"><i class="{{ $facility->icon }}"></i></div>
                        <span class="badge {{ $facility->status_badge_class }}">{{ ucfirst($facility->status) }}</span>
                    </div>
                    <h6 class="fw-bold mb-1">{{ $facility->name }}</h6>
                    <p class="text-secondary small mb-3">{{ $facility->description }}</p>
                    <div class="d-flex justify-content-between small mb-1"><span>Utilisation</span><span class="fw-semibold">{{ $facility->utilisation_percent }}%</span></div>
                    <div class="progress mb-3" style="height:6px;"><div class="progress-bar {{ $facility->utilisation_bar_class }}" style="width:{{ $facility->utilisation_percent }}%"></div></div>
                    <div class="d-flex justify-content-between small text-secondary">
                        @if ($facility->status === 'maintenance')
                            <span>Servicing</span><span>Until {{ $facility->next_maintenance_at->format('M d') }}</span>
                        @else
                            <span>Next maintenance</span><span>{{ $facility->next_maintenance_at->format('M d') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="panel mt-3">
        <h6 class="panel-title mb-3">Maintenance log</h6>
        <div class="table-responsive">
            <table class="table table-modern">
                <thead><tr><th>Facility</th><th>Task</th><th>Assigned to</th><th>Scheduled</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td class="fw-semibold">{{ $log->facility->name }}</td>
                            <td>{{ $log->task }}</td>
                            <td>{{ $log->assigned_to }}</td>
                            <td>{{ $log->scheduled_at->format('M d') }}</td>
                            <td><span class="badge {{ $log->status_badge_class }}">{{ $log->status_label }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-4">No maintenance scheduled.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
