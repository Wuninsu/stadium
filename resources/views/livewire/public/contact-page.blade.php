<div>
    <header class="hero py-5">
        <div class="container position-relative py-4" style="z-index:2;">
            <span class="eyebrow"><span class="dot"></span> Since 2004</span>
            <h1 class="fw-bold text-white mt-3 mb-2" style="font-family:'Space Grotesk',sans-serif;">About the Stadium</h1>
            <p class="mb-0" style="color:rgba(255,255,255,.8);">Named after the late Vice President Alhaji Aliu Mahama, a landmark of sport in the Northern Region.</p>
        </div>
        <div class="pitch-lines"></div>
    </header>

    <section class="py-5 bg-white">
        <div class="container">
            <div class="row g-3 g-lg-5 align-items-center mb-5">
                <div class="col-lg-6">
                    <div class="section-title mb-2">Our story</div>
                    <h2 class="fw-bold mb-3">A home ground for the Northern Region</h2>
                    <p class="text-secondary">The stadium serves as home ground for Real Tamale United and hosts Ghana Premier League fixtures,
                        regional athletics championships and community tournaments year-round. Facilities include a FIFA-standard main pitch,
                        an 8-lane synthetic athletics track, an indoor training annex and a 300-seat conference suite.</p>
                    <p class="text-secondary mb-0">This platform lets visitors browse fixtures and reserve facilities, while our operations
                        team manages bookings, maintenance, staffing and matchday logistics from a single dashboard.</p>
                </div>
                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="card-feature p-4 text-center">
                                <div class="stat-value font-mono">22,600</div>
                                <div class="stat-label">Seating capacity</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card-feature p-4 text-center">
                                <div class="stat-value font-mono">2004</div>
                                <div class="stat-label">Year opened</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card-feature p-4 text-center">
                                <div class="stat-value font-mono">{{ $facilityCount }}</div>
                                <div class="stat-label">Bookable facilities</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card-feature p-4 text-center">
                                <div class="stat-value font-mono">48+</div>
                                <div class="stat-label">Events hosted / year</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5" style="background:var(--chalk-100);">
        <div class="container">
            <div class="row g-3 g-lg-5">
                <div class="col-lg-6">
                    <div class="section-title mb-2">Get in touch</div>
                    <h2 class="fw-bold mb-4">Contact the office</h2>
                    <div class="panel">
                        @if ($sent)
                            <div class="text-center py-4">
                                <div class="icon-tile mx-auto mb-3" style="width:56px;height:56px;font-size:1.35rem;"><i class="bi bi-check-lg"></i></div>
                                <h5 class="fw-bold mb-2">Message sent</h5>
                                <p class="text-secondary mb-3">Thanks for reaching out, the office will get back to you shortly.</p>
                                <button type="button" class="btn btn-outline-pitch rounded-3 px-4" wire:click="$set('sent', false)">Send another message</button>
                            </div>
                        @else
                            <form class="row g-3" wire:submit="send">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name" placeholder="Your name">
                                    @error('name') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model="email" placeholder="you@email.com">
                                    @error('email') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold small">Subject</label>
                                    <select class="form-select" wire:model="subject">
                                        <option value="general">General enquiry</option>
                                        <option value="media">Media / press</option>
                                        <option value="sponsorship">Sponsorship</option>
                                        <option value="booking_support">Facility booking support</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold small">Message</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" rows="4" wire:model="message" placeholder="How can we help?"></textarea>
                                    @error('message') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-pitch rounded-3 px-4" wire:loading.attr="disabled">Send Message</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="section-title mb-2">Visit us</div>
                    <h2 class="fw-bold mb-4">Find the stadium</h2>
                    <div class="panel mb-3">
                        <div class="d-flex gap-3 mb-3">
                            <div class="icon-tile"><i class="bi bi-geo-alt"></i></div>
                            <div>
                                <div class="fw-semibold">Address</div>
                                <div class="text-secondary small">Stadium Road, Tamale, Northern Region, Ghana</div>
                            </div>
                        </div>
                        <div class="d-flex gap-3 mb-3">
                            <div class="icon-tile"><i class="bi bi-telephone"></i></div>
                            <div>
                                <div class="fw-semibold">Phone</div>
                                <div class="text-secondary small">+233 20 000 0000</div>
                            </div>
                        </div>
                        <div class="d-flex gap-3 mb-3">
                            <div class="icon-tile"><i class="bi bi-envelope"></i></div>
                            <div>
                                <div class="fw-semibold">Email</div>
                                <div class="text-secondary small">info@aliumahamastadium.gh</div>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="icon-tile"><i class="bi bi-clock"></i></div>
                            <div>
                                <div class="fw-semibold">Office hours</div>
                                <div class="text-secondary small">Mon - Sat, 8:00 AM - 6:00 PM</div>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="height:180px;background:var(--pitch-900);">
                        <span class="text-white-50 small"><i class="bi bi-map me-2"></i>Map preview</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
