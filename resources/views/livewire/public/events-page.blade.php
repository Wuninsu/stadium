<div>
    <header class="hero py-5">
        <div class="container position-relative py-4" style="z-index:2;">
            <span class="eyebrow"><span class="dot"></span> Calendar</span>
            <h1 class="fw-bold text-white mt-3 mb-2" style="font-family:'Space Grotesk',sans-serif;">Fixtures &amp; Events</h1>
            <p class="mb-0" style="color:rgba(255,255,255,.8);">Every match, meet and public event at the stadium this season.</p>
        </div>
        <div class="pitch-lines"></div>
    </header>

    <section class="py-5 bg-white">
        <div class="container">

            <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                <ul class="nav nav-pills-soft flex-wrap gap-1">
                    @foreach (['All', 'Football', 'Athletics', 'Community'] as $option)
                        <li class="nav-item">
                            <button type="button" wire:click="setFilter('{{ $option }}')" class="nav-link {{ $filter === $option ? 'active' : '' }}">{{ $option }}</button>
                        </li>
                    @endforeach
                </ul>
                <div class="ms-auto d-flex gap-2">
                    <div class="input-group" style="max-width:260px;">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" wire:model.live.debounce.400ms="search" class="form-control border-start-0" placeholder="Search fixtures…">
                    </div>
                </div>
            </div>

            <div class="row g-4">
                @forelse ($events as $event)
                    <div class="col-lg-6">
                        <div class="event-card h-100">
                            <div class="p-3 d-flex gap-3">
                                <div class="event-date"><div class="d">{{ $event->date->format('d') }}</div><div class="m">{{ $event->date->format('M') }}</div></div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <span class="badge badge-status-{{ $event->public_status }} mb-2">{{ $event->public_status_label }}</span>
                                        <span class="badge badge-soft-grey"><i class="bi bi-people me-1"></i>{{ number_format($event->tickets_sold) }} going</span>
                                    </div>
                                    <h5 class="fw-bold mb-1">{{ $event->title }}</h5>
                                    <p class="text-secondary small mb-3">{{ $event->category_label }} &middot; {{ $event->facility->name }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-secondary"><i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} &ndash; {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}</small>
                                        @if ($event->public_status === 'full')
                                            <a href="#" class="btn btn-sm btn-outline-pitch rounded-3 disabled">Waitlist</a>
                                        @else
                                            <a href="#" class="btn btn-sm btn-pitch rounded-3">Get tickets</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-secondary py-5">No fixtures match that search right now.</div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $events->links() }}
            </div>
        </div>
    </section>
</div>
