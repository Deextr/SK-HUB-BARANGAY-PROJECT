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
        </tr>
        @endforeach
    </tbody>
</table>
</div>

<div class="mt-4">{{ $reservations->links() }}</div>
@endsection
