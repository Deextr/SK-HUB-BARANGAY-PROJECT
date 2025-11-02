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
        <a href="{{ route('resident.reservation') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Back</a>
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Print</button>
        <button onclick="exportToImage()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Export to Image</button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
function exportToImage() {
    const ticketElement = document.getElementById('ticket-print');
    
    // Temporarily hide the no-print buttons for cleaner image
    const noPrintElements = document.querySelectorAll('.no-print');
    noPrintElements.forEach(el => {
        el.style.display = 'none';
    });
    
    html2canvas(ticketElement, {
        backgroundColor: '#ffffff',
        scale: 2,
        logging: false,
        useCORS: true
    }).then(canvas => {
        // Restore no-print elements
        noPrintElements.forEach(el => {
            el.style.display = '';
        });
        
        // Convert canvas to image and download
        const link = document.createElement('a');
        link.download = 'reservation-ticket-{{ $reservation->reference_no }}.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    }).catch(error => {
        // Restore no-print elements on error
        noPrintElements.forEach(el => {
            el.style.display = '';
        });
        alert('Failed to export image. Please try again.');
        console.error('Export error:', error);
    });
}
</script>

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


