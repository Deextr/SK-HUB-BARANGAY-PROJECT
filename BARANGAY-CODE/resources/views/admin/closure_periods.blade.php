@extends('layouts.admin_panel')

@section('title', 'Closure Periods')

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

<div class="bg-white rounded shadow p-4 mb-6">
    <div class="flex flex-col gap-3">
        <form method="GET" class="flex gap-2 items-center flex-wrap">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search reason/status/date" class="border rounded px-3 py-2" />
            <select name="sort" class="border rounded px-3 py-2">
                <option value="start_date" {{ ($sort ?? request('sort'))=='start_date'?'selected':'' }}>Start Date</option>
                <option value="end_date" {{ ($sort ?? request('sort'))=='end_date'?'selected':'' }}>End Date</option>
                <option value="status" {{ ($sort ?? request('sort'))=='status'?'selected':'' }}>Status</option>
                <option value="reason" {{ ($sort ?? request('sort'))=='reason'?'selected':'' }}>Reason</option>
                <option value="created_at" {{ ($sort ?? request('sort'))=='created_at'?'selected':'' }}>Created</option>
            </select>
            <select name="direction" class="border rounded px-3 py-2">
                <option value="asc" {{ ($direction ?? request('direction'))=='asc'?'selected':'' }}>Asc</option>
                <option value="desc" {{ ($direction ?? request('direction'))=='desc'?'selected':'' }}>Desc</option>
            </select>
            <button class="bg-gray-700 text-white px-4 py-2 rounded">Apply</button>
        </form>
        <div>
            <button id="btnOpenCreate" class="bg-blue-600 text-white px-4 py-2 rounded">+ Closure Period</button>
        </div>
    </div>
</div>

<div class="bg-white rounded shadow p-4">
    
    <div class="overflow-x-auto">
    <table class="min-w-full">
        <thead>
            <tr class="text-left bg-gray-100">
                <th class="py-2 px-3">Dates</th>
                <th class="py-2 px-3">Time</th>
                <th class="py-2 px-3">Reason</th>
                <th class="py-2 px-3">Status</th>
                <th class="py-2 px-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $p)
            <tr class="border-b">
                <td class="py-2 px-3">{{ $p->start_date->format('M d, Y') }} – {{ $p->end_date->format('M d, Y') }}</td>
                <td class="py-2 px-3">
                    @if($p->is_full_day)
                        Full day
                    @else
                        {{ $p->start_time }} - {{ $p->end_time }}
                    @endif
                </td>
                <td class="py-2 px-3">{{ $p->reason ?? '—' }}</td>
                <td class="py-2 px-3">
                    @if($p->status === 'active')
                        <span class="text-green-600 font-semibold">Active</span>
                    @else
                        <span class="text-yellow-600 font-semibold">Pending</span>
                    @endif
                </td>
                <td class="py-2 px-3">
                    <div class="flex gap-2">
                        <button title="Edit Closure Period" class="px-2 py-2 text-blue-600 hover:text-blue-800 font-medium btnEdit {{ $p->status==='active' ? 'opacity-50 cursor-not-allowed' : '' }}"
                            data-id="{{ $p->id }}"
                            data-start_date="{{ $p->start_date->toDateString() }}"
                            data-end_date="{{ $p->end_date->toDateString() }}"
                            data-is_full_day="{{ $p->is_full_day ? 1 : 0 }}"
                            data-start_time="{{ $p->start_time }}"
                            data-end_time="{{ $p->end_time }}"
                            data-reason="{{ $p->reason }}"
                            data-status="{{ $p->status }}"
                        {{ $p->status==='active' ? 'disabled' : '' }}>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <form method="POST" action="{{ route('admin.closure_periods.destroy', $p) }}" onsubmit="return confirm('Archive this period?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Archive Closure Period" class="px-2 py-2 text-red-600 hover:text-red-800 font-medium" {{ $p->status==='active' ? 'disabled' : '' }}>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td class="py-8 px-3 text-center text-gray-500" colspan="5">
                    @if(request('q'))
                        No results found for your search. <a href="{{ route('admin.closure_periods.index') }}" class="text-blue-600 underline">Clear search</a>
                    @else
                        No closure periods yet.
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if(($items ?? collect())->count() > 0)
    <div class="mt-6">
        {{ $items->links() }}
    </div>
    @endif
