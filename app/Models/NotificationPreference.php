<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'new_booking_requests', 'maintenance_reminders', 'weekly_summary'])]
class NotificationPreference extends Model
{
    protected function casts(): array
    {
        return [
            'new_booking_requests' => 'boolean',
            'maintenance_reminders' => 'boolean',
            'weekly_summary' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
