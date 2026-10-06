<?php

namespace App\Livewire\Auth;

use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Validate;
use Livewire\Component;

class RegisterPage extends Component
{
    #[Validate('required|string|max:100')]
    public string $first_name = '';

    #[Validate('required|string|max:100')]
    public string $last_name = '';

    #[Validate('nullable|string|max:150')]
    public string $organisation = '';

    #[Validate('required|email|unique:users,email')]
    public string $email = '';

    #[Validate('required|string|min:8')]
    public string $password = '';

    #[Validate('accepted')]
    public bool $terms = false;

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => trim("{$this->first_name} {$this->last_name}"),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'organisation' => $this->organisation ?: null,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'customer',
        ]);

        $code = (string) random_int(100000, 999999);

        OtpVerification::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(15),
        ]);

        try {
            Mail::raw("Your Aliu Mahama Sports Stadium verification code is: {$code}", function ($message) use ($user) {
                $message->to($user->email)->subject('Verify your email');
            });
        } catch (\Throwable $e) {
            // Mail transport not configured in this environment; code is still stored for verification.
        }

        session(['otp_user_id' => $user->id]);

        return redirect()->route('otp.verify');
    }

    public function render()
    {
        return view('livewire.auth.register-page')->layout('layouts.auth', [
            'title' => 'Create Account',
            'eyebrow' => 'Create an account',
            'heading' => 'Track every booking in one place.',
            'tagline' => 'Clubs, schools and organisers get faster approvals and a full booking history.',
            'steps' => [true, false, false],
        ]);
    }
}
