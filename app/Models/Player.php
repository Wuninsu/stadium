<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['team_id', 'jersey_number', 'name', 'avatar_url', 'position', 'age', 'nationality', 'fitness_status'])]
class Player extends Model
{
    use HasFactory;

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function getFitnessBadgeClassAttribute(): string
    {
        return $this->fitness_status === 'injured' ? 'badge-soft-yellow' : 'badge-soft-green';
    }
}
