<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ResetPasswordPage extends Component
{
    public string $token = '';

    #[Url]
    public string $email = '';

    #[Validate('required|string|min:8')]
    public string $password = '';

    #[Validate('required|same:password')]
    public string $password_confirmation = '';

    public bool $done = false;

    public function mount(string $token)
    {
        $this->token = $token;
    }

    public function resetPassword()
    {
        $this->validate();

        $status = Password::reset(
            [
                'email' => $this->email,
                'token' => $this->token,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
            ],
            function ($user) {
                $user->forceFill(['password' => Hash::make($this->password)])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            $this->done = true;

            return;
        }

        $this->addError('password', 'This reset link is invalid or has expired. Please request a new one.');
    }

    public function render()
    {
        return view('livewire.auth.reset-password-page')->layout('layouts.auth', [
            'title' => 'Reset Password',
            'eyebrow' => 'Almost done',
            'heading' => 'Choose a new password.',
            'tagline' => "Make it something strong you haven't used before.",
            'steps' => [false, false, true],
        ]);
    }
}
