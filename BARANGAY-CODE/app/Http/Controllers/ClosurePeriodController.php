<?php

namespace App\Http\Controllers;

use App\Models\ClosurePeriod;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ClosurePeriodController extends Controller
{
    public function index(Request $request)
    {
        $allowedSorts = ['start_date', 'end_date', 'status', 'reason', 'created_at'];
        $sort = in_array($request->get('sort'), $allowedSorts) ? $request->get('sort') : 'start_date';
        $direction = $request->get('direction') === 'asc' ? 'asc' : 'desc';

        $items = ClosurePeriod::when($request->filled('q'), function($q) use ($request) {
                $term = $request->get('q');
                $q->where('reason', 'like', "%$term%")
                  ->orWhere('status', 'like', "%$term%")
                  ->orWhereDate('start_date', $term)
                  ->orWhereDate('end_date', $term);
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
            'is_full_day' => ['nullable', 'boolean'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'status' => ['nullable', 'in:pending,active'],
        ]);

        $isFullDay = (bool) ($validated['is_full_day'] ?? true);
        if (!$isFullDay) {
            if (empty($validated['start_time']) || empty($validated['end_time']) || $validated['start_time'] >= $validated['end_time']) {
                return back()->withErrors(['start_time' => 'Provide a valid time range.']);
            }
        } else {
            $validated['start_time'] = null;
            $validated['end_time'] = null;
        }

        $closurePeriod = ClosurePeriod::create([
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'] ?? null,
            'is_full_day' => $isFullDay,
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
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
            'is_full_day' => ['nullable', 'boolean'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
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
        $isFullDay = (bool) ($validated['is_full_day'] ?? $closurePeriod->is_full_day ?? true);
        if (!$isFullDay) {
            if (empty($validated['start_time']) || empty($validated['end_time']) || $validated['start_time'] >= $validated['end_time']) {
                return back()->withErrors(['start_time' => 'Provide a valid time range.']);
            }
        } else {
            $validated['start_time'] = null;
            $validated['end_time'] = null;
        }

        $oldStatus = $closurePeriod->status;
        $newStatus = $validated['status'] ?? $closurePeriod->status;

        $closurePeriod->update([
            'start_date' => $validated['start_date'] ?? $closurePeriod->start_date->toDateString(),
            'end_date' => $validated['end_date'] ?? $closurePeriod->end_date->toDateString(),
            'reason' => array_key_exists('reason', $validated) ? ($validated['reason'] ?? null) : $closurePeriod->reason,
            'is_full_day' => $isFullDay,
            'start_time' => $validated['start_time'] ?? $closurePeriod->start_time,
            'end_time' => $validated['end_time'] ?? $closurePeriod->end_time,
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

    public function archives(Request $request)
    {
        $items = ClosurePeriod::onlyTrashed()
            ->when($request->filled('q'), function($q) use ($request) {
                $term = $request->get('q');
                $q->where('reason', 'like', "%$term%")
                  ->orWhere('status', 'like', "%$term%")
                  ->orWhereDate('start_date', $term)
                  ->orWhereDate('end_date', $term);
            })
            ->orderByDesc('deleted_at')
            ->paginate(6)
            ->withQueryString();
        return view('admin.closure_periods_archives', compact('items'));
    }

    public function restore($id)
    {
        $item = ClosurePeriod::onlyTrashed()->findOrFail($id);
        $item->restore();
        return redirect()->route('admin.closure_periods.archives')->with('status', 'Closure period unarchived.');
    }

    public function closedDatesApi(Request $request)
    {
        $start = $request->date('start', now()->startOfMonth());
        $end = $request->date('end', now()->addMonths(2)->endOfMonth());

        $periods = ClosurePeriod::active()
            ->whereDate('end_date', '>=', $start)
            ->whereDate('start_date', '<=', $end)
            ->get(['start_date','end_date','is_full_day','start_time','end_time','reason']);

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
        $query = Reservation::where('status', '!=', 'cancelled')
            ->where('status', '!=', 'completed')
            ->whereBetween('reservation_date', [$closurePeriod->start_date, $closurePeriod->end_date]);

        if ($closurePeriod->is_full_day) {
            // Full day closure: cancel all reservations on these dates
            return $query->update(['status' => 'cancelled']);
        } else {
            // Partial day closure: only cancel reservations that overlap with the time range
            $closureStart = $closurePeriod->start_time ?? '00:00';
            $closureEnd = $closurePeriod->end_time ?? '23:59';
            
            $reservations = $query->get();
            $cancelledCount = 0;
            
            foreach ($reservations as $reservation) {
                $resStart = $reservation->start_time;
                $resEnd = $reservation->end_time;
                
                // Check if time ranges overlap
                // Overlap occurs when: resStart < closureEnd AND resEnd > closureStart
                if ($resStart < $closureEnd && $resEnd > $closureStart) {
                    $reservation->update(['status' => 'cancelled']);
                    $cancelledCount++;
                }
            }
            
            return $cancelledCount;
        }
    }
}


