@extends('layouts.admin_panel')

@section('title', 'Reservations')

@section('content')

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
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by ID, reference, resident, service, or status..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <!-- Date Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <!-- Sort By -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                    <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="id" {{ request('sort')=='id'?'selected':'' }}>ID</option>
                        <option value="reference_no" {{ request('sort')=='reference_no'?'selected':'' }}>Reference</option>
                        <option value="resident" {{ request('sort')=='resident'?'selected':'' }}>Resident</option>
                        <option value="service" {{ request('sort')=='service'?'selected':'' }}>Service</option>
                        <option value="reservation_date" {{ request('sort')=='reservation_date'?'selected':'' }}>Date</option>
                        <option value="start_time" {{ request('sort')=='start_time'?'selected':'' }}>Start Time</option>
                        <option value="end_time" {{ request('sort')=='end_time'?'selected':'' }}>End Time</option>
                        <option value="status" {{ request('sort')=='status'?'selected':'' }}>Status</option>
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
                <a href="{{ route('reservation.dashboard') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition border border-gray-300">
                    Clear All
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Table Section -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    @if(($reservations ?? collect())->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Reference</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Resident</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Service</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Time</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Preferences</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($reservations as $res)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">#{{ $res->id }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="font-mono bg-gray-100 px-2 py-1 rounded text-gray-900">{{ $res->reference_no }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $res->user->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $res->service->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $res->reservation_date->format('M j, Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $res->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $res->end_time)->format('g:i A') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($res->status === 'cancelled')
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                Cancelled
                            </span>
                        @elseif($res->status === 'completed')
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                Completed
                            </span>
                        @else
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">
                                Pending
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($res->preferences)
                            <span class="text-gray-900">{{ Str::limit($res->preferences, 30) }}</span>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button type="button" 
                                title="View Details" 
                                data-id="{{ $res->id }}" 
                                data-ref="{{ $res->reference_no }}" 
                                data-resident="{{ $res->user->name }}"
                                data-service="{{ $res->service->name }}"
                                data-date="{{ $res->reservation_date->format('Y-m-d') }}" 
                                data-start="{{ substr($res->start_time,0,5) }}" 
                                data-end="{{ substr($res->end_time,0,5) }}" 
                                data-in="{{ $res->actual_time_in ? substr($res->actual_time_in,0,5) : '' }}" 
                                data-out="{{ $res->actual_time_out ? substr($res->actual_time_out,0,5) : '' }}" 
                                data-status="{{ $res->status }}"
                                data-preferences="{{ $res->preferences ?? 'None' }}"
                                class="btn-view px-3 py-1 text-blue-600 hover:text-blue-800 font-medium">
                            View
                        </button>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        @if(request('q') || request('date'))
            <p class="text-gray-600 mb-4">No results found for your current filters.</p>
            <a href="{{ route('reservation.dashboard') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Clear Filters
            </a>
        @else
            <p class="text-gray-600">No reservations to display.</p>
        @endif
    </div>
    @endif
</div>

<!-- Pagination -->
@if(($reservations ?? collect())->count() > 0)
<div class="mt-6">
    {{ $reservations->links() }}
</div>
@endif

<!-- Modal -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Reservation Details</h3>
                <button id="modalClose" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-4">
            <!-- Basic Info -->
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">Reference:</span>
                    <p id="m_ref" class="font-semibold text-gray-900 mt-1">—</p>
                </div>
                <div>
                    <span class="text-gray-600">Status:</span>
                    <p id="m_status" class="font-semibold text-gray-900 mt-1">—</p>
                </div>
                <div>
                    <span class="text-gray-600">Resident:</span>
                    <p id="m_resident" class="font-semibold text-gray-900 mt-1">—</p>
                </div>
                <div>
                    <span class="text-gray-600">Service:</span>
                    <p id="m_service" class="font-semibold text-gray-900 mt-1">—</p>
                </div>
                <div>
                    <span class="text-gray-600">Date:</span>
                    <p id="m_date" class="font-semibold text-gray-900 mt-1">—</p>
                </div>
                <div>
                    <span class="text-gray-600">Booked Time:</span>
                    <p id="m_time" class="font-semibold text-gray-900 mt-1">—</p>
                </div>
            </div>

            <!-- Preferences -->
            <div class="text-sm">
                <span class="text-gray-600">Preferences:</span>
                <p id="m_preferences" class="text-gray-900 mt-1">—</p>
            </div>

            <hr class="border-gray-200">

            <!-- Time Recording Form -->
            <form id="timesForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-900">Record Actual Time</h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Time In</label>
                            <input type="time" name="actual_time_in" id="m_time_in" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Time Out</label>
                            <input type="time" name="actual_time_out" id="m_time_out" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>

                    <!-- Info Note -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <p class="text-xs text-blue-800">
                            <strong>Save & Close:</strong> Stores the time(s) as a draft.<br>
                            <strong>Submit:</strong> Requires Time Out and marks as completed.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div id="actionsRow" class="flex justify-end gap-2">
                        <button type="submit" name="action" value="save" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 border border-gray-300">
                            Save & Close
                        </button>
                        <button type="submit" name="action" value="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Submit
                        </button>
                    </div>

                    <!-- Back Button (for locked reservations) -->
                    <div id="backRow" class="flex justify-end gap-2 hidden">
                        <button type="button" id="modalOnlyClose" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 border border-gray-300">
                            Back
                        </button>
                    </div>
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
    
    toggleBtn.addEventListener('click', function() {
        filtersContent.classList.toggle('hidden');
        toggleText.textContent = filtersContent.classList.contains('hidden') ? 'Show Filters' : 'Hide Filters';
    });

    // Modal Elements
    const modal = document.getElementById('viewModal');
    const close = document.getElementById('modalClose');
    const ref = document.getElementById('m_ref');
    const date = document.getElementById('m_date');
    const resident = document.getElementById('m_resident');
    const service = document.getElementById('m_service');
    const time = document.getElementById('m_time');
    const status = document.getElementById('m_status');
    const preferences = document.getElementById('m_preferences');
    const timeIn = document.getElementById('m_time_in');
    const timeOut = document.getElementById('m_time_out');
    const form = document.getElementById('timesForm');
    const actionsRow = document.getElementById('actionsRow');
    const backRow = document.getElementById('backRow');
    const onlyClose = document.getElementById('modalOnlyClose');

    // View Buttons
    document.querySelectorAll('.btn-view').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            ref.textContent = btn.dataset.ref;
            date.textContent = btn.dataset.date;
            resident.textContent = btn.dataset.resident;
            service.textContent = btn.dataset.service;
            time.textContent = `${btn.dataset.start} - ${btn.dataset.end}`;
            status.textContent = btn.dataset.status.charAt(0).toUpperCase() + btn.dataset.status.slice(1);
            preferences.textContent = btn.dataset.preferences;
            
            form.action = `{{ url('admin/reservations') }}/${id}/actual-times`;
            
            const isLocked = (btn.dataset.status === 'cancelled' || btn.dataset.status === 'completed');
            timeIn.disabled = isLocked;
            timeOut.disabled = isLocked;
            
            if (isLocked) {
                actionsRow.classList.add('hidden');
                backRow.classList.remove('hidden');
                timeIn.classList.add('bg-gray-100', 'cursor-not-allowed');
                timeOut.classList.add('bg-gray-100', 'cursor-not-allowed');
            } else {
                actionsRow.classList.remove('hidden');
                backRow.classList.add('hidden');
                timeIn.classList.remove('bg-gray-100', 'cursor-not-allowed');
                timeOut.classList.remove('bg-gray-100', 'cursor-not-allowed');
            }
            
            // Pre-fill existing draft values
            timeIn.value = btn.dataset.in || '';
            timeOut.value = btn.dataset.out || '';
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    // Close Modal Functions
    function hideModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    close.addEventListener('click', hideModal);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) hideModal();
    });
    
    if (onlyClose) {
        onlyClose.addEventListener('click', hideModal);
    }
});
</script>
@endsection