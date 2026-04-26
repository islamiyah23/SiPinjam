<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'type',
        'room_id',
        'equipment_id',
        'start_date',
        'end_date',
        'purpose',
        'notes',
        'status',
        'rejection_reason',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    // ── Relationships ──

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    // ── Accessors ──

    /**
     * Get the item name (room or equipment name).
     */
    public function getItemNameAttribute(): string
    {
        if ($this->type === 'room') {
            return $this->room?->name ?? '-';
        }
        return $this->equipment?->name ?? '-';
    }

    /**
     * Get the item ID (room_id or equipment_id).
     */
    public function getItemIdAttribute(): ?string
    {
        return $this->type === 'room' ? $this->room_id : $this->equipment_id;
    }

    /**
     * Get lowercase status for frontend display.
     */
    public function getStatusLabelAttribute(): string
    {
        return strtolower($this->status);
    }

    // ── Scopes ──

    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'APPROVED');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'REJECTED');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'COMPLETED');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['APPROVED', 'ACTIVE']);
    }
}
