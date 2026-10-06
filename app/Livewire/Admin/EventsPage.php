<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use App\Models\EventChecklistItem;
use App\Models\Facility;
use Carbon\CarbonImmutable;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EventsPage extends Component
{
    public int $month;

    public int $year;

    #[Validate('required|string|max:150')]
    public string $new_title = '';

    #[Validate('required|exists:facilities,id')]
    public ?int $new_facility_id = null;

    #[Validate('required|date')]
    public string $new_date = '';

    #[Validate('nullable|integer|min:1')]
    public ?int $new_expected_attendance = null;

    public function mount()
    {
        $this->month = now()->month;
        $this->year = now()->year;
    }

    public function prevMonth()
    {
        $cursor = CarbonImmutable::create($this->year, $this->month, 1)->subMonth();
        $this->month = $cursor->month;
        $this->year = $cursor->year;
    }

    public function nextMonth()
    {
        $cursor = CarbonImmutable::create($this->year, $this->month, 1)->addMonth();
        $this->month = $cursor->month;
        $this->year = $cursor->year;
    }

    public function scheduleEvent()
    {
        $this->validate();

        Event::create([
            'title' => $this->new_title,
            'type' => 'community',
            'facility_id' => $this->new_facility_id,
            'date' => $this->new_date,
            'start_time' => '10:00',
            'end_time' => '13:00',
            'expected_attendance' => $this->new_expected_attendance,
            'capacity' => Facility::find($this->new_facility_id)?->capacity,
            'status' => 'prep',
            'ticket_status' => 'open',
        ]);

        $this->reset(['new_title', 'new_facility_id', 'new_date', 'new_expected_attendance']);
        $this->dispatch('hide-modal', id: 'newEventModal');
    }

    public function toggleChecklistItem(int $itemId)
    {
        $item = EventChecklistItem::find($itemId);
        $item?->update(['is_done' => ! $item->is_done]);
    }

    public function render()
    {
        $first = CarbonImmutable::create($this->year, $this->month, 1);
        $gridStart = $first->startOfWeek(CarbonImmutable::SUNDAY);
        $gridEnd = $first->endOfMonth()->endOfWeek(CarbonImmutable::SATURDAY);

        $eventsByDay = Event::whereBetween('date', [$gridStart->toDateString(), $gridEnd->toDateString()])
            ->get()
            ->groupBy(fn ($event) => $event->date->toDateString());

        $weeks = [];
        $cursor = $gridStart;
        while ($cursor <= $gridEnd) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $week[] = [
                    'date' => $cursor,
                    'inMonth' => $cursor->month === $this->month,
                    'events' => $eventsByDay->get($cursor->toDateString(), collect()),
                ];
                $cursor = $cursor->addDay();
            }
            $weeks[] = $week;
        }

        $nextEvent = Event::where('date', '>=', now()->toDateString())->orderBy('date')->orderBy('start_time')->first();

        return view('livewire.admin.events-page', [
            'weeks' => $weeks,
            'monthLabel' => $first->format('F Y'),
            'events' => Event::with('facility')->orderBy('date')->get(),
            'facilities' => Facility::orderBy('id')->get(),
            'nextEvent' => $nextEvent,
            'checklistItems' => $nextEvent?->checklistItems ?? collect(),
        ])->layout('layouts.app', [
            'title' => 'Events & Fixtures',
            'searchPlaceholder' => 'Search events, fixtures…',
        ]);
    }
}
