<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'closure_period_id',
        'reference_no',
        'reservation_date',
        'start_time',
        'end_time',
        'actual_time_in',
        'actual_time_out',
        'units_reserved',
        'status',
        'preferences',
    ];

    protected $casts = [
        'reservation_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function closurePeriod()
    {
        return $this->belongsTo(ClosurePeriod::class);
    }
}