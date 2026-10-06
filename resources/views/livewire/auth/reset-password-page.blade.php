<div>
    <a href="{{ route('login') }}" class="text-secondary small d-inline-block mb-4"><i class="bi bi-arrow-left me-1"></i>Back to sign in</a>
    <div class="icon-tile mb-3" style="width:56px;height:56px;font-size:1.35rem;"><i class="bi bi-shield-lock"></i></div>

    @if ($done)
        <h3 class="fw-bold mb-1">Password updated</h3>
        <p class="text-secondary mb-4">You can now sign in with your new password.</p>
        <a href="{{ route('login') }}" class="btn btn-pitch rounded-3 px-4">Back to Sign In</a>
    @else
        <h3 class="fw-bold mb-1">Set a new password</h3>
        <p class="text-secondary mb-4">Your new password must be different from previous ones.</p>

        <form wire:submit="resetPassword">
            <div class="mb-2">
                <label class="form-label fw-semibold small">New password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="newPassword" wire:model="password" placeholder="Enter new password">
            </div>
            <div class="password-strength mb-1"><div class="bar" id="pwStrengthBar"></div></div>
            <div class="small text-secondary mb-3" id="pwStrengthLabel" style="min-height:1.1em;"></div>
            @error('password') <div class="small text-danger mt-1 mb-3">{{ $message }}</div> @enderror

            <div class="mb-4">
                <label class="form-label fw-semibold small">Confirm new password</label>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" wire:model="password_confirmation" placeholder="Re-enter password">
                @error('password_confirmation') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-flood btn-lg rounded-3 w-100 mb-3" wire:loading.attr="disabled">
                <i class="bi bi-check-circle me-2"></i>Reset Password
            </button>
        </form>
    @endif
</div>
