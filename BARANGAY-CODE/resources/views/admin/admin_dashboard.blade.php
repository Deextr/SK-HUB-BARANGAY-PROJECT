@extends('layouts.admin_panel')

@section('title', 'Dashboard')

@section('content')

<!-- Time Range Filter -->
<div class="mb-6">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('dashboard', ['time_range' => 'today']) }}" 
           class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ $timeRange === 'today' ? 'bg-gradient-to-r from-yellow-500 to-yellow-600 text-white shadow-lg shadow-yellow-500/30' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
            Today
        </a>
        <a href="{{ route('dashboard', ['time_range' => 'last_7_days']) }}" 
           class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ $timeRange === 'last_7_days' ? 'bg-gradient-to-r from-yellow-500 to-yellow-600 text-white shadow-lg shadow-yellow-500/30' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
            7 Days
        </a>
        <a href="{{ route('dashboard', ['time_range' => 'last_30_days']) }}" 
           class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ $timeRange === 'last_30_days' ? 'bg-gradient-to-r from-yellow-500 to-yellow-600 text-white shadow-lg shadow-yellow-500/30' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
            30 Days
        </a>
        <a href="{{ route('dashboard', ['time_range' => 'last_90_days']) }}" 
           class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ $timeRange === 'last_90_days' ? 'bg-gradient-to-r from-yellow-500 to-yellow-600 text-white shadow-lg shadow-yellow-500/30' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
            90 Days
        </a>
        <button type="button" id="customRangeBtn" 
                class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ $timeRange === 'custom' ? 'bg-gradient-to-r from-yellow-500 to-yellow-600 text-white shadow-lg shadow-yellow-500/30' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
            <i class="fas fa-calendar-alt mr-1.5"></i>Custom
        </button>
    </div>

    <!-- Custom Date Range -->
    <div id="custom_date_range" class="mt-4 pt-4 border-t border-gray-100 {{ $timeRange === 'custom' ? '' : 'hidden' }}">
        <form id="customRangeForm" action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap gap-3 items-end">
            <input type="hidden" name="time_range" value="custom">
            <div class="flex-1 min-w-[200px]">
                <label for="start_date" class="block text-xs font-semibold text-gray-600 mb-1.5">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" 
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label for="end_date" class="block text-xs font-semibold text-gray-600 mb-1.5">End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" 
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition">
            </div>
            <button type="submit" class="px-5 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-yellow-500/30 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-all duration-200">
                Apply Filter
            </button>
        </form>
    </div>
</div>

<!-- Metrics Cards - Icons on Left, Text Left Aligned, Numbers Right Aligned -->
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-5 mb-6">
    <!-- Total Reservations -->
    <a href="{{ route('reservation.dashboard') }}" class="group bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md hover:border-yellow-300 transition-all duration-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-blue-50 group-hover:bg-blue-100 transition-colors">
                    <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                </div>
                <div class="text-left">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total Reservations</p>
                    <p class="text-sm text-gray-400">All reservation records</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-3xl font-bold text-gray-900">{{ number_format($metrics['total_reservations']) }}</p>
            </div>
        </div>
    </a>

    <!-- Completed -->
    <a href="{{ route('reservation.dashboard', ['status' => 'completed']) }}" class="group bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md hover:border-green-300 transition-all duration-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-green-50 group-hover:bg-green-100 transition-colors">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="text-left">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Completed</p>
                    <p class="text-sm text-gray-400">Successfully completed</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-3xl font-bold text-gray-900">{{ number_format($metrics['completed_reservations']) }}</p>
            </div>
        </div>
    </a>

    <!-- Pending -->
    <a href="{{ route('reservation.dashboard', ['status' => 'pending']) }}" class="group bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md hover:border-amber-300 transition-all duration-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-amber-50 group-hover:bg-amber-100 transition-colors">
                    <i class="fas fa-hourglass-half text-amber-600 text-xl"></i>
                </div>
                <div class="text-left">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Pending</p>
                    <p class="text-sm text-gray-400">Waiting for completion</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-3xl font-bold text-gray-900">{{ number_format($metrics['pending_reservations']) }}</p>
            </div>
        </div>
    </a>

    <!-- Cancelled -->
    <a href="{{ route('reservation.dashboard', ['status' => 'cancelled']) }}" class="group bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md hover:border-red-300 transition-all duration-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-red-50 group-hover:bg-red-100 transition-colors">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
                <div class="text-left">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Cancelled</p>
                    <p class="text-sm text-gray-400">Cancelled by users</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-3xl font-bold text-gray-900">{{ number_format($metrics['cancelled_reservations']) }}</p>
            </div>
        </div>
    </a>

    <!-- Inactive Users -->
    <a href="{{ route('admin.user_accounts.index') }}" class="group bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md hover:border-indigo-300 transition-all duration-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-indigo-50 group-hover:bg-indigo-100 transition-colors">
                    <i class="fas fa-user-slash text-indigo-600 text-xl"></i>
                </div>
                <div class="text-left">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Inactive Users</p>
                    <p class="text-sm text-gray-400">Users without activity</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-3xl font-bold text-gray-900">{{ number_format($metrics['inactive_users']) }}</p>
            </div>
        </div>
    </a>

    <!-- Top Service -->
    <a href="{{ route('admin.services.index') }}" class="group bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md hover:border-blue-300 transition-all duration-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-blue-50 group-hover:bg-blue-100 transition-colors">
                    <i class="fas fa-star text-blue-600 text-xl"></i>
                </div>
                <div class="text-left flex-1 min-w-0">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Top Service</p>
                    <p class="text-sm text-gray-400 truncate" title="{{ $metrics['most_used_service'] }}">
                        {{ Str::limit($metrics['most_used_service'], 20) }}
                    </p>
                </div>
            </div>
            <div class="text-right ml-2">
                <p class="text-2xl font-bold text-gray-900">{{ number_format($metrics['most_used_service_count']) }}</p>
                <p class="text-xs text-gray-400">bookings</p>
            </div>
        </div>
    </a>
