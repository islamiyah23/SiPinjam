<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'equipment';

    protected $fillable = [
        'name',
        'category',
        'quantity',
        'available',
        'description',
        'image_url',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'available' => 'integer',
        ];
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Check if equipment is available for borrowing.
     */
    public function getIsAvailableAttribute(): bool
    {
        return $this->available > 0;
    }
}
