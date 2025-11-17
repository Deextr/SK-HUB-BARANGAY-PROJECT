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
        @php
            $currentSort = $sort ?? request('sort');
            $currentDirection = $direction ?? request('direction');
        @endphp
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
                        <option value="id" {{ $currentSort=='id'?'selected':'' }}>ID</option>
                        <option value="reference_no" {{ $currentSort=='reference_no'?'selected':'' }}>Reference</option>
                        <option value="resident" {{ $currentSort=='resident'?'selected':'' }}>Resident</option>
                        <option value="service" {{ $currentSort=='service'?'selected':'' }}>Service</option>
                        <option value="reservation_date" {{ $currentSort=='reservation_date'?'selected':'' }}>Date</option>
                        <option value="start_time" {{ $currentSort=='start_time'?'selected':'' }}>Start Time</option>
                        <option value="end_time" {{ $currentSort=='end_time'?'selected':'' }}>End Time</option>
                        <option value="status" {{ $currentSort=='status'?'selected':'' }}>Status</option>
                    </select>
                </div>

                <!-- Sort Direction -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                    <select name="direction" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="asc" {{ $currentDirection=='asc'?'selected':'' }}>Ascending</option>
                        <option value="desc" {{ $currentDirection=='desc'?'selected':'' }}>Descending</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 pt-2">
                <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition font-medium">
                    Apply Filters
                </button>
                <a href="{{ route('reservation.dashboard') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition border border-gray-300">
                    Clear All
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabs Navigation -->
<div class="mb-4 border-b border-gray-200">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
        <li class="mr-2">
            <a href="{{ route('reservation.dashboard', ['tab' => 'all'] + request()->except(['tab', 'page'])) }}" class="inline-block p-4 {{ $tab === 'all' ? 'text-yellow-600 border-b-2 border-yellow-500' : 'text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300' }} rounded-t-lg">
                All Reservations
            </a>
        </li>
        <li class="mr-2">
            <a href="{{ route('reservation.dashboard', ['tab' => 'today'] + request()->except(['tab', 'page'])) }}" class="inline-block p-4 {{ $tab === 'today' ? 'text-yellow-600 border-b-2 border-yellow-500' : 'text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300' }} rounded-t-lg">
                Today's Reservations
                @if($todayCount > 0)
                    <span class="ml-2 bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $todayCount }}</span>
                @endif
            </a>
        </li>
    </ul>
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
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Preferences</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($reservations as $res)
                @php
                    $isUpcoming = $res->status === 'pending' && $res->isUpcoming(10);
                    $rowClass = $isUpcoming ? 'bg-red-50 hover:bg-red-100' : 'hover:bg-gray-50';
                @endphp
                <tr class="{{ $rowClass }} transition-colors duration-200" 
                    data-reservation-date="{{ $res->reservation_date->format('Y-m-d') }}" 
                    data-date="{{ $res->reservation_date->format('Y-m-d') }}" 
                    data-start="{{ substr($res->start_time,0,5) }}" 
                    data-status="{{ $res->status }}">
                    @if($isUpcoming)
                    <td colspan="10" class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium">
                        ⚠️ This reservation is starting within 10 minutes!
                    </td>
                </tr>
                <tr class="{{ $rowClass }} transition-colors duration-200" 
                    data-reservation-date="{{ $res->reservation_date->format('Y-m-d') }}" 
                    data-date="{{ $res->reservation_date->format('Y-m-d') }}" 
                    data-start="{{ substr($res->start_time,0,5) }}" 
                    data-status="{{ $res->status }}">
                    @endif
                    <td class="px-6 py-4 text-sm text-gray-900">#{{ $res->id }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="font-mono bg-gray-100 px-2 py-1 rounded text-gray-900">{{ $res->reference_no }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $res->user?->full_name ?? '—' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $res->service->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $res->reservation_date->format('M j, Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $res->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $res->end_time)->format('g:i A') }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($res->preferences)
                            <span class="text-gray-900">{{ Str::limit($res->preferences, 30) }}</span>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($res->status === 'cancelled')
                            <span class="text-red-600 font-medium">Cancelled</span>
                        @elseif($res->status === 'completed')
                            <span class="text-green-600 font-medium">Completed</span>
                        @else
                            <span class="text-amber-600 font-medium">Pending</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-2">
                            <!-- View Button -->
                            <button type="button" 
                                    title="View Details" 
                                    data-id="{{ $res->id }}" 
                                    data-ref="{{ $res->reference_no }}" 
                                    data-resident="{{ $res->user?->full_name ?? 'Unknown Resident' }}"
                                    data-service="{{ $res->service->name }}"
                                    data-date="{{ $res->reservation_date->format('Y-m-d') }}" 
                                    data-start="{{ substr($res->start_time,0,5) }}" 
                                    data-end="{{ substr($res->end_time,0,5) }}" 
                                    data-in="{{ $res->actual_time_in ? substr($res->actual_time_in,0,5) : '' }}" 
                                    data-out="{{ $res->actual_time_out ? substr($res->actual_time_out,0,5) : '' }}" 
                                    data-status="{{ $res->status }}"
                                    data-preferences="{{ $res->preferences ?? 'None' }}"
                                    data-reason="{{ $res->reservation_reason ?? 'None' }}"
                                    data-other-reason="{{ $res->other_reason ?? '' }}"
                                    data-upcoming="{{ $isUpcoming ? 'true' : 'false' }}"
                                    class="btn-view px-2 py-2 text-blue-600 hover:text-blue-800 font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                            
                            <!-- Cancel Button (only for pending reservations) -->
                            @if($res->status === 'pending')
                            <button type="button" 
                                    title="Cancel Reservation" 
                                    data-id="{{ $res->id }}" 
                                    data-ref="{{ $res->reference_no }}" 
                                    data-resident="{{ $res->user?->full_name ?? 'Unknown Resident' }}"
                                    data-date="{{ $res->reservation_date->format('M j, Y') }}"
                                    data-time="{{ \Carbon\Carbon::createFromFormat('H:i:s', $res->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $res->end_time)->format('g:i A') }}"
                                    data-upcoming="{{ $isUpcoming ? 'true' : 'false' }}"
                                    class="btn-cancel px-2 py-2 text-red-600 hover:text-red-800 font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                            @endif
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

