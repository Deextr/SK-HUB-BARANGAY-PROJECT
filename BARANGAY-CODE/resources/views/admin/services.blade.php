@extends('layouts.admin_panel')

@section('title', 'Services')

@section('content')

@if(session('status'))
    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg border border-green-300">
        <div class="font-semibold">{{ session('status') }}</div>
        @if(session('archive_result'))
            @php $result = session('archive_result'); @endphp
            <div class="mt-3 text-sm space-y-1">
                <p><strong>Service:</strong> {{ $result['service_name'] }}</p>
                <p><strong>Units Archived:</strong> {{ $result['units_archived'] }}</p>
                <p><strong>Capacity:</strong> {{ $result['capacity_before'] }} → {{ $result['capacity_after'] }}</p>
                <p><strong>Reason:</strong> {{ $result['reason'] }}</p>
                <p><strong>Reservations Cancelled:</strong> {{ $result['reservations_cancelled'] }}</p>
                @if($result['reservations_cancelled'] > 0)
                    <details class="mt-2">
                        <summary class="cursor-pointer font-semibold">View Cancelled Reservations</summary>
                        <div class="mt-2 pl-4 border-l-2 border-green-300 space-y-1">
                            @foreach($result['cancelled_reservations'] as $cancelled)
                                <p class="text-xs">
                                    <strong>{{ $cancelled['reference_no'] }}</strong> - 
                                    {{ $cancelled['user_name'] }} on 
                                    {{ $cancelled['reservation_date'] }} 
                                    {{ $cancelled['start_time'] }}-{{ $cancelled['end_time'] }}
                                </p>
                            @endforeach
                        </div>
                    </details>
                @endif
            </div>
        @endif
    </div>
@endif

@if($errors->any())
    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg border border-red-300">
        <div class="font-semibold mb-2">Errors:</div>
        <ul class="list-disc pl-5 space-y-1">
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
                <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition font-medium">
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
        <button id="btnOpenCreate" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition font-medium">
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
                            <span class="text-green-600 font-medium">Active</span>
                        @else
                            <span class="text-gray-600 font-medium">Inactive</span>
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
                            <button type="button" 
                                    title="Archive"
                                    data-id="{{ $service->id }}"
                                    data-name="{{ $service->name }}"
                                    data-capacity="{{ $service->capacity_units }}"
                                    class="btn-archive-units px-2 py-2 text-amber-600 hover:text-amber-800 font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                </svg>
                            </button>
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
                    <input type="text" name="name" maxlength="50" pattern="^[a-zA-Z0-9\s\-]+$" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" placeholder="Enter service name" required />
                    <p class="text-xs text-gray-500 mt-1">Maximum 50 characters. Only letters, numbers, spaces, and hyphens allowed.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity (Capacity Units) <span class="text-red-500">*</span></label>
                    <input type="number" name="capacity_units" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" placeholder="Enter quantity" required />
                    <p class="text-xs text-gray-500 mt-1">Maximum number of units available for this service.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" placeholder="Enter service description (optional)"></textarea>
                </div>

                <div class="flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" id="create_is_active" checked class="rounded border-gray-300 text-yellow-500 focus:ring-yellow-500" />
                    <label for="create_is_active" class="ml-2 text-sm text-gray-700">Active</label>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
                    <button type="button" id="btnCancelCreate" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 border border-gray-300">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 font-medium">
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
            <!-- Error Message Display -->
            <div id="editErrorMessage" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg hidden">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm text-red-800">
                        <strong>Error:</strong> <span id="editErrorText"></span>
                    </div>
                </div>
            </div>

            <form id="editForm" method="POST" action="#" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit_name" maxlength="50" pattern="^[a-zA-Z0-9\s\-]+$" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" placeholder="Enter service name" required />
                    <p class="text-xs text-gray-500 mt-1">Maximum 50 characters. Only letters, numbers, spaces, and hyphens allowed.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity (Capacity Units) <span class="text-red-500">*</span></label>
                    <input type="number" name="capacity_units" id="edit_capacity" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" placeholder="Enter quantity" required />
                    <p class="text-xs text-gray-500 mt-1" id="capacityHelpText">Maximum number of units available for this service.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="edit_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" placeholder="Enter service description (optional)"></textarea>
                </div>

                <div class="flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" id="edit_is_active" class="rounded border-gray-300 text-yellow-500 focus:ring-yellow-500" />
                    <label for="edit_is_active" class="ml-2 text-sm text-gray-700">Active</label>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
                    <button type="button" id="btnCancelEdit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 border border-gray-300">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 font-medium">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Archive Modal (Combined: Partial Units + Full Service) -->
