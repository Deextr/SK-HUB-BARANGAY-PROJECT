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

<div class="max-w-6xl mx-auto space-y-4 px-4 py-4">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-xl shadow-lg p-5">
        <h1 class="text-2xl font-bold text-white mb-1">Welcome back, {{ Auth::user()->name }}! 👋</h1>
        <p class="text-yellow-50">What would you like to do today?</p>
    </div>

    <!-- Suspension Warning (if applicable) -->
    @if(Auth::user()->isSuspended())
    <div class="bg-red-50 border-2 border-red-400 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-exclamation text-white"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-red-800 mb-1">Account Temporarily Suspended</h3>
                <p class="text-sm text-red-700 leading-snug">
                    You can't make new bookings for <strong>{{ Auth::user()->suspension_days_remaining }} days</strong> (until {{ Auth::user()->suspension_end_date->format('M j, Y') }}) due to 3 missed reservations.
                </p>
            </div>
        </div>
    </div>
    @endif

    @if(session('errors') && session('errors')->has('suspension'))
    <div class="bg-red-50 border-2 border-red-400 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-exclamation text-white"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-red-800 mb-1">Account Temporarily Suspended</h3>
                <p class="text-sm text-red-700">{{ session('errors')->first('suspension') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Left Column: Actions + Stats -->
        <div class="lg:col-span-1 space-y-4">
            <!-- Primary Action Buttons -->
            <div class="space-y-3">
                @if(Auth::user()->isSuspended())
                <button disabled class="w-full bg-gray-300 text-gray-500 rounded-xl p-5 shadow-lg cursor-not-allowed">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gray-400 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-ban text-white text-xl"></i>
                        </div>
                        <div class="text-left flex-1">
                            <h3 class="text-lg font-bold">Book a Service</h3>
                            <p class="text-xs">Currently suspended</p>
                        </div>
                    </div>
                </button>
                @else
                <a href="{{ route('resident.reservation.add') }}" class="block w-full bg-gradient-to-br from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-white rounded-xl p-5 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white bg-opacity-30 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-plus-circle text-white text-xl"></i>
                        </div>
                        <div class="text-left flex-1">
                            <h3 class="text-lg font-bold">Book a Service</h3>
                            <p class="text-xs text-white text-opacity-90">Reserve facilities now</p>
                        </div>
                    </div>
                </a>
                @endif

                <a href="{{ route('resident.reservation') }}" class="block w-full bg-gradient-to-br from-blue-400 to-blue-500 hover:from-blue-500 hover:to-blue-600 text-white rounded-xl p-5 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white bg-opacity-30 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-calendar-alt text-white text-xl"></i>
                        </div>
                        <div class="text-left flex-1">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold">My Bookings</h3>
                                @if($pendingCount > 0)
                                <span class="text-xs font-semibold bg-red-500 px-2 py-1 rounded-full">{{ $pendingCount }}</span>
                                @endif
                            </div>
                            <p class="text-xs text-white text-opacity-90">View all reservations</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-xl shadow-lg p-4">
                <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-chart-simple text-yellow-500"></i>
                    Your Stats
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-800">{{ $totalCount }}</div>
                        <div class="text-xs text-gray-600">Total</div>
                    </div>
                    <div class="text-center p-3 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $approvedCount }}</div>
                        <div class="text-xs text-gray-600">Completed</div>
                    </div>
                    <div class="text-center p-3 bg-amber-50 rounded-lg">
                        <div class="text-2xl font-bold text-amber-600">{{ $pendingCount }}</div>
                        <div class="text-xs text-gray-600">Pending</div>
                    </div>
                    <div class="text-center p-3 bg-red-50 rounded-lg">
                        <div class="text-2xl font-bold text-red-600">{{ $cancelledCount }}</div>
                        <div class="text-xs text-gray-600">Cancelled</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Next Booking + Recent Activity -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Next Booking -->
            @if($upcoming)
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl shadow-lg p-5 border-2 border-purple-200">
                <div class="flex items-center gap-2 mb-3">
                    <i class="fas fa-star text-yellow-500"></i>
                    <h3 class="font-bold text-gray-800">Your Next Booking</h3>
                </div>
                
                <div class="bg-white rounded-lg p-4">
                    <div class="flex items-start gap-4 mb-3">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-pink-400 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-calendar-check text-white text-xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-lg font-bold text-gray-800 mb-1 truncate">{{ $upcoming->service?->name ?? 'Service' }}</h4>
                            <div class="flex flex-col gap-1 text-sm text-gray-600">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar text-gray-400 text-xs"></i>
                                    <span class="truncate">{{ $upcoming->reservation_date?->format('M d, Y') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-clock text-gray-400 text-xs"></i>
                                    <span>{{ \Carbon\Carbon::createFromFormat('H:i:s', $upcoming->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $upcoming->end_time)->format('g:i A') }}</span>
                                </div>
                            </div>
                            @if($upcoming->status === 'pending')
                                <span class="inline-block mt-2 text-xs font-semibold bg-amber-100 text-amber-700 px-2 py-1 rounded-full">Pending</span>
                            @else
                                <span class="inline-block mt-2 text-xs font-semibold bg-green-100 text-green-700 px-2 py-1 rounded-full">✓ {{ ucfirst($upcoming->status) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex gap-2">
                        @php
                            $minutesSinceCreation = $upcoming->created_at->diffInMinutes(now());
                            $canCancel = $minutesSinceCreation <= 10 && !in_array($upcoming->status, ['cancelled','completed']);
                        @endphp
                        @if($canCancel)
                            <form action="{{ route('resident.reservation.destroy', $upcoming->id) }}" method="POST" onsubmit="return confirm('Cancel this booking? You can\'t undo this.')" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2.5 rounded-lg font-semibold text-sm transition-colors">
                                    <i class="fas fa-times-circle mr-1"></i>Cancel
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('resident.reservation.ticket', $upcoming->id) }}" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white text-center py-2.5 rounded-lg font-semibold text-sm transition-colors">
                            <i class="fas fa-ticket-alt mr-1"></i>View Ticket
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-gray-50 rounded-xl p-6 text-center border-2 border-dashed border-gray-300">
                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-calendar-plus text-gray-400 text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-1">No Upcoming Bookings</h3>
                <p class="text-sm text-gray-600 mb-3">You don't have any reservations scheduled.</p>
                @if(!Auth::user()->isSuspended())
                <a href="{{ route('resident.reservation.add') }}" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2.5 rounded-lg font-semibold text-sm transition-colors">
                    <i class="fas fa-plus mr-1"></i>Make Your First Booking
                </a>
                @endif
            </div>
            @endif

            <!-- Recent Activity -->
            @if($recent->isNotEmpty())
            <div class="bg-white rounded-xl shadow-lg p-5">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-history text-gray-400"></i>
                        Recent Activity
                    </h3>
                    <a href="{{ route('resident.reservation') }}" class="text-sm text-blue-600 hover:text-blue-700 font-semibold">
                        See All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="space-y-2">
                    @foreach($recent as $item)
                    <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0
                                    @if($item->status === 'completed') bg-green-100
                                    @elseif($item->status === 'cancelled') bg-red-100
                                    @elseif($item->status === 'pending') bg-amber-100
                                    @else bg-blue-100
                                    @endif">
                                    <i class="fas text-sm
                                        @if($item->status === 'completed') fa-check-circle text-green-600
                                        @elseif($item->status === 'cancelled') fa-times-circle text-red-600
                                        @elseif($item->status === 'pending') fa-clock text-amber-600
                                        @else fa-calendar-check text-blue-600
                                        @endif"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-800 text-sm truncate">{{ $item->service?->name ?? 'Service' }}</h4>
                                    <p class="text-xs text-gray-600">{{ $item->reservation_date?->format('M d, Y') }} • {{ \Carbon\Carbon::createFromFormat('H:i:s', $item->start_time)->format('g:i A') }}</p>
                                </div>
                            </div>
                            <a href="{{ route('resident.reservation.ticket', $item->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors">
                                Ticket
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection