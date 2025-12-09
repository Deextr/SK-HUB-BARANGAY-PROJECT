<?php

namespace App\Http\Controllers;

use App\Models\ClosurePeriod;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClosurePeriodController extends Controller
{
    public function index(Request $request)
    {
        $requestedSort = $request->get('sort', 'start_date');
        $direction = strtolower($request->get('direction', 'desc'));
        $direction = in_array($direction, ['asc', 'desc'], true) ? $direction : 'desc';
        
        $allowedSorts = ['start_date', 'end_date', 'status', 'reason', 'created_at'];
        $sort = in_array($requestedSort, $allowedSorts) ? $requestedSort : 'start_date';

        $items = ClosurePeriod::when($request->filled('q'), function($q) use ($request) {
                $term = trim($request->get('q'));
                $like = "%{$term}%";
                $isDate = preg_match('/^\d{4}-\d{2}-\d{2}$/', $term) === 1;
                
                $q->where(function($w) use ($like, $term, $isDate) {
                    $w->where('reason', 'like', $like)
                      ->orWhere('status', 'like', $like);
                      
                    if ($isDate) {
                        $w->orWhereDate('start_date', $term)
                          ->orWhereDate('end_date', $term);
                    }
                });
            })
            ->orderBy($sort, $direction)
            ->paginate(6)
            ->withQueryString();
            
        return view('admin.closure_periods', compact('items', 'sort', 'direction'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'start_date' => ['required', 'date', 'after_or_equal:today'],
                'end_date' => ['required', 'date', 'after_or_equal:today', 'after_or_equal:start_date'],
                'reason' => ['nullable', 'string', 'max:200'],
                'status' => ['nullable', 'in:pending,active'],
            ], [
                'reason.max' => 'The reason field cannot exceed 200 characters.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        // Ensure all required fields are present (defensive check)
        if (!array_key_exists('start_date', $validated) || !array_key_exists('end_date', $validated)) {
            return back()->withErrors(['error' => 'Start date and end date are required.'])->withInput();
        }

        // Check for duplicate or overlapping closure periods
        $overlapError = $this->checkForOverlaps(
            $validated['start_date'],
            $validated['end_date']
        );
        
        if ($overlapError) {
            return back()->withErrors(['start_date' => $overlapError])->withInput();
        }

        $closurePeriod = ClosurePeriod::create([
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'reason' => $validated['reason'] ?? null,
            'status' => $validated['status'] ?? 'pending',
        ]);

        // Cancel overlapping reservations if status is active
        if (($validated['status'] ?? 'pending') === 'active') {
            $cancelledCount = $this->cancelOverlappingReservations($closurePeriod);
            if ($cancelledCount > 0) {
                return redirect()->route('admin.closure_periods.index')
                    ->with('status', "Closure period added. {$cancelledCount} reservation(s) automatically cancelled.");
            }
        }

        return redirect()->route('admin.closure_periods.index')->with('status', 'Closure period added.');
    }

    public function update(Request $request, ClosurePeriod $closurePeriod)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date', 'after_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after_or_equal:today', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string', 'max:200'],
            'status' => ['nullable', 'in:pending,active'],
        ], [
            'reason.max' => 'The reason field cannot exceed 200 characters.',
        ]);

        // If currently active, disallow any edits (including status changes)
        if ($closurePeriod->status === 'active') {
            return redirect()->route('admin.closure_periods.index')->with('status', 'Active closure periods cannot be edited.');
        }

        // Validate start_date if provided
        $startDate = $validated['start_date'] ?? $closurePeriod->start_date->toDateString();
        if (strtotime($startDate) < strtotime('today')) {
            return back()->withErrors(['start_date' => 'Start date cannot be in the past.']);
        }

        // Validate end_date if provided
        $endDate = $validated['end_date'] ?? $closurePeriod->end_date->toDateString();
        if (strtotime($endDate) < strtotime('today')) {
            return back()->withErrors(['end_date' => 'End date cannot be in the past.']);
        }

        // Pending: allow full edits
        $oldStatus = $closurePeriod->status;
        $newStatus = $validated['status'] ?? $closurePeriod->status;

        // Check if trying to activate a closure period with past dates
        if ($oldStatus !== 'active' && $newStatus === 'active') {
            $startDateObj = (isset($validated['start_date']) && $validated['start_date']) 
                ? \Carbon\Carbon::parse($validated['start_date']) 
                : $closurePeriod->start_date;
            $endDateObj = (isset($validated['end_date']) && $validated['end_date']) 
                ? \Carbon\Carbon::parse($validated['end_date']) 
                : $closurePeriod->end_date;
            
            if ($endDateObj->isPast()) {
                return back()->withErrors(['status' => 'Cannot activate a closure period that has already ended. Please update the dates to future dates.']);
            }
        }

        // Check for duplicate or overlapping closure periods (excluding current record)
        $newStartDate = $validated['start_date'] ?? $closurePeriod->start_date->toDateString();
        $newEndDate = $validated['end_date'] ?? $closurePeriod->end_date->toDateString();
        
        $overlapError = $this->checkForOverlaps(
            $newStartDate,
            $newEndDate,
            $closurePeriod->id // Exclude current record from check
        );
        
        if ($overlapError) {
            return back()->withErrors(['start_date' => $overlapError])->withInput();
        }

        $closurePeriod->update([
            'start_date' => $validated['start_date'] ?? $closurePeriod->start_date->toDateString(),
            'end_date' => $validated['end_date'] ?? $closurePeriod->end_date->toDateString(),
            'reason' => array_key_exists('reason', $validated) ? ($validated['reason'] ?? null) : $closurePeriod->reason,
            'status' => $newStatus,
        ]);

        // Cancel overlapping reservations if status changed to active
        if ($oldStatus !== 'active' && $newStatus === 'active') {
            $cancelledCount = $this->cancelOverlappingReservations($closurePeriod);
            if ($cancelledCount > 0) {
                return redirect()->route('admin.closure_periods.index')
                    ->with('status', "Closure period updated. {$cancelledCount} reservation(s) automatically cancelled.");
            }
        }

        return redirect()->route('admin.closure_periods.index')->with('status', 'Closure period updated.');
    }

    public function destroy(ClosurePeriod $closurePeriod)
    {
        // If closure period is active, only allow archiving after the end date has passed
        if ($closurePeriod->status === 'active') {
            $today = now()->startOfDay();
            $endDate = $closurePeriod->end_date->startOfDay();
            
            if ($endDate->isFuture() || $endDate->isToday()) {
                return redirect()->route('admin.closure_periods.index')
                    ->withErrors(['error' => 'Active closure periods can only be archived after the end date has passed.']);
            }
        }
        
        $closurePeriod->delete();
        return redirect()->route('admin.closure_periods.index')->with('status', 'Closure period archived.');
    }

    public function restore($id)
    {
        $item = ClosurePeriod::onlyTrashed()->findOrFail($id);
        $item->restore();
        return redirect()->route('admin.archives', ['tab' => 'closures'])->with('status', 'Closure period unarchived.');
    }

    public function closedDatesApi(Request $request)
    {
        $start = $request->date('start', now()->startOfMonth());
        $end = $request->date('end', now()->addMonths(2)->endOfMonth());

        $periods = ClosurePeriod::active()
            ->whereDate('end_date', '>=', $start)
            ->whereDate('start_date', '<=', $end)
            ->get(['start_date','end_date','reason']);

        $dates = [];
        foreach ($periods as $p) {
            $cursor = $p->start_date->clone();
            while ($cursor->lte($p->end_date)) {
                $dates[] = $cursor->toDateString();
                $cursor->addDay();
            }
        }

        $dates = array_values(array_unique($dates));
        sort($dates);

        return response()->json(['dates' => $dates, 'periods' => $periods]);
    }

    /**
     * Check if a closure period overlaps with any existing closure period
     * 
     * @param string $startDate Start date (Y-m-d format)
     * @param string $endDate End date (Y-m-d format)
     * @param int|null $excludeId ID of closure period to exclude from check (for updates)
     * @return string|null Error message if overlap found, null otherwise
     */
    private function checkForOverlaps(string $startDate, string $endDate, ?int $excludeId = null): ?string
    {
        $startDateObj = \Carbon\Carbon::parse($startDate)->startOfDay();
        $endDateObj = \Carbon\Carbon::parse($endDate)->startOfDay();

        // Query existing closure periods (excluding soft-deleted records)
        $query = ClosurePeriod::where(function($q) use ($startDateObj, $endDateObj) {
            // Overlap condition: New.Start <= Existing.End AND New.End >= Existing.Start
            $q->where(function($subQ) use ($startDateObj, $endDateObj) {
                $subQ->where('start_date', '<=', $endDateObj->toDateString())
                     ->where('end_date', '>=', $startDateObj->toDateString());
            });
        });

        // Exclude current record if updating
        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        $overlapping = $query->first();

        if ($overlapping) {
            // Check if it's an exact duplicate
            $existingStart = $overlapping->start_date->startOfDay();
            $existingEnd = $overlapping->end_date->startOfDay();
            
            if ($startDateObj->equalTo($existingStart) && $endDateObj->equalTo($existingEnd)) {
                return 'Cannot create closure period. The selected dates are identical to an existing closure period.';
            }
            
            // It's an overlap
            return 'Cannot create closure period. The selected dates overlap with an existing closure period.';
        }

        return null;
    }

    /**
     * Cancel all reservations that overlap with the closure period
     * 
     * @param ClosurePeriod $closurePeriod
     * @return int Number of reservations cancelled
     */
    private function cancelOverlappingReservations(ClosurePeriod $closurePeriod)
    {
        $reservations = Reservation::whereNotIn('status', ['cancelled', 'completed'])
            ->whereBetween('reservation_date', [$closurePeriod->start_date, $closurePeriod->end_date])
            ->get();

        $cancelledCount = 0;
        foreach ($reservations as $reservation) {
            $reservation->cancelWithReason(
                'Cancelled due to facility closure period.',
                false, // No suspension for system-initiated cancellations
                Auth::id() // Record the admin who created/activated the closure period
            );
            $cancelledCount++;
        }

        return $cancelledCount;
    }
}


