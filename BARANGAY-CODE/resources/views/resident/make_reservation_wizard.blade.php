@extends('layouts.resident_panel')

@section('title', 'Make Reservation')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md max-w-6xl mx-auto">
    <div class="mb-6">
        <p class="text-gray-600">Follow the steps to complete your reservation. Maximum 2 hours per reservation.</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded border border-red-200 bg-red-50 text-red-700">
            <strong>We couldn't submit your reservation:</strong>
            <ul class="list-disc ml-5 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @isset($onCooldown)
        @if ($onCooldown)
            <div class="mb-4 p-3 rounded border border-yellow-300 bg-yellow-50 text-yellow-800">
                <strong>One reservation every 24 hours.</strong>
                <div class="text-sm mt-1">You can make a new reservation after <span class="font-semibold">{{ optional($cooldownUntil)->timezone(config('app.timezone'))->format('M d, Y g:i A') }}</span>.</div>
            </div>
        @endif
    @endisset

    <form id="reservationForm" action="{{ route('resident.reservation.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Stepper Header -->
        <div class="flex items-center justify-between text-sm mb-2">
            <div class="flex-1 flex items-center">
                <div id="stepBadge1" class="w-6 h-6 rounded-full bg-yellow-500 text-white flex items-center justify-center mr-2">1</div>
                <span class="font-semibold">Date</span>
            </div>
            <div class="flex-1 hidden md:flex items-center">
                <div id="stepBadge2" class="w-6 h-6 rounded-full bg-gray-300 text-gray-700 flex items-center justify-center mr-2">2</div>
                <span>Select Time & Service</span>
            </div>
            <div class="flex-1 hidden md:flex items-center">
                <div id="stepBadge3" class="w-6 h-6 rounded-full bg-gray-300 text-gray-700 flex items-center justify-center mr-2">3</div>
                <span>Preferences</span>
            </div>
            <div class="flex-1 hidden md:flex items-center">
                <div id="stepBadge4" class="w-6 h-6 rounded-full bg-gray-300 text-gray-700 flex items-center justify-center mr-2">4</div>
                <span>Overview</span>
            </div>
        </div>

        <!-- STEP 1: Date Selection -->
        <div id="step1" class="space-y-4">
            <div>
                <label for="reservation_date" class="block text-sm font-medium text-gray-700 mb-2">Reservation Date</label>
                <input type="date" id="reservation_date" name="reservation_date" min="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" @isset($onCooldown) @if($onCooldown) disabled @endif @endisset>
                <p class="text-xs text-gray-500 mt-1">Fully booked or closed dates will show no available services.</p>
            </div>
        </div>

        <!-- STEP 2: Time & Service Selection Table -->
        <div id="step2" class="space-y-4 hidden">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Available Time Slots & Services</h3>
                <p class="text-sm text-gray-600 mb-4">Select a time slot and service. Each reservation is limited to 2 hours maximum.</p>
                
                <!-- Loading State -->
                <div id="loadingState" class="text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-yellow-500"></div>
                    <p class="mt-2 text-gray-600">Loading available slots...</p>
                </div>

                <!-- Error State -->
                <div id="errorState" class="hidden text-center py-8">
                    <div class="text-red-500 mb-2">
                        <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <p id="errorMessage" class="text-red-600 font-medium"></p>
                </div>

                <!-- Table Container -->
                <div id="tableContainer" class="hidden">
                    <!-- Sort Controls -->
                    <div class="mb-4 flex flex-wrap gap-2">
                        <button type="button" id="sortByTime" class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm">Sort by Time</button>
                        <button type="button" id="sortByDuration" class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm">Sort by Duration</button>
                        <button type="button" id="sortByService" class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm">Sort by Service</button>
                        <button type="button" id="sortByAvailability" class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm">Sort by Availability</button>
                    </div>

                    <!-- Selection Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Slot</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available Units</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Select</th>
                                </tr>
                            </thead>
                            <tbody id="availabilityTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Table rows will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Controls -->
                    <div id="paginationContainer" class="mt-4 flex items-center justify-between">
                        <div class="flex items-center text-sm text-gray-700">
                            <span id="paginationInfo">Showing 1 to 10 of 0 entries</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button id="prevPage" class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                Previous
                            </button>
                            <div id="pageNumbers" class="flex space-x-1">
                                <!-- Page numbers will be populated by JavaScript -->
                            </div>
                            <button id="nextPage" class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden inputs for selected values -->
        <input type="hidden" id="selected_service_id" name="service_id">
        <input type="hidden" id="selected_start_time" name="start_time">
        <input type="hidden" id="selected_end_time" name="end_time">

        <!-- STEP 3: Preferences -->
        <div id="step3" class="space-y-4 hidden">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Preferences (optional)</label>
                <textarea id="preferences" name="preferences" rows="3" placeholder="Special assistance if PWD, special requests, etc." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"></textarea>
            </div>
            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200 text-sm">
                <p class="mb-1"><span class="font-semibold">Note:</span> You can modify or cancel your reservation within 15 minutes after submission.</p>
                <p>Reservations are allowed only between 8:00 AM and 5:00 PM.</p>
            </div>
        </div>

        <!-- STEP 4: Overview & Terms -->
        <div id="step4" class="space-y-4 hidden">
            <div class="bg-gray-50 border rounded p-4">
                <h3 class="font-semibold mb-2">Overview</h3>
                <div class="text-sm space-y-1">
                    <div><span class="font-medium">Date:</span> <span id="ov_date">—</span></div>
                    <div><span class="font-medium">Time:</span> <span id="ov_time">—</span></div>
                    <div><span class="font-medium">Service:</span> <span id="ov_service">—</span></div>
                    <div><span class="font-medium">Preferences:</span> <span id="ov_prefs">—</span></div>
                </div>
            </div>
            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                <label class="flex items-start">
                    <input type="checkbox" id="terms" name="terms" required class="mt-1 rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                    <span class="ml-2 text-sm text-gray-700">
                        I accept the terms and conditions of reservation and understand I can modify/cancel only within 15 minutes after booking.
                    </span>
                </label>
            </div>
        </div>

        <!-- Wizard Navigation -->
        <div class="flex justify-between pt-2">
            <a href="{{ route('resident.dashboard') }}" class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400 transition duration-200 font-semibold">Cancel</a>
            <div class="ml-auto flex gap-2">
                <button type="button" id="btnBack" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-200 font-semibold hidden">Back</button>
                <button type="button" id="btnNext" class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600 transition duration-200 font-semibold" @isset($onCooldown) @if($onCooldown) disabled @endif @endisset>Next</button>
                <button type="submit" id="btnSubmit" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition duration-200 font-semibold hidden" @isset($onCooldown) @if($onCooldown) disabled @endif @endisset>Confirm & Submit</button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const dateEl = document.getElementById('reservation_date');
    const preferences = document.getElementById('preferences');

    // Wizard state
    let currentStep = 1;
    let availabilityData = null;
    let selectedSlot = null;
    
    // Pagination state
    let currentPage = 1;
    let itemsPerPage = 10;
    let totalItems = 0;
    let totalPages = 0;
    let currentSort = 'time'; // 'time', 'duration', 'service', 'availability'

    const stepPanels = {
        1: document.getElementById('step1'),
        2: document.getElementById('step2'),
        3: document.getElementById('step3'),
        4: document.getElementById('step4'),
    };
    const stepBadges = {
        1: document.getElementById('stepBadge1'),
        2: document.getElementById('stepBadge2'),
        3: document.getElementById('stepBadge3'),
        4: document.getElementById('stepBadge4'),
    };
    const btnBack = document.getElementById('btnBack');
    const btnNext = document.getElementById('btnNext');
    const btnSubmit = document.getElementById('btnSubmit');

    function setStep(step) {
        currentStep = step;
        Object.values(stepPanels).forEach(el => el.classList.add('hidden'));
        stepPanels[step].classList.remove('hidden');
        
        // Update step badges
        Object.entries(stepBadges).forEach(([idx, el]) => {
            if (parseInt(idx) <= step) {
                el.classList.remove('bg-gray-300','text-gray-700');
                el.classList.add('bg-yellow-500','text-white');
            } else {
                el.classList.add('bg-gray-300','text-gray-700');
                el.classList.remove('bg-yellow-500','text-white');
            }
        });
        
        btnBack.classList.toggle('hidden', step === 1);
        btnNext.classList.toggle('hidden', step === 4);
        btnSubmit.classList.toggle('hidden', step !== 4);
        
        if (step === 2) loadAvailability();
        if (step === 4) updateOverview();
    }

    function validateStep(step) {
        const isOnCooldown = {{ isset($onCooldown) && $onCooldown ? 'true' : 'false' }};
        if (isOnCooldown) {
            alert('You can only make one reservation every 24 hours. Please try again later or cancel your existing reservation.');
            return false;
        }
        
        // Same-day cutoff: do not allow proceeding past 3:00 PM when booking for today
        const now = new Date();
        const todayStr = now.toISOString().split('T')[0];
        const hhmm = `${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}`;
        if (dateEl.value === todayStr && hhmm >= '15:00') {
            alert('Same-day reservations are allowed only until 3:00 PM.');
            return false;
        }
        
        if (step === 1) {
            if (!dateEl.value) { 
                alert('Please select a reservation date.'); 
                return false;
            }
        }
        if (step === 2) {
            if (!selectedSlot) { 
                alert('Please select a time slot and service.'); 
                return false; 
            }
        }
        if (step === 3) {
            // No validation needed for preferences step
        }
        if (step === 4) {
            if (!document.getElementById('terms').checked) { 
                alert('Please accept the terms and conditions of reservation and confirm that you understand you can modify/cancel only within 15 minutes after booking.'); 
                return false; 
            }
        }
        return true;
    }

    function updateOverview() {
        document.getElementById('ov_date').textContent = dateEl.value || '—';
        document.getElementById('ov_time').textContent = selectedSlot ? selectedSlot.time_display : '—';
        document.getElementById('ov_service').textContent = selectedSlot ? selectedSlot.service_name : '—';
        document.getElementById('ov_prefs').textContent = preferences.value || '—';
    }

    // Load availability data
    async function loadAvailability() {
        const loadingState = document.getElementById('loadingState');
        const errorState = document.getElementById('errorState');
        const tableContainer = document.getElementById('tableContainer');
        const errorMessage = document.getElementById('errorMessage');
        
        loadingState.classList.remove('hidden');
        errorState.classList.add('hidden');
        tableContainer.classList.add('hidden');
        
        try {
            const params = new URLSearchParams({ date: dateEl.value });
            const res = await fetch(`{{ route('resident.reservation.time_slots') }}?` + params.toString(), { 
                headers: { 'Accept': 'application/json' } 
            });
            const data = await res.json();
            
            if (data.error) {
                errorMessage.textContent = data.error;
                errorState.classList.remove('hidden');
                loadingState.classList.add('hidden');
                return;
            }
            
            availabilityData = data;
            populateTable(data);
            loadingState.classList.add('hidden');
            tableContainer.classList.remove('hidden');
            
        } catch (e) {
            errorMessage.textContent = 'Failed to load availability data. Please try again.';
            errorState.classList.remove('hidden');
            loadingState.classList.add('hidden');
        }
    }

    function populateTable(data) {
        // Flatten the data for table display
        const allRows = [];
        data.services.forEach(service => {
            service.availability.forEach(availability => {
                allRows.push({
                    service: service.service,
                    timeSlot: availability.time_slot,
                    availableUnits: availability.available_units,
                    isAvailable: availability.is_available,
                    serviceName: service.service.name,
                    timeDisplay: availability.time_slot.start_time + ' - ' + availability.time_slot.end_time,
                    durationMinutes: availability.time_slot.duration_minutes || 120,
                    durationDisplay: availability.time_slot.duration_display || '2 hours'
                });
            });
        });
        
        // Sort the data
        sortTableData(allRows);
        
        // Update pagination info
        totalItems = allRows.length;
        totalPages = Math.ceil(totalItems / itemsPerPage);
        currentPage = 1; // Reset to first page
        
        // Display the current page
        displayCurrentPage(allRows);
    }

    function sortTableData(rows) {
        switch(currentSort) {
            case 'time':
                rows.sort((a, b) => {
                    // Sort by start time first, then by duration
                    if (a.timeSlot.start_time !== b.timeSlot.start_time) {
                        return a.timeSlot.start_time.localeCompare(b.timeSlot.start_time);
                    }
                    return a.durationMinutes - b.durationMinutes;
                });
                break;
            case 'duration':
                rows.sort((a, b) => {
                    // Sort by duration first (30 mins, 1 hour, 1.5 hours, 2 hours), then by start time
                    if (a.durationMinutes !== b.durationMinutes) {
                        return a.durationMinutes - b.durationMinutes;
                    }
                    return a.timeSlot.start_time.localeCompare(b.timeSlot.start_time);
                });
                break;
            case 'service':
                rows.sort((a, b) => {
                    // Sort by service name first, then by start time
                    if (a.serviceName !== b.serviceName) {
                        return a.serviceName.localeCompare(b.serviceName);
                    }
                    return a.timeSlot.start_time.localeCompare(b.timeSlot.start_time);
                });
                break;
            case 'availability':
                rows.sort((a, b) => {
                    // Sort by availability first, then by start time
                    if (b.availableUnits !== a.availableUnits) {
                        return b.availableUnits - a.availableUnits;
                    }
                    return a.timeSlot.start_time.localeCompare(b.timeSlot.start_time);
                });
                break;
        }
    }

    function displayCurrentPage(allRows) {
        const tbody = document.getElementById('availabilityTableBody');
        tbody.innerHTML = '';
        
        // Calculate pagination
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
        const currentPageRows = allRows.slice(startIndex, endIndex);
        
        // Display rows for current page
        currentPageRows.forEach(row => {
            const tr = document.createElement('tr');
            tr.className = row.isAvailable ? 'hover:bg-gray-50' : 'bg-gray-100 opacity-60';
            
            // Format time display
            const formatTime = (timeStr) => {
                const [hours, minutes] = timeStr.split(':');
                const hour = parseInt(hours);
                const ampm = hour >= 12 ? 'PM' : 'AM';
                const displayHour = hour > 12 ? hour - 12 : (hour === 0 ? 12 : hour);
                return `${displayHour}:${minutes} ${ampm}`;
            };
            
            const timeDisplay = `${formatTime(row.timeSlot.start_time)} - ${formatTime(row.timeSlot.end_time)}`;
            
            tr.innerHTML = `
                <td class="px-4 py-3 text-sm text-gray-900">${timeDisplay}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${row.durationDisplay}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${row.serviceName}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${row.availableUnits}</td>
                <td class="px-4 py-3 text-sm">
                    <span class="px-2 py-1 text-xs rounded-full ${row.isAvailable ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                        ${row.isAvailable ? 'Available' : 'Fully Booked'}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm">
                    <input type="radio" name="slot_selection" value="${row.service.id}_${row.timeSlot.start_time}_${row.timeSlot.end_time}" 
                           ${!row.isAvailable ? 'disabled' : ''} 
                           class="slot-radio">
                </td>
            `;
            
            tbody.appendChild(tr);
        });
        
        // Add event listeners to radio buttons
        document.querySelectorAll('.slot-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    const [serviceId, startTime, endTime] = this.value.split('_');
                    const serviceName = this.closest('tr').querySelector('td:nth-child(3)').textContent;
                    const timeDisplay = this.closest('tr').querySelector('td:nth-child(1)').textContent;
                    
                    selectedSlot = {
                        service_id: serviceId,
                        start_time: startTime,
                        end_time: endTime,
                        service_name: serviceName,
                        time_display: timeDisplay
                    };
                    
                    // Update hidden inputs
                    document.getElementById('selected_service_id').value = serviceId;
                    document.getElementById('selected_start_time').value = startTime;
                    document.getElementById('selected_end_time').value = endTime;
                }
            });
        });
        
        // Update pagination controls
        updatePaginationControls();
    }

    function updatePaginationControls() {
        const paginationInfo = document.getElementById('paginationInfo');
        const prevButton = document.getElementById('prevPage');
        const nextButton = document.getElementById('nextPage');
        const pageNumbers = document.getElementById('pageNumbers');
        
        // Update pagination info
        const startItem = totalItems === 0 ? 0 : (currentPage - 1) * itemsPerPage + 1;
        const endItem = Math.min(currentPage * itemsPerPage, totalItems);
        paginationInfo.textContent = `Showing ${startItem} to ${endItem} of ${totalItems} entries`;
        
        // Update navigation buttons
        prevButton.disabled = currentPage === 1;
        nextButton.disabled = currentPage === totalPages || totalPages === 0;
        
        // Update page numbers
        pageNumbers.innerHTML = '';
        const maxVisiblePages = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
        
        // Adjust start page if we're near the end
        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }
        
        for (let i = startPage; i <= endPage; i++) {
            const pageButton = document.createElement('button');
            pageButton.className = `px-3 py-1 text-sm rounded ${
                i === currentPage 
                    ? 'bg-yellow-500 text-white' 
                    : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
            }`;
            pageButton.textContent = i;
            pageButton.addEventListener('click', () => goToPage(i));
            pageNumbers.appendChild(pageButton);
        }
    }

    function goToPage(page) {
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            // Re-sort and display current page
            const allRows = flattenAvailabilityData();
            sortTableData(allRows);
            displayCurrentPage(allRows);
        }
    }

    // Helper function to flatten availability data
    function flattenAvailabilityData() {
        const allRows = [];
        availabilityData.services.forEach(service => {
            service.availability.forEach(availability => {
                allRows.push({
                    service: service.service,
                    timeSlot: availability.time_slot,
                    availableUnits: availability.available_units,
                    isAvailable: availability.is_available,
                    serviceName: service.service.name,
                    timeDisplay: availability.time_slot.start_time + ' - ' + availability.time_slot.end_time,
                    durationMinutes: availability.time_slot.duration_minutes || 120,
                    durationDisplay: availability.time_slot.duration_display || '2 hours'
                });
            });
        });
        return allRows;
    }

    // Sort functionality
    document.getElementById('sortByTime').addEventListener('click', function() {
        if (!availabilityData) return;
        currentSort = 'time';
        currentPage = 1; // Reset to first page
        const allRows = flattenAvailabilityData();
        sortTableData(allRows);
        displayCurrentPage(allRows);
    });

    document.getElementById('sortByDuration').addEventListener('click', function() {
        if (!availabilityData) return;
        currentSort = 'duration';
        currentPage = 1; // Reset to first page
        const allRows = flattenAvailabilityData();
        sortTableData(allRows);
        displayCurrentPage(allRows);
    });

    document.getElementById('sortByService').addEventListener('click', function() {
        if (!availabilityData) return;
        currentSort = 'service';
        currentPage = 1; // Reset to first page
        const allRows = flattenAvailabilityData();
        sortTableData(allRows);
        displayCurrentPage(allRows);
    });

    document.getElementById('sortByAvailability').addEventListener('click', function() {
        if (!availabilityData) return;
        currentSort = 'availability';
        currentPage = 1; // Reset to first page
        const allRows = flattenAvailabilityData();
        sortTableData(allRows);
        displayCurrentPage(allRows);
    });

    // Pagination navigation
    document.getElementById('prevPage').addEventListener('click', function() {
        if (currentPage > 1) {
            goToPage(currentPage - 1);
        }
    });

    document.getElementById('nextPage').addEventListener('click', function() {
        if (currentPage < totalPages) {
            goToPage(currentPage + 1);
        }
    });

    // Wizard navigation
    btnNext.addEventListener('click', () => {
        if (!validateStep(currentStep)) return;
        setStep(currentStep + 1);
    });
    btnBack.addEventListener('click', () => setStep(currentStep - 1));

    // Confirm dialog before submit
    document.getElementById('reservationForm').addEventListener('submit', function(e) {
        const msg = `Please confirm your reservation:\n\nDate: ${dateEl.value}\nTime: ${selectedSlot ? selectedSlot.time_display : '—'}\nService: ${selectedSlot ? selectedSlot.service_name : '—'}\n\nAgree to terms and submit?`;
        if (!confirm(msg)) {
            e.preventDefault();
        }
    });

    // Init
    setStep(1);
});
</script>

<style>
select:invalid { color: #6b7280; }
select option { color: #1f2937; }
.slot-radio:disabled { opacity: 0.5; cursor: not-allowed; }
#paginationContainer button:disabled { opacity: 0.5; cursor: not-allowed; }
#paginationContainer button:not(:disabled):hover { transform: translateY(-1px); }
#pageNumbers button { transition: all 0.2s ease; }
</style>
@endsection