<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/audio-notification-service.js') }}"></script>
    <style>
        /* Form styling */
        input, select, textarea {
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
            padding: 0.5rem 0.75rem;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #FBBF24;
            box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.2);
        }
        /* Smooth transitions */
        .transition {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Sidebar -->
    <div id="adminSidebar" class="w-full md:w-64 bg-yellow-500 text-white h-screen flex flex-col fixed inset-y-0 left-0 z-40 transform md:transform-none transition-transform duration-200 -translate-x-full md:translate-x-0 overflow-y-auto">
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

        <nav class="flex-1 mt-4">
            <a href="{{ route('dashboard') }}" 
               class="flex items-center py-3 px-6 transition duration-200 hover:bg-yellow-600 {{ request()->routeIs('dashboard') ? 'bg-yellow-600' : '' }}">
                <i class="fas fa-tachometer-alt w-6 mr-3"></i>
                Dashboard
            </a>
            
            <a href="{{ route('reservation.dashboard') }}" 
               class="flex items-center py-3 px-6 transition duration-200 hover:bg-yellow-600 {{ request()->routeIs('reservation.dashboard') ? 'bg-yellow-600' : '' }}">
                <i class="fas fa-calendar-check w-6 mr-3"></i>
                Reservation
            </a>
            
            <a href="{{ route('admin.services.index') }}" 
               class="flex items-center py-3 px-6 transition duration-200 hover:bg-yellow-600 {{ request()->routeIs('admin.services.index') ? 'bg-yellow-600' : '' }}">
                <i class="fas fa-toolbox w-6 mr-3"></i>
                Services
            </a>

            <a href="{{ route('admin.closure_periods.index') }}" 
               class="flex items-center py-3 px-6 transition duration-200 hover:bg-yellow-600 {{ request()->routeIs('admin.closure_periods.index') ? 'bg-yellow-600' : '' }}">
                <i class="fas fa-door-closed w-6 mr-3"></i>
                Closure Periods
            </a>

            <a href="{{ route('admin.user_accounts.index') }}" 
               class="flex items-center py-3 px-6 transition duration-200 hover:bg-yellow-600 {{ request()->routeIs('admin.user_accounts.*') ? 'bg-yellow-600' : '' }}">
                <i class="fas fa-users w-6 mr-3"></i>
                User Accounts
            </a>

            <a href="{{ route('admin.reports.index') }}" 
               class="flex items-center py-3 px-6 transition duration-200 hover:bg-yellow-600 {{ request()->routeIs('admin.reports.*') ? 'bg-yellow-600' : '' }}">
                <i class="fas fa-chart-bar w-6 mr-3"></i>
                Reports
            </a>

            <a href="{{ route('admin.archives', ['tab' => 'services']) }}" 
               class="flex items-center py-3 px-6 transition duration-200 hover:bg-yellow-600 {{ request()->routeIs('admin.archives') ? 'bg-yellow-600' : '' }}">
                <i class="fas fa-archive w-6 mr-3"></i>
                Archives
            </a>
        </nav>
        
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

    <!-- Main content -->
    <div class="md:ml-64 p-4 md:p-8 min-h-screen">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4 md:mb-8">
            <div class="flex items-center gap-3">
                <button id="btnAdminMenu" class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded bg-yellow-500 text-white"><i class="fas fa-bars"></i></button>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">@yield('title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center space-x-3 md:space-x-4">
                <span class="text-gray-600 hidden sm:inline">Admin</span>
                <div class="w-9 h-9 md:w-10 md:h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold">A</span>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        @yield('content')
    </div>

<!-- 5-Minute Warning Modal (Global for all admin pages) -->
<div id="warningModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-md">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b-2 border-amber-500 bg-amber-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-amber-900">Reservation Ending Soon</h3>
                <button id="warningClose" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-4">
            <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded">
                <p class="text-amber-900 font-semibold text-center">
                    <span id="warningTimeRemaining" class="text-2xl font-bold text-amber-700">5</span> minutes remaining
                </p>
            </div>

            <div id="warningReservationsList" class="space-y-3 text-sm">
                <!-- Single reservation display (default) -->
                <div id="singleReservationDisplay">
                    <div class="flex justify-between items-start">
                        <span class="text-gray-600 font-medium">Resident:</span>
                        <span id="warningResidentName" class="text-gray-900 font-semibold text-right">—</span>
                    </div>
                    <div class="flex justify-between items-start">
                        <span class="text-gray-600 font-medium">Service:</span>
                        <span id="warningService" class="text-gray-900 font-semibold text-right">—</span>
                    </div>
                    <div class="flex justify-between items-start">
                        <span class="text-gray-600 font-medium">Scheduled End Time:</span>
                        <span id="warningTime" class="text-gray-900 font-semibold text-right">—</span>
                    </div>
                </div>
                
                <!-- Multiple reservations display -->
                <div id="multipleReservationsDisplay" class="hidden">
                    <p class="text-gray-600 font-medium mb-2">Multiple reservations ending soon:</p>
                    <div id="warningReservationsListItems" class="space-y-2 max-h-48 overflow-y-auto">
                        <!-- Items will be populated dynamically -->
                    </div>
                </div>
            </div>

            <p class="text-sm text-gray-600 text-center italic">
                Please ensure the resident completes their reservation on time.
            </p>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-lg flex gap-3">
            <button id="warningDismiss" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium transition-colors">
                Dismiss
            </button>
            <button id="warningViewDetails" class="flex-1 px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 font-medium transition-colors">
                View Details
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const sidebar = document.getElementById('adminSidebar');
  const btn = document.getElementById('btnAdminMenu');
  btn?.addEventListener('click', () => {
    const hidden = sidebar.classList.contains('-translate-x-full');
    sidebar.classList.toggle('-translate-x-full', !hidden);
  });
  
  // ===== GLOBAL 5-MINUTE WARNING NOTIFICATION SYSTEM WITH CONTINUOUS AUDIO ALERT =====
  let warningShown = {}; // Track which reservations have shown warnings
  let activeWarningReservations = new Set(); // Track all active warning reservations
  let currentWarningReservationIds = []; // Store current warning reservation IDs (can be multiple)
  const warningModal = document.getElementById('warningModal');
  const warningClose = document.getElementById('warningClose');
  const warningDismiss = document.getElementById('warningDismiss');
  const warningViewDetails = document.getElementById('warningViewDetails');
  const reservationDashboardUrl = '{{ route('reservation.dashboard') }}';
  const reservationDashboardPath = (() => {
      try {
          return new URL(reservationDashboardUrl, window.location.origin).pathname;
      } catch (error) {
          console.error('[Global Warning] Unable to parse reservation dashboard URL:', error);
          return '/admin/reservations';
      }
  })();
  
  let primaryWarningReservationId = null;
  
  // Close warning modal
  function hideWarningModal() {
      warningModal.classList.add('hidden');
      warningModal.classList.remove('flex');
      
      // Stop all audio when modal is dismissed
      if (typeof audioNotificationService !== 'undefined' && audioNotificationService) {
          audioNotificationService.stopAllAudio();
      }
      
      // Clear active warnings
      activeWarningReservations.clear();
      currentWarningReservationIds = [];
  }
  
  if (warningClose) warningClose.addEventListener('click', hideWarningModal);
  if (warningDismiss) warningDismiss.addEventListener('click', hideWarningModal);
  if (warningModal) {
      warningModal.addEventListener('click', (e) => {
          if (e.target === warningModal) hideWarningModal();
      });
  }
  
  // View Details button - navigate to reservation dashboard
  if (warningViewDetails) {
      warningViewDetails.addEventListener('click', function() {
          // Use the first reservation in the active list as the primary target
          const targetReservationId = primaryWarningReservationId || currentWarningReservationIds[0] || null;
          
          // Always hide modal and stop audio first
          hideWarningModal();
          
          // If no specific reservation, just navigate to reservation dashboard
          if (!targetReservationId) {
              window.location.href = reservationDashboardUrl;
              return;
          }
          
          const isAlreadyOnReservationPage = window.location.pathname === reservationDashboardPath;
          
          if (isAlreadyOnReservationPage) {
              // Try to open the reservation modal directly
              const targetBtn = document.querySelector(`.btn-view[data-id="${targetReservationId}"]`);
              if (targetBtn) {
                  targetBtn.click();
              } else {
                  // If button not found (e.g., different filters), store ID and reload page
                  try {
                      localStorage.setItem('pendingReservationViewId', String(targetReservationId));
                  } catch (error) {
                      console.warn('[Global Warning] Unable to store pending reservation view ID:', error);
                  }
                  window.location.href = reservationDashboardUrl;
              }
          } else {
              // Store the reservation ID so the reservation page can auto-open it
              try {
                  localStorage.setItem('pendingReservationViewId', String(targetReservationId));
              } catch (error) {
                  console.warn('[Global Warning] Unable to store pending reservation view ID:', error);
              }
              
              window.location.href = reservationDashboardUrl;
          }
      });
  }
  
  // Fetch today's reservations and check for 5-minute warnings
  async function checkReservationWarnings() {
      try {
          const response = await fetch('{{ url("admin/reservations/today-warnings") }}');
          const data = await response.json();
          
          if (!data.reservations || data.reservations.length === 0) {
              // No reservations, stop audio if playing
              if (activeWarningReservations.size > 0) {
                  if (typeof audioNotificationService !== 'undefined' && audioNotificationService) {
                      audioNotificationService.stopAllAudio();
                  }
                  hideWarningModal();
              }
              return;
          }
          
          const now = new Date();
          const currentTime = now.getHours() * 60 + now.getMinutes(); // minutes since midnight
          const urgentReservations = [];
          
          // Check each reservation
          data.reservations.forEach(reservation => {
              // Only process pending/confirmed reservations
              if (reservation.status !== 'pending' && reservation.status !== 'confirmed') {
                  // If this reservation was in our active list, remove it
                  if (activeWarningReservations.has(reservation.id)) {
                      activeWarningReservations.delete(reservation.id);
                      warningShown[reservation.id] = false; // Allow re-showing if status changes back
                  }
                  return;
              }
              
              // Calculate time remaining
              const [hours, minutes] = reservation.end_time.split(':').map(Number);
              const endTimeMinutes = hours * 60 + minutes;
              let timeRemaining = endTimeMinutes - currentTime;
              
              // Handle case where end time is after midnight (e.g., 00:05 when current is 23:55)
              // If time remaining is very negative (more than 12 hours), it means end time is tomorrow
              if (timeRemaining < -720) {
                  timeRemaining += 1440; // Add 24 hours (1440 minutes)
              }
              
              // Also handle case where end time is early morning (00:00-05:00) and current is late night
              // This happens when reservation ends just after midnight
              if (timeRemaining < -12 * 60 && endTimeMinutes < 5 * 60) {
                  timeRemaining += 1440;
              }
              
              // Show warning if 5 minutes or less remaining and time is positive
              if (timeRemaining > 0 && timeRemaining <= 5) {
                  urgentReservations.push({
                      ...reservation,
                      timeRemaining: Math.floor(timeRemaining)
                  });
                  
                  // Mark as shown if not already
                  if (!warningShown[reservation.id]) {
                      warningShown[reservation.id] = true;
                      activeWarningReservations.add(reservation.id);
                  }
              } else if (timeRemaining <= 0) {
                  // Reservation has passed, remove from active warnings
                  if (activeWarningReservations.has(reservation.id)) {
                      activeWarningReservations.delete(reservation.id);
                      warningShown[reservation.id] = false;
                  }
              }
          });
          
          // Check if any previously active reservations are no longer urgent
          const currentReservationIds = new Set(urgentReservations.map(r => r.id));
          activeWarningReservations.forEach(reservationId => {
              if (!currentReservationIds.has(reservationId)) {
                  // This reservation is no longer urgent
                  activeWarningReservations.delete(reservationId);
              }
          });
          
          // If we have urgent reservations, show modal and play audio
          if (urgentReservations.length > 0) {
              // Update current warning reservation IDs
              currentWarningReservationIds = urgentReservations.map(r => r.id);
              primaryWarningReservationId = urgentReservations[0].id;
              
              // Update modal content
              if (urgentReservations.length === 1) {
                  // Single reservation
                  const res = urgentReservations[0];
                  document.getElementById('singleReservationDisplay').classList.remove('hidden');
                  document.getElementById('multipleReservationsDisplay').classList.add('hidden');
                  document.getElementById('warningResidentName').textContent = res.resident_name;
                  document.getElementById('warningService').textContent = res.service_name;
                  document.getElementById('warningTime').textContent = res.end_time;
                  document.getElementById('warningTimeRemaining').textContent = res.timeRemaining;
              } else {
                  // Multiple reservations
                  document.getElementById('singleReservationDisplay').classList.add('hidden');
                  document.getElementById('multipleReservationsDisplay').classList.remove('hidden');
                  
                  const listContainer = document.getElementById('warningReservationsListItems');
                  listContainer.innerHTML = urgentReservations.map(res => `
                      <div class="bg-gray-50 p-3 rounded border-l-4 border-amber-400">
                          <div class="flex justify-between items-start mb-1">
                              <span class="text-gray-600 font-medium">Resident:</span>
                              <span class="text-gray-900 font-semibold text-right">${res.resident_name}</span>
                          </div>
                          <div class="flex justify-between items-start mb-1">
                              <span class="text-gray-600 font-medium">Service:</span>
                              <span class="text-gray-900 font-semibold text-right">${res.service_name}</span>
                          </div>
                          <div class="flex justify-between items-start">
                              <span class="text-gray-600 font-medium">End Time:</span>
                              <span class="text-gray-900 font-semibold text-right">${res.end_time} (${res.timeRemaining} min)</span>
                          </div>
                      </div>
                  `).join('');
              }
              
              // Show modal
              if (warningModal) {
                  warningModal.classList.remove('hidden');
                  warningModal.classList.add('flex');
              }
              
              // Start continuous looping audio (only if not already playing)
              if (typeof audioNotificationService !== 'undefined' && audioNotificationService) {
                  if (!audioNotificationService.getIsPlaying()) {
                      // Use the first reservation ID for audio tracking
                      const primaryReservationId = urgentReservations[0].id;
                      console.log('[Global Warning] Starting continuous audio for reservation', primaryReservationId);
                      audioNotificationService.startContinuousAudio(primaryReservationId, 0.7);
                  }
              } else {
                  console.error('[Global Warning] Audio service NOT available!');
              }
          } else {
              // No urgent reservations, stop audio and hide modal
              primaryWarningReservationId = null;
              if (activeWarningReservations.size > 0) {
                  if (typeof audioNotificationService !== 'undefined' && audioNotificationService) {
                      audioNotificationService.stopAllAudio();
                  }
                  hideWarningModal();
              }
          }
          
          // Check if any active warning reservations have changed status
          if (activeWarningReservations.size > 0) {
              activeWarningReservations.forEach(reservationId => {
                  const reservation = data.reservations.find(r => r.id === reservationId);
                  if (!reservation || (reservation.status !== 'pending' && reservation.status !== 'confirmed')) {
                      // Status changed to completed/cancelled, remove from active
                      activeWarningReservations.delete(reservationId);
                      warningShown[reservationId] = false;
                      
                      // If this was the primary audio reservation, stop audio
                      if (typeof audioNotificationService !== 'undefined' && audioNotificationService) {
                          audioNotificationService.stopRepeatingAudio(reservationId);
                      }
                  }
              });
              
              // If no more active warnings, stop audio and hide modal
              if (activeWarningReservations.size === 0) {
                  if (typeof audioNotificationService !== 'undefined' && audioNotificationService) {
                      audioNotificationService.stopAllAudio();
                  }
                  hideWarningModal();
              }
          }
          
      } catch (error) {
          console.error('[Global Warning] Error checking reservation warnings:', error);
      }
  }
  
  // Check for warnings every 30 seconds
  setInterval(checkReservationWarnings, 30000);
  
  // Initial check on page load
  checkReservationWarnings();
});
</script>
</body>
</html>
