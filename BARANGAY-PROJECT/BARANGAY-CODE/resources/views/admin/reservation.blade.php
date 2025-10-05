@extends('layouts.admin_panel')

@section('title', 'Reservations')

@section('content')


<form method="GET" class="mb-4 flex gap-2 flex-wrap">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search id/ref/resident/service/date/time/status" class="border rounded px-3 py-2" />
    <input type="date" name="date" value="{{ request('date') }}" class="border rounded px-3 py-2" />
    <select name="sort" class="border rounded px-3 py-2">
        <option value="id" {{ request('sort')=='id'?'selected':'' }}>ID</option>
        <option value="reference_no" {{ request('sort')=='reference_no'?'selected':'' }}>Reference</option>
        <option value="resident" {{ request('sort')=='resident'?'selected':'' }}>Resident</option>
        <option value="service" {{ request('sort')=='service'?'selected':'' }}>Service</option>
        <option value="reservation_date" {{ request('sort')=='reservation_date'?'selected':'' }}>Date</option>
        <option value="start_time" {{ request('sort')=='start_time'?'selected':'' }}>Start Time</option>
        <option value="end_time" {{ request('sort')=='end_time'?'selected':'' }}>End Time</option>
        <option value="status" {{ request('sort')=='status'?'selected':'' }}>Status</option>
    </select>
    <select name="direction" class="border rounded px-3 py-2">
        <option value="asc" {{ request('direction')=='asc'?'selected':'' }}>Asc</option>
        <option value="desc" {{ request('direction')=='desc'?'selected':'' }}>Desc</option>
    </select>
    <button class="bg-blue-600 text-white px-4 py-2 rounded">Apply</button>
    <a href="{{ route('admin.services.index') }}" class="ml-auto bg-gray-600 text-white px-4 py-2 rounded">Manage Services</a>
    <a href="{{ route('reservation.dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Refresh</a>
    
</form>

<div class="overflow-x-auto">
<table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
    <thead>
        <tr class="bg-gray-100 text-left">
            <th class="py-2 px-4">ID</th>
            <th class="py-2 px-4">Reference</th>
            <th class="py-2 px-4">Resident</th>
            <th class="py-2 px-4">Service</th>
            <th class="py-2 px-4">Date</th>
            <th class="py-2 px-4">Time</th>
            <th class="py-2 px-4">Status</th>
            <th class="py-2 px-4">Preferences</th>
            <th class="py-2 px-4">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reservations as $res)
        <tr class="border-b hover:bg-gray-50">
            <td class="py-2 px-4">{{ $res->id }}</td>
            <td class="py-2 px-4">{{ $res->reference_no }}</td>
            <td class="py-2 px-4">{{ $res->user->name }}</td>
            <td class="py-2 px-4">{{ $res->service->name }}</td>
            <td class="py-2 px-4">{{ $res->reservation_date->format('M j, Y') }}</td>
            <td class="py-2 px-4">{{ \Carbon\Carbon::createFromFormat('H:i:s', $res->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $res->end_time)->format('g:i A') }}</td>
            <td class="py-2 px-4">
                @if($res->status === 'cancelled')
                    <span class="text-red-600 font-semibold">Cancelled</span>
                @elseif($res->status === 'completed')
                    <span class="text-green-600 font-semibold">Completed</span>
                @else
                    <span class="text-yellow-600 font-semibold">Pending</span>
                @endif
            </td>
            <td class="py-2 px-4">
                @if($res->preferences)
                    <span class="bg-pink-100 text-pink-800 px-2 py-1 rounded">{{ $res->preferences }}</span>
                @else
                    <span class="text-gray-400">—</span>
                @endif
            </td>
            <td class="py-2 px-4">
                <button type="button" title="View" data-id="{{ $res->id }}" data-ref="{{ $res->reference_no }}" data-date="{{ $res->reservation_date->format('Y-m-d') }}" data-start="{{ substr($res->start_time,0,5) }}" data-end="{{ substr($res->end_time,0,5) }}" data-in="{{ $res->actual_time_in ? substr($res->actual_time_in,0,5) : '' }}" data-out="{{ $res->actual_time_out ? substr($res->actual_time_out,0,5) : '' }}" data-status="{{ $res->status }}" class="btn-view px-2 py-1 text-blue-600 hover:text-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 3c-5 0-9 7-9 7s4 7 9 7 9-7 9-7-4-7-9-7Zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10Zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/></svg>
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

