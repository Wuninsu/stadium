<?php

use App\Livewire\Admin\BookingsPage;
use App\Livewire\Admin\DashboardPage;
use App\Livewire\Admin\EventsPage as AdminEventsPage;
use App\Livewire\Admin\FacilitiesPage;
use App\Livewire\Admin\ReportsPage;
use App\Livewire\Admin\SettingsPage;
use App\Livewire\Admin\StaffPage;
use App\Livewire\Admin\TeamsPage;
use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\Auth\VerifyOtpPage;
use App\Livewire\Public\BookingPage;
use App\Livewire\Public\ContactPage;
use App\Livewire\Public\EventsPage;
use App\Livewire\Public\HomePage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class)->name('home');
Route::get('/events', EventsPage::class)->name('events');
Route::get('/booking', BookingPage::class)->name('booking');
Route::get('/contact', ContactPage::class)->name('contact');

Route::middleware('guest')->group(function () {
    Route::get('/login', LoginPage::class)->name('login');
    Route::get('/register', RegisterPage::class)->name('register');
    Route::get('/forgot-password', ForgotPasswordPage::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPasswordPage::class)->name('password.reset');
});

Route::get('/verify-otp', VerifyOtpPage::class)->name('otp.verify');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('home');
})->name('logout');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', DashboardPage::class)->name('admin');
    Route::get('/bookings', BookingsPage::class)->name('admin.bookings');
    Route::get('/facilities', FacilitiesPage::class)->name('admin.facilities');
    Route::get('/events', AdminEventsPage::class)->name('admin.events');
    Route::get('/reports', ReportsPage::class)->name('admin.reports');
    Route::get('/settings', SettingsPage::class)->name('admin.settings');
    Route::get('/staff', StaffPage::class)->name('admin.staff');
    Route::get('/teams', TeamsPage::class)->name('admin.teams');
});
