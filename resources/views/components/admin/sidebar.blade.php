@php
    $pendingBookings = \Illuminate\Support\Facades\Schema::hasTable('bookings')
        ? \App\Models\Booking::where('status', 'pending')->count()
        : 0;

    $navSections = [
        'MAIN' => [
            ['route' => 'admin', 'icon' => 'bi-speedometer2', 'label' => 'Dashboard'],
            ['route' => 'admin.bookings', 'icon' => 'bi-calendar-check', 'label' => 'Bookings', 'badge' => $pendingBookings],
            ['route' => 'admin.events', 'icon' => 'bi-flag', 'label' => 'Events & Fixtures'],
        ],
        'FACILITIES' => [
            ['route' => 'admin.facilities', 'icon' => 'bi-building', 'label' => 'Facilities'],
            ['route' => 'admin.teams', 'icon' => 'bi-people', 'label' => 'Teams & Players'],
            ['route' => 'admin.staff', 'icon' => 'bi-person-badge', 'label' => 'Staff & Roster'],
        ],
        'INSIGHTS' => [
            ['route' => 'admin.reports', 'icon' => 'bi-bar-chart-line', 'label' => 'Reports & Analytics'],
            ['route' => 'admin.settings', 'icon' => 'bi-gear', 'label' => 'Settings'],
        ],
    ];

    $user = auth()->user();
    $displayName = $user->name ?? 'Yakubu Alhassan';
    $displayRole = $user->role ?? 'Facilities Manager';
    $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($displayName) . '&background=FFC72C&color=063D2C&bold=true';
@endphp
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>
<aside class="sidebar" id="appSidebar">
    <div class="sidebar-brand">
        <div class="mark">AM</div>
        <span class="txt">Aliu Mahama <span style="color:var(--flood-400)">Admin</span></span>
    </div>
    <nav class="sidebar-nav">
        @foreach ($navSections as $section => $items)
            <div class="nav-section-title">{{ $section }}</div>
            @foreach ($items as $item)
                <a href="{{ route($item['route']) }}" class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                    <i class="bi {{ $item['icon'] }}"></i>
                    <span class="nav-label">{{ $item['label'] }}</span>
                    @if (!empty($item['badge']))
                        <span class="badge badge-count rounded-pill">{{ $item['badge'] }}</span>
                    @endif
                </a>
            @endforeach
        @endforeach
    </nav>
    <div class="sidebar-user">
        <img src="{{ $avatarUrl }}" alt="">
        <div class="meta">
            <div class="name">{{ $displayName }}</div>
            <div class="role">{{ $displayRole }}</div>
        </div>
    </div>
</aside>
