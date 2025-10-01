@extends('layouts.admin_panel')

@section('title', 'Dashboard')

@section('content')

@php
    use App\Models\Reservation;
    use App\Models\Service;
    $totalReservations = Reservation::count();
    $pendingReservations = Reservation::where('status','pending')->count();
    $todayReservations = Reservation::whereDate('reservation_date', now()->toDateString())->count();
    $activeServices = Service::where('is_active', true)->count();

    $latest = Reservation::with(['user','service'])
        ->orderByDesc('created_at')
        ->limit(6)
        ->get();
@endphp

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg">
                <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Total Reservations</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalReservations }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-lg">
                <i class="fas fa-hourglass-half text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Pending Reservations</p>
                <p class="text-2xl font-bold text-gray-900">{{ $pendingReservations }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-lg">
                <i class="fas fa-calendar-day text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Today</p>
                <p class="text-2xl font-bold text-gray-900">{{ $todayReservations }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-lg">
                <i class="fas fa-toolbox text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Active Services</p>
                <p class="text-2xl font-bold text-gray-900">{{ $activeServices }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Latest Reservations -->
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-xl font-semibold text-gray-800">Latest Reservations</h3>
        <a href="{{ route('reservation.dashboard') }}" class="text-sm text-gray-700 hover:underline">View all</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="py-2 px-3">Ref</th>
                    <th class="py-2 px-3">Resident</th>
                    <th class="py-2 px-3">Service</th>
                    <th class="py-2 px-3">Date</th>
                    <th class="py-2 px-3">Time</th>
                    <th class="py-2 px-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($latest as $row)
                <tr class="border-b">
                    <td class="py-2 px-3">{{ $row->reference_no }}</td>
                    <td class="py-2 px-3">{{ $row->user?->name }}</td>
                    <td class="py-2 px-3">{{ $row->service?->name }}</td>
                    <td class="py-2 px-3">{{ $row->reservation_date?->format('M d, Y') }}</td>
                    <td class="py-2 px-3">{{ \Carbon\Carbon::createFromFormat('H:i:s', $row->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $row->end_time)->format('g:i A') }}</td>
                    <td class="py-2 px-3">
                        @if($row->status === 'pending')
                            <span class="text-yellow-600 font-semibold">Pending</span>
                        @elseif($row->status === 'cancelled')
                            <span class="text-red-600 font-semibold">Cancelled</span>
                        @elseif($row->status === 'completed')
                            <span class="text-green-600 font-semibold">Completed</span>
                        @else
                            <span class="text-green-600 font-semibold">{{ ucfirst($row->status) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="py-4 px-3 text-gray-500" colspan="6">No recent reservations</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection