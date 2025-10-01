@extends('layouts.admin_panel')

@section('title', 'Services')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">Services</h2>
        <form method="GET" class="flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name/description/capacity/active" class="border rounded px-3 py-2" />
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Search</button>
        </form>
    </div>

    @if(session('status'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
    @endif

    <p class="text-sm text-gray-600 mb-3">Add services and set how many units are available.</p>

    <form action="{{ route('admin.services.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        @csrf
        <input type="text" name="name" placeholder="Service Name" class="border rounded px-3 py-2" required />
        <input type="number" name="capacity_units" placeholder="Quantity" min="1" class="border rounded px-3 py-2" required />
        <input type="hidden" name="description" value="" />
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Add Service</button>
    </form>

    <div class="overflow-x-auto">
    <table class="min-w-full border rounded">
        <thead class="bg-gray-100">
            <tr>
                <th class="text-left px-3 py-2 border">Name</th>
                <th class="text-left px-3 py-2 border">Capacity</th>
                <th class="text-left px-3 py-2 border">Active</th>
                <th class="text-left px-3 py-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($services as $service)
            <tr class="border">
                <td class="px-3 py-2 border">{{ $service->name }}</td>
                <td class="px-3 py-2 border">{{ $service->capacity_units }}</td>
                <td class="px-3 py-2 border">{{ $service->is_active ? 'Yes' : 'No' }}</td>
                <td class="px-3 py-2 border">
                    <button data-open-edit="{{ $service->id }}" class="bg-yellow-600 text-white px-3 py-1 rounded">Edit</button>
                    <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Archive this service?')">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-600 text-white px-3 py-1 rounded">Archive</button>
                    </form>
                </td>
            </tr>

            <!-- Edit Modal -->
            <div id="edit-modal-{{ $service->id }}" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center">
                <div class="bg-white rounded shadow-lg w-full max-w-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">Edit Service</h3>
                        <button data-close-edit="{{ $service->id }}" class="text-gray-500 hover:text-gray-700">✕</button>
                    </div>
                    <form action="{{ route('admin.services.update', $service) }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm font-medium mb-1">Name</label>
                            <input type="text" name="name" value="{{ $service->name }}" class="border rounded px-3 py-2 w-full" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Quantity</label>
                            <input type="number" name="capacity_units" value="{{ $service->capacity_units }}" min="1" class="border rounded px-3 py-2 w-full" required />
                        </div>
                        <input type="hidden" name="description" value="{{ $service->description }}" />
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ $service->is_active ? 'checked' : '' }} class="mr-2" /> Active
                        </label>
                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" data-close-edit="{{ $service->id }}" class="bg-gray-600 text-white px-4 py-2 rounded">Cancel</button>
                            <button class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
    </div>

    <div class="mt-4">{{ $services->links() }}</div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-open-edit]').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-open-edit');
                const modal = document.getElementById('edit-modal-' + id);
                if (modal) modal.classList.remove('hidden');
                if (modal) modal.classList.add('flex');
            });
        });
        document.querySelectorAll('[data-close-edit]').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-close-edit');
                const modal = document.getElementById('edit-modal-' + id);
                if (modal) modal.classList.add('hidden');
                if (modal) modal.classList.remove('flex');
            });
        });
    });
    </script>
</div>
@endsection


