@props(['searchPlaceholder' => 'Search bookings, teams, events…'])
<header class="topbar">
    <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>
    <div class="search-box input-group d-none d-md-flex">
        <span class="input-group-text bg-transparent border-0 pe-0"><i class="bi bi-search text-secondary"></i></span>
        <input type="text" class="form-control" placeholder="{{ $searchPlaceholder }}">
    </div>
    <div class="ms-auto d-flex align-items-center gap-2">
        <button class="icon-btn"><i class="bi bi-bell"></i><span class="ping"></span></button>
        <button class="icon-btn"><i class="bi bi-envelope"></i></button>
        <div class="vr mx-1 d-none d-sm-block"></div>
        <a href="{{ route('home') }}" class="btn btn-outline-pitch btn-sm rounded-3 d-none d-sm-inline-flex align-items-center gap-1">
            <i class="bi bi-box-arrow-up-right"></i> View site
        </a>
        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Yakubu Alhassan') }}&background=0B6E4F&color=fff&bold=true" class="row-avatar d-sm-none" alt="">
    </div>
</header>
