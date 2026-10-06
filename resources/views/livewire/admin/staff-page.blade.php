<div>
    <div class="page-header d-flex flex-wrap justify-content-between align-items-end gap-3">
        <div>
            <div class="crumb mb-1">Admin / Staff &amp; Roster</div>
            <h3 class="fw-bold mb-0">Staff &amp; Roster</h3>
            <p class="text-secondary mb-0 small">Manage stadium personnel, shifts and matchday assignments.</p>
        </div>
        <button type="button" class="btn btn-flood rounded-3"><i class="bi bi-person-plus me-1"></i>Add Staff Member</button>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card"><div><div class="stat-value">{{ $totalStaff }}</div><div class="stat-label">Total staff</div></div><div class="stat-icon" style="background:var(--pitch-100);color:var(--pitch-700);"><i class="bi bi-people"></i></div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card"><div><div class="stat-value">{{ $onShift }}</div><div class="stat-label">On shift today</div></div><div class="stat-icon" style="background:#FFF3D6;color:var(--flood-600);"><i class="bi bi-person-check"></i></div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card"><div><div class="stat-value">{{ $departmentCount }}</div><div class="stat-label">Departments</div></div><div class="stat-icon" style="background:var(--pitch-100);color:var(--pitch-700);"><i class="bi bi-diagram-3"></i></div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card"><div><div class="stat-value">0</div><div class="stat-label">Open positions</div></div><div class="stat-icon" style="background:#FBE7E7;color:var(--danger);"><i class="bi bi-briefcase"></i></div></div>
        </div>
    </div>

    <div class="panel">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <ul class="nav nav-pills-soft gap-1">
                @foreach (['All' => 'All staff', 'Groundskeeping' => 'Groundskeeping', 'Security' => 'Security', 'Medical' => 'Medical', 'Admin' => 'Admin'] as $key => $label)
                    <li class="nav-item">
                        <button type="button" wire:click="setDepartment('{{ $key }}')" class="nav-link {{ $department === $key ? 'active' : '' }}">{{ $label }}</button>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="table-responsive">
            <table class="table table-modern">
                <thead><tr><th>Staff</th><th>Department</th><th>Role</th><th>Shift</th><th>Matchday duty</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($staff as $member)
                        <tr>
                            <td><div class="d-flex align-items-center gap-2"><img class="row-avatar" src="{{ $member->avatar_url }}">{{ $member->name }}</div></td>
                            <td>{{ $member->department_label }}</td>
                            <td>{{ $member->role_title }}</td>
                            <td>{{ $member->shift }}</td>
                            <td>{{ $member->matchday_duty ?? '—' }}</td>
                            <td><span class="badge {{ $member->status_badge_class }}">{{ $member->status_label }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-secondary py-4">No staff in this department.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
