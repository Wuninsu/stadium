<?php

namespace App\Livewire\Public;

use App\Models\Booking;
use App\Models\Facility;
use Livewire\Attributes\Validate;
use Livewire\Component;

class BookingPage extends Component
{
    #[Validate('required|exists:facilities,id')]
    public ?int $facility_id = null;

    #[Validate('required|date|after_or_equal:today')]
    public string $date = '';

    #[Validate('required')]
    public string $start_time = '15:00';

    #[Validate('required|after:start_time')]
    public string $end_time = '17:00';

    #[Validate('required|in:league_fixture,training,community_event,private_function')]
    public string $purpose = 'league_fixture';

    #[Validate('nullable|integer|min:1')]
    public ?int $expected_attendance = null;

    #[Validate('required|string|max:150')]
    public string $contact_name = '';

    #[Validate('nullable|string|max:150')]
    public string $organisation = '';

    #[Validate('required|email')]
    public string $contact_email = '';

    #[Validate('nullable|string|max:30')]
    public string $contact_phone = '';

    #[Validate('nullable|string|max:1000')]
    public string $notes = '';

    public function mount()
    {
        if (auth()->check()) {
            $this->contact_name = auth()->user()->name;
            $this->contact_email = auth()->user()->email;
            $this->organisation = auth()->user()->organisation ?? '';
        }
    }

    public function submit()
    {
        $this->validate();

        $facility = Facility::findOrFail($this->facility_id);

        Booking::create([
            'booking_ref' => Booking::generateReference(),
            'user_id' => auth()->id(),
            'facility_id' => $facility->id,
            'organisation' => $this->organisation ?: null,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'purpose' => $this->purpose,
            'expected_attendance' => $this->expected_attendance,
            'contact_name' => $this->contact_name,
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone ?: null,
            'notes' => $this->notes ?: null,
            'amount' => round($facility->hourly_rate * $this->hoursRequested(), 2),
            'status' => 'pending',
        ]);

        $this->reset(['date', 'expected_attendance', 'notes']);
        $this->dispatch('show-modal', id: 'confirmModal');
    }

    protected function hoursRequested(): float
    {
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);

        return max(1, $start->diffInMinutes($end) / 60);
    }

    public function render()
    {
        return view('livewire.public.booking-page', [
            'facilities' => Facility::orderBy('id')->get(),
        ])->layout('layouts.main', ['title' => 'Book a Facility']);
    }
}