<div class="mt-4">{{ $reservations->links() }}</div>

<!-- Modal -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-5">
    <div class="flex items-center justify-between mb-3">
      <h3 class="text-lg font-semibold">Reservation Details</h3>
      <button id="modalClose" class="text-gray-500 hover:text-gray-700">&times;</button>
    </div>
    <div id="modalBody" class="space-y-3 text-sm">
      <div><span class="font-semibold">Reference:</span> <span id="m_ref">—</span></div>
      <div><span class="font-semibold">Date:</span> <span id="m_date">—</span></div>
      <div><span class="font-semibold">Booked Time:</span> <span id="m_time">—</span></div>
      <div><span class="font-semibold">Status:</span> <span id="m_status">—</span></div>
      <form id="timesForm" method="POST">
        @csrf
        <div class="grid grid-cols-2 gap-3 mt-2">
          <div>
            <label class="block text-xs text-gray-600 mb-1">Time In</label>
            <input type="time" name="actual_time_in" id="m_time_in" class="border rounded px-2 py-1 w-full" />
          </div>
          <div>
            <label class="block text-xs text-gray-600 mb-1">Time Out</label>
            <input type="time" name="actual_time_out" id="m_time_out" class="border rounded px-2 py-1 w-full" />
          </div>
        </div>
        <div id="actionsRow" class="mt-4 flex justify-end gap-2">
          <button type="submit" name="action" value="save" class="px-3 py-2 bg-gray-200 rounded">Save & Close</button>
          <button type="submit" name="action" value="submit" class="px-3 py-2 bg-blue-600 text-white rounded">Submit</button>
        </div>
        <div id="backRow" class="mt-4 flex justify-end gap-2 hidden">
          <button type="button" id="modalOnlyClose" class="px-3 py-2 bg-gray-200 rounded">Back</button>
        </div>
      </form>
      <p id="m_note" class="text-xs text-gray-500 mt-2">Save & Close stores the time(s) as draft. Submit requires Time Out and marks the reservation as completed.</p>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById('viewModal');
  const close = document.getElementById('modalClose');
  const ref = document.getElementById('m_ref');
  const date = document.getElementById('m_date');
  const time = document.getElementById('m_time');
  const status = document.getElementById('m_status');
  const timeIn = document.getElementById('m_time_in');
  const timeOut = document.getElementById('m_time_out');
  const form = document.getElementById('timesForm');
  const actionsRow = document.getElementById('actionsRow');
  const backRow = document.getElementById('backRow');
  const onlyClose = document.getElementById('modalOnlyClose');

  document.querySelectorAll('.btn-view').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.id;
      ref.textContent = btn.dataset.ref;
      date.textContent = btn.dataset.date;
      time.textContent = `${btn.dataset.start} - ${btn.dataset.end}`;
      status.textContent = btn.dataset.status;
      form.action = `{{ url('admin/reservations') }}/${id}/actual-times`;
      const isLocked = (btn.dataset.status === 'cancelled' || btn.dataset.status === 'completed');
      timeIn.disabled = isLocked;
      timeOut.disabled = isLocked;
      if (isLocked) {
        actionsRow.classList.add('hidden');
        backRow.classList.remove('hidden');
      } else {
        actionsRow.classList.remove('hidden');
        backRow.classList.add('hidden');
      }
      // Pre-fill existing draft values if any
      timeIn.value = btn.dataset.in || '';
      timeOut.value = btn.dataset.out || '';
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    });
  });
  function hide() { modal.classList.add('hidden'); modal.classList.remove('flex'); }
  close.addEventListener('click', hide);
  modal.addEventListener('click', (e) => { if (e.target === modal) hide(); });
  if (onlyClose) { onlyClose.addEventListener('click', hide); }
});
</script>
@endsection
