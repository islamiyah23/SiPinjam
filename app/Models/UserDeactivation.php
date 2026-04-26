<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDeactivation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reason',
        'deactivated_by',
        'duration',
        'reactivate_at',
    ];

    protected function casts(): array
    {
        return [
            'duration' => 'integer',
            'reactivate_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
