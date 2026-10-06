<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'title', 'type', 'facility_id', 'date', 'start_time', 'end_time', 'capacity',
    'expected_attendance', 'tickets_sold', 'ticket_price', 'status', 'ticket_status',
])]
class Event extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'ticket_price' => 'decimal:2',
        ];
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function checklistItems(): HasMany
    {
        return $this->hasMany(EventChecklistItem::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->type) {
            'league_fixture' => 'Ghana Premier League',
            'athletics' => 'Track & Field',
            'tournament' => 'Community Tournament',
            'youth' => 'Youth Athletics',
            default => 'Community',
        };
    }

    public function getPublicStatusAttribute(): string
    {
        return match ($this->ticket_status) {
            'sold_out' => 'full',
            'selling_fast' => 'soon',
            default => 'open',
        };
    }

    public function getPublicStatusLabelAttribute(): string
    {
        return match ($this->ticket_status) {
            'sold_out' => 'Sold out',
            'selling_fast' => 'Selling fast',
            default => 'Tickets open',
        };
    }
}
