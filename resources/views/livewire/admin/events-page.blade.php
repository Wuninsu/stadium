<div>
    @php
        $typeShortLabel = fn ($type) => match ($type) {
            'league_fixture' => 'Match',
            'athletics' => 'Athletics',
            'tournament' => 'Final',
            'youth' => 'Relay',
            default => 'Event',
        };
        $dayBadgeClass = fn ($event) => $event->type === 'tournament' ? 'badge-soft-red' : ($event->status === 'confirmed' ? 'badge-soft-green' : 'badge-soft-yellow');
    @endphp

    <div class="page-header d-flex flex-wrap justify-content-between align-items-end gap-3">
        <div>
            <div class="crumb mb-1">Admin / Events &amp; Fixtures</div>
            <h3 class="fw-bold mb-0">Events &amp; Fixtures</h3>
            <p class="text-secondary mb-0 small">Schedule matches, tournaments and public events across all facilities.</p>
        </div>
        <button type="button" class="btn btn-flood rounded-3" data-bs-toggle="modal" data-bs-target="#newEventModal"><i class="bi bi-plus-lg me-1"></i>Schedule Event</button>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="panel mb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="panel-title mb-0">{{ $monthLabel }}</h6>
                    <div class="btn-group">
                        <button type="button" wire:click="prevMonth" class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-left"></i></button>
                        <button type="button" wire:click="nextMonth" class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-right"></i></button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-modern text-center">
                        <thead><tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr></thead>
                        <tbody>
                            @foreach ($weeks as $week)
                                <tr>
                                    @foreach ($week as $day)
                                        <td class="{{ $day['inMonth'] ? '' : 'text-secondary' }}">
                                            <div class="fw-semibold">{{ $day['date']->day }}</div>
                                            @foreach ($day['events'] as $event)
                                                <span class="badge {{ $dayBadgeClass($event) }}" style="font-size:.62rem;">{{ $typeShortLabel($event->type) }}</span>
                                            @endforeach
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel">
                <h6 class="panel-title mb-3">All scheduled events</h6>
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead><tr><th>Event</th><th>Type</th><th>Facility</th><th>Date</th><th>Capacity</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse ($events as $event)
                                <tr>
                                    <td class="fw-semibold">{{ $event->title }}</td>
                                    <td>{{ $event->category_label }}</td>
                                    <td>{{ $event->facility->name }}</td>
                                    <td>{{ $event->date->format('M d') }}</td>
                                    <td>{{ $event->capacity ? number_format($event->capacity) : '—' }}</td>
                                    <td><span class="badge {{ $dayBadgeClass($event) }}">{{ $event->status === 'confirmed' ? 'Confirmed' : 'Prep' }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-secondary py-4">No events scheduled.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="panel mb-3">
                <h6 class="panel-title mb-3">Next fixture countdown</h6>
                @if ($nextEvent)
                    @php
                        $diff = now()->diff(\Carbon\Carbon::parse($nextEvent->date->format('Y-m-d') . ' ' . $nextEvent->start_time));
                        $salesPct = $nextEvent->capacity ? round(($nextEvent->tickets_sold / $nextEvent->capacity) * 100) : 0;
                    @endphp
                    <div class="text-center py-3">
                        <div class="font-mono fw-bold" style="font-size:2.4rem;color:var(--pitch-700);">{{ sprintf('%02d:%02d:%02d', $diff->days * 24 + $diff->h, $diff->i, $diff->s) }}</div>
                        <div class="text-secondary small">until {{ $nextEvent->title }}</div>
                    </div>
                    <div class="progress mb-2" style="height:6px;"><div class="progress-bar progress-bar-flood" style="width:{{ $salesPct }}%"></div></div>
                    <div class="d-flex justify-content-between small text-secondary">
                        <span>Ticket sales: {{ $salesPct }}%</span><span>{{ number_format($nextEvent->tickets_sold) }} / {{ number_format($nextEvent->capacity) }}</span>
                    </div>
                @else
                    <p class="text-secondary small mb-0">No upcoming fixtures scheduled.</p>
                @endif
            </div>
            @if ($nextEvent)
                <div class="panel">
                    <h6 class="panel-title mb-3">Event checklist &mdash; {{ $nextEvent->date->format('M d') }}</h6>
                    <div class="d-flex flex-column gap-2">
                        @foreach ($checklistItems as $item)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" wire:click="toggleChecklistItem({{ $item->id }})" @checked($item->is_done)>
                                <label class="form-check-label small">{{ $item->label }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="newEventModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <form wire:submit="scheduleEvent">
                    <div class="modal-header border-0"><h5 class="modal-title fw-bold">Schedule a new event</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Event name</label>
                            <input class="form-control @error('new_title') is-invalid @enderror" wire:model="new_title" placeholder="e.g. RTU vs Hearts of Oak">
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Facility</label>
                                <select class="form-select" wire:model="new_facility_id">
                                    <option value="">Select…</option>
                                    @foreach ($facilities as $facility)
                                        <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Date</label>
                                <input type="date" class="form-control @error('new_date') is-invalid @enderror" wire:model="new_date">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Expected attendance</label>
                            <input type="number" class="form-control" wire:model="new_expected_attendance" placeholder="e.g. 20000">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-pitch rounded-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-flood rounded-3" wire:loading.attr="disabled">Schedule Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