</div>

<!-- Charts Section - Stacked Vertically with Equal Width -->
<div class="space-y-6 mb-6">
    <!-- Appointment Trends Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-5">
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Appointment Trends</h3>
                <p class="text-xs text-gray-500">Reservation activity over time</p>
            </div>
            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line text-blue-600"></i>
            </div>
        </div>
        <div class="h-80">
            <canvas id="appointmentTrendsChart"></canvas>
        </div>
    </div>

    <!-- Inactive Users Trend -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-5">
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Inactive Users Trend</h3>
                <p class="text-xs text-gray-500">Users without recent activity</p>
            </div>
            <div class="w-10 h-10 bg-yellow-50 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-bar text-yellow-600"></i>
            </div>
        </div>
        <div class="h-80">
            <canvas id="inactiveUsersChart"></canvas>
        </div>
    </div>

    <!-- Popular Services Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-5">
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Popular Services</h3>
                <p class="text-xs text-gray-500">Most frequently reserved services</p>
            </div>
            <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                <i class="fas fa-trophy text-amber-600"></i>
            </div>
        </div>
        <div class="h-80">
            <canvas id="popularServicesChart"></canvas>
        </div>
    </div>
</div>

<!-- Latest Reservations -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Latest Reservations</h3>
            <p class="text-xs text-gray-500">Most recent reservation activity</p>
        </div>
        <a href="{{ route('reservation.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white text-sm font-semibold rounded-lg hover:shadow-lg hover:shadow-yellow-500/30 transition-all duration-200">
            View All <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Reference</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Resident</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Service</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Time</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($latestReservations as $row)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row->reference_no }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row->user?->full_name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row->service?->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row->reservation_date?->format('M d, Y') ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($row->start_time && $row->end_time)
                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $row->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $row->end_time)->format('g:i A') }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($row->status === 'cancelled')
                            <span class="text-red-600 font-medium">Cancelled</span>
                        @elseif($row->status === 'completed')
                            <span class="text-green-600 font-medium">Completed</span>
                        @else
                            <span class="text-amber-600 font-medium">Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="px-6 py-12 text-center" colspan="6">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center mb-4">
                                <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-sm font-semibold text-gray-700 mb-1">No Recent Reservations</p>
                            <p class="text-xs text-gray-500">Reservations will appear here once created</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Custom Range Toggle
        const customRangeBtn = document.getElementById('customRangeBtn');
        const customDateRange = document.getElementById('custom_date_range');
        
        customRangeBtn.addEventListener('click', function() {
            customDateRange.classList.toggle('hidden');
        });
        
        // Chart.js Defaults
        Chart.defaults.font.family = "'Inter', system-ui, -apple-system, sans-serif";
        Chart.defaults.font.size = 12;
        Chart.defaults.color = '#6B7280';
        
        // Color mapping for datasets
        const colorMapping = {
            'Total': { border: '#FBBF24', bg: 'rgba(251, 191, 36, 0.1)' },
            'Completed': { border: '#22C55E', bg: 'rgba(34, 197, 94, 0.1)' },
            'Cancelled': { border: '#EF4444', bg: 'rgba(239, 68, 68, 0.1)' },
            'Pending': { border: '#F97316', bg: 'rgba(249, 115, 22, 0.1)' }
        };
        
        // Appointment Trends Chart
        const appointmentTrendsCtx = document.getElementById('appointmentTrendsChart').getContext('2d');
        
        const appointmentTrendsChart = new Chart(appointmentTrendsCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($appointmentTrends['labels']) !!},
                datasets: {!! json_encode($appointmentTrends['datasets']) !!}.map(dataset => {
                    const colors = colorMapping[dataset.label] || { border: '#6B7280', bg: 'rgba(107, 114, 128, 0.1)' };
                    return {
                        ...dataset,
                        backgroundColor: colors.bg,
                        borderColor: colors.border,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: colors.border,
                        pointBorderWidth: 2,
                        tension: 0.4,
                        fill: true
                    };
                })
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        align: 'end',
                        labels: {
                            boxWidth: 8,
                            boxHeight: 8,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 15,
                            font: { size: 12, weight: '600' }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.98)',
                        titleColor: '#111827',
                        bodyColor: '#374151',
                        borderColor: '#E5E7EB',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: true,
                        boxPadding: 6
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { font: { size: 11 }, color: '#9CA3AF' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F3F4F6', drawBorder: false },
                        border: { display: false },
                        ticks: { precision: 0, font: { size: 11 }, color: '#9CA3AF', padding: 8 }
                    }
                },
                interaction: { mode: 'index', intersect: false }
            }
        });
        
        // Inactive Users Chart - Yellow Color
        const inactiveUsersCtx = document.getElementById('inactiveUsersChart').getContext('2d');
        const inactiveUsersChart = new Chart(inactiveUsersCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($inactiveUsersTrend['labels']) !!},
                datasets: {!! json_encode($inactiveUsersTrend['datasets']) !!}.map(dataset => ({
                    ...dataset,
                    backgroundColor: 'rgba(251, 191, 36, 0.8)',
                    borderColor: '#FBBF24',
                    borderWidth: 1,
                    borderRadius: 8,
                    borderSkipped: false
                }))
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.98)',
                        titleColor: '#111827',
                        bodyColor: '#374151',
                        borderColor: '#E5E7EB',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 12
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { font: { size: 11 }, color: '#9CA3AF' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F3F4F6', drawBorder: false },
                        border: { display: false },
                        ticks: { precision: 0, font: { size: 11 }, color: '#9CA3AF', padding: 8 }
                    }
                }
            }
        });
        
        // Popular Services Chart
        const popularServicesCtx = document.getElementById('popularServicesChart').getContext('2d');
        // Color order: Yellow (1st), Blue (2nd), Green (3rd), then others
        const colors = ['#FBBF24', '#3B82F6', '#10B981', '#8B5CF6', '#EC4899', '#F59E0B', '#6366F1', '#EF4444', '#F97316', '#06B6D4'];
        
        const popularServicesChart = new Chart(popularServicesCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($popularServices['labels']) !!},
                datasets: {!! json_encode($popularServices['datasets']) !!}.map((dataset, index) => ({
                    ...dataset,
                    backgroundColor: {!! json_encode($popularServices['labels']) !!}.map((_, i) => colors[i % colors.length]),
                    borderRadius: 8,
                    borderSkipped: false
                }))
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.98)',
                        titleColor: '#111827',
                        bodyColor: '#374151',
                        borderColor: '#E5E7EB',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 12,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.raw + ' reservation' + (context.raw !== 1 ? 's' : '');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: '#F3F4F6', drawBorder: false },
                        border: { display: false },
                        ticks: { precision: 0, font: { size: 11 }, color: '#9CA3AF', padding: 8 }
                    },
                    y: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { font: { size: 11, weight: '500' }, color: '#374151', padding: 12 }
                    }
                }
            }
        });
    });
</script>
@endsection