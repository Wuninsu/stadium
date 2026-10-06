<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class LoginPage extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public function login()
    {
        $this->validate();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', 'Those credentials don\'t match our records.');

            return;
        }

        request()->session()->regenerate();

        return redirect()->intended(route('admin'));
    }

    public function render()
    {
        return view('livewire.auth.login-page')->layout('layouts.auth', [
            'title' => 'Sign In',
            'eyebrow' => 'Staff & Admin Portal',
            'heading' => 'Run matchday from one dashboard.',
            'tagline' => 'Bookings, fixtures, facilities and staff rosters, sign in to manage the stadium.',
            'steps' => [true, false, false],
        ]);
    }
}
