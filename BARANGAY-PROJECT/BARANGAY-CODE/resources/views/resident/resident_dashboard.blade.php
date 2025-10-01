@extends('layouts.resident_panel')

@section('title', 'Dashboard')

@section('content')
@php
    use App\Models\Reservation;
    $userId = Auth::id();
    $totalCount = Reservation::where('user_id', $userId)->count();
    $approvedCount = Reservation::where('user_id', $userId)->whereIn('status', ['confirmed','completed'])->count();
    $pendingCount = Reservation::where('user_id', $userId)->where('status', 'pending')->count();
    $cancelledCount = Reservation::where('user_id', $userId)->where('status', 'cancelled')->count();
    $upcoming = Reservation::with('service')
        ->where('user_id', $userId)
        ->where(function($q){
            $q->whereDate('reservation_date', '>', now()->toDateString())
              ->orWhere(function($qq){
                  $qq->whereDate('reservation_date', now()->toDateString())
                     ->where('end_time', '>=', now()->format('H:i:s'));
              });
        })
        ->whereIn('status', ['pending','confirmed'])
        ->orderBy('reservation_date')
        ->orderBy('start_time')
        ->first();
    $recent = Reservation::with('service')
        ->where('user_id', $userId)
        ->orderByDesc('reservation_date')
        ->orderByDesc('start_time')
        ->limit(3)
        ->get();
@endphp

<div class="space-y-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-5 rounded-lg shadow flex items-center">
            <div class="p-3 bg-yellow-100 rounded-lg mr-3"><i class="fas fa-calendar-check text-yellow-600"></i></div>
            <div>
                <p class="text-gray-500 text-sm">Total Reservations</p>
                <p class="text-2xl font-bold">{{ $totalCount }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-lg shadow flex items-center">
            <div class="p-3 bg-green-100 rounded-lg mr-3"><i class="fas fa-check-circle text-green-600"></i></div>
            <div>
                <p class="text-gray-500 text-sm">Approved/Completed</p>
                <p class="text-2xl font-bold">{{ $approvedCount }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-lg shadow flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg mr-3"><i class="fas fa-clock text-blue-600"></i></div>
            <div>
                <p class="text-gray-500 text-sm">Pending</p>
                <p class="text-2xl font-bold">{{ $pendingCount }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-lg shadow flex items-center">
            <div class="p-3 bg-red-100 rounded-lg mr-3"><i class="fas fa-times-circle text-red-600"></i></div>
            <div>
                <p class="text-gray-500 text-sm">Cancelled</p>
                <p class="text-2xl font-bold">{{ $cancelledCount }}</p>
            </div>
        </div>
    </div>

    <!-- Content Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Upcoming Reservation -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Upcoming Reservation</h3>
                <a href="{{ route('resident.reservation') }}" class="text-sm text-yellow-700 hover:underline">View all</a>
            </div>
            @if($upcoming)
                <div class="border rounded-lg p-4 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                            <i class="fas fa-calendar text-yellow-700"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $upcoming->service?->name ?? 'Service' }}</p>
                            <p class="text-sm text-gray-600">{{ $upcoming->reservation_date?->format('M d, Y') }} • {{ \Carbon\Carbon::createFromFormat('H:i:s', $upcoming->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $upcoming->end_time)->format('g:i A') }}</p>
                            @if($upcoming->status === 'pending')
                                <span class="text-yellow-600 font-semibold">Pending</span>
                            @else
                                <span class="text-green-600 font-semibold">{{ ucfirst($upcoming->status) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('resident.reservation.edit', $upcoming->id) }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-sm">Modify</a>
                        <a href="{{ route('resident.reservation.ticket', $upcoming->id) }}" class="px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded text-sm">Ticket</a>
                    </div>
                </div>
            @else
                <div class="text-gray-500">No upcoming reservations. Create one below.</div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6 h-max">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('resident.reservation.add') }}" class="w-full inline-flex items-center justify-center bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Make Reservation
                </a>
                <a href="{{ route('resident.reservation') }}" class="w-full inline-flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg transition">
                    <i class="fas fa-list mr-2"></i>My Reservations
                </a>
                <a href="{{ route('resident.booking.history') }}" class="w-full inline-flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg transition">
                    <i class="fas fa-history mr-2"></i>View History
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Reservations -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Reservations</h3>
        @if($recent->isEmpty())
            <div class="text-gray-500">No recent activity.</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left bg-gray-50">
                            <th class="py-2 px-3">Reference</th>
                            <th class="py-2 px-3">Service</th>
                            <th class="py-2 px-3">Date</th>
                            <th class="py-2 px-3">Time</th>
                            <th class="py-2 px-3">Status</th>
                            <th class="py-2 px-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent as $item)
                        <tr class="border-b">
                            <td class="py-2 px-3">{{ $item->reference_no }}</td>
                            <td class="py-2 px-3">{{ $item->service?->name ?? 'Service' }}</td>
                            <td class="py-2 px-3">{{ $item->reservation_date?->format('M d, Y') }}</td>
                            <td class="py-2 px-3">{{ \Carbon\Carbon::createFromFormat('H:i:s', $item->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $item->end_time)->format('g:i A') }}</td>
                            <td class="py-2 px-3">
                                @if($item->status === 'pending')
                                    <span class="text-yellow-600 font-semibold">Pending</span>
                                @elseif($item->status === 'cancelled')
                                    <span class="text-red-600 font-semibold">Cancelled</span>
                                @elseif($item->status === 'completed')
                                    <span class="text-green-600 font-semibold">Completed</span>
                                @else
                                    <span class="text-green-600 font-semibold">{{ ucfirst($item->status) }}</span>
                                @endif
                            </td>
                            <td class="py-2 px-3 flex gap-2">
                                <a href="{{ route('resident.reservation.ticket', $item->id) }}" class="inline-flex items-center bg-gray-700 hover:bg-gray-800 text-white px-3 py-1 rounded text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-600">
                                    <i class="fa fa-ticket-alt mr-1"></i> Ticket
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection