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
    $user = User::where('account_status', 'approved')
        ->where('is_admin', 0)
        ->inRandomOrder()
        ->first() ?? User::factory()->approved()->create();

    $service = Service::where('is_active', 1)
        ->whereNull('deleted_at')
        ->inRandomOrder()
        ->first() ?? Service::factory()->create();

    /*
    |--------------------------------------------------------------------------
    | DATE LOGIC (FIXED)
    |--------------------------------------------------------------------------
    */
    $dateType = fake()->randomElement(['past', 'today', 'future']);

    if ($dateType === 'past') {
        $reservationDate = Carbon::today()->subDays(fake()->numberBetween(1, 60));
        $status = fake()->randomElement(['completed', 'cancelled']);
    } elseif ($dateType === 'today') {
        $reservationDate = Carbon::today();
        $status = fake()->randomElement(['pending', 'completed']);
    } else {
        $reservationDate = Carbon::today()->addDays(fake()->numberBetween(1, 30));
        $status = fake()->randomElement(['pending', 'cancelled']);
    }

    // created_at is ALWAYS before or same day as reservation_date
    $createdAt = $reservationDate->copy()->subDays(fake()->numberBetween(0, 7));
    if ($createdAt->isFuture()) {
        $createdAt = Carbon::now();
    }

    $reference = 'RSV-' . $createdAt->format('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));

    /*
    |--------------------------------------------------------------------------
    | TIME SLOT (FIXED OVERLAP CHECK)
    |--------------------------------------------------------------------------
    */
    $startTime = null;
    $endTime = null;

    for ($i = 0; $i < 10; $i++) {
        $startHour = fake()->numberBetween(8, 15);
        $startMinute = fake()->randomElement([0, 30]);
        $startTime = sprintf('%02d:%02d:00', $startHour, $startMinute);

        $duration = fake()->randomElement([30, 60, 90, 120]);
        $endTime = Carbon::createFromFormat('H:i:s', $startTime)
            ->addMinutes($duration)
            ->format('H:i:s');

        $conflicts = \App\Models\Reservation::where('service_id', $service->id)
            ->whereDate('reservation_date', $reservationDate)
            ->whereIn('status', ['pending', 'completed'])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            })
            ->count();

        if ($conflicts < $service->capacity_units) {
            break;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ACTUAL TIMES
    |--------------------------------------------------------------------------
    */
    $actualIn = null;
    $actualOut = null;

    if ($status === 'completed') {
        $actualIn = Carbon::parse($startTime)
            ->addMinutes(fake()->numberBetween(0, 10))
            ->format('H:i:s');

        $actualOut = Carbon::parse($endTime)
            ->addMinutes(fake()->numberBetween(-5, 15))
            ->format('H:i:s');
    }

    /*
    |--------------------------------------------------------------------------
    | REASONS
    |--------------------------------------------------------------------------
    */
    $reservationReason = fake()->randomElement(['Surfing', 'Reading', 'Making Activity', 'Others']);
    $otherReason = $reservationReason === 'Others'
        ? fake()->randomElement([
            'Project work',
            'Homework',
            'Thesis writing',
            'Online meeting',
            'Video editing',
            'Gaming',
            'Watching videos',
            'Social media',
            'Email checking',
        ]): null;
    return [
        'user_id' => $user->id,
        'service_id' => $service->id,
        'closure_period_id' => null,
        'reference_no' => $reference,
        'reservation_date' => $reservationDate,
        'start_time' => $startTime,
        'end_time' => $endTime,
        'actual_time_in' => $actualIn,
        'actual_time_out' => $actualOut,
        'units_reserved' => 1,
        'status' => $status,
        'cancellation_reason' => null,
        'suspension_applied' => 0,
        'cancelled_at' => null,
        'cancelled_by' => null,
        'preferences' => null,
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
    return $this->state(function () {
        $reservationDate = Carbon::today()->addDays(fake()->numberBetween(1, 30));
        $createdAt = $reservationDate->copy()->subDays(fake()->numberBetween(1, 7));

        return [
            'reservation_date' => $reservationDate,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
            'status' => 'pending',
        ];
    });
}


    /**
     * Indicate that the reservation is for a past date.
     */
    public function past(): static
{
    return $this->state(function () {
        $reservationDate = Carbon::today()->subDays(fake()->numberBetween(1, 60));
        $createdAt = $reservationDate->copy()->subDays(fake()->numberBetween(1, 7));

        return [
            'reservation_date' => $reservationDate,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
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
        $start = Carbon::parse($attributes['start_time']);
        $end = Carbon::parse($attributes['end_time']);

        return [
            'status' => 'completed',
            'actual_time_in' => $start->addMinutes(fake()->numberBetween(0, 10))->format('H:i:s'),
            'actual_time_out' => $end->addMinutes(fake()->numberBetween(-5, 15))->format('H:i:s'),
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
                    'Personal emergency',
                    'Schedule conflict',
                    'No longer needed',
                    'Weather conditions',
                    'Transportation issues',
                    'Health reasons',
                    'Family matters',
                    'Work commitment',
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
