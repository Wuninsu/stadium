<div>
    <div class="page-header">
        <div class="crumb mb-1">Admin / Settings</div>
        <h3 class="fw-bold mb-0">Settings</h3>
        <p class="text-secondary mb-0 small">Manage your profile, notifications and system preferences.</p>
    </div>

    <div class="row g-3">
        <div class="col-lg-3">
            <div class="panel">
                <div class="nav flex-column nav-pills-soft gap-1">
                    <button type="button" wire:click="setTab('profile')" class="nav-link text-start {{ $activeTab === 'profile' ? 'active' : '' }}"><i class="bi bi-person me-2"></i>Profile</button>
                    <button type="button" wire:click="setTab('notifications')" class="nav-link text-start {{ $activeTab === 'notifications' ? 'active' : '' }}"><i class="bi bi-bell me-2"></i>Notifications</button>
                    <button type="button" wire:click="setTab('roles')" class="nav-link text-start {{ $activeTab === 'roles' ? 'active' : '' }}"><i class="bi bi-people me-2"></i>User roles</button>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            @if ($activeTab === 'profile')
                <div class="panel mb-3">
                    <h6 class="panel-title mb-3">Profile information</h6>
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($name) }}&background=FFC72C&color=063D2C&bold=true&size=72" class="rounded-3">
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-pitch rounded-3 me-2" disabled>Upload photo</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-3" disabled>Remove</button>
                        </div>
                    </div>
                    <form wire:submit="saveProfile">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Full name</label>
                                <input class="form-control @error('name') is-invalid @enderror" wire:model="name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Role</label>
                                <input class="form-control" value="{{ auth()->user()->role_label }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Email</label>
                                <input class="form-control @error('email') is-invalid @enderror" wire:model="email">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Phone</label>
                                <input class="form-control" wire:model="phone">
                            </div>
                        </div>
                        <div class="mt-4 d-flex align-items-center gap-3">
                            <button type="submit" class="btn btn-pitch rounded-3 px-4" wire:loading.attr="disabled">Save Changes</button>
                            @if ($saved) <span class="small text-pitch fw-semibold">Saved.</span> @endif
                        </div>
                    </form>
                </div>
            @endif

            @if ($activeTab === 'notifications')
                <div class="panel mb-3">
                    <h6 class="panel-title mb-3">Notification preferences</h6>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div><div class="fw-semibold small">New booking requests</div><div class="text-secondary small">Get notified when a new reservation is submitted</div></div>
                        <div class="form-check form-switch"><input class="form-check-input" type="checkbox" wire:model.live="new_booking_requests" style="width:2.5em;height:1.3em;"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div><div class="fw-semibold small">Maintenance reminders</div><div class="text-secondary small">Scheduled upkeep and inspection alerts</div></div>
                        <div class="form-check form-switch"><input class="form-check-input" type="checkbox" wire:model.live="maintenance_reminders" style="width:2.5em;height:1.3em;"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <div><div class="fw-semibold small">Weekly summary report</div><div class="text-secondary small">Emailed every Monday at 8:00 AM</div></div>
                        <div class="form-check form-switch"><input class="form-check-input" type="checkbox" wire:model.live="weekly_summary" style="width:2.5em;height:1.3em;"></div>
                    </div>
                </div>
            @endif

            @if ($activeTab === 'roles')
                <div class="panel">
                    <h6 class="panel-title mb-3">User roles &amp; permissions</h6>
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead><tr><th>User</th><th>Role</th><th>Access level</th><th></th></tr></thead>
                            <tbody>
                                @foreach ($adminUsers as $user)
                                    <tr>
                                        <td><div class="d-flex align-items-center gap-2"><img class="row-avatar" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0B6E4F&color=fff">{{ $user->name }}</div></td>
                                        <td>{{ $user->role_label }}</td>
                                        <td><span class="badge {{ $user->access_level_badge_class }}">{{ $user->access_level_label }}</span></td>
                                        <td><i class="bi bi-three-dots text-secondary"></i></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
