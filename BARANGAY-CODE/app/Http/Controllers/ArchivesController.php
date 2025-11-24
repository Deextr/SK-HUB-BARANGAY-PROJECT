<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ClosurePeriod;
use App\Models\User;
use App\Models\ServiceArchive;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ArchivesController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'services');
        $sort = 'created_at';
        $direction = 'desc';

        if ($tab === 'closures') {
            // Determine sort column and direction for closures
            $allowedSorts = ['start_date', 'end_date', 'reason', 'status', 'deleted_at'];
            $sort = in_array($request->get('sort'), $allowedSorts) ? $request->get('sort') : 'deleted_at';
            $direction = $request->get('direction') === 'asc' ? 'asc' : 'desc';

            // Build query with search and filtering
            $closures = ClosurePeriod::onlyTrashed()
                ->when($request->filled('q'), function($q) use ($request) {
                    $term = $request->get('q');
                    $q->where(function($w) use ($term) {
                        $w->where('reason', 'like', "%$term%")
                          ->orWhere('status', 'like', "%$term%")
                          ->orWhereDate('start_date', $term)
                          ->orWhereDate('end_date', $term);
                    });
                })
                ->orderBy($sort, $direction)
                ->paginate(6)
                ->withQueryString();

            $services = collect();
            $serviceArchives = collect();
            $users = collect();
            $serviceRows = null;
        } elseif ($tab === 'users') {
            // Determine sort column and direction for users
            $allowedSorts = ['full_name', 'email', 'archive_reason', 'archived_at'];
            $sort = in_array($request->get('sort'), $allowedSorts) ? $request->get('sort') : 'archived_at';
            $direction = $request->get('direction') === 'asc' ? 'asc' : 'desc';

            // Build query with search and filtering
            $users = User::where('is_archived', true)
                ->where('is_admin', false)
                ->when($request->filled('q'), function($q) use ($request) {
                    $term = $request->get('q');
                    $q->where(function($w) use ($term) {
                        $w->where('full_name', 'like', "%$term%")
                          ->orWhere('email', 'like', "%$term%")
                          ->orWhere('archive_reason', 'like', "%$term%");
                    });
                })
                ->orderBy($sort, $direction)
                ->paginate(6)
                ->withQueryString();

            $services = collect();
            $serviceArchives = collect();
            $closures = collect();
            $serviceRows = null;
        } else {
            // Determine sort column and direction for services
            $allowedSorts = ['service_id', 'units_archived', 'capacity_before', 'capacity_after', 'reason', 'created_at'];
            $sort = in_array($request->get('sort'), $allowedSorts) ? $request->get('sort') : 'created_at';
            $direction = $request->get('direction') === 'asc' ? 'asc' : 'desc';

            $searchTerm = trim((string) $request->get('q', ''));

            // Partial archives (service_archives table)
            $partialArchives = ServiceArchive::with('service')
                ->when($searchTerm !== '', function ($q) use ($searchTerm) {
                    $q->where(function ($w) use ($searchTerm) {
                        $w->whereHas('service', function ($s) use ($searchTerm) {
                            $s->where('name', 'like', "%{$searchTerm}%")
                              ->orWhere('description', 'like', "%{$searchTerm}%");
                        })
                        ->orWhere('reason', 'like', "%{$searchTerm}%")
                        ->orWhere('units_archived', 'like', "%{$searchTerm}%");
                    });
                })
                ->get();

            // Fully archived services (services table - soft deleted)
            $fullServices = Service::onlyTrashed()
                ->whereNotNull('deleted_at')
                ->when($searchTerm !== '', function ($q) use ($searchTerm) {
                    $q->where(function ($w) use ($searchTerm) {
                        $w->where('name', 'like', "%{$searchTerm}%")
                          ->orWhere('description', 'like', "%{$searchTerm}%");
                    });
                })
                ->get();

            // Build unified rows collection
            $rows = collect();

            foreach ($partialArchives as $archive) {
                $rows->push((object) [
                    'type' => 'partial',
                    'id' => $archive->id,
                    'service_name' => optional($archive->service)->name ?? 'Deleted Service',
                    'description' => optional($archive->service)->description,
                    'units_archived' => $archive->units_archived,
                    'capacity_before' => $archive->capacity_before,
                    'capacity_after' => $archive->capacity_after,
                    'reason' => $archive->reason ?? null,
                    'archived_at' => $archive->created_at,
                ]);
            }

            foreach ($fullServices as $svc) {
                $rows->push((object) [
                    'type' => 'full',
                    'id' => $svc->id,
                    'service_name' => $svc->name,
                    'description' => $svc->description,
                    'units_archived' => $svc->capacity_units, // for sorting
                    'capacity_before' => $svc->capacity_units,
                    'capacity_after' => 0,
                    'reason' => 'Service fully archived',
                    'archived_at' => $svc->deleted_at,
                ]);
            }

            // Map sort key to unified row fields
            switch ($sort) {
                case 'service_id':
                    $sortKey = 'service_name';
                    break;
                case 'units_archived':
                    $sortKey = 'units_archived';
                    break;
                case 'capacity_before':
                    $sortKey = 'capacity_before';
                    break;
                case 'capacity_after':
                    $sortKey = 'capacity_after';
                    break;
                case 'reason':
                    $sortKey = 'reason';
                    break;
                case 'created_at':
                default:
                    $sortKey = 'archived_at';
                    break;
            }

            $sorted = $rows->sortBy(
                $sortKey,
                SORT_NATURAL | SORT_FLAG_CASE,
                $direction === 'desc'
            )->values();

            // Manual pagination for unified collection
            $perPage = 6;
            $page = (int) $request->get('page', 1);
            $total = $sorted->count();
            $items = $sorted->slice(($page - 1) * $perPage, $perPage)->values();

            $serviceRows = new LengthAwarePaginator(
                $items,
                $total,
                $perPage,
                $page,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]
            );

            // Backward-compat variables (no longer used by view for services tab)
            $services = collect();
            $serviceArchives = collect();
            $closures = collect();
            $users = collect();
        }

        return view('admin.archives', compact('services', 'serviceArchives', 'closures', 'users', 'tab', 'sort', 'direction', 'serviceRows'));
    }
}
