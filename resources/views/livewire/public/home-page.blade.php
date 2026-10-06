<div>
    <header class="hero">
        <div class="container position-relative py-5 py-lg-6" style="z-index:2;">
            <div class="row align-items-center py-4">
                <div class="col-lg-6">
                    <span class="eyebrow"><span class="dot"></span> Tamale, Northern Region - Ghana</span>
                    <h1 class="display-4 fw-bold text-white mt-3 mb-3" style="font-family:'Space Grotesk',sans-serif;">
                        Where the Northern Region <span class="text-flood">comes to compete.</span>
                    </h1>
                    <p class="fs-5 mb-4" style="color:rgba(255,255,255,.8);">
                        A {{ number_format($seatingCapacity) }}-seat capacity stadium hosting Ghana Premier League fixtures, regional athletics,
                        concerts and community sport. Book pitches, track fixtures and manage events in one place.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('booking') }}" class="btn btn-flood btn-lg rounded-3 px-4"><i class="bi bi-calendar-check me-2"></i>Reserve a Facility</a>
                        <a href="{{ route('events') }}" class="btn btn-outline-light btn-lg rounded-3 px-4"><i class="bi bi-play-circle me-2"></i>View Fixtures</a>
                    </div>
                </div>

                <div class="col-lg-6 mt-5 mt-lg-0">
                    <div class="scoreboard">
                        <div class="d-flex align-items-center justify-content-between px-2 pb-3 mb-2" style="border-bottom:1px dashed rgba(255,255,255,.15);">
                            <span class="font-mono text-white-50 small">MATCH DAY STATUS</span>
                            <span class="badge bg-flood text-dark fw-bold"><i class="bi bi-broadcast me-1"></i>LIVE FEED</span>
                        </div>
                        <div class="row g-0">
                            <div class="col-4 seg">
                                <div class="digits" data-count="{{ $seatingCapacity }}">0</div>
                                <div class="cap">Seating capacity</div>
                            </div>
                            <div class="col-4 seg">
                                <div class="digits" data-count="48">0</div>
                                <div class="cap">Fixtures / year</div>
                            </div>
                            <div class="col-4 seg">
                                <div class="digits" data-count="96" data-suffix="%">0</div>
                                <div class="cap">Pitch availability</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pitch-lines"></div>
    </header>

    <section class="py-5 py-lg-6 bg-white">
        <div class="container py-4">
            <div class="row mb-5">
                <div class="col-lg-7">
                    <div class="section-title mb-2">What we manage</div>
                    <h2 class="fw-bold">One stadium, every fixture, fully booked out.</h2>
                </div>
            </div>
            <div class="row g-4">
                @foreach ($facilities as $facility)
                    <div class="col-md-6 col-lg-3">
                        <div class="card-feature p-4 h-100">
                            <div class="icon-tile mb-3"><i class="{{ $facility->icon }}"></i></div>
                            <h6 class="fw-bold">{{ $facility->name }}</h6>
                            <p class="text-secondary small mb-0">{{ $facility->description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-5 py-lg-6" style="background:var(--chalk-100);">
        <div class="container py-4">
            <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
                <div>
                    <div class="section-title mb-2">This month</div>
                    <h2 class="fw-bold mb-0">Upcoming fixtures &amp; events</h2>
                </div>
                <a href="{{ route('events') }}" class="btn btn-outline-pitch rounded-3">See full calendar <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
            <div class="row g-4">
                @foreach ($upcomingEvents as $event)
                    <div class="col-lg-4">
                        <div class="event-card">
                            <div class="d-flex align-items-center gap-3 p-3">
                                <div class="event-date"><div class="d">{{ $event->date->format('d') }}</div><div class="m">{{ $event->date->format('M') }}</div></div>
                                <div>
                                    <span class="badge badge-status-{{ $event->public_status }} mb-1">{{ $event->public_status_label }}</span>
                                    <h6 class="fw-bold mb-0">{{ $event->title }}</h6>
                                    <small class="text-secondary">{{ $event->category_label }} &middot; {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="rounded-4 p-5 d-flex flex-wrap align-items-center justify-content-between gap-4" style="background:var(--pitch-700);">
                <div>
                    <h3 class="fw-bold text-white mb-1">Planning a match, training session or event?</h3>
                    <p class="mb-0" style="color:rgba(255,255,255,.8);">Check live availability and reserve the pitch, track or conference suite in minutes.</p>
                </div>
                <a href="{{ route('booking') }}" class="btn btn-flood btn-lg rounded-3 px-4 flex-shrink-0">Start a Booking</a>
            </div>
        </div>
    </section>
</div>
