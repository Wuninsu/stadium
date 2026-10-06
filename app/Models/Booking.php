<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'booking_ref', 'user_id', 'facility_id', 'organisation', 'date', 'start_time', 'end_time',
    'purpose', 'expected_attendance', 'contact_name', 'contact_email', 'contact_phone',
    'notes', 'amount', 'status',
])]
class Booking extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateReference(): string
    {
        return 'BK-' . str_pad((string) (static::max('id') + 1), 4, '0', STR_PAD_LEFT);
    }

    public function getPurposeLabelAttribute(): string
    {
        return match ($this->purpose) {
            'league_fixture' => 'League fixture',
            'training' => 'Team training',
            'community_event' => 'Community event',
            'private_function' => 'Private function',
            default => ucfirst(str_replace('_', ' ', $this->purpose)),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'confirmed' => 'badge-soft-green',
            'pending' => 'badge-soft-yellow',
            'cancelled' => 'badge-soft-red',
            default => 'badge-soft-grey',
        };
    }
}
