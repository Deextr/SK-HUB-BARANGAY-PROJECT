<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Default to 'today' if no time range is provided
        $timeRange = $request->input('time_range', 'today');
        $customStartDate = $request->input('start_date');
        $customEndDate = $request->input('end_date');
        
        // Get date range based on selected filter
        $dateRange = $this->getDateRange($timeRange, $customStartDate, $customEndDate);
        $startDate = $dateRange['start_date'];
        $endDate = $dateRange['end_date'];
        
        // Get summary metrics
        $metrics = $this->getSummaryMetrics($startDate, $endDate);
        
        // Get chart data
        $appointmentTrends = $this->getAppointmentTrends($startDate, $endDate);
        $inactiveUsersTrend = $this->getInactiveUsersTrend($startDate, $endDate);
        $popularServices = $this->getPopularServices($startDate, $endDate);
        
        // Get latest reservations
        $latestReservations = Reservation::with(['user', 'service'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();
        
        return view('admin.admin_dashboard', compact(
            'metrics',
            'appointmentTrends',
            'inactiveUsersTrend',
            'popularServices',
            'latestReservations',
            'timeRange',
            'startDate',
            'endDate'
        ));
    }
    
    private function getDateRange($timeRange, $customStartDate = null, $customEndDate = null)
    {
        $endDate = Carbon::now()->endOfDay();
        $startDate = null;
        
        switch ($timeRange) {
            case 'today':
                $startDate = Carbon::now()->startOfDay();
                break;
            case 'last_7_days':
                $startDate = Carbon::now()->subDays(6)->startOfDay();
                break;
            case 'last_30_days':
                $startDate = Carbon::now()->subDays(29)->startOfDay();
                break;
            case 'last_90_days':
                $startDate = Carbon::now()->subDays(89)->startOfDay();
                break;
            case 'custom':
                if ($customStartDate && $customEndDate) {
                    $startDate = Carbon::parse($customStartDate)->startOfDay();
                    $endDate = Carbon::parse($customEndDate)->endOfDay();
                } else {
                    $startDate = Carbon::now()->startOfDay();
                }
                break;
            default:
                $startDate = Carbon::now()->startOfDay();
        }
        
        return [
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
    }
    
    private function getSummaryMetrics($startDate, $endDate)
    {
        // Total reservations within the date range
        $totalReservations = Reservation::whereBetween('created_at', [$startDate, $endDate])->count();
        
        // Reservations by status
        $completedReservations = Reservation::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $cancelledReservations = Reservation::where('status', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $pendingReservations = Reservation::where('status', 'pending')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        // Inactive users (approved users with no reservations in the date range)
        $inactiveUsers = User::where('is_admin', false)
            ->where('account_status', 'approved')
            ->where(function($query) {
                $query->whereNull('is_archived')
                      ->orWhere('is_archived', false);
            })
            ->whereNotIn('id', function($query) use ($startDate, $endDate) {
                $query->select('user_id')
                    ->from('reservations')
                    ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->count();
            
        // Most used services
        $mostUsedService = Service::select('services.name', DB::raw('COUNT(reservations.id) as reservation_count'))
            ->join('reservations', 'services.id', '=', 'reservations.service_id')
            ->whereBetween('reservations.created_at', [$startDate, $endDate])
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('reservation_count')
            ->first();
            
        return [
            'total_reservations' => $totalReservations,
            'completed_reservations' => $completedReservations,
            'cancelled_reservations' => $cancelledReservations,
            'pending_reservations' => $pendingReservations,
            'inactive_users' => $inactiveUsers,
            'most_used_service' => $mostUsedService ? $mostUsedService->name : 'No services used',
            'most_used_service_count' => $mostUsedService ? $mostUsedService->reservation_count : 0,
        ];
    }
    
    private function getAppointmentTrends($startDate, $endDate)
    {
        // Determine the appropriate grouping based on date range
        $diffInDays = $startDate->diffInDays($endDate);
        
        if ($diffInDays <= 1) {
            // Group by hour for a single day
            $groupFormat = 'H:00'; // Hour format
            $dbFormat = '%H:00';
            $dateFormat = 'Y-m-d H:00:00';
            $interval = 'hour';
        } elseif ($diffInDays <= 31) {
            // Group by day for up to a month
            $groupFormat = 'M d';
            $dbFormat = '%Y-%m-%d';
            $dateFormat = 'Y-m-d';
            $interval = 'day';
        } elseif ($diffInDays <= 90) {
            // Group by day for up to 3 months (better granularity)
            $groupFormat = 'M d';
            $dbFormat = '%Y-%m-%d';
            $dateFormat = 'Y-m-d';
            $interval = 'day';
        } else {
            // Group by month for longer periods
            $groupFormat = 'M Y';
            $dbFormat = '%Y-%m';
            $dateFormat = 'Y-m';
            $interval = 'month';
        }
        
        // Generate all periods in the range
        $periods = [];
        $labels = [];
        $current = clone $startDate;
        
        while ($current <= $endDate) {
            $key = $current->format($dateFormat);
            $label = $current->format($groupFormat);
            $periods[$key] = [
                'label' => $label,
                'total' => 0,
                'completed' => 0,
                'cancelled' => 0,
                'pending' => 0
            ];
            $labels[] = $label;
            
            // Advance to next period
            if ($interval === 'hour') {
                $current->addHour();
            } elseif ($interval === 'day') {
                $current->addDay();
            } elseif ($interval === 'week') {
                $current->addWeek();
            } else {
                $current->addMonth();
            }
        }
        
        // Get reservation data grouped by the appropriate time period
        $reservations = DB::table('reservations')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '$dbFormat') as period"),
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed"),
                DB::raw("SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled"),
                DB::raw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending")
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('period')
            ->get();
            
        // Fill in the data
        foreach ($reservations as $row) {
            $key = $row->period;
            if ($interval === 'hour') {
                $key = $startDate->format('Y-m-d') . ' ' . $row->period . ':00';
            } elseif ($interval === 'week') {
                // Extract year and week from period (format: YYYY-WW)
                list($year, $week) = explode('-', $row->period);
                $key = $year . '-' . $week;
            }
            
            if (isset($periods[$key])) {
                $periods[$key]['total'] = $row->total;
                $periods[$key]['completed'] = $row->completed;
                $periods[$key]['cancelled'] = $row->cancelled;
                $periods[$key]['pending'] = $row->pending;
            }
        }
        
        // Prepare data for the chart
        $chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total',
                    'data' => array_column($periods, 'total'),
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderWidth' => 2,
                    'tension' => 0.4
                ],
                [
                    'label' => 'Completed',
                    'data' => array_column($periods, 'completed'),
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'borderWidth' => 2,
                    'tension' => 0.4
                ],
                [
                    'label' => 'Cancelled',
                    'data' => array_column($periods, 'cancelled'),
                    'borderColor' => '#DC2626',
                    'backgroundColor' => 'rgba(220, 38, 38, 0.1)',
                    'borderWidth' => 2,
                    'tension' => 0.4
                ],
                [
                    'label' => 'Pending',
                    'data' => array_column($periods, 'pending'),
                    'borderColor' => '#D97706',
                    'backgroundColor' => 'rgba(217, 119, 6, 0.1)',
                    'borderWidth' => 2,
                    'tension' => 0.4
                ]
            ]
        ];
        
        return $chartData;
    }
    
    private function getInactiveUsersTrend($startDate, $endDate)
    {
        // Determine the appropriate grouping based on date range
        $diffInDays = $startDate->diffInDays($endDate);
        
        if ($diffInDays <= 1) {
            $groupFormat = 'H:00';
            $dbFormat = '%H:00';
            $dateFormat = 'Y-m-d H:00:00';
            $interval = 'hour';
        } elseif ($diffInDays <= 31) {
            $groupFormat = 'M d';
            $dbFormat = '%Y-%m-%d';
            $dateFormat = 'Y-m-d';
            $interval = 'day';
        } elseif ($diffInDays <= 90) {
            $groupFormat = 'M d';
            $dbFormat = '%Y-%m-%d';
            $dateFormat = 'Y-m-d';
            $interval = 'day'; // Use day interval for better accuracy
        } else {
            $groupFormat = 'M Y';
            $dbFormat = '%Y-%m';
            $dateFormat = 'Y-m';
            $interval = 'month';
        }
        
        // Generate all periods in the range
        $periods = [];
        $labels = [];
        $current = clone $startDate;
        
        while ($current <= $endDate) {
            $key = $current->format($dateFormat);
            $label = $current->format($groupFormat);
            $periods[$key] = [
                'label' => $label,
                'inactive' => 0,
                'period_start' => clone $current
            ];
            $labels[] = $label;
            
            // Advance to next period
            if ($interval === 'hour') {
                $current->addHour();
            } elseif ($interval === 'day') {
                $current->addDay();
            } elseif ($interval === 'week') {
                $current->addWeek();
            } else {
                $current->addMonth();
            }
        }
        
        // For each period, count users who were approved but didn't make reservations
        foreach ($periods as $key => $value) {
            $periodStart = clone $value['period_start'];
            
            if ($interval === 'hour') {
                $periodEnd = (clone $periodStart)->addHour();
            } elseif ($interval === 'day') {
                $periodEnd = (clone $periodStart)->endOfDay();
            } elseif ($interval === 'week') {
                $periodEnd = (clone $periodStart)->addWeek();
            } else {
                $periodEnd = (clone $periodStart)->endOfMonth();
            }
            
            // Count users who were approved by this period but didn't make reservations during this period
            $inactiveCount = User::where('is_admin', false)
                ->where('account_status', 'approved')
                ->where(function($query) {
                    $query->whereNull('is_archived')
                          ->orWhere('is_archived', false);
                })
                ->where('created_at', '<=', $periodEnd)
                ->whereNotIn('id', function($query) use ($periodStart, $periodEnd) {
                    $query->select('user_id')
                        ->from('reservations')
                        ->whereBetween('created_at', [$periodStart, $periodEnd]);
                })
                ->count();
                
            $periods[$key]['inactive'] = $inactiveCount;
        }
        
        // Prepare data for the chart
        $chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Inactive Users',
                    'data' => array_column($periods, 'inactive'),
                    'borderColor' => '#6366F1',
                    'backgroundColor' => 'rgba(99, 102, 241, 0.2)',
                    'borderWidth' => 2,
                    'tension' => 0.4
                ]
            ]
        ];
        
        return $chartData;
    }
    
    private function getPopularServices($startDate, $endDate)
    {
        $services = Service::select('services.name', DB::raw('COUNT(reservations.id) as reservation_count'))
            ->leftJoin('reservations', function($join) use ($startDate, $endDate) {
                $join->on('services.id', '=', 'reservations.service_id')
                    ->whereBetween('reservations.created_at', [$startDate, $endDate]);
            })
            ->where('services.is_active', true)
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('reservation_count')
            ->limit(10)
            ->get();
            
        $labels = $services->pluck('name')->toArray();
        $data = $services->pluck('reservation_count')->toArray();
        
        // Generate colors for each service
        $backgroundColors = [
            'rgba(251, 191, 36, 0.7)',  // Yellow
            'rgba(16, 185, 129, 0.7)',  // Green
            'rgba(217, 119, 6, 0.7)',   // Amber
            'rgba(220, 38, 38, 0.7)',   // Red
            'rgba(99, 102, 241, 0.7)',  // Indigo
            'rgba(236, 72, 153, 0.7)',  // Pink
            'rgba(6, 182, 212, 0.7)',   // Cyan
            'rgba(139, 92, 246, 0.7)',  // Purple
            'rgba(245, 158, 11, 0.7)',  // Amber
            'rgba(59, 130, 246, 0.7)'   // Blue
        ];
        
        // Prepare data for the chart
        $chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Reservations',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderWidth' => 1
                ]
            ]
        ];
        
        return $chartData;
    }
}
