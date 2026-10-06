<?php

namespace App\Livewire\Admin;

use App\Models\Team;
use Livewire\Component;

class TeamsPage extends Component
{
    public ?int $selectedTeamId = null;

    public string $playerSearch = '';

    public function selectTeam(int $teamId)
    {
        $this->selectedTeamId = $teamId;
        $this->playerSearch = '';
    }

    public function render()
    {
        $teams = Team::withCount('players')->orderBy('id')->get();

        $selectedTeam = $teams->firstWhere('id', $this->selectedTeamId) ?? $teams->first();

        $players = $selectedTeam
            ? $selectedTeam->players()
                ->when($this->playerSearch, fn ($q) => $q->where('name', 'like', '%' . $this->playerSearch . '%'))
                ->orderBy('jersey_number')
                ->get()
            : collect();

        return view('livewire.admin.teams-page', [
            'teams' => $teams,
            'selectedTeam' => $selectedTeam,
            'players' => $players,
        ])->layout('layouts.app', [
            'title' => 'Teams & Players',
            'searchPlaceholder' => 'Search teams, players…',
        ]);
    }
}
