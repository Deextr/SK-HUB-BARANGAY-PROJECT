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
            $currentSort = $sort ?? request('sort', 'start_date');
            $currentDirection = $direction ?? request('direction', 'desc');
        @endphp
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search Input -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by reason, status, or date..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <!-- Sort By -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                    <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="start_date" {{ $currentSort=='start_date'?'selected':'' }}>Start Date</option>
                        <option value="end_date" {{ $currentSort=='end_date'?'selected':'' }}>End Date</option>
                        <option value="status" {{ $currentSort=='status'?'selected':'' }}>Status</option>
                        <option value="reason" {{ $currentSort=='reason'?'selected':'' }}>Reason</option>
                        <option value="created_at" {{ $currentSort=='created_at'?'selected':'' }}>Created</option>
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
                <a href="{{ route('admin.closure_periods.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition border border-gray-300">
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
            + Closure Period
        </button>
    </div>

    @if(($items ?? collect())->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Dates</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Reason</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($items as $p)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <div>{{ $p->start_date->format('M d, Y') }} – {{ $p->end_date->format('M d, Y') }}</div>
                        <div class="text-xs text-gray-500">Full day</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $p->reason ?? '—' }}</td>
                    <td class="px-6 py-4">
                        @if($p->status === 'active')
                            <span class="text-green-600 font-medium">Active</span>
                        @else
                            <span class="text-amber-600 font-medium">Pending</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button title="Edit Closure Period" class="px-2 py-2 text-blue-600 hover:text-blue-800 font-medium btnEdit {{ $p->status==='active' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                data-id="{{ $p->id }}"
                                data-start_date="{{ $p->start_date->toDateString() }}"
                                data-end_date="{{ $p->end_date->toDateString() }}"
                                data-reason="{{ $p->reason ?? '' }}"
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
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-12 px-4">
        <div class="text-gray-400 mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        @if(request('q'))
            <p class="text-gray-600 mb-4">No results found for your current filters.</p>
            <a href="{{ route('admin.closure_periods.index') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Clear Filters
            </a>
        @else
            <p class="text-gray-600">No closure periods found.</p>
        @endif
    </div>
    @endif
</div>

<!-- Pagination -->
@if(($items ?? collect())->count() > 0)
<div class="mt-6">
    {{ $items->links() }}
</div>
@endif

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
                <button class="bg-yellow-500 text-white px-4 py-2 rounded font-medium hover:bg-yellow-600">Add</button>
            </div>
        </form>
        <p class="text-xs text-gray-500 mt-2">
            Closure periods are always full-day events.<br>
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
                <button class="bg-yellow-500 text-white px-4 py-2 rounded font-medium hover:bg-yellow-600">Save</button>
            </div>
        </form>
        <p class="text-xs text-gray-500 mt-2">
            Closure periods are always full-day events.<br>
            Active items: only Status is editable.<br>
            <strong>Note:</strong> Changing status to "Active" will automatically cancel all reservations within the date range.
        </p>
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
        
        const reason = (createForm.querySelector('input[name="reason"]').value || '').trim();
        const status = createForm.querySelector('select[name="status"]').value;
        const cancelNote = status === 'active' ? '\n\n⚠️ All reservations within this date range will be automatically cancelled!' : '';
        const msg = `Add closure period?\n\nDates: ${startDate} to ${endDate}\nReason: ${reason || '—'}\nStatus: ${status.toUpperCase()}${cancelNote}`;
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
            const reason = btn.dataset.reason || '';
            const status = btn.dataset.status || 'pending';

            const actionTemplate = `{{ route('admin.closure_periods.update', ['closurePeriod' => '__ID__']) }}`;
            editForm.action = actionTemplate.replace('__ID__', id);

            const elStart = document.getElementById('edit_start_date');
            const elEnd = document.getElementById('edit_end_date');
            const elReason = document.getElementById('edit_reason');
            const elStatus = document.getElementById('edit_status');

            // Store original status for comparison
            oldStatus = status;

            elStart.value = startDate;
            elEnd.value = endDate;
            elReason.value = reason;
            elStatus.value = status;

            const locked = status === 'active';
            elStart.disabled = locked;
            elEnd.disabled = locked;
            elReason.disabled = locked;

            show(editModal);
        });
    });

    document.getElementById('edit_status')?.addEventListener('change', function() {
        const isActive = this.value === 'active';
        document.getElementById('edit_start_date').disabled = isActive;
        document.getElementById('edit_end_date').disabled = isActive;
        document.getElementById('edit_reason').disabled = isActive;
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
        
        const reason = (document.getElementById('edit_reason').value || '').trim();
        const status = document.getElementById('edit_status').value;
        const cancelNote = (oldStatus !== 'active' && status === 'active') ? '\n\n⚠️ All reservations within this date range will be automatically cancelled!' : '';
        const msg = `Save changes to closure period?\n\nDates: ${startDate} to ${endDate}\nReason: ${reason || '—'}\nStatus: ${status.toUpperCase()}${cancelNote}`;
        if (!confirm(msg)) {
            e.preventDefault();
        }
    });
});
</script>
@endsection


