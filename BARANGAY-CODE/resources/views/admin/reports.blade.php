@extends('layouts.admin_panel')

@section('title', 'Reports')

@section('content')
<div class="space-y-6">
    <!-- Date Range Filter Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Filter Reports by Date Range</h3>
        </div>
        <div class="px-6 py-4">
            <form method="GET" action="{{ route('admin.reports.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Report Type Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Report Type</label>
                        <select name="report_type" id="report_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="all" {{ isset($reportType) && $reportType == 'all' ? 'selected' : '' }}>All Reports</option>
                            <option value="reservations" {{ isset($reportType) && $reportType == 'reservations' ? 'selected' : '' }}>Reservations Report</option>
                            <option value="services" {{ isset($reportType) && $reportType == 'services' ? 'selected' : '' }}>Services Report</option>
                        </select>
                    </div>
                    
                    <!-- Date Range Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                        <select name="date_range" id="date_range" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="all" {{ $dateRange == 'all' ? 'selected' : '' }}>All Time</option>
                            <option value="weekly" {{ $dateRange == 'weekly' ? 'selected' : '' }}>This Week</option>
                            <option value="monthly" {{ $dateRange == 'monthly' ? 'selected' : '' }}>This Month</option>
                            <option value="yearly" {{ $dateRange == 'yearly' ? 'selected' : '' }}>This Year</option>
                            <option value="custom" {{ $dateRange == 'custom' ? 'selected' : '' }}>Custom Range</option>
                        </select>
                    </div>

                    <!-- Start Date (shown when custom is selected) -->
                    <div id="start_date_container" class="{{ $dateRange == 'custom' ? '' : 'hidden' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- End Date (shown when custom is selected) -->
                    <div id="end_date_container" class="{{ $dateRange == 'custom' ? '' : 'hidden' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Apply Button -->
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition font-medium">
                            <i class="fas fa-filter mr-2"></i>Apply Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Export Options -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Export Reports</h3>
        </div>
        <div class="px-6 py-4">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.reports.export.csv', ['date_range' => $dateRange, 'start_date' => $startDate, 'end_date' => $endDate, 'report_type' => isset($reportType) ? $reportType : 'all']) }}" 
                   class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-medium">
                    <i class="fas fa-file-csv mr-2"></i>Export as CSV
                </a>
                <a href="{{ route('admin.reports.export.pdf', ['date_range' => $dateRange, 'start_date' => $startDate, 'end_date' => $endDate, 'report_type' => isset($reportType) ? $reportType : 'all']) }}" 
                   class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-medium">
                    <i class="fas fa-file-pdf mr-2"></i>Export as PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Reservations Report Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 {{ (!isset($reportType) || $reportType == 'all' || $reportType == 'reservations') ? '' : 'hidden' }}" id="reservations-report">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Reservations Report</h3>
            <p class="text-sm text-gray-500 mt-1">
                @if($calculatedStartDate && $calculatedEndDate)
                    Period: {{ $calculatedStartDate->format('M d, Y') }} - {{ $calculatedEndDate->format('M d, Y') }}
                @else
                    Period: All Time
                @endif
                @if(isset($reservationsData))
                    • Total Records: <strong>{{ $reservationsData->count() }}</strong>
                @endif
            </p>
        </div>
        <div class="px-6 py-4">
            @if(isset($reservationsData) && $reservationsData->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Reference No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Resident Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Service Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($reservationsData as $reservation)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $reservation->reference_no }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $reservation->user ? $reservation->user->name : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $reservation->service ? $reservation->service->name : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($reservation->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('h:i A') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($reservation->status === 'pending')
                                            <span class="text-amber-600 font-medium">Pending</span>
                                        @elseif($reservation->status === 'confirmed')
                                            <span class="text-green-600 font-medium">Confirmed</span>
                                        @elseif($reservation->status === 'completed')
                                            <span class="text-green-600 font-medium">Completed</span>
                                        @elseif($reservation->status === 'cancelled')
                                            <span class="text-red-600 font-medium">Cancelled</span>
                                        @else
                                            <span class="text-gray-600 font-medium">{{ ucfirst($reservation->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Reservations Found</h3>
                    <p class="text-gray-500">There are no reservations in the selected period.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Services Report Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 {{ (!isset($reportType) || $reportType == 'all' || $reportType == 'services') ? '' : 'hidden' }}" id="services-report">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Services Report</h3>
            <p class="text-sm text-gray-500 mt-1">
                @if($calculatedStartDate && $calculatedEndDate)
                    Period: {{ $calculatedStartDate->format('M d, Y') }} - {{ $calculatedEndDate->format('M d, Y') }}
                @else
                    Period: All Time
                @endif
                @if(isset($servicesData))
                    • Total Services: <strong>{{ count($servicesData) }}</strong>
                @endif
            </p>
        </div>
        <div class="px-6 py-4">
            @if(isset($servicesData) && count($servicesData) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Service Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Total Usage</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Unique Users</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($servicesData as $data)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $data['service']->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $data['service']->description ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">{{ number_format($data['usage_count']) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ number_format($data['unique_users']) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $data['service']->capacity_units }} units</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($data['service']->is_active)
                                            <span class="text-green-600 font-medium">Active</span>
                                        @else
                                            <span class="text-gray-600 font-medium">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-toolbox text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Services Data Available</h3>
                    <p class="text-gray-500">There are no active services or reservations in the selected period.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateRangeSelect = document.getElementById('date_range');
    const startDateContainer = document.getElementById('start_date_container');
    const endDateContainer = document.getElementById('end_date_container');
    const reportTypeSelect = document.getElementById('report_type');
    const reservationsReport = document.getElementById('reservations-report');
    const servicesReport = document.getElementById('services-report');
    
    dateRangeSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            startDateContainer.classList.remove('hidden');
            endDateContainer.classList.remove('hidden');
        } else {
            startDateContainer.classList.add('hidden');
            endDateContainer.classList.add('hidden');
        }
    });
    
    // Handle report type changes
    reportTypeSelect.addEventListener('change', function() {
        if (this.value === 'all') {
            reservationsReport.classList.remove('hidden');
            servicesReport.classList.remove('hidden');
        } else if (this.value === 'reservations') {
            reservationsReport.classList.remove('hidden');
            servicesReport.classList.add('hidden');
        } else if (this.value === 'services') {
            reservationsReport.classList.add('hidden');
            servicesReport.classList.remove('hidden');
        }
    });
});
</script>
@endsection

