<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Facility;
use Livewire\Component;

class ReportsPage extends Component
{
    public function render()
    {
        $months = collect(range(5, 0))->map(fn ($i) => now()->subMonthsNoOverflow($i)->startOfMonth());
        $monthLabels = $months->map(fn ($m) => $m->format('M'))->values();

        $facilities = Facility::orderBy('id')->get();
        $revenueByFacility = $facilities->map(function ($facility) use ($months) {
            return $months->map(function ($month) use ($facility) {
                return (float) Booking::where('facility_id', $facility->id)
                    ->whereBetween('date', [$month, $month->copy()->endOfMonth()])
                    ->where('status', '!=', 'cancelled')
                    ->sum('amount');
            })->values();
        });

        $purposeSplit = Booking::selectRaw('purpose, count(*) as total')->groupBy('purpose')->pluck('total', 'purpose');

        $attendanceTrend = $months->map(function ($month) {
            return (int) Event::whereBetween('date', [$month, $month->copy()->endOfMonth()])->sum('tickets_sold');
        });

        $topEvents = Event::get()
            ->map(fn ($event) => $event->setAttribute('estimated_revenue', $event->tickets_sold * (float) $event->ticket_price))
            ->sortByDesc('estimated_revenue')
            ->take(5);

        $totalRevenue = (float) Booking::where('status', '!=', 'cancelled')->sum('amount');
        $totalBookings = Booking::where('status', '!=', 'cancelled')->count();

        return view('livewire.admin.reports-page', [
            'monthLabels' => $monthLabels,
            'revenueByFacility' => $revenueByFacility,
            'facilities' => $facilities,
            'purposeSplit' => $purposeSplit,
            'attendanceTrend' => $attendanceTrend,
            'topEvents' => $topEvents,
            'totalRevenue' => $totalRevenue,
            'totalAttendance' => (int) Event::sum('tickets_sold'),
            'eventsHosted' => Event::count(),
            'avgBookingValue' => $totalBookings > 0 ? $totalRevenue / $totalBookings : 0,
        ])->layout('layouts.app', [
            'title' => 'Reports & Analytics',
            'searchPlaceholder' => 'Search reports…',
        ]);
    }
}
