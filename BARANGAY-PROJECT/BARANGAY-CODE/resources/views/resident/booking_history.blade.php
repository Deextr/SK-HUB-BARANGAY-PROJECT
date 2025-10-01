@extends('layouts.resident_panel')

@section('title', 'Booking History')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6">Booking History</h2>
    
    <div class="overflow-x-auto">
        <table class="w-full table-auto border border-gray-300 rounded">
            <thead class="bg-yellow-100">
                <tr>
                    <th class="px-4 py-2 border">Date</th>
                    <th class="px-4 py-2 border">Time</th>
                    <th class="px-4 py-2 border">Service Used</th>
                    <th class="px-4 py-2 border">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center border">
                    <td class="px-4 py-2 border">2024-01-15</td>
                    <td class="px-4 py-2 border">10:00 AM</td>
                    <td class="px-4 py-2 border">Study Area</td>
                    <td class="px-4 py-2 border">
                        <span class="px-2 py-1 rounded bg-green-200 text-green-800">Completed</span>
                    </td>
                </tr>
                <tr class="text-center border">
                    <td class="px-4 py-2 border">2024-01-10</td>
                    <td class="px-4 py-2 border">02:00 PM</td>
                    <td class="px-4 py-2 border">Computer Area</td>
                    <td class="px-4 py-2 border">
                        <span class="px-2 py-1 rounded bg-red-200 text-red-800">Cancelled</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection