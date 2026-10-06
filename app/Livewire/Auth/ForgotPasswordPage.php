<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ForgotPasswordPage extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    public bool $sent = false;

    public function sendResetLink()
    {
        $this->validate();

        Password::sendResetLink(['email' => $this->email]);

        // Always show success, regardless of whether the account exists, to avoid leaking which emails are registered.
        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.auth.forgot-password-page')->layout('layouts.auth', [
            'title' => 'Forgot Password',
            'eyebrow' => 'Account recovery',
            'heading' => 'Locked out happens to the best of us.',
            'tagline' => "We'll send a secure reset link straight to your inbox.",
            'steps' => [false, true, false],
        ]);
    }
}
