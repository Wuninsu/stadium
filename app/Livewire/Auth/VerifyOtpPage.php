<?php

namespace App\Livewire\Auth;

use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class VerifyOtpPage extends Component
{
    public array $digits = ['', '', '', '', '', ''];

    public bool $verified = false;

    public ?string $maskedEmail = null;

    public function mount()
    {
        $user = $this->resolveUser();

        if ($user) {
            $this->maskedEmail = $user->email;
        }
    }

    protected function resolveUser(): ?User
    {
        $userId = session('otp_user_id');

        return $userId ? User::find($userId) : null;
    }

    public function verify()
    {
        $user = $this->resolveUser();

        if (! $user) {
            $this->addError('code', 'Your verification session has expired. Please register again.');

            return;
        }

        $code = implode('', $this->digits);

        $otp = OtpVerification::where('user_id', $user->id)
            ->where('code', $code)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $otp) {
            $this->addError('code', 'That code is invalid or has expired.');

            return;
        }

        $otp->update(['verified_at' => now()]);
        $user->update(['email_verified_at' => now()]);

        $this->verified = true;
    }

    public function resend()
    {
        $user = $this->resolveUser();

        if (! $user) {
            return;
        }

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
    }

    public function render()
    {
        return view('livewire.auth.verify-otp-page')->layout('layouts.auth', [
            'title' => 'Verify Email',
            'eyebrow' => 'Verification',
            'heading' => 'One last check.',
            'tagline' => "Confirming it's you keeps bookings and match tickets secure.",
            'steps' => [true, true, false],
        ]);
    }
}
