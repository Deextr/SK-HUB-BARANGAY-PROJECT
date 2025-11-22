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
    <!-- Suspension Warning -->
    @if(Auth::user()->isSuspended())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Account Suspended</h3>
                <div class="mt-2 text-sm text-red-700">
                    <p>Your account is suspended for {{ Auth::user()->suspension_days_remaining }} days due to 3 no-show or cancellation violations. You cannot make reservations until {{ Auth::user()->suspension_end_date->format('M j, Y') }}.</p>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    @if(session('errors') && session('errors')->has('suspension'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Account Suspended</h3>
                <div class="mt-2 text-sm text-red-700">
                    <p>{{ session('errors')->first('suspension') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-5 rounded-lg shadow flex items-center">
            <div class="p-3 bg-yellow-100 rounded-lg mr-3"><i class="fas fa-calendar-check text-yellow-600 text-xl"></i></div>
            <div>
                <p class="text-gray-500 text-sm">Total Reservations</p>
                <p class="text-2xl font-bold">{{ $totalCount }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-lg shadow flex items-center">
            <div class="p-3 bg-green-100 rounded-lg mr-3"><i class="fas fa-check-circle text-green-600 text-xl"></i></div>
            <div>
                <p class="text-gray-500 text-sm">Approved/Completed</p>
                <p class="text-2xl font-bold">{{ $approvedCount }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-lg shadow flex items-center">
            <div class="p-3 bg-amber-100 rounded-lg mr-3"><i class="fas fa-clock text-amber-600 text-xl"></i></div>
            <div>
                <p class="text-gray-500 text-sm">Pending</p>
                <p class="text-2xl font-bold">{{ $pendingCount }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-lg shadow flex items-center">
            <div class="p-3 bg-red-100 rounded-lg mr-3"><i class="fas fa-times-circle text-red-600 text-xl"></i></div>
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
                                <span class="text-amber-600 font-medium">Pending</span>
                            @else
                                <span class="text-green-600 font-medium">{{ ucfirst($upcoming->status) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('resident.reservation.ticket', $upcoming->id) }}" class="px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded text-sm">Ticket</a>
                        @php
                            $minutesSinceCreation = $upcoming->created_at->diffInMinutes(now());
                            $canCancel = $minutesSinceCreation <= 10 && !in_array($upcoming->status, ['cancelled','completed']);
                        @endphp
                        @if($canCancel)
                            <form action="{{ route('resident.reservation.destroy', $upcoming->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this reservation? This action cannot be undone.')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded text-sm">
                                    Cancel
                                </button>
                            </form>
                        @endif
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
                @if(Auth::user()->isSuspended())
                <button disabled class="w-full inline-flex items-center justify-center bg-gray-400 text-white px-4 py-2 rounded-lg cursor-not-allowed">
                    <i class="fas fa-ban mr-2"></i>Make Reservation (Suspended)
                </button>
                @else
                <a href="{{ route('resident.reservation.add') }}" class="w-full inline-flex items-center justify-center bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Make Reservation
                </a>
                @endif
                <a href="{{ route('resident.reservation') }}" class="w-full inline-flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg transition">
                    <i class="fas fa-list mr-2"></i>My Reservations
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
                                    <span class="text-amber-600 font-medium">Pending</span>
                                @elseif($item->status === 'cancelled')
                                    <span class="text-red-600 font-medium">Cancelled</span>
                                @elseif($item->status === 'completed')
                                    <span class="text-green-600 font-medium">Completed</span>
                                @else
                                    <span class="text-green-600 font-medium">{{ ucfirst($item->status) }}</span>
                                @endif
                            </td>
                            <td class="py-2 px-3 flex gap-2">
                                <a href="{{ route('resident.reservation.ticket', $item->id) }}" class="inline-flex items-center bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-600">
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