@extends('layouts.resident_panel')

@section('title', 'Make Reservation')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md max-w-4xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Make New Reservation</h2>
        <p class="text-gray-600 mt-2">Fill out the form below to create a new facility reservation</p>
    </div>

    <form action="{{ route('resident.reservation.store') }}" method="POST" class="space-y-6">
        @csrf
        

        <!-- Date Selection -->
        <div>
            <label for="reservation_date" class="block text-sm font-medium text-gray-700 mb-2">Reservation Date</label>
            <input type="date" id="reservation_date" name="reservation_date" min="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
        </div>

        <!-- Time Selection -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                <select id="start_time" name="start_time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    <option value="">Select start time...</option>
                    @php
                        for($hour = 8; $hour <= 16; $hour++) {
                            for($minute = 0; $minute < 60; $minute += 30) {
                                $time = sprintf('%02d:%02d', $hour, $minute);
                                $display_time = date('g:i A', strtotime($time));
                                echo "<option value='$time'>$display_time</option>";
                            }
                        }
                    @endphp
                </select>
            </div>
            
            <div>
                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                <select id="end_time" name="end_time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    <option value="">Select end time...</option>
                    @php
                        for($hour = 9; $hour <= 17; $hour++) {
                            for($minute = 0; $minute < 60; $minute += 30) {
                                $time = sprintf('%02d:%02d', $hour, $minute);
                                $display_time = date('g:i A', strtotime($time));
                                echo "<option value='$time'>$display_time</option>";
                            }
                        }
                    @endphp
                </select>
            </div>
        </div>

        <!-- Available Services (populated via availability) -->
        <div>
            <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">Select Service</label>
            <select id="service_id" name="service_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                <option value="">Choose date/time to load services...</option>
            </select>
            <p id="service_hint" class="text-xs text-gray-500 mt-1">Only active services with available units are shown.</p>
        </div>

        

        <!-- Additional Requirements -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Preferences (optional)</label>
            <div class="space-y-2">
                <textarea id="preferences" name="preferences" rows="3" placeholder="Special assistance if PWD, special requests, etc." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"></textarea>
            </div>
        </div>

        <!-- Terms and Conditions -->
        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
            <label class="flex items-start">
                <input type="checkbox" name="terms" class="mt-1 rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                <span class="ml-2 text-sm text-gray-700">
                    I agree to the <a href="#" class="text-yellow-600 hover:text-yellow-700 underline">terms and conditions</a> of facility reservation. 
                    I understand that any damage to the facility will be my responsibility and that reservations may be subject to approval.
                </span>
            </label>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4 pt-4">
            <a href="{{ route('resident.dashboard') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-200 font-semibold">
                Cancel
            </a>
            <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition duration-200 font-semibold">
                Submit Reservation
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    const dateEl = document.getElementById('reservation_date');
    dateEl.min = today;

    // Time validation
    const startTimeSelect = document.getElementById('start_time');
    const endTimeSelect = document.getElementById('end_time');
    const dateInput = document.getElementById('reservation_date');
    const serviceSelect = document.getElementById('service_id');

    startTimeSelect.addEventListener('change', function() {
        const startTime = this.value;
        if (startTime) {
            // Enable only end times after start time
            Array.from(endTimeSelect.options).forEach(option => {
                if (option.value && option.value <= startTime) {
                    option.disabled = true;
                } else {
                    option.disabled = false;
                }
            });
        }
    });

    async function loadAvailability() {
        serviceSelect.innerHTML = `<option value="">Loading services...</option>`;
        if (!dateInput.value) {
            try {
                const res = await fetch(`{{ route('resident.reservation.active_services') }}`, { headers: { 'Accept': 'application/json' }});
                const data = await res.json();
                const options = (data.services || []).map(s => `<option value="${s.id}">${s.name}</option>`);
                serviceSelect.innerHTML = options.length ? `<option value="">Select a service...</option>` + options.join('') : `<option value="">No services configured by admin</option>`;
            } catch (e) {
                serviceSelect.innerHTML = `<option value="">Failed to load services</option>`;
            }
            return;
        }
        try {
            const params = new URLSearchParams({ reservation_date: dateInput.value });
            const res = await fetch(`{{ route('resident.reservation.available') }}?` + params.toString(), { headers: { 'Accept': 'application/json' }});
            const data = await res.json();
            const options = (data.services || []).map(s => `<option value="${s.id}">${s.name} (Remaining: ${s.remaining_units})</option>`);
            serviceSelect.innerHTML = options.length ? `<option value="">Select a service...</option>` + options.join('') : `<option value="">No services available for this date</option>`;
        } catch (e) {
            serviceSelect.innerHTML = `<option value="">Failed to load services</option>`;
        }
    }

    dateInput.addEventListener('change', loadAvailability);

    // Disable fully booked/closed dates dynamically; add visual styling
    async function refreshDisabledDates() {
        try {
            const params = new URLSearchParams({ start: today });
            const res = await fetch(`{{ route('resident.reservation.fully_booked') }}?` + params.toString(), { headers: { 'Accept': 'application/json' }});
            const data = await res.json();
            const fullyBooked = new Set(data.dates || []);
            dateEl.addEventListener('input', function() {
                if (fullyBooked.has(this.value)) {
                    alert('Selected date is unavailable (closed or fully booked). Please choose another date.');
                    this.value = '';
                }
            });

            // Visual hint below the input
            const hintId = 'blockedDatesHint';
            let hint = document.getElementById(hintId);
            if (!hint) {
                hint = document.createElement('div');
                hint.id = hintId;
                hint.className = 'text-xs text-gray-500 mt-1';
                dateEl.parentElement.appendChild(hint);
            }
            if (fullyBooked.size > 0) {
                const examples = Array.from(fullyBooked).slice(0, 5).join(', ');
                hint.innerHTML = `<span class="line-through text-gray-400">Blocked dates</span>: <span class="text-gray-600">${examples}${fullyBooked.size>5?'...':''}</span>`;
            }

            // Render blocked-dates calendar grid with disabled (unclickable) dates
            renderBlockedCalendar(fullyBooked);
        } catch (e) {}
    }

    function renderBlockedCalendar(blockedSet) {
        const containerId = 'calendar_preview_blocked';
        let container = document.getElementById(containerId);
        if (!container) {
            container = document.createElement('div');
            container.id = containerId;
            container.className = 'mt-2';
            dateEl.parentElement.appendChild(container);
        }
        const base = dateEl.value ? new Date(dateEl.value) : new Date();
        const year = base.getFullYear();
        const month = base.getMonth();
        const first = new Date(year, month, 1);
        const last = new Date(year, month + 1, 0);
        const startWeekday = (first.getDay() + 6) % 7; // Monday=0
        const daysInMonth = last.getDate();

        let html = '';
        html += `<div class="text-xs text-gray-600 mb-1">${first.toLocaleString([], { month: 'long', year: 'numeric' })}</div>`;
        html += '<div class="grid grid-cols-7 gap-1 text-center text-xs">';
        const labels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        html += labels.map(l=>`<div class=\"text-gray-500\">${l}</div>`).join('');
        for (let i=0;i<startWeekday;i++) html += '<div></div>';
        const todayStrLocal = new Date().toISOString().split('T')[0];
        for (let d=1; d<=daysInMonth; d++) {
            const dayStr = `${year}-${String(month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
            const isBlocked = blockedSet.has(dayStr);
            const isPastMin = dayStr < dateEl.min; // respect min
            const disabled = isBlocked || isPastMin;
            const classes = disabled ? 'bg-gray-200 text-gray-400 line-through cursor-not-allowed' : (dayStr < todayStrLocal ? 'text-gray-300' : 'hover:bg-yellow-100 cursor-pointer');
            html += `<button type=\"button\" data-date=\"${dayStr}\" ${disabled?'disabled':''} class=\"px-2 py-1 rounded ${classes}\">${d}</button>`;
        }
        html += '</div>';
        container.innerHTML = html;
        container.querySelectorAll('button[data-date]').forEach(btn => {
            btn.addEventListener('click', () => {
                const value = btn.getAttribute('data-date');
                if (blockedSet.has(value) || value < dateEl.min) return;
                dateEl.value = value;
                const ev = new Event('change');
                dateEl.dispatchEvent(ev);
            });
        });
    }
    refreshDisabledDates();
    // Initial services load on page open
    loadAvailability();
});
</script>

<style>
select:invalid {
    color: #6b7280;
}
select option {
    color: #1f2937;
}
</style>
@endsection