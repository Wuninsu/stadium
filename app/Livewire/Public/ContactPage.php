<?php

namespace App\Livewire\Public;

use App\Models\ContactMessage;
use App\Models\Facility;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ContactPage extends Component
{
    #[Validate('required|string|max:150')]
    public string $name = '';

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|in:general,media,sponsorship,booking_support')]
    public string $subject = 'general';

    #[Validate('required|string|max:2000')]
    public string $message = '';

    public bool $sent = false;

    public function send()
    {
        $this->validate();

        ContactMessage::create([
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        $this->reset(['name', 'email', 'subject', 'message']);
        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.public.contact-page', [
            'facilityCount' => Facility::count(),
        ])->layout('layouts.main', ['title' => 'About & Contact']);
    }
}
