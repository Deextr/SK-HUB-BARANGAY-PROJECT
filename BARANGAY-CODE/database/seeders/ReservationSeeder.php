<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Service;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all approved users and services
        $users = User::where('account_status', 'approved')->pluck('id')->toArray();
        $services = Service::pluck('id', 'name')->toArray();
        
        if (empty($users)) {
            $this->command->error('No approved users found! Please run ResidentAccountsSeeder first.');
            return;
        }
        
        if (empty($services)) {
            $this->command->error('No services found! Please run ServiceSeeder first.');
            return;
        }
        
        $reservations = [];
        $referenceCounter = 1000;
        
        // Time slots for reservations (8 AM to 5 PM)
        $timeSlots = [
            ['08:00:00', '09:00:00'],
            ['09:00:00', '10:00:00'],
            ['10:00:00', '11:00:00'],
            ['11:00:00', '12:00:00'],
            ['13:00:00', '14:00:00'],
            ['14:00:00', '15:00:00'],
            ['15:00:00', '16:00:00'],
            ['16:00:00', '17:00:00'],
        ];
        
        
        $reservationReasons = [
            'Surfing',
            'Reading',
            'Making Activity',
            'Others',
        ];
        
        $otherReasons = [
            'Group study',
            'Project work',
            'Homework',
            'Thesis writing',
            'Online meeting',
            'Video editing',
            'Gaming',
            'Watching videos',
            'Social media',
            'Email checking',
        ];
        
        $cancellationReasons = [
            'Personal emergency',
            'Schedule conflict',
            'No longer needed',
            'Weather conditions',
            'Transportation issues',
            'Health reasons',
            'Family matters',
            'Work commitment',
        ];
        
        // Generate 1000 reservations for 2025
        // 600 completed (past), 250 cancelled (past), 150 pending (future: Dec 15-31)
        for ($i = 0; $i < 1000; $i++) {
            $userId = $users[array_rand($users)];
            $serviceName = array_rand($services);
            $serviceId = $services[$serviceName];
            
            // Determine status and date range
            // 0-599: Completed (past dates: Jan 1 - Dec 14)
            // 600-849: Cancelled (past dates: Jan 1 - Dec 14)
            // 850-999: Pending (future dates: Dec 15 - Dec 31)
            if ($i < 600) {
                $status = 'completed';
                // Past dates: January 1 to December 14
                $reservationDate = Carbon::create(2025, rand(1, 12), rand(1, 14));
            } elseif ($i < 850) {
                $status = 'cancelled';
                // Past dates: January 1 to December 14
                $reservationDate = Carbon::create(2025, rand(1, 12), rand(1, 14));
            } else {
                $status = 'pending';
                // Future dates: December 15 to December 31
                $reservationDate = Carbon::create(2025, 12, rand(15, 31));
            }
            
            // Random time slot
            $timeSlot = $timeSlots[array_rand($timeSlots)];
            $startTime = $timeSlot[0];
            $endTime = $timeSlot[1];
            
            // Set fields based on status
            if ($status === 'completed') {
                $cancellationReason = null;
                $cancelledAt = null;
                $cancelledBy = null;
                $suspensionApplied = false;
                $actualTimeIn = $startTime;
                $actualTimeOut = $endTime;
            } elseif ($status === 'cancelled') {
                $cancellationReason = $cancellationReasons[array_rand($cancellationReasons)];
                $cancelledAt = $reservationDate->copy()->subDays(rand(1, 5));
                $cancelledBy = $userId;
                $suspensionApplied = rand(0, 10) === 0;
                $actualTimeIn = null;
                $actualTimeOut = null;
            } else { // pending
                $cancellationReason = null;
                $cancelledAt = null;
                $cancelledBy = null;
                $suspensionApplied = false;
                $actualTimeIn = null;
                $actualTimeOut = null;
            }
            
            // Set reservation reason and other_reason
            $reservationReason = $reservationReasons[array_rand($reservationReasons)];
            $otherReason = null;
            
            // If "Others" is selected, add custom text (max 20 chars as per form validation)
            if ($reservationReason === 'Others') {
                $otherReason = $otherReasons[array_rand($otherReasons)];
            }
            
            $reservations[] = [
                'user_id' => $userId,
                'service_id' => $serviceId,
                'closure_period_id' => null,
                'reference_no' => 'REF-2025-' . str_pad($referenceCounter++, 6, '0', STR_PAD_LEFT),
                'reservation_date' => $reservationDate,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'actual_time_in' => $actualTimeIn,
                'actual_time_out' => $actualTimeOut,
                'units_reserved' => 1,
                'status' => $status,
                'cancellation_reason' => $cancellationReason,
                'suspension_applied' => $suspensionApplied,
                'cancelled_at' => $cancelledAt,
                'cancelled_by' => $cancelledBy,
                'preferences' => null,
                'reservation_reason' => $reservationReason,
                'other_reason' => $otherReason,
                'created_at' => $reservationDate->copy()->subDays(rand(1, 14)),
                'updated_at' => $status === 'completed' ? $reservationDate->copy()->addHour() : $cancelledAt,
            ];
            
            // Insert in batches of 100 to avoid memory issues
            if (count($reservations) >= 100) {
                Reservation::insert($reservations);
                $reservations = [];
            }
        }
        
        // Insert remaining reservations
        if (!empty($reservations)) {
            Reservation::insert($reservations);
        }
        
        $this->command->info('1000 reservations seeded successfully for 2025!');
        $this->command->info('- 600 completed reservations (past dates)');
        $this->command->info('- 250 cancelled reservations (past dates)');
        $this->command->info('- 150 pending reservations (Dec 15-31, 2025)');
    }
}
