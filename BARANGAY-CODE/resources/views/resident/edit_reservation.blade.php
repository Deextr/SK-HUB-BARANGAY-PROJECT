@extends('layouts.resident_panel')

@section('title', 'Edit Reservation')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md max-w-3xl mx-auto">
    <h2 class="text-2xl font-bold mb-4">Edit Reservation</h2>

    <form action="{{ route('resident.reservation.update', $reservation->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">Date</label>
            <input type="date" name="reservation_date" value="{{ $reservation->reservation_date->format('Y-m-d') }}" min="{{ date('Y-m-d') }}" class="border rounded px-3 py-2 w-full" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Start Time</label>
                <input type="time" name="start_time" value="{{ substr($reservation->start_time,0,5) }}" class="border rounded px-3 py-2 w-full" />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">End Time</label>
                <input type="time" name="end_time" value="{{ substr($reservation->end_time,0,5) }}" class="border rounded px-3 py-2 w-full" />
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Service</label>
            <select name="service_id" class="border rounded px-3 py-2 w-full">
                @foreach($services as $service)
                    <option value="{{ $service->id }}" {{ $reservation->service_id == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                @endforeach
            </select>
        </div>


        <div>
            <label class="block text-sm font-medium mb-1">Preferences (optional)</label>
            <textarea name="preferences" rows="3" class="border rounded px-3 py-2 w-full">{{ $reservation->preferences }}</textarea>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('resident.reservation') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Back</a>
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
        </div>
    </form>
</div>
@endsection


