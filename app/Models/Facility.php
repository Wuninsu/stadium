<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'type', 'description', 'capacity', 'hourly_rate', 'status', 'utilisation_percent', 'next_maintenance_at', 'icon'])]
class Facility extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'hourly_rate' => 'decimal:2',
            'next_maintenance_at' => 'date',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(FacilityMaintenanceLog::class);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status === 'maintenance' ? 'badge-soft-yellow' : 'badge-soft-green';
    }

    public function getUtilisationBarClassAttribute(): string
    {
        return $this->status === 'maintenance' ? 'progress-bar-flood' : 'progress-bar-pitch';
    }
}
