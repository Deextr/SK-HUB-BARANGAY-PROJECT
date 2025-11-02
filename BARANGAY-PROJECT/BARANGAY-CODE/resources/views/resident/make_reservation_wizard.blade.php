@extends('layouts.resident_panel')

@section('title', 'Make Reservation')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md max-w-4xl mx-auto">
    <div class="mb-6">
      
        <p class="text-gray-600 mt-2">Follow the steps to complete your reservation.</p>
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
                <span class="font-semibold">Date & Time</span>
            </div>
            <div class="flex-1 hidden md:flex items-center">
                <div id="stepBadge2" class="w-6 h-6 rounded-full bg-gray-300 text-gray-700 flex items-center justify-center mr-2">2</div>
                <span>Select Service</span>
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

        <!-- STEP 1: Date & optional Time -->
        <div id="step1" class="space-y-4">
            <div>
                <label for="reservation_date" class="block text-sm font-medium text-gray-700 mb-2">Reservation Date</label>
                <input type="date" id="reservation_date" name="reservation_date" min="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" @isset($onCooldown) @if($onCooldown) disabled @endif @endisset>
                <p class="text-xs text-gray-500 mt-1">Fully booked or closed dates will show no available services.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time (optional)</label>
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
                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time (optional)</label>
                    <select id="end_time" name="end_time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        <option value="">Select end time...</option>
                        @php
                            for($hour = 9; $hour <= 17; $hour++) {
                                for($minute = 0; $minute < 60; $minute += 30) {
                                    // Disallow 17:30 to keep end <= 17:00 per business hours
                                    if ($hour === 17 && $minute > 0) { continue; }
                                    $time = sprintf('%02d:%02d', $hour, $minute);
                                    $display_time = date('g:i A', strtotime($time));
                                    echo "<option value='$time'>$display_time</option>";
                                }
                            }
                        @endphp
                    </select>
                </div>
            </div>
        </div>

        <!-- STEP 2: Select Service -->
        <div id="step2" class="space-y-4 hidden">
            <div>
                <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">Select Service</label>
                <select id="service_id" name="service_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    <option value="">Choose a date first to load services...</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Shows only active services with remaining units on the chosen date.</p>
            </div>
        </div>

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
                        I agree that I am responsible for any damages to the facility. I accept the terms and conditions of reservation.
                        I understand I can modify/cancel only within 15 minutes after booking.
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

    const startTimeSelect = document.getElementById('start_time');
    const endTimeSelect = document.getElementById('end_time');
    const serviceSelect = document.getElementById('service_id');
    const preferences = document.getElementById('preferences');

    // Wizard state
    let currentStep = 1;
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
            if (!dateEl.value) { alert('Please select a reservation date.'); return false; }
            if (startTimeSelect.value && endTimeSelect.value && endTimeSelect.value <= startTimeSelect.value) {
                alert('End time must be after start time.');
                return false;
            }
        }
        if (step === 2) {
            if (!serviceSelect.value) { alert('Please select a service.'); return false; }
        }
        if (step === 4) {
            if (!document.getElementById('terms').checked) { alert('Please agree to the terms and conditions.'); return false; }
        }
        return true;
    }

    function updateOverview() {
        document.getElementById('ov_date').textContent = dateEl.value || '—';
        const timeText = (startTimeSelect.value && endTimeSelect.value)
            ? `${formatTime(startTimeSelect.value)} - ${formatTime(endTimeSelect.value)}`
            : 'Whole day';
        document.getElementById('ov_time').textContent = timeText;
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        document.getElementById('ov_service').textContent = selectedOption ? selectedOption.text.replace(/\s*\(Remaining:.*\)$/,'') : '—';
        document.getElementById('ov_prefs').textContent = preferences.value || '—';
    }

    function formatTime(val) {
        try {
            const [h,m] = val.split(':');
            const d = new Date(); d.setHours(parseInt(h), parseInt(m));
            return d.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        } catch { return val; }
    }

    // Availability
    async function loadAvailability() {
        serviceSelect.innerHTML = `<option value="">Loading services...</option>`;
        if (!dateEl.value) {
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
            // Same-day cutoff at 15:00
            const now = new Date();
            if (dateEl.value === today) {
                const hhmm = `${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}`;
                if (hhmm >= '15:00') {
                    alert('Same-day reservations are allowed only until 3:00 PM.');
                    serviceSelect.innerHTML = `<option value="">Same-day booking closed after 3:00 PM</option>`;
                    return;
                }
            }
            // Block if user already has a reservation for selected date
            const r = await fetch(`{{ route('resident.reservation.has_for_date') }}?date=${encodeURIComponent(dateEl.value)}`, { headers: { 'Accept': 'application/json' }});
            const rb = await r.json();
            if (rb.blocked) {
                alert(rb.message || 'You already have a reservation for this date.');
                serviceSelect.innerHTML = `<option value="">Date not allowed</option>`;
                return;
            }
            const params = new URLSearchParams({ reservation_date: dateEl.value });
            const res = await fetch(`{{ route('resident.reservation.available') }}?` + params.toString(), { headers: { 'Accept': 'application/json' }});
            const data = await res.json();
            const options = (data.services || []).map(s => `<option value="${s.id}">${s.name} (Remaining: ${s.remaining_units})</option>`);
            serviceSelect.innerHTML = options.length ? `<option value="">Select a service...</option>` + options.join('') : `<option value="">No services available for this date</option>`;
        } catch (e) {
            serviceSelect.innerHTML = `<option value="">Failed to load services</option>`;
        }
    }

    // Fully booked date guard + closed dates guard with visual hint
    async function refreshDisabledDates() {
        try {
            const params = new URLSearchParams({ start: today });
            const res = await fetch(`{{ route('resident.reservation.fully_booked') }}?` + params.toString(), { headers: { 'Accept': 'application/json' }});
            const data = await res.json();
            const blocked = new Set(data.dates || []);
            dateEl.addEventListener('input', function() {
                if (blocked.has(this.value)) {
                    alert('Selected date is unavailable (closed or fully booked). Please choose another date.');
                    this.value = '';
                }
            });
            const hintId = 'blockedDatesHint';
            let hint = document.getElementById(hintId);
            if (!hint) {
                hint = document.createElement('div');
                hint.id = hintId;
                hint.className = 'text-xs text-gray-500 mt-1';
                dateEl.parentElement.appendChild(hint);
            }
            if (blocked.size > 0) {
                const examples = Array.from(blocked).slice(0, 5).join(', ');
                hint.innerHTML = `<span class="line-through text-gray-400">Blocked dates</span>: <span class="text-gray-600">${examples}${blocked.size>5?'...':''}</span>`;
            }
        } catch (e) { /* ignore */ }
    }

    // Time validation UI
    startTimeSelect.addEventListener('change', () => {
        const startTime = startTimeSelect.value;
        Array.from(endTimeSelect.options).forEach(option => {
            if (!option.value) return;
            // enforce business hours and sequencing: end must be > start and <= 17:00
            const overDay = option.value > '17:00';
            const notAfterStart = startTime && option.value <= startTime;
            option.disabled = overDay || notAfterStart;
        });
    });

    // Wizard navigation
    btnNext.addEventListener('click', () => {
        if (!validateStep(currentStep)) return;
        setStep(currentStep + 1);
    });
    btnBack.addEventListener('click', () => setStep(currentStep - 1));

    // Confirm dialog before submit
    document.getElementById('reservationForm').addEventListener('submit', function(e) {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        const svc = selectedOption ? selectedOption.text.replace(/\s*\(Remaining:.*\)$/,'') : '';
        const msg = `Please confirm your reservation:\n\nDate: ${dateEl.value}\nTime: ` + (startTimeSelect.value && endTimeSelect.value ? `${startTimeSelect.value} - ${endTimeSelect.value}` : 'Whole day') + `\nService: ${svc}\n\nAgree to terms and submit?`;
        if (!confirm(msg)) {
            e.preventDefault();
        }
    });

    // Init
    refreshDisabledDates();
    loadAvailability();
    setStep(1);
});
</script>


<style>
select:invalid { color: #6b7280; }
select option { color: #1f2937; }
</style>
@endsection


