<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'name', 'avatar_url', 'department', 'role_title', 'shift', 'matchday_duty', 'status'])]
class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status === 'on_shift' ? 'badge-soft-green' : 'badge-soft-grey';
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'on_shift' ? 'On shift' : 'Off duty';
    }

    public function getDepartmentLabelAttribute(): string
    {
        return ucfirst($this->department);
    }
}
