@extends('layouts.admin_panel')

@section('title', 'Services')

@section('content')

@if(session('status'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
@endif

@if($errors->any())
    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Filters Card -->
<div class="bg-white rounded-lg shadow mb-6">
    <!-- Header with Toggle -->
    <div class="px-5 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Filters & Sorting</h3>
            <button type="button" id="toggleFilters" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                <span id="toggleText">Hide Filters</span>
            </button>
        </div>
    </div>

    <!-- Filter Form -->
    <div id="filtersContent" class="px-5 py-4">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search Input -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name, description, or quantity..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <!-- Sort By -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                    <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="name" {{ request('sort')=='name'?'selected':'' }}>Name</option>
                        <option value="capacity_units" {{ request('sort')=='capacity_units'?'selected':'' }}>Quantity</option>
                        <option value="is_active" {{ request('sort')=='is_active'?'selected':'' }}>Status</option>
                        <option value="created_at" {{ request('sort')=='created_at'?'selected':'' }}>Created</option>
                    </select>
                </div>

                <!-- Sort Direction -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                    <select name="direction" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="asc" {{ request('direction')=='asc'?'selected':'' }}>Ascending</option>
                        <option value="desc" {{ request('direction')=='desc'?'selected':'' }}>Descending</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 pt-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Apply Filters
                </button>
                <a href="{{ route('admin.services.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition border border-gray-300">
                    Clear All
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Table Section -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-start">
        <button id="btnOpenCreate" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Add Service
        </button>
    </div>

    @if(($services ?? collect())->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($services as $service)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $service->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $service->description ?: '—' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $service->capacity_units }}</td>
                    <td class="px-6 py-4">
                        @if($service->is_active)
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                Active
                            </span>
                        @else
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">
                                Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button type="button" 
                                    title="Edit Service"
                                    data-id="{{ $service->id }}"
                                    data-name="{{ $service->name }}"
                                    data-description="{{ $service->description ?? '' }}"
                                    data-capacity="{{ $service->capacity_units }}"
                                    data-active="{{ $service->is_active ? '1' : '0' }}"
                                    class="btn-edit px-2 py-2 text-blue-600 hover:text-blue-800 font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="inline" onsubmit="return confirm('Archive this service?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Archive Service" class="px-2 py-2 text-red-600 hover:text-red-800 font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-12 px-4">
        <div class="text-gray-400 mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        @if(request('q'))
            <p class="text-gray-600 mb-4">No results found for your current filters.</p>
            <a href="{{ route('admin.services.index') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Clear Filters
            </a>
        @else
            <p class="text-gray-600">No services found.</p>
        @endif
    </div>
    @endif
</div>

<!-- Pagination -->
@if(($services ?? collect())->count() > 0)
<div class="mt-6">
    {{ $services->links() }}
</div>
@endif

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Add Service</h3>
                <button id="btnCloseCreate" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form id="createForm" method="POST" action="{{ route('admin.services.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter service name" required />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity (Capacity Units) <span class="text-red-500">*</span></label>
                    <input type="number" name="capacity_units" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter quantity" required />
                    <p class="text-xs text-gray-500 mt-1">Maximum number of units available for this service.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter service description (optional)"></textarea>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" id="create_is_active" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                    <label for="create_is_active" class="ml-2 text-sm text-gray-700">Active</label>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
                    <button type="button" id="btnCancelCreate" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 border border-gray-300">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Add Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Edit Service</h3>
                <button id="btnCloseEdit" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form id="editForm" method="POST" action="#" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter service name" required />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity (Capacity Units) <span class="text-red-500">*</span></label>
                    <input type="number" name="capacity_units" id="edit_capacity" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter quantity" required />
                    <p class="text-xs text-gray-500 mt-1">Maximum number of units available for this service.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="edit_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter service description (optional)"></textarea>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" id="edit_is_active" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                    <label for="edit_is_active" class="ml-2 text-sm text-gray-700">Active</label>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
                    <button type="button" id="btnCancelEdit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 border border-gray-300">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter Toggle
    const toggleBtn = document.getElementById('toggleFilters');
    const filtersContent = document.getElementById('filtersContent');
    const toggleText = document.getElementById('toggleText');
    
    toggleBtn?.addEventListener('click', function() {
        filtersContent.classList.toggle('hidden');
        toggleText.textContent = filtersContent.classList.contains('hidden') ? 'Show Filters' : 'Hide Filters';
    });

    // Modal Elements
    const createModal = document.getElementById('createModal');
    const editModal = document.getElementById('editModal');
    const btnOpenCreate = document.getElementById('btnOpenCreate');
    const btnCloseCreate = document.getElementById('btnCloseCreate');
    const btnCancelCreate = document.getElementById('btnCancelCreate');
    const btnCloseEdit = document.getElementById('btnCloseEdit');
    const btnCancelEdit = document.getElementById('btnCancelEdit');
    const editForm = document.getElementById('editForm');

    function show(el) { el.classList.remove('hidden'); el.classList.add('flex'); }
    function hide(el) { el.classList.add('hidden'); el.classList.remove('flex'); }

    // Create Modal
    btnOpenCreate?.addEventListener('click', () => show(createModal));
    btnCloseCreate?.addEventListener('click', () => hide(createModal));
    btnCancelCreate?.addEventListener('click', () => hide(createModal));
    
    createModal?.addEventListener('click', (e) => {
        if (e.target === createModal) hide(createModal);
    });

    // Edit Modal
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            const description = btn.dataset.description || '';
            const capacity = btn.dataset.capacity;
            const active = btn.dataset.active === '1';

            // Set form action
            const actionTemplate = `{{ route('admin.services.update', ['service' => '__ID__']) }}`;
            editForm.action = actionTemplate.replace('__ID__', id);

            // Populate form fields
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_capacity').value = capacity;
            document.getElementById('edit_is_active').checked = active;

            show(editModal);
        });
    });

    btnCloseEdit?.addEventListener('click', () => hide(editModal));
    btnCancelEdit?.addEventListener('click', () => hide(editModal));
    
    editModal?.addEventListener('click', (e) => {
        if (e.target === editModal) hide(editModal);
    });
});
</script>
@endsection
