<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Service;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get random approved, non-admin user
        $user = User::where('account_status', 'approved')
            ->where('is_admin', 0)
            ->inRandomOrder()
            ->first();
        
        // Get random service
        $service = Service::where('is_active', 1)
            ->whereNull('deleted_at')
            ->inRandomOrder()
            ->first();
        
        // Created at is today or past (1-90 days ago)
        $createdAt = Carbon::now()->subDays(fake()->numberBetween(0, 90));
        
        // Reservation date is same as created date or future (0-30 days from created)
        $reservationDate = Carbon::parse($createdAt)->addDays(fake()->numberBetween(0, 30));
        
        // Generate reference number based on created_at
        $reference = 'RSV-' . $createdAt->format('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
        
        // Try to find available time slot (max 10 attempts)
        $maxAttempts = 10;
        $attempt = 0;
        $isAvailable = false;
        $startTime = null;
        $endTime = null;
        
        while (!$isAvailable && $attempt < $maxAttempts) {
            // Random start time between 8 AM and 5 PM
            $startHour = fake()->numberBetween(8, 15);
            $startMinute = fake()->randomElement([0, 30]);
            $startTime = sprintf('%02d:%02d:00', $startHour, $startMinute);
            
            // Random duration: 30 min, 1 hr, 1.5 hr, or 2 hr
            $durationMinutes = fake()->randomElement([30, 60, 90, 120]);
            $endTime = Carbon::parse($startTime)->addMinutes($durationMinutes)->format('H:i:s');
            
            // Check if this time slot is available for this service
            if ($service) {
                $conflictingReservations = \App\Models\Reservation::where('service_id', $service->id)
                    ->where('reservation_date', $reservationDate)
                    ->whereIn('status', ['pending', 'confirmed', 'completed'])
                    ->where(function($query) use ($startTime, $endTime) {
                        $query->where(function($q) use ($startTime, $endTime) {
                            // New reservation starts during existing reservation
                            $q->where('start_time', '<=', $startTime)
                              ->where('end_time', '>', $startTime);
                        })->orWhere(function($q) use ($startTime, $endTime) {
                            // New reservation ends during existing reservation
                            $q->where('start_time', '<', $endTime)
                              ->where('end_time', '>=', $endTime);
                        })->orWhere(function($q) use ($startTime, $endTime) {
                            // New reservation completely overlaps existing reservation
                            $q->where('start_time', '>=', $startTime)
                              ->where('end_time', '<=', $endTime);
                        });
                    })
                    ->count();
                
                // Check if capacity allows this reservation
                if ($conflictingReservations < $service->capacity_units) {
                    $isAvailable = true;
                } else {
                    $attempt++;
                }
            } else {
                $isAvailable = true;
            }
        }
        
        // If no available slot found after max attempts, use the last generated time anyway
        // (this can happen with test data, but real app should handle this)
        
        // Random reservation reason
        $reservationReason = fake()->randomElement(['Surfing', 'Reading', 'Making Activity', 'Others']);
        $otherReason = $reservationReason === 'Others' ? fake()->words(fake()->numberBetween(1, 2), true) : null;
        
        // Determine status based on reservation date
        // Future reservations: only 'pending' or 'cancelled'
        // Past reservations: only 'completed' or 'cancelled' (never pending)
        $isPast = $reservationDate->isPast();
        $status = $isPast 
            ? fake()->randomElement(['completed', 'cancelled'])
            : fake()->randomElement(['pending', 'cancelled']);
        
        return [
            'user_id' => $user ? $user->id : User::factory()->approved(),
            'service_id' => $service ? $service->id : Service::factory(),
            'closure_period_id' => null,
            'reference_no' => $reference,
            'reservation_date' => $reservationDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'actual_time_in' => null,
            'actual_time_out' => null,
            'units_reserved' => 1,
            'status' => $status,
            'cancellation_reason' => null,
            'suspension_applied' => 0,
            'cancelled_at' => null,
            'cancelled_by' => null,
            'preferences' => fake()->boolean(60) ? fake()->sentence() : null,
            'reservation_reason' => $reservationReason,
            'other_reason' => $otherReason,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }

    /**
     * Indicate that the reservation is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'actual_time_in' => null,
            'actual_time_out' => null,
            'cancellation_reason' => null,
            'cancelled_at' => null,
            'cancelled_by' => null,
        ]);
    }

    /**
     * Indicate that the reservation is for a future date.
     */
    public function future(): static
    {
        return $this->state(function (array $attributes) {
            // Future reservation (1-30 days from TODAY)
            $reservationDate = Carbon::now()->addDays(fake()->numberBetween(1, 30));
            // Created at is 1-7 days before reservation date (but not in the future)
            $createdAtNew = $reservationDate->copy()->subDays(fake()->numberBetween(1, 7));
            if ($createdAtNew->isFuture()) {
                $createdAtNew = Carbon::now();
            }
            
            // Generate reference number based on created_at
            $reference = 'RSV-' . $createdAtNew->format('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
            
            return [
                'reservation_date' => $reservationDate,
                'created_at' => $createdAtNew,
                'updated_at' => $createdAtNew,
                'reference_no' => $reference,
                'status' => fake()->randomElement(['pending', 'cancelled']),
            ];
        });
    }

    /**
     * Indicate that the reservation is for a past date.
     */
    public function past(): static
    {
        return $this->state(function (array $attributes) {
            $createdAt = $attributes['created_at'] ?? Carbon::now();
            // Past reservation (reservation date is before today)
            $daysAgo = fake()->numberBetween(1, 90);
            $reservationDate = Carbon::now()->subDays($daysAgo);
            $createdAtNew = $reservationDate->copy()->subDays(fake()->numberBetween(1, 7));
            
            // Generate reference number based on created_at
            $reference = 'RSV-' . $createdAtNew->format('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
            
            return [
                'reservation_date' => $reservationDate,
                'created_at' => $createdAtNew,
                'updated_at' => $createdAtNew,
                'reference_no' => $reference,
                'status' => fake()->randomElement(['completed', 'cancelled']),
            ];
        });
    }

    /**
     * Indicate that the reservation is completed.
     */
    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            $startTime = Carbon::parse($attributes['start_time']);
            $endTime = Carbon::parse($attributes['end_time']);
            
            // Actual time in is within 10 minutes of start time
            $actualTimeIn = $startTime->copy()->addMinutes(fake()->numberBetween(-5, 10))->format('H:i:s');
            
            // Actual time out is around end time
            $actualTimeOut = $endTime->copy()->addMinutes(fake()->numberBetween(-10, 15))->format('H:i:s');
            
            // Make sure reservation date is in the past
            $createdAt = $attributes['created_at'] ?? Carbon::now();
            $reservationDate = Carbon::parse($createdAt);
            
            return [
                'reservation_date' => $reservationDate,
                'status' => 'completed',
                'actual_time_in' => $actualTimeIn,
                'actual_time_out' => $actualTimeOut,
                'cancellation_reason' => null,
                'cancelled_at' => null,
                'cancelled_by' => null,
            ];
        });
    }

    /**
     * Indicate that the reservation is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(function (array $attributes) {
            $createdAt = $attributes['created_at'] ?? Carbon::now();
            $cancelledAt = Carbon::parse($createdAt)->addHours(fake()->numberBetween(1, 48));
            
            // Get a random user to be the canceller (could be admin)
            $cancelledBy = User::where('is_admin', 1)->inRandomOrder()->first()?->id 
                ?? User::inRandomOrder()->first()?->id;
            
            return [
                'status' => 'cancelled',
                'actual_time_in' => null,
                'actual_time_out' => null,
                'cancellation_reason' => fake()->randomElement([
                    'User requested cancellation',
                    'Emergency came up',
                    'Schedule conflict',
                    'No longer needed',
                    'Weather conditions',
                    'Personal reasons',
                ]),
                'suspension_applied' => fake()->boolean(10), // 10% chance of suspension
                'cancelled_at' => $cancelledAt,
                'cancelled_by' => $cancelledBy,
                'updated_at' => $cancelledAt,
            ];
        });
    }

    /**
     * Indicate that the reservation is no-show.
     */
    public function noShow(): static
    {
        return $this->state(function (array $attributes) {
            $reservationDate = Carbon::parse($attributes['reservation_date']);
            $cancelledAt = $reservationDate->copy()->addHours(fake()->numberBetween(1, 24));
            
            // Get a random admin user
            $cancelledBy = User::where('is_admin', 1)->inRandomOrder()->first()?->id;
            
            return [
                'status' => 'cancelled',
                'actual_time_in' => null,
                'actual_time_out' => null,
                'cancellation_reason' => 'No-show',
                'suspension_applied' => 1,
                'cancelled_at' => $cancelledAt,
                'cancelled_by' => $cancelledBy,
                'updated_at' => $cancelledAt,
            ];
        });
    }

    /**
     * Set a specific reservation reason.
     */
    public function withReason(string $reason): static
    {
        return $this->state(fn (array $attributes) => [
            'reservation_reason' => $reason,
            'other_reason' => null,
        ]);
    }

    /**
     * Set "Others" as reason with custom text.
     */
    public function withOtherReason(string $otherReason): static
    {
        return $this->state(fn (array $attributes) => [
            'reservation_reason' => 'Others',
            'other_reason' => $otherReason,
        ]);
    }
}
