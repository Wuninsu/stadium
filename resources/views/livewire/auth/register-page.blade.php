<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <a href="{{ route('home') }}" class="text-secondary small"><i class="bi bi-arrow-left me-1"></i>Back to site</a>
        <span class="badge badge-soft-yellow">Free account</span>
    </div>
    <h3 class="fw-bold mb-1">Create your account</h3>
    <p class="text-secondary mb-4">Takes less than a minute.</p>

    <form wire:submit="register">
        <div class="row g-3 mb-3">
            <div class="col-sm-6">
                <label class="form-label fw-semibold small">First name</label>
                <input class="form-control @error('first_name') is-invalid @enderror" wire:model="first_name" placeholder="Kwame">
                @error('first_name') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
            </div>
            <div class="col-sm-6">
                <label class="form-label fw-semibold small">Last name</label>
                <input class="form-control @error('last_name') is-invalid @enderror" wire:model="last_name" placeholder="Boateng">
                @error('last_name') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold small">Organisation (optional)</label>
            <input class="form-control" wire:model="organisation" placeholder="Club, school or company">
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold small">Email address</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model="email" placeholder="you@email.com">
            @error('email') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
        </div>
        <div class="mb-2">
            <label class="form-label fw-semibold small">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="newPassword" wire:model="password" placeholder="Create a password">
        </div>
        <div class="password-strength mb-1"><div class="bar" id="pwStrengthBar"></div></div>
        <div class="small text-secondary mb-3" id="pwStrengthLabel" style="min-height:1.1em;"></div>
        @error('password') <div class="small text-danger mt-1 mb-3">{{ $message }}</div> @enderror

        <div class="form-check mb-4">
            <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" id="terms" wire:model="terms">
            <label class="form-check-label small text-secondary" for="terms">I agree to the Terms of Service and Booking Policy</label>
        </div>

        <button type="submit" class="btn btn-flood btn-lg rounded-3 w-100 mb-3" wire:loading.attr="disabled">
            <i class="bi bi-person-plus me-2"></i>Create Account
        </button>
    </form>

    <p class="text-center small text-secondary mt-4 mb-0">
        Already have an account? <a href="{{ route('login') }}" class="fw-semibold text-pitch">Sign in</a>
    </p>
</div>
