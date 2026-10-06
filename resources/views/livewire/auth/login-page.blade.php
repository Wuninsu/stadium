<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <a href="{{ route('home') }}" class="text-secondary small"><i class="bi bi-arrow-left me-1"></i>Back to site</a>
        <span class="badge badge-soft-green">Secure sign in</span>
    </div>
    <h3 class="fw-bold mb-1">Welcome back</h3>
    <p class="text-secondary mb-4">Sign in to your staff account to continue.</p>

    <form wire:submit="login">
        <div class="mb-3">
            <label class="form-label fw-semibold small">Email address</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model="email" placeholder="you@aliumahamastadium.gh">
            </div>
            @error('email') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
        </div>
        <div class="mb-2">
            <label class="form-label fw-semibold small">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                <input type="password" id="loginPassword" class="form-control @error('password') is-invalid @enderror" wire:model="password" placeholder="••••••••">
                <span class="input-group-text bg-white" role="button" data-toggle-password="#loginPassword"><i class="bi bi-eye"></i></span>
            </div>
            @error('password') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
        </div>
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember" wire:model="remember">
                <label class="form-check-label small text-secondary" for="remember">Remember me</label>
            </div>
            <a href="{{ route('password.request') }}" class="small fw-semibold text-pitch">Forgot password?</a>
        </div>

        <button type="submit" class="btn btn-flood btn-lg rounded-3 w-100 mb-3" wire:loading.attr="disabled">
            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
        </button>

        <div class="d-flex align-items-center gap-2 my-3">
            <hr class="flex-grow-1"><span class="small text-secondary">or</span><hr class="flex-grow-1">
        </div>

        <button type="button" class="btn btn-outline-pitch rounded-3 w-100 mb-2" disabled title="Not configured in this deployment">
            <i class="bi bi-shield-lock me-2"></i>Sign in with SSO
        </button>
    </form>

    <p class="text-center small text-secondary mt-4 mb-0">
        Booking as a visitor? <a href="{{ route('booking') }}" class="fw-semibold text-pitch">Reserve a facility</a> without an account,
        or <a href="{{ route('register') }}" class="fw-semibold text-pitch">create an account</a> to track your bookings.
    </p>
</div>