<!-- View Modal -->
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

            <!-- Reason for Reservation -->
            <div class="text-sm">
                <span class="text-gray-600">Reason for Reservation:</span>
                <p id="m_reason" class="text-gray-900 mt-1">—</p>
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Time In <span class="text-red-500">*</span></label>
                            <input type="time" name="actual_time_in" id="m_time_in" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" />
                            <p id="timeInError" class="text-red-500 text-xs mt-1 hidden">Time In is required</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Time Out <span class="text-red-500">*</span></label>
                            <input type="time" name="actual_time_out" id="m_time_out" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" />
                            <p id="timeOutError" class="text-red-500 text-xs mt-1 hidden">Time Out is required and must be after Time In</p>
                        </div>
                    </div>

                    <!-- Info Note -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                        <p class="text-xs text-yellow-800">
                            <strong>Submit:</strong> Requires both Time In and Time Out, and marks the reservation as completed.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div id="actionsRow" class="flex justify-end gap-2">
                            <button type="submit" name="action" value="save" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 border border-gray-300">
                                Save & Close
                            </button>
                            <button type="submit" name="action" value="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                                Submit
                            </button>
                        </div>
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
    const reason = document.getElementById('m_reason');
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
            // Format time to include AM/PM
            const formatTimeWithAMPM = (timeStr) => {
                if (!timeStr) return '';
                const [hours, minutes] = timeStr.split(':').map(Number);
                const period = hours >= 12 ? 'PM' : 'AM';
                const displayHours = hours % 12 || 12; // Convert 0 to 12 for 12 AM
                return `${displayHours}:${minutes.toString().padStart(2, '0')} ${period}`;
            };
            
            const startTimeAMPM = formatTimeWithAMPM(btn.dataset.start);
            const endTimeAMPM = formatTimeWithAMPM(btn.dataset.end);
            time.textContent = `${startTimeAMPM} - ${endTimeAMPM}`;
            status.textContent = btn.dataset.status.charAt(0).toUpperCase() + btn.dataset.status.slice(1);
            
            // Set reservation reason
            if (btn.dataset.reason === 'Others' && btn.dataset.otherReason) {
                reason.textContent = `${btn.dataset.reason} (${btn.dataset.otherReason})`;
            } else {
                reason.textContent = btn.dataset.reason;
            }
            
            preferences.textContent = btn.dataset.preferences;
            
            form.action = `{{ url('admin/reservations') }}/${id}/actual-times`;
            
            const isLocked = (btn.dataset.status === 'cancelled' || btn.dataset.status === 'completed');
            timeIn.disabled = isLocked;
            timeOut.disabled = isLocked;
            
            // Cancel button removed from modal
            
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

    // Cancel button in modal removed

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
    
    // Time validation
    const timeInInput = document.getElementById('m_time_in');
    const timeOutInput = document.getElementById('m_time_out');
    const timeInError = document.getElementById('timeInError');
    const timeOutError = document.getElementById('timeOutError');
    
    // Add event listeners to time inputs
    timeInInput.addEventListener('change', function() {
        // When Time In changes, update the min attribute of Time Out
        if (timeInInput.value) {
            // Set minimum time out to be at least 1 minute after time in
            const timeInValue = timeInInput.value;
            const [hours, minutes] = timeInValue.split(':').map(Number);
            
            // Calculate new minimum (add 1 minute)
            let newMinutes = minutes + 1;
            let newHours = hours;
            
            if (newMinutes >= 60) {
                newMinutes = 0;
                newHours += 1;
                if (newHours >= 24) {
                    newHours = 23;
                    newMinutes = 59;
                }
            }
            
            // Format as HH:MM
            const minTimeOut = `${newHours.toString().padStart(2, '0')}:${newMinutes.toString().padStart(2, '0')}`;
            timeOutInput.min = minTimeOut;
            
            // If current Time Out is now invalid, clear it
            if (timeOutInput.value && timeOutInput.value <= timeInValue) {
                timeOutInput.value = '';
            }
        }
        
        validateTimes();
    });
    timeOutInput.addEventListener('change', validateTimes);
    
    // Validate form before submission
    form.addEventListener('submit', function(e) {
        const action = e.submitter.value;
        let isValid = true;
        
        // Reset validation state
        timeInError.classList.add('hidden');
        timeOutError.classList.add('hidden');
        timeInInput.classList.remove('border-red-500');
        timeOutInput.classList.remove('border-red-500');
        
        // For 'save' action, no validation needed - can save without any inputs
        if (action === 'save') {
            return true;
        }
        
        // For 'submit' action, require both Time In and Time Out
        if (action === 'submit') {
            // Time In is required for Submit action
            if (!timeInInput.value) {
                e.preventDefault();
                timeInError.classList.remove('hidden');
                timeInInput.classList.add('border-red-500');
                isValid = false;
            }
            
            // Time Out is required for Submit action
            if (!timeOutInput.value) {
                e.preventDefault();
                timeOutError.textContent = 'Time Out is required';
                timeOutError.classList.remove('hidden');
                timeOutInput.classList.add('border-red-500');
                isValid = false;
            } 
            // If both are filled, validate that Time Out is after Time In
            else if (timeInInput.value && timeOutInput.value && timeOutInput.value <= timeInInput.value) {
                e.preventDefault();
                timeOutError.textContent = 'Time Out must be after Time In';
                timeOutError.classList.remove('hidden');
                timeOutInput.classList.add('border-red-500');
                isValid = false;
            }
        }
        
        return isValid;
    });
    
    // Function to validate times
    function validateTimes() {
        // Reset validation state
        timeInError.classList.add('hidden');
        timeOutError.classList.add('hidden');
        timeInInput.classList.remove('border-red-500');
        timeOutInput.classList.remove('border-red-500');
        
        let isValid = true;
        
        // Only validate if both fields have values
        if (timeInInput.value && timeOutInput.value) {
            // Check if Time Out is after Time In
            if (timeOutInput.value <= timeInInput.value) {
                timeOutError.textContent = 'Time Out must be after Time In';
                timeOutError.classList.remove('hidden');
                timeOutInput.classList.add('border-red-500');
                isValid = false;
            }
        }
        
        return isValid;
    }
});
</script>

