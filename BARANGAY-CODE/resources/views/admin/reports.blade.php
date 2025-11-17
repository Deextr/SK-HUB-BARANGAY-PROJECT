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
                   target="_blank"
                   class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-medium">
                    <i class="fas fa-file-pdf mr-2"></i>Export as PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Reservations Report -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 {{ (!isset($reportType) || $reportType == 'all' || $reportType == 'reservations') ? '' : 'hidden' }}" id="reservations-report">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Reservations Report</h3>
            <p class="text-sm text-gray-500 mt-1">
                @if($calculatedStartDate && $calculatedEndDate)
                    Period: {{ $calculatedStartDate->format('M d, Y') }} - {{ $calculatedEndDate->format('M d, Y') }}
                @else
                    Period: All Time
                @endif
            </p>
        </div>
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Reservations -->
                <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-yellow-600 uppercase tracking-wide">Total Reservations</p>
                            <p class="text-3xl font-bold text-yellow-900 mt-2">{{ isset($reservationsData) ? number_format($reservationsData['total']) : 0 }}</p>
                        </div>
                        <div class="bg-yellow-100 rounded-full p-4">
                            <i class="fas fa-calendar-check text-yellow-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Cancelled Reservations -->
                <div class="bg-red-50 rounded-lg p-6 border border-red-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-red-600 uppercase tracking-wide">Cancelled Reservations</p>
                            <p class="text-3xl font-bold text-red-900 mt-2">{{ isset($reservationsData) ? number_format($reservationsData['cancelled']) : 0 }}</p>
                        </div>
                        <div class="bg-red-100 rounded-full p-4">
                            <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Completed Reservations -->
                <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-600 uppercase tracking-wide">Completed Reservations</p>
                            <p class="text-3xl font-bold text-green-900 mt-2">{{ isset($reservationsData) ? number_format($reservationsData['completed']) : 0 }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-4">
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Statistics Table -->
            <div class="mt-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metric</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Count</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Total Reservations</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($reservationsData['total']) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">100%</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Cancelled</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($reservationsData['cancelled']) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $reservationsData['total'] > 0 ? number_format(($reservationsData['cancelled'] / $reservationsData['total']) * 100, 2) : 0 }}%
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Completed</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($reservationsData['completed']) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $reservationsData['total'] > 0 ? number_format(($reservationsData['completed'] / $reservationsData['total']) * 100, 2) : 0 }}%
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Other Status</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($reservationsData['total'] - $reservationsData['cancelled'] - $reservationsData['completed']) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $reservationsData['total'] > 0 ? number_format((($reservationsData['total'] - $reservationsData['cancelled'] - $reservationsData['completed']) / $reservationsData['total']) * 100, 2) : 0 }}%
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Services Report -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 {{ (!isset($reportType) || $reportType == 'all' || $reportType == 'services') ? '' : 'hidden' }}" id="services-report">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Services Report</h3>
            <p class="text-sm text-gray-500 mt-1">
                @if($calculatedStartDate && $calculatedEndDate)
                    Period: {{ $calculatedStartDate->format('M d, Y') }} - {{ $calculatedEndDate->format('M d, Y') }}
                @else
                    Period: All Time
                @endif
            </p>
        </div>
        <div class="px-6 py-6">
            @if(count($servicesData) > 0)
                <div class="space-y-6">
                    @foreach($servicesData as $data)
                        <div class="border border-gray-200 rounded-lg p-6">
                            <!-- Service Header -->
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h4 class="text-xl font-semibold text-gray-900">{{ $data['service']->name }}</h4>
                                    <p class="text-sm text-gray-500 mt-1">{{ $data['service']->description }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Total Usage</p>
                                    <p class="text-2xl font-bold text-yellow-600">{{ number_format($data['usage_count']) }}</p>
                                </div>
                            </div>

                            <!-- Usage Statistics -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm font-medium text-gray-600">Total Reservations</p>
                                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($data['usage_count']) }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm font-medium text-gray-600">Unique Users</p>
                                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($data['unique_users']) }}</p>
                                </div>
                            </div>

                            <!-- Age Range Breakdown -->
                            <div>
                                <h5 class="text-md font-semibold text-gray-800 mb-3">Age Range Breakdown</h5>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age Range</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Count</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($data['age_breakdown'] as $range => $count)
                                                <tr>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $range }} years</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ number_format($count) }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $data['usage_count'] > 0 ? number_format(($count / $data['usage_count']) * 100, 2) : 0 }}%
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
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

