<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceArchive extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'units_archived',
        'capacity_before',
        'capacity_after',
        'reason',
        'reservations_cancelled',
        'cancelled_reservation_ids',
    ];

    protected $casts = [
        'cancelled_reservation_ids' => 'array',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
