@extends('layouts.resident_panel')

@section('title', 'My Reservations')

@section('content')


    <!-- Feedback Messages -->
    @if(session('status'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-r flex items-start gap-3">
            <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-r">
            @foreach($errors->all() as $error)
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                    <span>{{ $error }}</span>
                </div>
            @endforeach
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
                $currentSort = $sort ?? request('sort');
                $currentDirection = $direction ?? request('direction');
            @endphp
            <form method="GET" class="space-y-4">
                <!-- Row 1: Search and Date -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search Input -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by reference, service, or status..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" />
                    </div>

                    <!-- Date Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" name="date" value="{{ request('date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" />
                    </div>
                </div>

                <!-- Row 2: Sort By, Status, and Order (Horizontal) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Sort By -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                        <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="reservation_date" {{ $currentSort=='reservation_date'?'selected':'' }}>Date</option>
                            <option value="reference_no" {{ $currentSort=='reference_no'?'selected':'' }}>Reference</option>
                            <option value="status" {{ $currentSort=='status'?'selected':'' }}>Status</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <!-- Sort Direction -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                        <select name="direction" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
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
                    <a href="{{ route('resident.reservation') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition border border-gray-300">
                        Clear All
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Reservations List -->
    @if(($items ?? collect())->count() > 0)
        <div class="space-y-4">
            @foreach($items as $res)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                    <!-- Mobile & Desktop Layout -->
                    <div class="p-5">
                        <!-- Header Row -->
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="text-lg font-semibold text-gray-900">{{ $res->service->name }}</span>
                                    @if($res->status === 'cancelled')
                                        <span class="text-xs font-medium text-red-600">Cancelled</span>
                                    @elseif($res->status === 'completed')
                                        <span class="text-xs font-medium text-green-600">Completed</span>
                                    @elseif($res->status === 'pending')
                                        <span class="text-xs font-medium text-amber-600">Pending</span>
                                    @else
                                        <span class="text-xs font-medium text-blue-600">{{ ucfirst($res->status) }}</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500">Ref: {{ $res->reference_no }}</p>
                            </div>
                        </div>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 pb-4 border-b border-gray-100">
                            <div class="flex items-center gap-2 text-sm">
                                <i class="fas fa-calendar text-gray-400"></i>
                                <span class="text-gray-700">{{ $res->reservation_date->format('M j, Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <i class="fas fa-clock text-gray-400"></i>
                                <span class="text-gray-700">{{ \Carbon\Carbon::parse($res->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('g:i A') }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-wrap gap-2">
                            <button 
                                type="button" 
                                onclick="openDetailsModal({{ $res->id }}, '{{ $res->status }}')" 
                                class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition"
                            >
                                <i class="fas fa-info-circle mr-2"></i>View Details
                            </button>

                            <a 
                                href="{{ route('resident.reservation.ticket', $res->id) }}" 
                                class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg text-sm font-medium hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition"
                            >
                                <i class="fas fa-ticket-alt mr-2"></i>View Ticket
                            </a>

                            @php
                                $minutesSinceCreation = $res->created_at->diffInMinutes(now());
                                $canCancel = $minutesSinceCreation <= 10 && !in_array($res->status, ['cancelled','completed']);
                            @endphp

                            @if($canCancel)
                                <form action="{{ route('resident.reservation.destroy', $res->id) }}" method="POST" onsubmit="return confirm('Cancel this reservation? This action cannot be undone.')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-medium hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition"
                                    >
                                        <i class="fas fa-times mr-2"></i>Cancel Reservation
                                    </button>
                                </form>
                            @elseif(!in_array($res->status, ['cancelled','completed']) && $minutesSinceCreation > 10)
                                <span class="inline-flex items-center px-3 py-2 text-xs text-gray-500 bg-gray-50 rounded-lg" title="Cancellation period expired (10 minutes after booking)">
                                    <i class="fas fa-lock mr-1.5"></i>Cancellation period expired
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $items->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
            @if(request('q') || request('date'))
                <div class="max-w-md mx-auto">
                    <i class="fas fa-search text-gray-300 text-5xl mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Results Found</h3>
                    <p class="text-gray-600 mb-6">We couldn't find any reservations matching your filters. Try adjusting your search criteria.</p>
                    <a 
                        href="{{ route('resident.reservation') }}" 
                        class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg font-medium hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition"
                    >
                        <i class="fas fa-redo mr-2"></i>Clear Filters
                    </a>
                </div>
            @else
                <div class="max-w-md mx-auto">
                    <i class="fas fa-calendar-plus text-gray-300 text-5xl mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Reservations Yet</h3>
                    <p class="text-gray-600 mb-6">You haven't made any facility reservations. Start by making your first reservation!</p>
                    <a 
                        href="{{ route('resident.reservation.add') }}" 
                        class="inline-flex items-center px-6 py-3 bg-yellow-500 text-white rounded-lg font-medium hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition"
                    >
                        <i class="fas fa-plus mr-2"></i>Make a Reservation
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>

<!-- Details Modal -->
<div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4" style="display: none;">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col">
        <!-- Modal Header -->
        <div class="bg-yellow-500 px-6 py-4 flex items-center justify-between flex-shrink-0">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <i class="fas fa-file-alt"></i>Reservation Details
            </h2>
            <button 
                type="button" 
                onclick="closeDetailsModal()" 
                class="text-white hover:text-gray-200 transition focus:outline-none"
                aria-label="Close modal"
            >
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Modal Content -->
        <div id="modalContent" class="p-6 overflow-y-auto flex-1">
            <div class="flex items-center justify-center py-8">
                <i class="fas fa-spinner fa-spin text-yellow-500 text-3xl"></i>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t flex justify-end flex-shrink-0">
            <button 
                type="button" 
                onclick="closeDetailsModal()" 
                class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition font-medium"
            >
                Close
            </button>
        </div>
    </div>
</div>

<script>
// Store reservation data
let reservationsData = {!! json_encode($items->map(function($res) {
    $cancelled_by_name = null;
    $cancelled_by_admin = false;
    
    if ($res->cancelled_by) {
        $cancelledByUser = \App\Models\User::find($res->cancelled_by);
        if ($cancelledByUser) {
            $cancelled_by_name = $cancelledByUser->name;
            $cancelled_by_admin = $res->user_id !== $res->cancelled_by;
        }
    }
    
    return [
        'id' => $res->id,
        'reference_no' => $res->reference_no,
        'status' => $res->status,
        'reservation_date' => $res->reservation_date->format('M d, Y'),
        'start_time' => \Carbon\Carbon::parse($res->start_time)->format('g:i A'),
        'end_time' => \Carbon\Carbon::parse($res->end_time)->format('g:i A'),
        'service_name' => $res->service->name,
        'service_description' => $res->service->description ?? 'N/A',
        'units_reserved' => $res->units_reserved,
        'cancellation_reason' => $res->cancellation_reason,
        'suspension_applied' => $res->suspension_applied,
        'cancelled_by_name' => $cancelled_by_name,
        'cancelled_by_admin' => $cancelled_by_admin,
        'cancelled_at' => $res->cancelled_at ? $res->cancelled_at->format('M d, Y g:i A') : null,
        'created_at' => $res->created_at->format('M d, Y g:i A'),
        'actual_time_in' => $res->actual_time_in ? \Carbon\Carbon::parse($res->actual_time_in)->format('g:i A') : null,
        'actual_time_out' => $res->actual_time_out ? \Carbon\Carbon::parse($res->actual_time_out)->format('g:i A') : null,
        'preferences' => $res->preferences,
        'reservation_reason' => $res->reservation_reason,
        'other_reason' => $res->other_reason,
    ];
})->keyBy('id')) !!};

function openDetailsModal(reservationId, status) {
    const modal = document.getElementById('detailsModal');
    const content = document.getElementById('modalContent');
    const reservation = reservationsData[reservationId];

    if (!reservation) {
        content.innerHTML = '<div class="text-center text-red-600 py-8">Reservation not found</div>';
        modal.style.display = 'flex';
        return;
    }

    let html = `
        <div class="space-y-6">
            <!-- Reference & Status -->
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Reference Number</p>
                        <p class="text-lg font-bold text-gray-900">${reservation.reference_no}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Status</p>
                        ${getStatusBadge(status)}
                    </div>
                </div>
            </div>

            ${status === 'cancelled' ? getCancelledDetails(reservation) : ''}

            <!-- Reservation Details -->
            <div>
                <h3 class="text-base font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-yellow-500"></i>Reservation Information
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Date:</span>
                        <span class="text-sm font-medium text-gray-900">${reservation.reservation_date}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Time:</span>
                        <span class="text-sm font-medium text-gray-900">${reservation.start_time} - ${reservation.end_time}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Service:</span>
                        <span class="text-sm font-medium text-gray-900">${reservation.service_name}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Units:</span>
                        <span class="text-sm font-medium text-gray-900">${reservation.units_reserved} unit(s)</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-sm text-gray-600">Booked On:</span>
                        <span class="text-sm font-medium text-gray-900">${reservation.created_at}</span>
                    </div>
                </div>
            </div>

            ${status === 'completed' && reservation.actual_time_in ? `
            <div>
                <h3 class="text-base font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <i class="fas fa-clock text-yellow-500"></i>Actual Usage
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Time In:</span>
                        <span class="text-sm font-medium text-gray-900">${reservation.actual_time_in}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-sm text-gray-600">Time Out:</span>
                        <span class="text-sm font-medium text-gray-900">${reservation.actual_time_out || 'N/A'}</span>
                    </div>
                </div>
            </div>
            ` : ''}

            ${reservation.reservation_reason || reservation.other_reason ? `
            <div>
                <h3 class="text-base font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <i class="fas fa-comment text-yellow-500"></i>Additional Notes
                </h3>
                <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                    ${reservation.reservation_reason ? `
                        <p class="text-sm"><span class="font-medium text-gray-700">Reason:</span> <span class="text-gray-900">${reservation.reservation_reason}</span></p>
                    ` : ''}
                    ${reservation.other_reason ? `
                        <p class="text-sm"><span class="font-medium text-gray-700">Details:</span> <span class="text-gray-900">${reservation.other_reason}</span></p>
                    ` : ''}
                </div>
            </div>
            ` : ''}
        </div>
    `;

    content.innerHTML = html;
    modal.style.display = 'flex';
}

function getStatusBadge(status) {
    const badges = {
        'cancelled': '<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1.5"></i>Cancelled</span>',
        'completed': '<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1.5"></i>Completed</span>',
        'pending': '<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800"><i class="fas fa-clock mr-1.5"></i>Pending</span>',
    };
    return badges[status] || `<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800"><i class="fas fa-calendar-check mr-1.5"></i>${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
}

function getCancelledDetails(reservation) {
    return `
        <div class="bg-red-50 rounded-lg p-4 border border-red-200">
            <h3 class="text-base font-bold text-red-900 mb-3 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle"></i>Cancellation Information
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-red-100">
                    <span class="text-sm text-gray-700">Cancelled By:</span>
                    <span class="text-sm font-medium text-gray-900">${reservation.cancelled_by_admin ? 'Administrator' : (reservation.cancelled_by_name || 'You')}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-red-100">
                    <span class="text-sm text-gray-700">Reason:</span>
                    <span class="text-sm font-medium text-gray-900">${reservation.cancellation_reason || 'No reason provided'}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-red-100">
                    <span class="text-sm text-gray-700">Cancelled On:</span>
                    <span class="text-sm font-medium text-gray-900">${reservation.cancelled_at || 'N/A'}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-sm text-gray-700">Suspension:</span>
                    <span class="text-sm font-medium ${reservation.suspension_applied ? 'text-red-600' : 'text-green-600'}">
                        ${reservation.suspension_applied ? 'Applied' : 'Not Applied'}
                    </span>
                </div>
            </div>
        </div>
    `;
}

function closeDetailsModal() {
    document.getElementById('detailsModal').style.display = 'none';
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('detailsModal');
    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeDetailsModal();
        }
    });
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDetailsModal();
    }
});

// Filter Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleFilters');
    const filtersContent = document.getElementById('filtersContent');
    const toggleText = document.getElementById('toggleText');
    
    if (toggleBtn && filtersContent) {
        toggleBtn.addEventListener('click', function() {
            filtersContent.classList.toggle('hidden');
            toggleText.textContent = filtersContent.classList.contains('hidden') ? 'Show Filters' : 'Hide Filters';
        });
    }
});
</script>

@endsection