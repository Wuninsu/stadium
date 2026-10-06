<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class BookingsPage extends Component
{
    use WithPagination;

    #[Url]
    public string $status = 'All';

    #[Url]
    public string $search = '';

    public function setStatus(string $status)
    {
        $this->status = $status;
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function approve(int $bookingId)
    {
        Booking::whereKey($bookingId)->update(['status' => 'confirmed']);
    }

    public function cancel(int $bookingId)
    {
        Booking::whereKey($bookingId)->update(['status' => 'cancelled']);
    }

    public function render()
    {
        $base = Booking::query();

        $counts = [
            'All' => (clone $base)->count(),
            'Pending' => (clone $base)->where('status', 'pending')->count(),
            'Confirmed' => (clone $base)->where('status', 'confirmed')->count(),
            'Cancelled' => (clone $base)->where('status', 'cancelled')->count(),
        ];

        $query = Booking::with('facility')->latest('date');

        if ($this->status !== 'All') {
            $query->where('status', strtolower($this->status));
        }

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('booking_ref', 'like', '%' . $this->search . '%')
                    ->orWhere('organisation', 'like', '%' . $this->search . '%')
                    ->orWhere('contact_name', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.admin.bookings-page', [
            'bookings' => $query->paginate(10),
            'counts' => $counts,
            'revenueBooked' => (float) Booking::where('status', '!=', 'cancelled')->sum('amount'),
        ])->layout('layouts.app', [
            'title' => 'Bookings',
            'searchPlaceholder' => 'Search booking, organisation…',
        ]);
    }
}
