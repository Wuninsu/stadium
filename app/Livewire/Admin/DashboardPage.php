<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Facility;
use Livewire\Component;

class DashboardPage extends Component
{
    protected function trend(int $current, int $previous): array
    {
        if ($previous === 0) {
            return $current > 0 ? ['dir' => 'up', 'text' => 'New this month'] : ['dir' => 'up', 'text' => 'No activity yet'];
        }

        $pct = round((($current - $previous) / $previous) * 100);

        return [
            'dir' => $pct >= 0 ? 'up' : 'down',
            'text' => ($pct >= 0 ? '+' : '') . $pct . '% vs last month',
        ];
    }

    public function render()
    {
        $monthStart = now()->startOfMonth();
        $lastMonthStart = now()->subMonthNoOverflow()->startOfMonth();
        $lastMonthEnd = now()->subMonthNoOverflow()->endOfMonth();

        $bookingsThisMonth = Booking::whereBetween('date', [$monthStart, now()->endOfMonth()])->count();
        $bookingsLastMonth = Booking::whereBetween('date', [$lastMonthStart, $lastMonthEnd])->count();

        $revenueThisMonth = (float) Booking::whereBetween('date', [$monthStart, now()->endOfMonth()])->where('status', '!=', 'cancelled')->sum('amount');
        $revenueLastMonth = (float) Booking::whereBetween('date', [$lastMonthStart, $lastMonthEnd])->where('status', '!=', 'cancelled')->sum('amount');

        $upcomingFixtures = Event::where('date', '>=', now()->toDateString())->count();

        $mainPitchUtilisation = (int) (Facility::where('slug', 'main-pitch')->value('utilisation_percent') ?? 0);

        // Revenue & bookings trend, last 6 months including current.
        $months = collect(range(5, 0))->map(fn ($i) => now()->subMonthsNoOverflow($i)->startOfMonth());
        $revenueSeries = $months->map(function ($month) {
            return (float) Booking::whereBetween('date', [$month, $month->copy()->endOfMonth()])
                ->where('status', '!=', 'cancelled')
                ->sum('amount');
        });
        $bookingsSeries = $months->map(function ($month) {
            return Booking::whereBetween('date', [$month, $month->copy()->endOfMonth()])->count();
        });

        // Facility usage split (by booking count).
        $usage = Facility::withCount('bookings')->orderBy('id')->get();

        return view('livewire.admin.dashboard-page', [
            'stats' => [
                'bookings' => ['value' => $bookingsThisMonth, 'trend' => $this->trend($bookingsThisMonth, $bookingsLastMonth)],
                'revenue' => ['value' => $revenueThisMonth, 'trend' => $this->trend((int) $revenueThisMonth, (int) $revenueLastMonth)],
                'fixtures' => ['value' => $upcomingFixtures],
                'utilisation' => ['value' => $mainPitchUtilisation],
            ],
            'chartLabels' => $months->map(fn ($m) => $m->format('M'))->values(),
            'revenueSeries' => $revenueSeries->values(),
            'bookingsSeries' => $bookingsSeries->values(),
            'usageFacilities' => $usage,
            'recentBookings' => Booking::with('facility')->latest('created_at')->take(4)->get(),
            'upcomingEvents' => Event::with('facility')->where('date', '>=', now()->toDateString())->orderBy('date')->take(4)->get(),
        ])->layout('layouts.app', [
            'title' => 'Dashboard',
            'searchPlaceholder' => 'Search bookings, teams, events…',
        ]);
    }
}
