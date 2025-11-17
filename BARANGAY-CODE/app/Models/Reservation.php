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
        'cancellation_reason',
        'suspension_applied',
        'cancelled_at',
        'preferences',
        'reservation_reason',
        'other_reason',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'suspension_applied' => 'boolean',
        'cancelled_at' => 'datetime',
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
    
    /**
     * Cancel a reservation with reason and optional suspension
     */
    public function cancelWithReason(string $reason, bool $applySuspension = false): void
    {
        $this->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
            'suspension_applied' => $applySuspension,
            'cancelled_at' => now(),
        ]);
        
        // If suspension is applied, increment the user's suspension count
        if ($applySuspension && $this->user) {
            // Check if this is the third suspension
            if ($this->user->suspension_count >= 2) {
                // Apply a 7-day suspension on the third strike
                $this->user->applySuspension(7);
            } else {
                // Just increment the count but don't suspend yet
                $this->user->increment('suspension_count');
            }
        }
    }
    
    /**
     * Check if the reservation is upcoming (within 10 minutes)
     */
    public function isUpcoming(int $minutesThreshold = 10): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }
        
        $reservationDateTime = $this->reservation_date->setTimeFromTimeString($this->start_time);
        $diffInMinutes = now()->diffInMinutes($reservationDateTime, false);
        
        // If difference is positive and less than threshold, it's upcoming
        return $diffInMinutes > 0 && $diffInMinutes <= $minutesThreshold;
    }
}