<div id="archiveUnitsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Archive Service</h3>
                <button id="btnCloseArchiveUnits" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <!-- Archive Type Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Archive Type <span class="text-red-500">*</span></label>
                <div class="space-y-3">
                    <!-- Partial Archive Option -->
                    <label class="flex items-start p-4 border-2 border-yellow-300 rounded-lg cursor-pointer hover:bg-yellow-50 transition" id="partialArchiveLabel">
                        <input type="radio" name="archive_type" value="partial" id="archiveTypePartial" class="mt-1 w-4 h-4 text-yellow-500 focus:ring-yellow-500" checked />
                        <div class="ml-3">
                            <p class="font-semibold text-gray-900">Archive Selected Units</p>
                            <p class="text-xs text-gray-600 mt-1">Archive specific number of units. Overbooked reservations will be automatically cancelled (newest first).</p>
                        </div>
                    </label>

                    <!-- Full Archive Option -->
                    <label class="flex items-start p-4 border-2 border-red-300 rounded-lg cursor-pointer hover:bg-red-50 transition" id="fullArchiveLabel">
                        <input type="radio" name="archive_type" value="full" id="archiveTypeFull" class="mt-1 w-4 h-4 text-red-500 focus:ring-red-500" />
                        <div class="ml-3">
                            <p class="font-semibold text-gray-900">Archive Entire Service</p>
                            <p class="text-xs text-gray-600 mt-1">Archive the entire service. All future reservations will be cancelled.</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Warning Message -->
            <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm text-amber-800">
                        <strong id="warningTitle">Important:</strong> <span id="warningText">Archiving units may cancel reservations that exceed the new capacity. Cancelled reservations will be those most recently created (newest bookings).</span>
                    </div>
                </div>
            </div>

            <form id="archiveUnitsForm" method="POST" action="#" class="space-y-5">
                @csrf
                <input type="hidden" name="archive_type" id="archiveTypeInput" value="partial" />
                
                <!-- Partial Archive Section -->
                <div id="partialArchiveSection">
                    <!-- Current Capacity Display -->
                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <p class="text-sm text-gray-700">
                            <strong>Current Capacity:</strong> <span id="currentCapacity" class="text-lg font-semibold text-blue-600">0</span> units
                        </p>
                    </div>

                    <!-- Units to Archive -->
                    <div class="mt-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Number of Units to Archive <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <input type="number" 
                                   name="units_to_archive" 
                                   id="unitsToArchive" 
                                   min="1" 
                                   max="1"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" 
                                   placeholder="Enter number of units" />
                            <span class="text-sm text-gray-600 whitespace-nowrap">Max: <span id="maxUnits">0</span></span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Minimum capacity must remain at least 1 unit.</p>
                    </div>

                    <!-- New Capacity Preview -->
                    <div class="p-4 bg-green-50 rounded-lg border border-green-200 mt-5">
                        <p class="text-sm text-gray-700">
                            <strong>New Capacity:</strong> <span id="newCapacity" class="text-lg font-semibold text-green-600">0</span> units
                        </p>
                    </div>
                </div>

                <!-- Reason for Archiving -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Reason for Archiving <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" 
                              id="archiveReason"
                              rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" 
                              placeholder="e.g., Under maintenance, Damaged unit, Scheduled service check"
                              required></textarea>
                    <p class="text-xs text-gray-500 mt-1">Provide a clear reason for the archival.</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
                    <button type="button" id="btnCancelArchiveUnits" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 border border-gray-300 font-medium">
                        Cancel
                    </button>
                    <button type="submit" id="archiveSubmitBtn" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 font-medium transition">
                        Archive Units
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
    const archiveUnitsModal = document.getElementById('archiveUnitsModal');
    const btnOpenCreate = document.getElementById('btnOpenCreate');
    const btnCloseCreate = document.getElementById('btnCloseCreate');
    const btnCancelCreate = document.getElementById('btnCancelCreate');
    const btnCloseEdit = document.getElementById('btnCloseEdit');
    const btnCancelEdit = document.getElementById('btnCancelEdit');
    const btnCloseArchiveUnits = document.getElementById('btnCloseArchiveUnits');
    const btnCancelArchiveUnits = document.getElementById('btnCancelArchiveUnits');
    const editForm = document.getElementById('editForm');
    const archiveUnitsForm = document.getElementById('archiveUnitsForm');

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
    let currentServiceCapacity = null;
    
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            const description = btn.dataset.description || '';
            const capacity = btn.dataset.capacity;
            const active = btn.dataset.active === '1';

            // Store current capacity for validation
            currentServiceCapacity = parseInt(capacity);

            // Set form action
            const actionTemplate = `{{ route('admin.services.update', ['service' => '__ID__']) }}`;
            editForm.action = actionTemplate.replace('__ID__', id);

            // Populate form fields
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_capacity').value = capacity;
            document.getElementById('edit_capacity').min = capacity;
            document.getElementById('edit_is_active').checked = active;

            // Update help text with current capacity
            document.getElementById('capacityHelpText').textContent = `Maximum number of units available for this service. Current: ${capacity} units. You can only increase or keep the same capacity.`;

            // Clear error message
            hideEditError();

            show(editModal);
        });
    });

    // Real-time validation for capacity field
    document.getElementById('edit_capacity')?.addEventListener('input', function() {
        const newCapacity = parseInt(this.value);
        const errorDiv = document.getElementById('editErrorMessage');
        const errorText = document.getElementById('editErrorText');

        if (newCapacity < currentServiceCapacity) {
            errorText.textContent = `Capacity units cannot be decreased. Current capacity is ${currentServiceCapacity} units. To reduce capacity, use the Archive Units feature.`;
            errorDiv.classList.remove('hidden');
            this.classList.add('border-red-500', 'border-2');
            this.classList.remove('border-gray-300');
        } else {
            hideEditError();
            this.classList.remove('border-red-500', 'border-2');
            this.classList.add('border-gray-300');
        }
    });

    function hideEditError() {
        document.getElementById('editErrorMessage').classList.add('hidden');
    }

    // Form submission validation
    editForm?.addEventListener('submit', function(e) {
        const newCapacity = parseInt(document.getElementById('edit_capacity').value);
        
        if (newCapacity < currentServiceCapacity) {
            e.preventDefault();
            const errorDiv = document.getElementById('editErrorMessage');
            const errorText = document.getElementById('editErrorText');
            errorText.textContent = `Capacity units cannot be decreased. Current capacity is ${currentServiceCapacity} units. To reduce capacity, use the Archive Units feature.`;
            errorDiv.classList.remove('hidden');
            document.getElementById('edit_capacity').classList.add('border-red-500', 'border-2');
            document.getElementById('edit_capacity').classList.remove('border-gray-300');
        }
    });

    btnCloseEdit?.addEventListener('click', () => hide(editModal));
    btnCancelEdit?.addEventListener('click', () => hide(editModal));
    
    editModal?.addEventListener('click', (e) => {
        if (e.target === editModal) hide(editModal);
    });

    // Archive Modal - Combined Partial + Full
    let currentServiceId = null;
    
    document.querySelectorAll('.btn-archive-units').forEach(btn => {
        btn.addEventListener('click', () => {
            currentServiceId = btn.dataset.id;
            const name = btn.dataset.name;
            const capacity = parseInt(btn.dataset.capacity);

            // Set form action for partial archive
            const actionTemplate = `{{ route('admin.services.archive_units', ['service' => '__ID__']) }}`;
            archiveUnitsForm.action = actionTemplate.replace('__ID__', currentServiceId);

            // Update capacity display
            document.getElementById('currentCapacity').textContent = capacity;
            const maxArchive = capacity - 1;
            document.getElementById('maxUnits').textContent = maxArchive;
            
            // Reset form
            document.getElementById('unitsToArchive').value = '';
            document.getElementById('unitsToArchive').max = maxArchive;
            document.getElementById('archiveReason').value = '';
            document.getElementById('newCapacity').textContent = capacity;
            document.getElementById('archiveTypePartial').checked = true;
            document.getElementById('archiveTypeInput').value = 'partial';
            
            // Reset UI
            updateArchiveTypeUI('partial');
            show(archiveUnitsModal);
        });
    });

    // Archive Type Toggle
    document.getElementById('archiveTypePartial')?.addEventListener('change', function() {
        if (this.checked) {
            updateArchiveTypeUI('partial');
        }
    });

    document.getElementById('archiveTypeFull')?.addEventListener('change', function() {
        if (this.checked) {
            updateArchiveTypeUI('full');
        }
    });

    function updateArchiveTypeUI(type) {
        const partialSection = document.getElementById('partialArchiveSection');
        const unitsInput = document.getElementById('unitsToArchive');
        const submitBtn = document.getElementById('archiveSubmitBtn');
        const warningText = document.getElementById('warningText');
        const archiveTypeInput = document.getElementById('archiveTypeInput');

        if (type === 'partial') {
            partialSection.style.display = 'block';
            unitsInput.required = true;
            submitBtn.textContent = 'Archive Units';
            submitBtn.className = 'px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 font-medium transition';
            warningText.textContent = 'Archiving units may cancel reservations that exceed the new capacity. Cancelled reservations will be those most recently created (newest bookings).';
            archiveTypeInput.value = 'partial';
        } else {
            partialSection.style.display = 'none';
            unitsInput.required = false;
            submitBtn.textContent = 'Archive Entire Service';
            submitBtn.className = 'px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 font-medium transition';
            warningText.textContent = 'Archiving the entire service will permanently remove it and cancel all future reservations. This action cannot be undone.';
            archiveTypeInput.value = 'full';
        }
    }

    // Update new capacity preview
    document.getElementById('unitsToArchive')?.addEventListener('input', function() {
        const current = parseInt(document.getElementById('currentCapacity').textContent);
        const toArchive = parseInt(this.value) || 0;
        const newCapacity = current - toArchive;
        document.getElementById('newCapacity').textContent = newCapacity >= 0 ? newCapacity : 0;
    });

    // Form submission
    archiveUnitsForm?.addEventListener('submit', function(e) {
        const archiveType = document.getElementById('archiveTypeInput').value;
        
        if (archiveType === 'full') {
            // For full archive, redirect to destroy endpoint
            e.preventDefault();
            if (confirm('Archive this entire service? This cannot be undone.')) {
                const destroyForm = document.createElement('form');
                destroyForm.method = 'POST';
                destroyForm.action = `{{ route('admin.services.destroy', ['service' => '__ID__']) }}`.replace('__ID__', currentServiceId);
                destroyForm.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(destroyForm);
                destroyForm.submit();
            }
        }
        // For partial, form submits normally
    });

    btnCloseArchiveUnits?.addEventListener('click', () => hide(archiveUnitsModal));
    btnCancelArchiveUnits?.addEventListener('click', () => hide(archiveUnitsModal));
    
    archiveUnitsModal?.addEventListener('click', (e) => {
        if (e.target === archiveUnitsModal) hide(archiveUnitsModal);
    });
});
</script>
@endsection
