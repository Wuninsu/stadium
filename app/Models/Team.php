<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'category', 'logo_url', 'type'])]
class Team extends Model
{
    use HasFactory;

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return $this->type === 'resident' ? 'badge-soft-green' : 'badge-soft-yellow';
    }

    public function getRosterNounAttribute(): string
    {
        return str_contains($this->category, 'Athletic') ? 'athletes' : 'players';
    }
}
