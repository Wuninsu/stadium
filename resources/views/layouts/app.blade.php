<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Portal' }} - Aliu Mahama Sports Stadium Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @livewireStyles
</head>
<body>
<div class="admin-shell">
    <x-admin.sidebar />

    <div class="main-content">
        <x-admin.topbar :search-placeholder="$searchPlaceholder ?? 'Search bookings, teams, events…'" />

        <div class="page-body">
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
