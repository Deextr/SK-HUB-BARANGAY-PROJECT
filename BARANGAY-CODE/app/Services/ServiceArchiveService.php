<?php

namespace App\Services;

use App\Models\Service;
use App\Models\ServiceArchive;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ServiceArchiveService
{
    /**
     * Archive units from a service and handle overbooked reservations
     *
     * @param Service $service
     * @param int $unitsToArchive
     * @param string $reason
     * @return array Result with summary of archival and cancellations
     */
    public function archiveUnits(Service $service, int $unitsToArchive, string $reason): array
    {
        return DB::transaction(function () use ($service, $unitsToArchive, $reason) {
            $capacityBefore = $service->capacity_units;
            $capacityAfter = $capacityBefore - $unitsToArchive;

            // Validate capacity
            if ($capacityAfter < 1) {
                throw new \Exception('Cannot archive more units than available. Minimum capacity must be 1.');
            }

            // Find and cancel overbooked reservations
            $cancelledReservations = $this->cancelOverbookedReservations(
                $service,
                $capacityAfter
            );

            // Update service capacity
            $service->update(['capacity_units' => $capacityAfter]);

            // Record the archival
            $archive = ServiceArchive::create([
                'service_id' => $service->id,
                'units_archived' => $unitsToArchive,
                'capacity_before' => $capacityBefore,
                'capacity_after' => $capacityAfter,
                'reason' => $reason,
                'reservations_cancelled' => count($cancelledReservations),
                'cancelled_reservation_ids' => array_keys($cancelledReservations),
            ]);

            return [
                'success' => true,
                'service_id' => $service->id,
                'service_name' => $service->name,
                'units_archived' => $unitsToArchive,
                'capacity_before' => $capacityBefore,
                'capacity_after' => $capacityAfter,
                'reason' => $reason,
                'reservations_cancelled' => count($cancelledReservations),
                'cancelled_reservations' => $cancelledReservations,
                'archive_id' => $archive->id,
            ];
        });
    }

    /**
     * Find and cancel overbooked reservations
     * Uses fair cancellation: cancel newest reservations first (latest created_at)
     *
     * @param Service $service
     * @param int $newCapacity
     * @return array Cancelled reservations with details
     */
    private function cancelOverbookedReservations(Service $service, int $newCapacity): array
    {
        $cancelledReservations = [];

        // Get all future pending/confirmed reservations for this service
        $futureReservations = Reservation::where('service_id', $service->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('reservation_date', '>=', Carbon::today())
            ->orderBy('reservation_date')
            ->orderBy('start_time')
            ->get();

        // Group reservations by date and time slot
        $timeSlots = $this->groupReservationsByTimeSlot($futureReservations);

        // Process each time slot
        foreach ($timeSlots as $slotKey => $reservations) {
            $bookedCount = count($reservations);

            // If overbooked, cancel the newest ones
            if ($bookedCount > $newCapacity) {
                $toCancel = $bookedCount - $newCapacity;

                // Sort by created_at DESC to get newest first
                $sorted = collect($reservations)
                    ->sortByDesc('created_at')
                    ->take($toCancel);

                foreach ($sorted as $reservation) {
                    $reservation->cancelWithReason(
                        'Service capacity reduced due to unit archival. Cancelled to maintain fair booking.',
                        false // No suspension for system-initiated cancellations
                    );

                    $cancelledReservations[$reservation->id] = [
                        'id' => $reservation->id,
                        'reference_no' => $reservation->reference_no,
                        'user_name' => $reservation->user->name ?? 'Unknown',
                        'reservation_date' => $reservation->reservation_date->format('Y-m-d'),
                        'start_time' => $reservation->start_time,
                        'end_time' => $reservation->end_time,
                        'created_at' => $reservation->created_at->format('Y-m-d H:i:s'),
                    ];
                }
            }
        }

        return $cancelledReservations;
    }

    /**
     * Group reservations by date and overlapping time slots
     * Detects overlapping reservations, not just exact time matches
     *
     * @param \Illuminate\Database\Eloquent\Collection $reservations
     * @return array Grouped by overlapping time slots
     */
    private function groupReservationsByTimeSlot($reservations): array
    {
        $slots = [];
        $slotIndex = 0;

        foreach ($reservations as $reservation) {
            $found = false;

            // Check if this reservation overlaps with any existing slot
            foreach ($slots as $index => $slotReservations) {
                if ($this->hasTimeOverlap($reservation, $slotReservations)) {
                    $slots[$index][] = $reservation;
                    $found = true;
                    break;
                }
            }

            // If no overlap found, create a new slot
            if (!$found) {
                $slots[$slotIndex] = [$reservation];
                $slotIndex++;
            }
        }

        return $slots;
    }

    /**
     * Check if a reservation overlaps with any reservation in a group
     *
     * @param Reservation $reservation
     * @param array $slotReservations
     * @return bool
     */
    private function hasTimeOverlap($reservation, $slotReservations): bool
    {
        foreach ($slotReservations as $existing) {
            // Must be same date
            if ($reservation->reservation_date->format('Y-m-d') !== $existing->reservation_date->format('Y-m-d')) {
                continue;
            }

            // Convert times to comparable format (HH:MM:SS)
            $newStart = strtotime($reservation->start_time);
            $newEnd = strtotime($reservation->end_time);
            $existingStart = strtotime($existing->start_time);
            $existingEnd = strtotime($existing->end_time);

            // Check for overlap: new starts before existing ends AND new ends after existing starts
            if ($newStart < $existingEnd && $newEnd > $existingStart) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get archival history for a service
     *
     * @param Service $service
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getArchiveHistory(Service $service)
    {
        return ServiceArchive::where('service_id', $service->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
