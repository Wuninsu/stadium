<?php

namespace App\Livewire\Admin;

use App\Models\NotificationPreference;
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Component;

class SettingsPage extends Component
{
    public string $activeTab = 'profile';

    #[Validate('required|string|max:150')]
    public string $name = '';

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('nullable|string|max:30')]
    public string $phone = '';

    public bool $new_booking_requests = true;

    public bool $maintenance_reminders = true;

    public bool $weekly_summary = false;

    public bool $saved = false;

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';

        $prefs = NotificationPreference::firstOrCreate(['user_id' => $user->id]);
        $this->new_booking_requests = $prefs->new_booking_requests;
        $this->maintenance_reminders = $prefs->maintenance_reminders;
        $this->weekly_summary = $prefs->weekly_summary;
    }

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    public function saveProfile()
    {
        $this->validate();

        auth()->user()->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
        ]);

        $this->saved = true;
    }

    public function updated($property)
    {
        if (in_array($property, ['new_booking_requests', 'maintenance_reminders', 'weekly_summary'])) {
            NotificationPreference::updateOrCreate(['user_id' => auth()->id()], [
                'new_booking_requests' => $this->new_booking_requests,
                'maintenance_reminders' => $this->maintenance_reminders,
                'weekly_summary' => $this->weekly_summary,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.settings-page', [
            'adminUsers' => User::whereNotNull('access_level')->orderBy('name')->get(),
        ])->layout('layouts.app', [
            'title' => 'Settings',
            'searchPlaceholder' => 'Search settings…',
        ]);
    }
}
