@extends('layouts.resident_panel')

@section('title', 'My Reservations')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <form method="GET" class="mb-4 flex gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search ref/service/status/date/time" class="border rounded px-3 py-2" />
        <input type="date" name="date" value="{{ request('date') }}" class="border rounded px-3 py-2" />
        <select name="sort" class="border rounded px-3 py-2">
            <option value="reservation_date" {{ ($sort ?? request('sort'))=='reservation_date'?'selected':'' }}>Date</option>
            <option value="reference_no" {{ ($sort ?? request('sort'))=='reference_no'?'selected':'' }}>Reference</option>
            <option value="status" {{ ($sort ?? request('sort'))=='status'?'selected':'' }}>Status</option>
        </select>
        <select name="direction" class="border rounded px-3 py-2">
            <option value="asc" {{ ($direction ?? request('direction'))=='asc'?'selected':'' }}>Asc</option>
            <option value="desc" {{ ($direction ?? request('direction'))=='desc'?'selected':'' }}>Desc</option>
        </select>
        <button class="bg-yellow-500 text-white px-4 py-2 rounded font-medium hover:bg-yellow-600">Apply</button>
    </form>

    @if(session('status'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if(($items ?? collect())->count() > 0)
    <table class="min-w-full border rounded-lg">
        <thead class="bg-yellow-100">
            <tr>
                <th class="py-3 px-4 border text-left">Reference</th>
                <th class="py-3 px-4 border text-left">Date</th>
                <th class="py-3 px-4 border text-left">Time</th>
                <th class="py-3 px-4 border text-left">Service</th>
                <th class="py-3 px-4 border text-left">Status</th>
                <th class="py-3 px-4 border text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $res)
            <tr class="border hover:bg-gray-50">
                <td class="py-3 px-4 border">{{ $res->reference_no }}</td>
                <td class="py-3 px-4 border">{{ $res->reservation_date->format('M j, Y') }}</td>
                <td class="py-3 px-4 border">{{ \Carbon\Carbon::parse($res->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('g:i A') }}</td>
                <td class="py-3 px-4 border">{{ $res->service->name }}</td>
                <td class="py-3 px-4 border">
                    @if($res->status === 'cancelled')
                        <span class="text-red-600 font-medium">Cancelled</span>
                    @elseif($res->status === 'completed')
                        <span class="text-green-600 font-medium">Completed</span>
                    @elseif($res->status === 'pending')
                        <span class="text-amber-600 font-medium">Pending</span>
                    @else
                        <span class="text-green-600 font-medium">{{ ucfirst($res->status) }}</span>
                    @endif
                </td>
                <td class="py-3 px-4 border">
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('resident.reservation.ticket', $res->id) }}" class="inline-flex items-center bg-gray-700 hover:bg-gray-800 text-white px-3 py-1 rounded text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-600">
                            <i class="fa fa-ticket-alt mr-1"></i> Ticket
                        </a>
                        @php
                            $minutesSinceCreation = $res->created_at->diffInMinutes(now());
                            $canCancel = $minutesSinceCreation <= 10 && !in_array($res->status, ['cancelled','completed']);
                        @endphp
                        @if($canCancel)
                            <form action="{{ route('resident.reservation.destroy', $res->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this reservation? This action cannot be undone.')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition duration-200">
                                    <i class="fas fa-times mr-1"></i> Cancel
                                </button>
                            </form>
                        @elseif(!in_array($res->status, ['cancelled','completed']) && $minutesSinceCreation > 10)
                            <span class="text-xs text-gray-500 italic" title="Cancellation period expired (10 minutes after booking)">
                                Cancellation expired
                            </span>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $items->links() }}</div>
    @else
    @if(request('q') || request('date'))
    <div class="text-center py-8 bg-gray-50 rounded-lg">
        <p class="text-gray-500 text-lg mb-4">No results found for your current filters.</p>
        <a href="{{ route('resident.reservation') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition duration-200 font-semibold inline-block">Clear Filters</a>
    </div>
    @else
    <div class="text-center py-8 bg-gray-50 rounded-lg">
        <p class="text-gray-500 text-lg mb-4">You don't have any reservations yet.</p>
        <a href="{{ route('resident.reservation.add') }}" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition duration-200 font-semibold inline-block">
            Make Your First Reservation
        </a>
    </div>
    @endif
    @endif
</div>
@endsection
