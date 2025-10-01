<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 md:flex">

    <!-- Sidebar -->
    <div id="adminSidebar" class="w-full md:w-64 bg-yellow-500 text-gray-900 min-h-screen flex flex-col md:static fixed inset-y-0 left-0 z-40 transform md:transform-none transition-transform duration-200 -translate-x-full md:translate-x-0">
        <div class="p-6 flex flex-col items-center border-b border-yellow-400">
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

        <nav class="flex-1 mt-4">
            <a href="{{ route('dashboard') }}" 
               class="flex items-center py-3 px-6 transition duration-200 {{ request()->routeIs('dashboard') ? 'bg-yellow-400' : '' }}">
                <i class="fas fa-tachometer-alt w-6 mr-3"></i>
                Dashboard
            </a>
            
            <a href="{{ route('reservation.dashboard') }}" 
               class="flex items-center py-3 px-6 transition duration-200 {{ request()->routeIs('reservation.dashboard') ? 'bg-yellow-400' : '' }}">
                <i class="fas fa-calendar-check w-6 mr-3"></i>
                Reservation
            </a>
            
            <a href="{{ route('admin.services.index') }}" 
               class="flex items-center py-3 px-6 transition duration-200 {{ request()->routeIs('admin.services.index') ? 'bg-yellow-400' : '' }}">
                <i class="fas fa-toolbox w-6 mr-3"></i>
                Services
            </a>

            <a href="{{ route('admin.closure_periods.index') }}" 
               class="flex items-center py-3 px-6 transition duration-200 {{ request()->routeIs('admin.closure_periods.index') ? 'bg-yellow-400' : '' }}">
                <i class="fas fa-door-closed w-6 mr-3"></i>
                Closure Periods
            </a>

            <a href="{{ route('admin.services.archives') }}" 
               class="flex items-center py-3 px-6 transition duration-200 {{ request()->routeIs('admin.services.archives') ? 'bg-yellow-400' : '' }}">
                <i class="fas fa-archive w-6 mr-3"></i>
                Archives
            </a>
            
           
          
            

            
        </nav>
        
        <div class="mt-auto p-4 border-t border-yellow-400">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full py-3 px-6 hover:bg-yellow-400 transition duration-200 rounded">
                    <i class="fas fa-sign-out-alt w-6 mr-3"></i>
                    Log Out
                </button>
            </form>
        </div>
    </div>

    <!-- Main content -->
    <div class="flex-1 p-4 md:p-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4 md:mb-8">
            <div class="flex items-center gap-3">
                <button id="btnAdminMenu" class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded bg-yellow-500 text-white"><i class="fas fa-bars"></i></button>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">@yield('title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center space-x-3 md:space-x-4">
                <span class="text-gray-600 hidden sm:inline">Welcome, {{ Auth::user()->name }}</span>
                <div class="w-9 h-9 md:w-10 md:h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        @yield('content')
    </div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const sidebar = document.getElementById('adminSidebar');
  const btn = document.getElementById('btnAdminMenu');
  btn?.addEventListener('click', () => {
    const hidden = sidebar.classList.contains('-translate-x-full');
    sidebar.classList.toggle('-translate-x-full', !hidden);
  });
});
</script>
</body>
</html>