<!-- Include the Cancel Reservation Modal -->
@include('admin.partials.cancel_reservation_modal')

<script>
// Auto-refresh for real-time updates (only on Today's tab)
document.addEventListener('DOMContentLoaded', function() {
    // Only auto-refresh on Today's tab
    if ('{{ $tab }}' === 'today') {
        // Set up a timer to refresh the page every 60 seconds
        const refreshInterval = 60 * 1000; // 60 seconds
        setInterval(function() {
            // Refresh the page without losing current filters/sort
            window.location.reload();
        }, refreshInterval);
        
        // Update the countdown timer for upcoming reservations every second
        setInterval(updateUpcomingTimers, 1000);
    }
    
    function updateUpcomingTimers() {
        // Get all pending reservations
        document.querySelectorAll('tr[data-reservation-date]').forEach(row => {
            if (row.dataset.status !== 'pending') return;
            
            const reservationDate = row.dataset.date;
            const startTime = row.dataset.start;
            
            // Create a date object for the reservation time
            const [year, month, day] = reservationDate.split('-').map(Number);
            const [hours, minutes] = startTime.split(':').map(Number);
            const reservationDateTime = new Date(year, month - 1, day, hours, minutes);
            
            // Calculate time difference in minutes
            const now = new Date();
            const diffMs = reservationDateTime - now;
            const diffMinutes = Math.floor(diffMs / 60000);
            
            // If within 10 minutes and not already marked as upcoming
            if (diffMinutes > 0 && diffMinutes <= 10 && !row.classList.contains('bg-red-50')) {
                row.classList.add('bg-red-50', 'hover:bg-red-100');
                row.classList.remove('hover:bg-gray-50');
                
                // Add warning message if not already present
                if (!row.previousElementSibling || !row.previousElementSibling.classList.contains('warning-row')) {
                    const warningRow = document.createElement('tr');
                    warningRow.classList.add('warning-row', 'bg-red-100');
                    warningRow.innerHTML = `
                        <td colspan="10" class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium">
                            ⚠️ This reservation is starting within 10 minutes!
                        </td>
                    `;
                    row.parentNode.insertBefore(warningRow, row);
                }
            }
        });
    }
});

