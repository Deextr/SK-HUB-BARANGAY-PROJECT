<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClosurePeriod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'start_date',
        'end_date',
        'reason',
        'is_full_day',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_full_day' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}


