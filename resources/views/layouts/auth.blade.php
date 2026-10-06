<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Authentication' }} - Aliu Mahama Sports Stadium</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @livewireStyles
</head>
<body>

<div class="auth-shell">
    <div class="auth-brand d-flex">
        <div class="d-flex align-items-center gap-2">
            <div class="mark">AM</div>
            <span class="fw-bold" style="font-family:'Space Grotesk',sans-serif;">Aliu Mahama <span style="color:var(--flood-400)">Stadium</span></span>
        </div>
        <div>
            <span class="eyebrow"><span class="dot"></span> {{ $eyebrow ?? 'Staff & Admin Portal' }}</span>
            <h2 class="fw-bold text-white mt-3 mb-3" style="font-family:'Space Grotesk',sans-serif;">{{ $heading ?? 'Run matchday from one dashboard.' }}</h2>
            <p class="mb-0" style="color:rgba(255,255,255,.75);">{{ $tagline ?? 'Bookings, fixtures, facilities and staff rosters, sign in to manage the stadium.' }}</p>
        </div>
        <div class="d-flex gap-2">
            @foreach (($steps ?? [true, false, false]) as $dotActive)
                <span class="auth-step-dot {{ $dotActive ? 'active' : '' }}"></span>
            @endforeach
        </div>
        <div class="pitch-lines"></div>
    </div>

    <div class="auth-form-side">
        <div class="auth-card">
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot ?? '' }}
            @endif
        </div>
    </div>
</div>

@livewireScripts
@stack('scripts')
</body>
</html>