</div>

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-xl rounded shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Add Closure Period</h3>
            <button id="btnCloseCreate" class="text-gray-500 hover:text-gray-700">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.closure_periods.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @csrf
            <div>
                <label class="block text-sm text-gray-600 mb-1">Start Date</label>
                <input type="date" name="start_date" class="border rounded px-3 py-2 w-full" min="{{ date('Y-m-d') }}" required />
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">End Date</label>
                <input type="date" name="end_date" class="border rounded px-3 py-2 w-full" min="{{ date('Y-m-d') }}" required />
            </div>
            <div class="md:col-span-2 flex items-center gap-2">
                <input type="checkbox" name="is_full_day" value="1" id="create_full_day" checked />
                <label for="create_full_day" class="text-sm text-gray-700">Full day(s)</label>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Start Time</label>
                <input type="time" name="start_time" id="create_start_time" class="border rounded px-3 py-2 w-full" />
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">End Time</label>
                <input type="time" name="end_time" id="create_end_time" class="border rounded px-3 py-2 w-full" />
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-600 mb-1">Reason</label>
                <input type="text" name="reason" class="border rounded px-3 py-2 w-full" placeholder="e.g. Holiday" />
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Status</label>
                <select name="status" class="border rounded px-3 py-2 w-full">
                    <option value="pending">Pending</option>
                    <option value="active">Active</option>
                </select>
            </div>
            <div class="md:col-span-2 flex justify-end gap-2 mt-2">
                <button type="button" id="btnCancelCreate" class="px-4 py-2 rounded border">Cancel</button>
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Add</button>
            </div>
        </form>
        <p class="text-xs text-gray-500 mt-2">
            If "Full day(s)" is checked, time fields are ignored.<br>
            <strong>Note:</strong> When status is set to "Active", all reservations within the date range will be automatically cancelled.
        </p>
    </div>
    
    
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-xl rounded shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Edit Closure Period</h3>
            <button id="btnCloseEdit" class="text-gray-500 hover:text-gray-700">✕</button>
        </div>
        <form id="editForm" method="POST" action="#" class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm text-gray-600 mb-1">Start Date</label>
                <input type="date" name="start_date" id="edit_start_date" class="border rounded px-3 py-2 w-full" min="{{ date('Y-m-d') }}" required />
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">End Date</label>
                <input type="date" name="end_date" id="edit_end_date" class="border rounded px-3 py-2 w-full" min="{{ date('Y-m-d') }}" required />
            </div>
            <div class="md:col-span-2 flex items-center gap-2">
                <input type="checkbox" name="is_full_day" value="1" id="edit_full_day" />
                <label for="edit_full_day" class="text-sm text-gray-700">Full day(s)</label>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Start Time</label>
                <input type="time" name="start_time" id="edit_start_time" class="border rounded px-3 py-2 w-full" />
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">End Time</label>
                <input type="time" name="end_time" id="edit_end_time" class="border rounded px-3 py-2 w-full" />
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-600 mb-1">Reason</label>
                <input type="text" name="reason" id="edit_reason" class="border rounded px-3 py-2 w-full" />
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Status</label>
                <select name="status" id="edit_status" class="border rounded px-3 py-2 w-full">
                    <option value="pending">Pending</option>
                    <option value="active">Active</option>
                </select>
            </div>
            <div class="md:col-span-2 flex justify-end gap-2 mt-2">
                <button type="button" id="btnCancelEdit" class="px-4 py-2 rounded border">Cancel</button>
                <button class="bg-gray-700 text-white px-4 py-2 rounded">Save</button>
            </div>
        </form>
        <p class="text-xs text-gray-500 mt-2">
            Active items: only Status is editable.<br>
            <strong>Note:</strong> Changing status to "Active" will automatically cancel all reservations within the date range.
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const createModal = document.getElementById('createModal');
    const editModal = document.getElementById('editModal');
    const btnOpenCreate = document.getElementById('btnOpenCreate');
    const btnCloseCreate = document.getElementById('btnCloseCreate');
    const btnCancelCreate = document.getElementById('btnCancelCreate');
    const btnCloseEdit = document.getElementById('btnCloseEdit');
    const btnCancelEdit = document.getElementById('btnCancelEdit');
    const editForm = document.getElementById('editForm');
    let oldStatus = 'pending'; // Store original status for comparison

    function show(el) { el.classList.remove('hidden'); el.classList.add('flex'); }
    function hide(el) { el.classList.add('hidden'); el.classList.remove('flex'); }

    btnOpenCreate?.addEventListener('click', () => show(createModal));
    btnCloseCreate?.addEventListener('click', () => hide(createModal));
    btnCancelCreate?.addEventListener('click', () => hide(createModal));
    btnCloseEdit?.addEventListener('click', () => hide(editModal));
    btnCancelEdit?.addEventListener('click', () => hide(editModal));

    // Create modal: toggle time fields
    const createFullDay = document.getElementById('create_full_day');
    const createStart = document.getElementById('create_start_time');
    const createEnd = document.getElementById('create_end_time');
    function toggleCreateTimes(){
        const dis = createFullDay.checked;
        createStart.disabled = dis; createEnd.disabled = dis;
        if (dis) { createStart.value = ''; createEnd.value = ''; }
    }
    createFullDay?.addEventListener('change', toggleCreateTimes);
    toggleCreateTimes();

    // Validate date inputs
    const createStartDate = createModal?.querySelector('input[name="start_date"]');
    const createEndDate = createModal?.querySelector('input[name="end_date"]');
    
    createStartDate?.addEventListener('change', function() {
        const today = new Date().toISOString().split('T')[0];
        if (this.value < today) {
            alert('Start date cannot be in the past.');
            this.value = today;
        }
        // Update end date minimum if start date changes
        if (createEndDate && this.value) {
            createEndDate.min = this.value >= today ? this.value : today;
        }
    });

    createEndDate?.addEventListener('change', function() {
        const today = new Date().toISOString().split('T')[0];
        if (this.value < today) {
            alert('End date cannot be in the past.');
            this.value = today;
        }
    });

    // Confirm before submit (Create)
    const createForm = createModal?.querySelector('form');
    createForm?.addEventListener('submit', function(e) {
        const startDate = createForm.querySelector('input[name="start_date"]').value;
        const endDate = createForm.querySelector('input[name="end_date"]').value;
        
        // Validate dates
        const today = new Date().toISOString().split('T')[0];
        if (startDate < today) {
            alert('Start date cannot be in the past.');
            e.preventDefault();
            return;
        }
        if (endDate < today) {
            alert('End date cannot be in the past.');
            e.preventDefault();
            return;
        }
        
        const fullDay = createFullDay.checked;
        const st = createStart.value || '';
        const et = createEnd.value || '';
        const reason = (createForm.querySelector('input[name="reason"]').value || '').trim();
        const status = createForm.querySelector('select[name="status"]').value;
        const timeText = fullDay ? 'Full day(s)' : `${st || '—'} - ${et || '—'}`;
        const cancelNote = status === 'active' ? '\n\n⚠️ All reservations within this date range will be automatically cancelled!' : '';
        const msg = `Add closure period?\n\nDates: ${startDate} to ${endDate}\nTime: ${timeText}\nReason: ${reason || '—'}\nStatus: ${status.toUpperCase()}${cancelNote}`;
        if (!confirm(msg)) {
            e.preventDefault();
        }
    });

    // Edit modal: populate and toggle
    document.querySelectorAll('.btnEdit').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const startDate = btn.dataset.start_date;
            const endDate = btn.dataset.end_date;
            const isFullDay = btn.dataset.is_full_day === '1';
            const startTime = btn.dataset.start_time || '';
            const endTime = btn.dataset.end_time || '';
            const reason = btn.dataset.reason || '';
            const status = btn.dataset.status || 'pending';

            const actionTemplate = `{{ route('admin.closure_periods.update', ['closurePeriod' => '__ID__']) }}`;
            editForm.action = actionTemplate.replace('__ID__', id);

            const elStart = document.getElementById('edit_start_date');
            const elEnd = document.getElementById('edit_end_date');
            const elFull = document.getElementById('edit_full_day');
            const elST = document.getElementById('edit_start_time');
            const elET = document.getElementById('edit_end_time');
            const elReason = document.getElementById('edit_reason');
            const elStatus = document.getElementById('edit_status');

            // Store original status for comparison
            oldStatus = status;

            elStart.value = startDate;
            elEnd.value = endDate;
            elFull.checked = isFullDay;
            elST.value = startTime;
            elET.value = endTime;
            elReason.value = reason;
            elStatus.value = status;

            // Active periods: lock fields except status
            const locked = status === 'active';
            elStart.disabled = locked;
            elEnd.disabled = locked;
            elFull.disabled = locked;
            elReason.disabled = locked;
            const timesLocked = locked || elFull.checked;
            elST.disabled = timesLocked;
            elET.disabled = timesLocked;

            show(editModal);
        });
    });

    document.getElementById('edit_full_day')?.addEventListener('change', function() {
        const locked = document.getElementById('edit_status').value === 'active';
        const elST = document.getElementById('edit_start_time');
        const elET = document.getElementById('edit_end_time');
        const dis = locked || this.checked;
        elST.disabled = dis; elET.disabled = dis;
        if (this.checked) { elST.value = ''; elET.value = ''; }
    });

    document.getElementById('edit_status')?.addEventListener('change', function() {
        const isActive = this.value === 'active';
        const elStart = document.getElementById('edit_start_date');
        const elEnd = document.getElementById('edit_end_date');
        const elFull = document.getElementById('edit_full_day');
        const elReason = document.getElementById('edit_reason');
        const elST = document.getElementById('edit_start_time');
        const elET = document.getElementById('edit_end_time');
        elStart.disabled = isActive;
        elEnd.disabled = isActive;
        elFull.disabled = isActive;
        elReason.disabled = isActive;
        const timesLocked = isActive || elFull.checked;
        elST.disabled = timesLocked;
        elET.disabled = timesLocked;
        if (elFull.checked) { elST.value = ''; elET.value = ''; }
    });

    // Validate date inputs in edit form
    const editStartDate = document.getElementById('edit_start_date');
    const editEndDate = document.getElementById('edit_end_date');
    
    editStartDate?.addEventListener('change', function() {
        const today = new Date().toISOString().split('T')[0];
        if (this.value < today) {
            alert('Start date cannot be in the past.');
            this.value = today;
        }
        // Update end date minimum if start date changes
        if (editEndDate && this.value) {
            editEndDate.min = this.value >= today ? this.value : today;
        }
    });

    editEndDate?.addEventListener('change', function() {
        const today = new Date().toISOString().split('T')[0];
        if (this.value < today) {
            alert('End date cannot be in the past.');
            this.value = today;
        }
    });

    // Confirm before submit (Edit)
    editForm?.addEventListener('submit', function(e) {
        const startDate = (document.getElementById('edit_start_date').value || '');
        const endDate = (document.getElementById('edit_end_date').value || '');
        
        // Validate dates
        const today = new Date().toISOString().split('T')[0];
        if (startDate < today) {
            alert('Start date cannot be in the past.');
            e.preventDefault();
            return;
        }
        if (endDate < today) {
            alert('End date cannot be in the past.');
            e.preventDefault();
            return;
        }
        
        const fullDay = document.getElementById('edit_full_day').checked;
        const st = document.getElementById('edit_start_time').value || '';
        const et = document.getElementById('edit_end_time').value || '';
        const reason = (document.getElementById('edit_reason').value || '').trim();
        const status = document.getElementById('edit_status').value;
        const timeText = fullDay ? 'Full day(s)' : `${st || '—'} - ${et || '—'}`;
        const cancelNote = (oldStatus !== 'active' && status === 'active') ? '\n\n⚠️ All reservations within this date range will be automatically cancelled!' : '';
        const msg = `Save changes to closure period?\n\nDates: ${startDate} to ${endDate}\nTime: ${timeText}\nReason: ${reason || '—'}\nStatus: ${status.toUpperCase()}${cancelNote}`;
        if (!confirm(msg)) {
            e.preventDefault();
        }
    });
});
</script>
@endsection


