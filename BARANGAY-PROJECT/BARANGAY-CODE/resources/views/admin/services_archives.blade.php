@extends('layouts.admin_panel')

@section('title', 'Service Archives')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">Archived Services</h2>
        <a href="{{ route('admin.services.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Back to Services</a>
    </div>

    @if(session('status'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
    @endif

    <div class="overflow-x-auto">
    <form method="GET" class="mb-4 flex gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name/description/quantity" class="border rounded px-3 py-2" />
        <button class="bg-gray-700 text-white px-4 py-2 rounded">Search</button>
    </form>
    @if(($services ?? collect())->count() > 0)
    <table class="min-w-full border rounded">
        <thead class="bg-gray-100">
            <tr>
                <th class="text-left px-3 py-2 border">Name</th>
                <th class="text-left px-3 py-2 border">Description</th>
                <th class="text-left px-3 py-2 border">Quantity</th>
                <th class="text-left px-3 py-2 border">Archived At</th>
                <th class="text-left px-3 py-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($services as $service)
            <tr class="border">
                <td class="px-3 py-2 border">{{ $service->name }}</td>
                <td class="px-3 py-2 border">{{ $service->description ?: '—' }}</td>
                <td class="px-3 py-2 border">{{ $service->capacity_units }}</td>
                <td class="px-3 py-2 border">{{ optional($service->deleted_at)->format('M j, Y g:i A') }}</td>
                <td class="px-3 py-2 border">
                    <form action="{{ route('admin.services.restore', $service->id) }}" method="POST" class="inline" onsubmit="return confirm('Unarchive this service?')">
                        @csrf
                        <button class="bg-green-600 text-white px-3 py-1 rounded">Unarchive</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td class="px-3 py-6 text-center text-gray-500" colspan="4">No archived services.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @else
    <div class="text-center py-10 bg-gray-50 rounded-lg">
        @if(request('q'))
            <p class="text-gray-600 mb-3">No archived services match your search.</p>
            <a href="{{ route('admin.services.archives') }}" class="inline-block bg-gray-600 text-white px-4 py-2 rounded">Clear Filters</a>
        @else
            <p class="text-gray-600">No archived services.</p>
        @endif
    </div>
    @endif
    </div>

    @if(($services ?? collect())->count() > 0)
        <div class="mt-4">{{ $services->links() }}</div>
    @endif
</div>
@endsection


