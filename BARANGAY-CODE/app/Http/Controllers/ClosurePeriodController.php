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
        $validated = $request->validate([
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:today', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:pending,active'],
        ]);

        $closurePeriod = ClosurePeriod::create([
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
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
            'reason' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:pending,active'],
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
            $startDateObj = $validated['start_date'] ? \Carbon\Carbon::parse($validated['start_date']) : $closurePeriod->start_date;
            $endDateObj = $validated['end_date'] ? \Carbon\Carbon::parse($validated['end_date']) : $closurePeriod->end_date;
            
            if ($endDateObj->isPast()) {
                return back()->withErrors(['status' => 'Cannot activate a closure period that has already ended. Please update the dates to future dates.']);
            }
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


