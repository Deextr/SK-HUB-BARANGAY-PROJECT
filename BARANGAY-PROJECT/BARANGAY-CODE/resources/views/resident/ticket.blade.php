@extends('layouts.resident_panel')

@section('title', 'Reservation Ticket')

@section('content')
<div id="ticket-print" class="bg-white p-6 rounded-lg shadow-md max-w-xl mx-auto">
    <h2 class="text-2xl font-bold mb-4">Reservation Ticket</h2>
    <div class="border rounded p-4">
        <div class="mb-2"><span class="font-semibold">Reference:</span> {{ $reservation->reference_no }}</div>
        <div class="mb-2"><span class="font-semibold">Reservation ID:</span> {{ $reservation->id }}</div>
        <div class="mb-2"><span class="font-semibold">Service:</span> {{ $reservation->service->name }}</div>
        <div class="mb-2"><span class="font-semibold">Date:</span> {{ $reservation->reservation_date->format('M j, Y') }}</div>
        <div class="mb-2"><span class="font-semibold">Time:</span> {{ \Carbon\Carbon::parse($reservation->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('g:i A') }}</div>
        <div class="mb-2"><span class="font-semibold">Status:</span> {{ ucfirst($reservation->status) }}</div>
        @if($reservation->preferences)
            <div class="mb-2"><span class="font-semibold">Preferences:</span> {{ $reservation->preferences }}</div>
        @endif
    </div>

    <div class="mt-4 flex gap-2 no-print">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded">Print</button>
        <a href="{{ route('resident.reservation') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Back</a>
    </div>
</div>

<style>
@media print {
    /* Hide everything */
    body * { visibility: hidden; }
    /* Show ticket area only */
    #ticket-print, #ticket-print * { visibility: visible; }
    #ticket-print { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none; }
    /* Hide action buttons */
    .no-print { display: none !important; }
}
</style>
@endsection