// Cancel Reservation Modal Functionality
document.addEventListener('DOMContentLoaded', function() {
    const cancelModal = document.getElementById('cancelModal');
    const cancelModalClose = document.getElementById('cancelModalClose');
    const cancelModalDismiss = document.getElementById('cancelModalDismiss');
    const cancelForm = document.getElementById('cancelReservationForm');
    const cancelRef = document.getElementById('cancel_ref');
    const cancelResident = document.getElementById('cancel_resident');
    const cancelDatetime = document.getElementById('cancel_datetime');
    const upcomingWarning = document.getElementById('upcomingWarning');
    
    // Cancel buttons
    document.querySelectorAll('.btn-cancel').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            cancelRef.textContent = btn.dataset.ref;
            cancelResident.textContent = btn.dataset.resident;
            cancelDatetime.textContent = `${btn.dataset.date} at ${btn.dataset.time}`;
            
            // Set the form action
            cancelForm.action = `{{ url('admin/reservations') }}/${id}/cancel`;
            
            // Show warning for upcoming reservations
            if (btn.dataset.upcoming === 'true') {
                upcomingWarning.classList.remove('hidden');
            } else {
                upcomingWarning.classList.add('hidden');
            }
            
            // Show modal
            cancelModal.classList.remove('hidden');
            cancelModal.classList.add('flex');
            
            // Focus on the reason textarea
            document.getElementById('cancellation_reason').focus();
        });
    });
    
    // Close modal functions
    function hideCancelModal() {
        cancelModal.classList.add('hidden');
        cancelModal.classList.remove('flex');
        document.getElementById('cancellation_reason').value = '';
        document.getElementById('apply_suspension').checked = false;
    }
    
    cancelModalClose.addEventListener('click', hideCancelModal);
    cancelModalDismiss.addEventListener('click', hideCancelModal);
    cancelModal.addEventListener('click', (e) => {
        if (e.target === cancelModal) hideCancelModal();
    });
    
    // Form validation
    cancelForm.addEventListener('submit', function(e) {
        const reason = document.getElementById('cancellation_reason').value.trim();
        if (!reason) {
            e.preventDefault();
            alert('Please provide a reason for cancellation.');
            document.getElementById('cancellation_reason').focus();
        }
    });
});
</script>
@endsection