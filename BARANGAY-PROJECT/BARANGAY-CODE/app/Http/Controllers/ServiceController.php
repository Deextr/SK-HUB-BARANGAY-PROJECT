<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::query()
            ->when($request->filled('q'), function($q) use ($request) {
                $term = $request->get('q');
                $q->where('name', 'like', "%$term%")
                  ->orWhere('description', 'like', "%$term%")
                  ->orWhere('capacity_units', 'like', "%$term%")
                  ->orWhere('is_active', 'like', "%$term%");
            })
            ->when($request->boolean('active_only'), fn($q) => $q->where('is_active', true))
            ->orderBy('name')
            ->paginate(6)
            ->withQueryString();

        return view('admin.services', compact('services'));
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
            'name' => ['required', 'string', 'max:255', Rule::unique('services', 'name')],
            'description' => ['nullable', 'string', 'max:2000'],
            'capacity_units' => ['required', 'integer', 'min:1'],
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
            'name' => ['required', 'string', 'max:255', Rule::unique('services', 'name')->ignore($service->id)],
            'description' => ['nullable', 'string', 'max:2000'],
            'capacity_units' => ['required', 'integer', 'min:1'],
        ]);

        $service->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'capacity_units' => (int) $validated['capacity_units'],
            'is_active' => $request->boolean('is_active'),
        ]);
        return redirect()->back()->with('status', 'Service updated');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->back()->with('status', 'Service archived');
    }

    public function restore($id)
    {
        $service = Service::onlyTrashed()->findOrFail($id);
        $service->restore();
        return redirect()->back()->with('status', 'Service unarchived');
    }
}


