<?php

namespace App\Http\Controllers;

use App\Models\ClosurePeriod;
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
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
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

        ClosurePeriod::create([
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'] ?? null,
            'is_full_day' => $isFullDay,
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
            'status' => $validated['status'] ?? 'pending',
        ]);

        return redirect()->route('admin.closure_periods.index')->with('status', 'Closure period added.');
    }

    public function update(Request $request, ClosurePeriod $closurePeriod)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
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

        $closurePeriod->update([
            'start_date' => $validated['start_date'] ?? $closurePeriod->start_date->toDateString(),
            'end_date' => $validated['end_date'] ?? $closurePeriod->end_date->toDateString(),
            'reason' => array_key_exists('reason', $validated) ? ($validated['reason'] ?? null) : $closurePeriod->reason,
            'is_full_day' => $isFullDay,
            'start_time' => $validated['start_time'] ?? $closurePeriod->start_time,
            'end_time' => $validated['end_time'] ?? $closurePeriod->end_time,
            'status' => $validated['status'] ?? $closurePeriod->status,
        ]);

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
}


