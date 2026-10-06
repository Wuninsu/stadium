<?php

namespace App\Livewire\Admin;

use App\Models\Facility;
use App\Models\FacilityMaintenanceLog;
use Livewire\Component;

class FacilitiesPage extends Component
{
    public function render()
    {
        return view('livewire.admin.facilities-page', [
            'facilities' => Facility::orderBy('id')->get(),
            'logs' => FacilityMaintenanceLog::with('facility')->orderBy('scheduled_at')->get(),
        ])->layout('layouts.app', [
            'title' => 'Facilities',
            'searchPlaceholder' => 'Search facilities…',
        ]);
    }
}
