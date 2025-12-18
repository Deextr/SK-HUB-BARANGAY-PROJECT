<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Resident Panel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">

    <!-- Sidebar -->
    <div id="residentSidebar" class="w-full md:w-64 bg-yellow-500 text-white h-screen flex flex-col fixed inset-y-0 left-0 z-40 transform md:transform-none transition-transform duration-200 -translate-x-full md:translate-x-0 overflow-y-auto">
        <div class="p-6 flex flex-col items-center border-b border-yellow-600">
            <!-- Logo Circle -->
            <div class="flex justify-center mb-2 -mt-2">
                <div class="w-24 h-24 rounded-full overflow-hidden shadow-xl">
                    <img src="{{ asset('LOGO.png') }}" 
                         alt="Barangay Logo" 
                         class="w-full h-full object-cover scale-125">
                </div>
            </div>
            <!-- Logo Name -->
            <span class="text-2xl font-bold text-center">BARANGAY 22-C</span>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 mt-4">
            <!-- Dashboard -->
            <a href="{{ route('resident.dashboard') }}" 
               class="flex items-center py-3 px-6 transition duration-200 hover:bg-yellow-600 {{ request()->routeIs('resident.dashboard') ? 'bg-yellow-600' : '' }}">
                <i class="fas fa-tachometer-alt w-6 mr-3"></i>
                Dashboard
            </a>

            <!-- Make Reservation -->
            <a href="{{ route('resident.reservation.add') }}" 
               class="flex items-center py-3 px-6 transition duration-200 hover:bg-yellow-600 {{ request()->routeIs('resident.reservation.add') ? 'bg-yellow-600' : '' }}">
                <i class="fas fa-plus-circle w-6 mr-3"></i>
                Make Reservation
            </a>

            <!-- My Reservations -->
            <a href="{{ route('resident.reservation') }}" 
               class="flex items-center py-3 px-6 transition duration-200 hover:bg-yellow-600 {{ request()->routeIs('resident.reservation') ? 'bg-yellow-600' : '' }}">
                <i class="fas fa-calendar-check w-6 mr-3"></i>
                My Reservations
            </a>

            <!-- Settings -->
            <a href="{{ route('resident.settings.index') }}" 
               class="flex items-center py-3 px-6 transition duration-200 hover:bg-yellow-600 {{ request()->routeIs('resident.settings.*') ? 'bg-yellow-600' : '' }}">
                <i class="fas fa-cog w-6 mr-3"></i>
                Settings
            </a>

            
        </nav>

        <!-- Logout at bottom -->
        <div class="mt-auto p-4 border-t border-yellow-600">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full py-3 px-6 hover:bg-yellow-600 transition duration-200 rounded">
                    <i class="fas fa-sign-out-alt w-6 mr-3"></i>
                    Log Out
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="md:ml-64 p-4 md:p-8 min-h-screen">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4 md:mb-8">
            <div class="flex items-center gap-3">
                <button id="btnResidentMenu" class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded bg-yellow-500 text-white"><i class="fas fa-bars"></i></button>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">@yield('title', 'Resident Panel')</h1>
            </div>
            <div class="flex items-center space-x-3 md:space-x-4">
                <span class="text-gray-600 hidden sm:inline">{{ Auth::user()->first_name ?? 'Resident' }}</span>
                <div class="w-9 h-9 md:w-10 md:h-10 bg-yellow-500 rounded-full flex items-center justify-center overflow-hidden">
                    @if(Auth::user()->profile_picture)
                    <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" 
                         alt="Profile" 
                         class="w-full h-full object-cover">
                    @else
                    <span class="text-white font-bold">{{ strtoupper(substr(Auth::user()->first_name ?? 'R', 0, 1)) }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Page Content -->
        @yield('content')
    </div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const sidebar = document.getElementById('residentSidebar');
  const btn = document.getElementById('btnResidentMenu');
  btn?.addEventListener('click', () => {
    const hidden = sidebar.classList.contains('-translate-x-full');
    sidebar.classList.toggle('-translate-x-full', !hidden);
  });
});
</script>
</body>
</html>
