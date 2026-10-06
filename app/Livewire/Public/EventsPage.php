<?php

namespace App\Livewire\Public;

use App\Models\Event;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class EventsPage extends Component
{
    use WithPagination;

    #[Url]
    public string $filter = 'All';

    #[Url]
    public string $search = '';

    protected array $categoryMap = [
        'Football' => ['league_fixture', 'tournament'],
        'Athletics' => ['athletics', 'youth'],
        'Community' => ['community'],
    ];

    public function setFilter(string $filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Event::with('facility')->orderBy('date');

        if ($this->filter !== 'All' && isset($this->categoryMap[$this->filter])) {
            $query->whereIn('type', $this->categoryMap[$this->filter]);
        }

        if ($this->search !== '') {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        return view('livewire.public.events-page', [
            'events' => $query->paginate(6),
        ])->layout('layouts.main', ['title' => 'Fixtures & Events']);
    }
}
