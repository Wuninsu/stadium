<?php

namespace App\Livewire\Admin;

use App\Models\Staff;
use Livewire\Attributes\Url;
use Livewire\Component;

class StaffPage extends Component
{
    #[Url]
    public string $department = 'All';

    protected array $departments = [
        'Groundskeeping' => 'groundskeeping',
        'Security' => 'security',
        'Medical' => 'medical',
        'Admin' => 'administration',
    ];

    public function setDepartment(string $department)
    {
        $this->department = $department;
    }

    public function render()
    {
        $query = Staff::query();

        if ($this->department !== 'All' && isset($this->departments[$this->department])) {
            $query->where('department', $this->departments[$this->department]);
        }

        return view('livewire.admin.staff-page', [
            'staff' => $query->orderBy('name')->get(),
            'totalStaff' => Staff::count(),
            'onShift' => Staff::where('status', 'on_shift')->count(),
            'departmentCount' => Staff::distinct('department')->count('department'),
        ])->layout('layouts.app', [
            'title' => 'Staff & Roster',
            'searchPlaceholder' => 'Search staff…',
        ]);
    }
}
