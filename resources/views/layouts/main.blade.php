<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@hasSection('title')@yield('title')@else{{ $title ?? 'Aliu Mahama Sports Stadium' }}@endif</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @livewireStyles
</head>
<body>

<nav class="navbar navbar-expand-lg public-nav py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <span class="icon-tile" style="width:38px;height:38px;font-size:1rem;"><i class="fa-solid fa-futbol"></i></span>
            Aliu Mahama <span class="text-flood">Stadium</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <i class="bi bi-list fs-3"></i>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('events') ? 'active' : '' }}" href="{{ route('events') }}">Fixtures &amp; Events</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('booking') ? 'active' : '' }}" href="{{ route('booking') }}">Book a Facility</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">About &amp; Contact</a></li>
            </ul>
            <div class="d-flex gap-2">
                <a href="{{ route('login') }}" class="btn btn-outline-pitch btn-sm rounded-3 px-3">Staff Login</a>
                <a href="{{ route('booking') }}" class="btn btn-flood btn-sm rounded-3 px-3">Book Now</a>
            </div>
        </div>
    </div>
</nav>

@hasSection('content')
    @yield('content')
@else
    {{ $slot ?? '' }}
@endif

<footer class="public-footer py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h6 class="fw-bold mb-2" style="font-family:'Space Grotesk',sans-serif;">Aliu Mahama Stadium</h6>
                <p class="small mb-0">Tamale, Northern Region, Ghana. Home of Real Tamale United and the region's premier sporting venue.</p>
            </div>
            <div class="col-lg-2 col-6">
                <h6 class="small fw-bold text-uppercase mb-3">Explore</h6>
                <ul class="list-unstyled small d-grid gap-2">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('events') }}">Fixtures</a></li>
                    <li><a href="{{ route('booking') }}">Bookings</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-6">
                <h6 class="small fw-bold text-uppercase mb-3">Admin</h6>
                <ul class="list-unstyled small d-grid gap-2">
                    <li><a href="{{ route('admin') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.bookings') }}">Bookings</a></li>
                    <li><a href="{{ route('admin.reports') }}">Reports</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6 class="small fw-bold text-uppercase mb-3">Contact</h6>
                <p class="small mb-1"><i class="bi bi-geo-alt me-2"></i>Stadium Road, Tamale</p>
                <p class="small mb-1"><i class="bi bi-telephone me-2"></i>+233 20 000 0000</p>
                <p class="small mb-0"><i class="bi bi-envelope me-2"></i>info@aliumahamastadium.gh</p>
            </div>
        </div>
        <hr class="border-secondary my-4">
        <div class="d-flex flex-wrap justify-content-between small">
            <span>&copy; {{ now()->year }} Aliu Mahama Sports Stadium. All rights reserved.</span>
            <span>Built for the community of the Northern Region.</span>
        </div>
    </div>
</footer>

@livewireScripts
@stack('scripts')
</body>
</html>
