<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * Display the reports index page
     */
    public function index(Request $request)
    {
        // Get date range from request or default to all time
        $dateRange = $request->get('date_range', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $reportType = $request->get('report_type', 'all'); // 'reservations', 'services', or 'all'
        
        // Calculate date range
        [$calculatedStartDate, $calculatedEndDate] = $this->calculateDateRange($dateRange, $startDate, $endDate);
        
        // Initialize data arrays
        $reservationsData = null;
        $servicesData = null;
        
        // Get data based on report type
        if ($reportType === 'all' || $reportType === 'reservations') {
            $reservationsData = $this->getReservationsReport($calculatedStartDate, $calculatedEndDate);
        }
        
        if ($reportType === 'all' || $reportType === 'services') {
            $servicesData = $this->getServicesReport($calculatedStartDate, $calculatedEndDate);
        }
        
        return view('admin.reports', [
            'reservationsData' => $reservationsData,
            'servicesData' => $servicesData,
            'dateRange' => $dateRange,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'calculatedStartDate' => $calculatedStartDate,
            'calculatedEndDate' => $calculatedEndDate,
            'reportType' => $reportType,
        ]);
    }
    
    /**
     * Calculate date range based on filter type
     */
    private function calculateDateRange($dateRange, $startDate, $endDate)
    {
        switch ($dateRange) {
            case 'weekly':
                return [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
            case 'monthly':
                return [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
            case 'yearly':
                return [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()];
            case 'custom':
                if ($startDate && $endDate) {
                    return [Carbon::parse($startDate)->startOfDay(), Carbon::parse($endDate)->endOfDay()];
                }
                // Fall through to 'all' if custom dates not provided
            case 'all':
            default:
                return [null, null];
        }
    }
    
    /**
     * Get Reservations Report data
     */
    private function getReservationsReport($startDate, $endDate)
    {
        $baseQuery = Reservation::query();
        
        if ($startDate && $endDate) {
            $baseQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        $totalReservations = (clone $baseQuery)->count();
        $totalCancelled = (clone $baseQuery)->where('status', 'cancelled')->count();
        $totalCompleted = (clone $baseQuery)->where('status', 'completed')->count();
        
        return [
            'total' => $totalReservations,
            'cancelled' => $totalCancelled,
            'completed' => $totalCompleted,
        ];
    }
    
    /**
     * Get Services Report data
     */
    private function getServicesReport($startDate, $endDate)
    {
        // Get all services
        $services = Service::where('is_active', true)->get();
        
        $serviceUsage = [];
        $ageRangeBreakdown = [];
        
        foreach ($services as $service) {
            $reservationsQuery = Reservation::where('service_id', $service->id);
            
            if ($startDate && $endDate) {
                // Use reservation_date for services report (when service was actually used/scheduled)
                $reservationsQuery->whereBetween('reservation_date', [$startDate->toDateString(), $endDate->toDateString()]);
            }
            
            $reservations = $reservationsQuery->with('user')->get();
            
            // Count total usage
            $usageCount = $reservations->count();
            
            // Get unique users who used this service
            $uniqueUsers = $reservations->pluck('user_id')->unique()->count();
            
            // Age range breakdown
            $ageGroups = [
                '13-17' => 0,
                '18-21' => 0,
                '22-25' => 0,
                '26-30' => 0,
                '31-35' => 0,
                '36-40' => 0,
                '41+' => 0,
            ];
            
            foreach ($reservations as $reservation) {
                if ($reservation->user && $reservation->user->birth_date) {
                    $age = $reservation->user->age;
                    
                    if ($age >= 13 && $age <= 17) {
                        $ageGroups['13-17']++;
                    } elseif ($age >= 18 && $age <= 21) {
                        $ageGroups['18-21']++;
                    } elseif ($age >= 22 && $age <= 25) {
                        $ageGroups['22-25']++;
                    } elseif ($age >= 26 && $age <= 30) {
                        $ageGroups['26-30']++;
                    } elseif ($age >= 31 && $age <= 35) {
                        $ageGroups['31-35']++;
                    } elseif ($age >= 36 && $age <= 40) {
                        $ageGroups['36-40']++;
                    } elseif ($age >= 41) {
                        $ageGroups['41+']++;
                    }
                }
            }
            
            $serviceUsage[] = [
                'service' => $service,
                'usage_count' => $usageCount,
                'unique_users' => $uniqueUsers,
                'age_breakdown' => $ageGroups,
            ];
        }
        
        // Sort by usage count (most frequently used first)
        usort($serviceUsage, function($a, $b) {
            return $b['usage_count'] - $a['usage_count'];
        });
        
        return $serviceUsage;
    }
    
    /**
     * Export reports as CSV
     */
    public function exportCsv(Request $request)
    {
        $dateRange = $request->get('date_range', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $reportType = $request->get('report_type', 'all'); // 'reservations', 'services', or 'all'
        
        [$calculatedStartDate, $calculatedEndDate] = $this->calculateDateRange($dateRange, $startDate, $endDate);
        
        $filename = 'reports_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($reportType, $calculatedStartDate, $calculatedEndDate) {
            $file = fopen('php://output', 'w');
            
            if ($reportType === 'reservations' || $reportType === 'all') {
                // Reservations Report
                fputcsv($file, ['RESERVATIONS REPORT']);
                fputcsv($file, []);
                
                $reservationsData = $this->getReservationsReport($calculatedStartDate, $calculatedEndDate);
                
                fputcsv($file, ['Total Reservations', $reservationsData['total']]);
                fputcsv($file, ['Total Cancelled', $reservationsData['cancelled']]);
                fputcsv($file, ['Total Completed', $reservationsData['completed']]);
                fputcsv($file, []);
            }
            
            if ($reportType === 'services' || $reportType === 'all') {
                // Services Report
                fputcsv($file, ['SERVICES REPORT']);
                fputcsv($file, []);
                
                $servicesData = $this->getServicesReport($calculatedStartDate, $calculatedEndDate);
                
                // Header
                fputcsv($file, ['Service Name', 'Total Usage', 'Unique Users', '13-17', '18-21', '22-25', '26-30', '31-35', '36-40', '41+']);
                
                foreach ($servicesData as $data) {
                    fputcsv($file, [
                        $data['service']->name,
                        $data['usage_count'],
                        $data['unique_users'],
                        $data['age_breakdown']['13-17'],
                        $data['age_breakdown']['18-21'],
                        $data['age_breakdown']['22-25'],
                        $data['age_breakdown']['26-30'],
                        $data['age_breakdown']['31-35'],
                        $data['age_breakdown']['36-40'],
                        $data['age_breakdown']['41+'],
                    ]);
                }
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Export reports as PDF
     */
    public function exportPdf(Request $request)
    {
        $dateRange = $request->get('date_range', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $reportType = $request->get('report_type', 'all');
        
        [$calculatedStartDate, $calculatedEndDate] = $this->calculateDateRange($dateRange, $startDate, $endDate);
        
        // Initialize data arrays
        $reservationsData = null;
        $servicesData = null;
        
        // Get data based on report type
        if ($reportType === 'all' || $reportType === 'reservations') {
            $reservationsData = $this->getReservationsReport($calculatedStartDate, $calculatedEndDate);
        }
        
        if ($reportType === 'all' || $reportType === 'services') {
            $servicesData = $this->getServicesReport($calculatedStartDate, $calculatedEndDate);
        }
        
        // Format date range for display
        $dateRangeText = $this->formatDateRange($dateRange, $calculatedStartDate, $calculatedEndDate);
        
        // Generate HTML content for PDF
        $html = view('admin.reports_pdf', [
            'reservationsData' => $reservationsData,
            'servicesData' => $servicesData,
            'dateRangeText' => $dateRangeText,
            'reportType' => $reportType,
        ])->render();
        
        // Use DomPDF or similar library
        // For now, we'll return a simple HTML response that can be printed
        // In production, you would use a library like barryvdh/laravel-dompdf
        
        return response()->make($html, 200, [
            'Content-Type' => 'text/html',
        ]);
    }
    
    /**
     * Format date range for display
     */
    private function formatDateRange($dateRange, $startDate, $endDate)
    {
        switch ($dateRange) {
            case 'weekly':
                return 'This Week (' . Carbon::now()->startOfWeek()->format('M d, Y') . ' - ' . Carbon::now()->endOfWeek()->format('M d, Y') . ')';
            case 'monthly':
                return 'This Month (' . Carbon::now()->startOfMonth()->format('M d, Y') . ' - ' . Carbon::now()->endOfMonth()->format('M d, Y') . ')';
            case 'yearly':
                return 'This Year (' . Carbon::now()->startOfYear()->format('M d, Y') . ' - ' . Carbon::now()->endOfYear()->format('M d, Y') . ')';
            case 'custom':
                if ($startDate && $endDate) {
                    return 'Custom Range (' . Carbon::parse($startDate)->format('M d, Y') . ' - ' . Carbon::parse($endDate)->format('M d, Y') . ')';
                }
            case 'all':
            default:
                return 'All Time';
        }
    }
}

