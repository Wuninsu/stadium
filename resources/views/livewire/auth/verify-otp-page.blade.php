<div>
    <a href="{{ route('register') }}" class="text-secondary small d-inline-block mb-4"><i class="bi bi-arrow-left me-1"></i>Back</a>
    <div class="icon-tile mb-3" style="width:56px;height:56px;font-size:1.35rem;"><i class="bi bi-envelope-open"></i></div>

    @if ($verified)
        <h3 class="fw-bold mb-1">Verified</h3>
        <p class="text-secondary mb-4">Your account is confirmed and ready to use.</p>
        <a href="{{ route('login') }}" class="btn btn-pitch rounded-3 px-4">Continue to Sign In</a>
    @else
        <h3 class="fw-bold mb-1">Enter verification code</h3>
        <p class="text-secondary mb-4">We sent a 6-digit code to
            <span class="fw-semibold text-ink">{{ $maskedEmail ?? 'your email' }}</span>
        </p>

        <form wire:submit="verify">
            <div class="d-flex gap-2 justify-content-between mb-4">
                @foreach ($digits as $i => $digit)
                    <input type="text" inputmode="numeric" maxlength="1" class="otp-input" wire:model="digits.{{ $i }}">
                @endforeach
            </div>
            @error('code') <div class="small text-danger mb-3">{{ $message }}</div> @enderror

            <button type="submit" class="btn btn-flood btn-lg rounded-3 w-100 mb-3" wire:loading.attr="disabled">
                <i class="bi bi-shield-check me-2"></i>Verify Code
            </button>
        </form>

        <p class="text-center small text-secondary mt-4 mb-0">
            Didn't get a code? <a href="#" wire:click.prevent="resend" class="fw-semibold text-pitch">Resend code</a>
        </p>
    @endif
</div>
