<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceArchive;
use App\Models\Reservation;
use App\Services\ServiceArchiveService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $allowedSorts = ['name', 'capacity_units', 'is_active', 'created_at'];
        $sort = in_array($request->get('sort'), $allowedSorts) ? $request->get('sort') : 'name';
        $direction = $request->get('direction') === 'asc' ? 'asc' : 'desc';

        $services = Service::query()
            ->when($request->filled('q'), function($q) use ($request) {
                $term = $request->get('q');
                $q->where(function($query) use ($term) {
                    $query->where('name', 'like', "%$term%")
                          ->orWhere('description', 'like', "%$term%")
                          ->orWhere('capacity_units', 'like', "%$term%")
                          ->orWhere('is_active', 'like', "%$term%");
                });
            })
            ->when($request->boolean('active_only'), fn($q) => $q->where('is_active', true))
            ->orderBy($sort, $direction)
            ->paginate(6)
            ->withQueryString();

        return view('admin.services', compact('services', 'sort', 'direction'));
    }

    public function archives(Request $request)
    {
        $services = Service::onlyTrashed()
            ->when($request->filled('q'), function($q) use ($request) {
                $term = $request->get('q');
                $q->where('name', 'like', "%$term%")
                  ->orWhere('description', 'like', "%$term%")
                  ->orWhere('capacity_units', 'like', "%$term%");
            })
            ->orderBy('name')
            ->paginate(6)
            ->withQueryString();
        return view('admin.services_archives', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9\s\-]+$/', Rule::unique('services', 'name')],
            'description' => ['nullable', 'string', 'max:2000'],
            'capacity_units' => ['required', 'integer', 'min:1'],
        ], [
            'name.max' => 'Service name cannot exceed 50 characters.',
            'name.regex' => 'Service name can only contain letters, numbers, spaces, and hyphens. Special characters are not allowed.',
        ]);

        $service = Service::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'capacity_units' => (int) $validated['capacity_units'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->back()->with('status', 'Service created');
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9\s\-]+$/', Rule::unique('services', 'name')->ignore($service->id)],
            'description' => ['nullable', 'string', 'max:2000'],
            'capacity_units' => ['required', 'integer', 'min:' . $service->capacity_units],
        ], [
            'name.max' => 'Service name cannot exceed 50 characters.',
            'name.regex' => 'Service name can only contain letters, numbers, spaces, and hyphens. Special characters are not allowed.',
            'capacity_units.min' => 'Capacity units cannot be decreased. Current capacity is ' . $service->capacity_units . ' units. To reduce capacity, use the Archive Units feature.',
        ]);

        $isBecomingInactive = !$request->boolean('is_active') && $service->is_active;
        $cancelledCount = 0;

        // If service is being set to inactive, cancel all future reservations
        if ($isBecomingInactive) {
            $cancelledCount = Reservation::where('service_id', $service->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('reservation_date', '>=', Carbon::today())
                ->count();

            Reservation::where('service_id', $service->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('reservation_date', '>=', Carbon::today())
                ->each(function ($reservation) {
                    $reservation->cancelWithReason(
                        'Service has been deactivated.',
                        false, // No suspension for system-initiated cancellations
                        Auth::id() // Record the admin who deactivated the service
                    );
                });
        }

        $service->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'capacity_units' => (int) $validated['capacity_units'],
            'is_active' => $request->boolean('is_active'),
        ]);

        $message = 'Service updated';
        if ($isBecomingInactive && $cancelledCount > 0) {
            $message .= " - $cancelledCount future reservation(s) cancelled";
        }

        return redirect()->back()->with('status', $message);
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->back()->with('status', 'Service archived');
    }

    public function archiveUnits(Request $request, Service $service)
    {
        $validated = $request->validate([
            'units_to_archive' => ['required', 'integer', 'min:1', 'max:' . $service->capacity_units - 1],
            'reason' => ['required', 'string', 'max:1000'],
        ], [
            'units_to_archive.max' => 'Cannot archive more than ' . ($service->capacity_units - 1) . ' units. Minimum capacity must be 1.',
        ]);

        try {
            $archiveService = new ServiceArchiveService();
            $result = $archiveService->archiveUnits(
                $service,
                (int) $validated['units_to_archive'],
                $validated['reason']
            );

            return redirect()->back()->with([
                'status' => 'Service units archived successfully',
                'archive_result' => $result,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['archive_error' => $e->getMessage()]);
        }
    }

    public function restore($id)
    {
        $service = Service::onlyTrashed()->findOrFail($id);
        $service->restore();
        return redirect()->back()->with('status', 'Service unarchived');
    }

    public function restoreArchiveUnits(ServiceArchive $serviceArchive)
    {
        $service = $serviceArchive->service;
        
        if (!$service) {
            return redirect()->back()->withErrors(['error' => 'Service not found']);
        }

        // Restore the capacity by adding back the archived units
        $service->capacity_units += $serviceArchive->units_archived;
        $service->save();

        // Delete the archive record
        $serviceArchive->delete();

        return redirect()->back()->with('status', "Service units restored successfully. Capacity increased by {$serviceArchive->units_archived} units.");
    }
}


