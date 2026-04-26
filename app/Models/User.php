<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ── Relationships ──

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function sipinjamNotifications()
    {
        return $this->hasMany(SipinjamNotification::class);
    }

    public function deactivations()
    {
        return $this->hasMany(UserDeactivation::class);
    }

    // ── Helpers ──

    public function isAdmin(): bool
    {
        return strtolower($this->role) === 'admin';
    }

    public function isUser(): bool
    {
        return strtolower($this->role) === 'user';
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->name, 0, 2));
    }
}
