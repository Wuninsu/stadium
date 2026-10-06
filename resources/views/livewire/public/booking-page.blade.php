<div>
    <header class="hero py-5">
        <div class="container position-relative py-4" style="z-index:2;">
            <span class="eyebrow"><span class="dot"></span> Reservations</span>
            <h1 class="fw-bold text-white mt-3 mb-2" style="font-family:'Space Grotesk',sans-serif;">Book a Facility</h1>
            <p class="mb-0" style="color:rgba(255,255,255,.8);">Reserve the pitch, track, training annex or conference suite, confirmed within 24 hours.</p>
        </div>
        <div class="pitch-lines"></div>
    </header>

    <section class="py-5 bg-white">
        <div class="container">
            <div class="row g-5">

                <div class="col-lg-7">
                    <div class="panel">
                        <h5 class="panel-title mb-4">Reservation details</h5>
                        <form wire:submit="submit">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Facility</label>
                                <select class="form-select @error('facility_id') is-invalid @enderror" wire:model="facility_id">
                                    <option value="">Select a facility…</option>
                                    @foreach ($facilities as $facility)
                                        <option value="{{ $facility->id }}">{{ $facility->name }} &mdash; {{ $facility->description }}</option>
                                    @endforeach
                                </select>
                                @error('facility_id') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Date</label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror" wire:model="date">
                                    @error('date') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small">Start time</label>
                                    <input type="time" class="form-control" wire:model="start_time">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small">End time</label>
                                    <input type="time" class="form-control @error('end_time') is-invalid @enderror" wire:model="end_time">
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Purpose</label>
                                    <select class="form-select" wire:model="purpose">
                                        <option value="league_fixture">League fixture</option>
                                        <option value="training">Team training</option>
                                        <option value="community_event">School / community event</option>
                                        <option value="private_function">Private function</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Expected attendance</label>
                                    <input type="number" class="form-control" wire:model="expected_attendance" placeholder="e.g. 500">
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Contact name</label>
                                    <input type="text" class="form-control @error('contact_name') is-invalid @enderror" wire:model="contact_name" placeholder="Full name">
                                    @error('contact_name') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Organisation</label>
                                    <input type="text" class="form-control" wire:model="organisation" placeholder="Club / school / company">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Email</label>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror" wire:model="contact_email" placeholder="you@email.com">
                                    @error('contact_email') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Phone</label>
                                    <input type="tel" class="form-control" wire:model="contact_phone" placeholder="+233 ...">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold small">Additional notes</label>
                                    <textarea class="form-control" rows="3" wire:model="notes" placeholder="Any special requirements..."></textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-flood btn-lg rounded-3 w-100" wire:loading.attr="disabled">
                                <i class="bi bi-calendar-check me-2"></i>Submit Reservation Request
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="panel mb-4">
                        <h5 class="panel-title mb-3">This week's availability</h5>
                        <div class="d-flex flex-column gap-3">
                            @foreach ($facilities as $facility)
                                @php
                                    $pct = $facility->status === 'maintenance' ? 100 : $facility->utilisation_percent;
                                    $badgeClass = $facility->status === 'maintenance' ? 'badge-soft-red' : ($pct >= 90 ? 'badge-soft-red' : ($pct >= 70 ? 'badge-soft-yellow' : 'badge-soft-green'));
                                    $badgeLabel = $facility->status === 'maintenance' ? 'Booked out' : ($pct >= 90 ? 'Booked out' : ($pct >= 70 ? '2 slots left' : 'Open'));
                                    $barColor = $facility->status === 'maintenance' || $pct >= 90 ? 'background:var(--danger);' : '';
                                    $barClass = $facility->status === 'maintenance' || $pct >= 90 ? '' : ($pct >= 70 ? 'progress-bar-flood' : 'progress-bar-pitch');
                                @endphp
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small fw-semibold">{{ $facility->name }}</span>
                                    <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                                </div>
                                <div class="progress" role="progressbar" style="height:8px;">
                                    <div class="progress-bar {{ $barClass }}" style="width:{{ $pct }}%;{{ $barColor }}"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="panel" style="background:var(--pitch-900);border-color:var(--pitch-900);">
                        <h6 class="fw-bold text-white mb-2"><i class="bi bi-info-circle me-2 text-flood"></i>Booking policy</h6>
                        <ul class="small mb-0" style="color:rgba(255,255,255,.75);padding-left:1.1rem;">
                            <li class="mb-2">Requests are reviewed within 24 hours by the facilities team.</li>
                            <li class="mb-2">League fixtures take scheduling priority over private events.</li>
                            <li class="mb-0">A confirmation and invoice will be sent to the email provided.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-body text-center p-5">
                    <div class="icon-tile mx-auto mb-3" style="width:64px;height:64px;font-size:1.6rem;">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Request received</h5>
                    <p class="text-secondary mb-4">Your reservation request has been sent to the facilities team. You'll receive a confirmation email once it's reviewed.</p>
                    <button type="button" class="btn btn-pitch rounded-3 px-4" data-bs-dismiss="modal">Done</button>
                </div>
            </div>
        </div>
    </div>
</div>
