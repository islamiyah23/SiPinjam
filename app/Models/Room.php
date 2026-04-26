<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'capacity',
        'description',
        'building',
        'floor',
        'image_url',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'floor' => 'integer',
        ];
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get facilities parsed from description (comma-separated).
     */
    public function getFacilitiesAttribute(): array
    {
        return $this->description ? array_map('trim', explode(',', $this->description)) : [];
    }
}
