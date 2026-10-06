<div>
    <a href="{{ route('login') }}" class="text-secondary small d-inline-block mb-4"><i class="bi bi-arrow-left me-1"></i>Back to sign in</a>
    <div class="icon-tile mb-3" style="width:56px;height:56px;font-size:1.35rem;"><i class="bi bi-key"></i></div>

    @if ($sent)
        <h3 class="fw-bold mb-1">Check your inbox</h3>
        <p class="text-secondary mb-4">If an account exists for that email, a reset link is on its way.</p>
        <a href="{{ route('login') }}" class="btn btn-pitch rounded-3 px-4">Back to Sign In</a>
    @else
        <h3 class="fw-bold mb-1">Forgot your password?</h3>
        <p class="text-secondary mb-4">Enter the email linked to your account and we'll send a reset link.</p>

        <form wire:submit="sendResetLink">
            <div class="mb-4">
                <label class="form-label fw-semibold small">Email address</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model="email" placeholder="you@email.com">
                </div>
                @error('email') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="btn btn-flood btn-lg rounded-3 w-100 mb-3" wire:loading.attr="disabled">
                <i class="bi bi-send me-2"></i>Send Reset Link
            </button>
        </form>

        <p class="text-center small text-secondary mt-4 mb-0">
            Remembered it after all? <a href="{{ route('login') }}" class="fw-semibold text-pitch">Sign in</a>
        </p>
    @endif
</div>
