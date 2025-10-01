<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Service;
use App\Models\ClosurePeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReservationController extends Controller
{
    public function residentIndex(Request $request)
    {
        $now = now();

        // Auto-complete: mark past pending reservations as completed (not cancelled)
        Reservation::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->where(function ($q) use ($now) {
                $q->whereDate('reservation_date', '<', $now->toDateString())
                  ->orWhere(function ($qq) use ($now) {
                      $qq->whereDate('reservation_date', $now->toDateString())
                         ->where('end_time', '<', $now->format('H:i:s'));
                  });
            })
            ->update(['status' => 'completed']);

        $allowedSorts = ['reservation_date', 'reference_no', 'status'];
        $sort = in_array($request->get('sort'), $allowedSorts) ? $request->get('sort') : 'reservation_date';
        $direction = $request->get('direction') === 'asc' ? 'asc' : 'desc';

        $items = Reservation::with('service')
            ->where('user_id', Auth::id())
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->get('q');
                $q->where('reference_no', 'like', "%$term%")
                  ->orWhereHas('service', fn($sq) => $sq->where('name', 'like', "%$term%"));
            })
            ->when($request->filled('date'), fn($q) => $q->whereDate('reservation_date', $request->date))
            ->orderBy($sort, $direction)
            ->orderBy('start_time', $direction === 'asc' ? 'asc' : 'desc')
            ->paginate(6)
            ->withQueryString();

        return view('resident.resident_reservation', compact('items', 'sort', 'direction'));
    }

    public function index(Request $request)
    {
        $sort = $request->get('sort', 'reservation_date');
        $direction = $request->get('direction', 'asc');

        $sortable = [
            'id' => 'reservations.id',
            'reference_no' => 'reservations.reference_no',
            'resident' => 'users.name',
            'service' => 'services.name',
            'reservation_date' => 'reservations.reservation_date',
            'start_time' => 'reservations.start_time',
            'end_time' => 'reservations.end_time',
            'status' => 'reservations.status',
        ];
        $orderBy = $sortable[$sort] ?? 'reservations.reservation_date';

        $reservations = Reservation::query()
            ->leftJoin('users', 'users.id', '=', 'reservations.user_id')
            ->leftJoin('services', 'services.id', '=', 'reservations.service_id')
            ->select('reservations.*')
            ->with(['user', 'service'])
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->get('q');
                $q->where(function($w) use ($term) {
                    $w->where('reservations.reference_no', 'like', "%$term%")
                      ->orWhere('reservations.status', 'like', "%$term%")
                      ->orWhere('reservations.id', $term)
                      ->orWhere('users.name', 'like', "%$term%")
                      ->orWhere('services.name', 'like', "%$term%")
                      ->orWhereDate('reservations.reservation_date', $term)
                      ->orWhere('reservations.start_time', 'like', "%$term%")
                      ->orWhere('reservations.end_time', 'like', "%$term%");
                });
            })
            ->when($request->filled('date'), fn($q) => $q->whereDate('reservations.reservation_date', $request->date))
            ->orderBy($orderBy, $direction)
            ->paginate(6)
            ->withQueryString();

        return view('admin.reservation', compact('reservations'));
    }

    public function residentAvailable(Request $request)
    {
        $validated = $request->validate([
            'reservation_date' => ['required', 'date'],
        ]);

        $date = $validated['reservation_date'];

        // If date is within any active closure period, return no services
        $hasAnyClosure = \App\Models\ClosurePeriod::active()
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->exists();
        if ($hasAnyClosure) {
            return response()->json(['services' => []]);
        }

        $services = Service::query()->where('is_active', true)->get()
            ->map(function (Service $service) use ($date) {
                $usedUnits = Reservation::query()
                    ->where('service_id', $service->id)
                    ->whereDate('reservation_date', $date)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->sum('units_reserved');

                $remaining = max(0, $service->capacity_units - $usedUnits);
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'description' => $service->description,
                    'capacity_units' => $service->capacity_units,
                    'remaining_units' => $remaining,
                ];
            })
            ->filter(fn($s) => $s['remaining_units'] > 0)
            ->values();

        // Fallback: if none available (e.g., no time overlap logic, or new day), show active services
        if ($services->isEmpty()) {
            $fallback = Service::query()->where('is_active', true)->orderBy('name')->get()
                ->map(fn(Service $svc) => [
                    'id' => $svc->id,
                    'name' => $svc->name,
                    'description' => $svc->description,
                    'capacity_units' => $svc->capacity_units,
                    'remaining_units' => $svc->capacity_units,
                ]);
            return response()->json(['services' => $fallback]);
        }

        return response()->json(['services' => $services]);
    }

    public function activeServices()
    {
        $services = Service::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id','name','description','capacity_units']);
        return response()->json(['services' => $services]);
    }

    public function fullyBookedDates(Request $request)
    {
        $start = $request->date('start', now()->startOfMonth());
        $end = $request->date('end', now()->addMonths(2)->endOfMonth());

        $services = Service::where('is_active', true)->get(['id', 'capacity_units']);
        if ($services->isEmpty()) {
            return response()->json(['dates' => []]);
        }

        $capacityByService = $services->pluck('capacity_units', 'id');
        $dates = [];

        $current = $start->clone();
        while ($current->lte($end)) {
            $date = $current->toDateString();

            $isFullyBooked = true;
            foreach ($services as $service) {
                $totalUnits = (int) Reservation::where('service_id', $service->id)
                    ->whereDate('reservation_date', $date)
                    ->whereIn('status', ['pending','confirmed'])
                    ->sum('units_reserved');
                if ($totalUnits < $service->capacity_units) {
                    $isFullyBooked = false;
                    break;
                }
            }

            if ($isFullyBooked) {
                $dates[] = $date;
            }

            $current->addDay();
        }

        return response()->json(['dates' => $dates]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => ['required', Rule::exists('services', 'id')->where('is_active', true)],
            'reservation_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'preferences' => ['nullable', 'string', 'max:2000'],
            'terms' => ['accepted'],
        ]);

        $startTime = $validated['start_time'] ?? '08:00';
        $endTime = $validated['end_time'] ?? '17:00';
        if ($startTime >= $endTime) {
            abort(422, 'End time must be after start time.');
        }
        $this->validateBusinessHours($startTime, $endTime);
        $this->assertNotClosed($validated['reservation_date'], $startTime, $endTime);
        $this->assertOncePerDay(Auth::id(), $validated['reservation_date']);
        $this->assertCapacity($validated['service_id'], $validated['reservation_date'], $startTime, $endTime, 1);

        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'service_id' => $validated['service_id'],
            'reference_no' => $this->generateReference(),
            'reservation_date' => $validated['reservation_date'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'units_reserved' => 1,
            'preferences' => $request->get('preferences'),
            'status' => 'pending',
        ]);

        return redirect()->route('resident.reservation.ticket', $reservation->id)
            ->with('status', 'Reservation submitted. Reference: '.$reservation->reference_no);
    }

    public function edit($id)
    {
        $reservation = Reservation::where('user_id', Auth::id())->findOrFail($id);
        $this->assertModifiable($reservation);
        $services = Service::where('is_active', true)->orderBy('name')->get();
        return view('resident.edit_reservation', compact('reservation', 'services'));
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::where('user_id', Auth::id())->findOrFail($id);
        $this->assertModifiable($reservation);

        $validated = $request->validate([
            'service_id' => ['required', Rule::exists('services', 'id')->where('is_active', true)],
            'reservation_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'preferences' => ['nullable', 'string', 'max:2000'],
        ]);

        $startTime = $validated['start_time'] ?? '08:00';
        $endTime = $validated['end_time'] ?? '17:00';
        if ($startTime >= $endTime) {
            abort(422, 'End time must be after start time.');
        }
        $this->validateBusinessHours($startTime, $endTime);
        $this->assertNotClosed($validated['reservation_date'], $startTime, $endTime);
        $this->assertCapacity($validated['service_id'], $validated['reservation_date'], $startTime, $endTime, 1, $reservation->id);

        $reservation->update([
            'service_id' => $validated['service_id'],
            'reservation_date' => $validated['reservation_date'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'preferences' => $validated['preferences'] ?? null,
        ]);
        return redirect()->route('resident.reservation')->with('status', 'Reservation updated');
    }

    public function destroy($id)
    {
        $reservation = Reservation::where('user_id', Auth::id())->findOrFail($id);
        $this->assertModifiable($reservation);
        $reservation->update(['status' => 'cancelled']);
        return redirect()->route('resident.reservation')->with('status', 'Reservation cancelled');
    }

    public function ticket($id)
    {
        $reservation = Reservation::with('service')->where('user_id', Auth::id())->findOrFail($id);
        return view('resident.ticket', compact('reservation'));
    }

    public function history()
    {
        $reservations = Reservation::with('service')
            ->where('user_id', Auth::id())
            ->orderByDesc('reservation_date')
            ->orderByDesc('start_time')
            ->get();

        return view('resident.booking_history', compact('reservations'));
    }

    private function validateBusinessHours(string $start, string $end): void
    {
        if ($start < '08:00' || $end > '17:00') {
            abort(422, 'Reservations are allowed only between 08:00 and 17:00.');
        }
    }

    private function assertOncePerDay(int $userId, string $date): void
    {
        $exists = Reservation::where('user_id', $userId)
            ->whereDate('reservation_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();
        if ($exists) {
            abort(422, 'You already have a reservation for this date.');
        }
    }

    private function assertCapacity(int $serviceId, string $date, string $start, string $end, int $requestedUnits, ?int $ignoreReservationId = null): void
    {
        $service = Service::findOrFail($serviceId);

        $usedUnitsQuery = Reservation::query()
            ->where('service_id', $serviceId)
            ->whereDate('reservation_date', $date)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_time', [$start, $end])
                  ->orWhereBetween('end_time', [$start, $end])
                  ->orWhere(function ($o) use ($start, $end) {
                      $o->where('start_time', '<=', $start)
                        ->where('end_time', '>=', $end);
                  });
            })
            ->whereIn('status', ['pending', 'confirmed']);

        if ($ignoreReservationId) {
            $usedUnitsQuery->where('id', '!=', $ignoreReservationId);
        }

        $usedUnits = (int) $usedUnitsQuery->sum('units_reserved');

        if (($usedUnits + $requestedUnits) > $service->capacity_units) {
            abort(422, 'Not enough capacity for the selected time.');
        }
    }

    private function assertModifiable(Reservation $reservation): void
    {
        if (in_array($reservation->status, ['cancelled', 'completed'])) {
            abort(422, 'Reservation cannot be modified.');
        }

        if ($reservation->created_at->diffInMinutes(now()) > 15) {
            abort(422, 'You can modify or cancel only within 15 minutes of booking.');
        }
    }

    private function generateReference(): string
    {
        do {
            $ref = 'RSV-'.now()->format('Ymd').'-'.strtoupper(bin2hex(random_bytes(3)));
        } while (Reservation::where('reference_no', $ref)->exists());

        return $ref;
    }

    private function assertNotClosed(string $date, string $start, string $end): void
    {
        $periods = ClosurePeriod::active()
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->get();

        foreach ($periods as $p) {
            if ($p->is_full_day) {
                abort(422, 'The selected date is closed.');
            }
            $pStart = $p->start_time ?? '00:00';
            $pEnd = $p->end_time ?? '23:59';
            // If time overlaps with a partial-day closure
            $overlap = !($end <= $pStart || $start >= $pEnd);
            if ($overlap) {
                abort(422, 'The selected time falls within a closure period.');
            }
        }
    }
}
