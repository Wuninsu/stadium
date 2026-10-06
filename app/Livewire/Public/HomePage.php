<?php

namespace App\Livewire\Public;

use App\Models\Event;
use App\Models\Facility;
use Livewire\Component;

class HomePage extends Component
{
    public function render()
    {
        return view('livewire.public.home-page', [
            'facilities' => Facility::orderBy('id')->take(4)->get(),
            'upcomingEvents' => Event::with('facility')
                ->where('date', '>=', now()->toDateString())
                ->orderBy('date')
                ->take(3)
                ->get(),
            'seatingCapacity' => Facility::max('capacity') ?? 22600,
        ])->layout('layouts.main', ['title' => 'Aliu Mahama Sports Stadium']);
    }
}
