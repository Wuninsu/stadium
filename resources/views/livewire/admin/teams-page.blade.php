<div>
    <div class="page-header d-flex flex-wrap justify-content-between align-items-end gap-3">
        <div>
            <div class="crumb mb-1">Admin / Teams &amp; Players</div>
            <h3 class="fw-bold mb-0">Teams &amp; Players</h3>
            <p class="text-secondary mb-0 small">Manage resident clubs, academy squads and player rosters.</p>
        </div>
        <button type="button" class="btn btn-flood rounded-3"><i class="bi bi-plus-lg me-1"></i>Add Team</button>
    </div>

    <div class="row g-3 mb-3">
        @foreach ($teams as $team)
            <div class="col-md-6 col-xl-4">
                <div class="panel h-100 d-flex align-items-center gap-3" role="button" wire:click="selectTeam({{ $team->id }})" style="cursor:pointer;{{ $selectedTeam && $selectedTeam->id === $team->id ? 'border-color:var(--pitch-500);' : '' }}">
                    <img src="{{ $team->logo_url }}" class="rounded-3" width="64" height="64" alt="">
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-0">{{ $team->name }}</h6>
                        <span class="text-secondary small">{{ $team->category }} &middot; {{ $team->players_count }} {{ $team->roster_noun }}</span>
                    </div>
                    <span class="badge {{ $team->type_badge_class }}">{{ ucfirst($team->type) }}</span>
                </div>
            </div>
        @endforeach
    </div>

    @if ($selectedTeam)
        <div class="panel">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <h6 class="panel-title mb-0">{{ $selectedTeam->name }} &mdash; Roster</h6>
                <div class="input-group input-group-sm" style="max-width:220px;">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input class="form-control" wire:model.live.debounce.400ms="playerSearch" placeholder="Search player...">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead><tr><th>#</th><th>Player</th><th>Position</th><th>Age</th><th>Nationality</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse ($players as $player)
                            <tr>
                                <td class="font-mono">{{ $player->jersey_number }}</td>
                                <td><div class="d-flex align-items-center gap-2"><img class="row-avatar" src="{{ $player->avatar_url }}">{{ $player->name }}</div></td>
                                <td>{{ $player->position }}</td>
                                <td>{{ $player->age }}</td>
                                <td>{{ $player->nationality }}</td>
                                <td><span class="badge {{ $player->fitness_badge_class }}">{{ ucfirst($player->fitness_status) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-secondary py-4">No players on this roster yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
