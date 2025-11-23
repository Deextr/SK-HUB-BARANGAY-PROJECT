<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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
     * Get Reservations Report data - returns detailed reservation records
     */
    private function getReservationsReport($startDate, $endDate)
    {
        $query = Reservation::query()
            ->with(['user', 'service'])
            ->orderBy('created_at', 'desc');
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        return $query->get();
    }
    
    /**
     * Get Services Report data - returns service usage statistics
     */
    private function getServicesReport($startDate, $endDate)
    {
        // Get all services
        $services = Service::where('is_active', true)->get();
        
        $serviceUsage = [];
        
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
            
            $serviceUsage[] = [
                'service' => $service,
                'usage_count' => $usageCount,
                'unique_users' => $uniqueUsers,
                'reservations' => $reservations,
            ];
        }
        
        // Sort by usage count (most frequently used first)
        usort($serviceUsage, function($a, $b) {
            return $b['usage_count'] - $a['usage_count'];
        });
        
        return $serviceUsage;
    }
    
    /**
     * Export reports as CSV with proper formatting
     */
    public function exportCsv(Request $request)
    {
        $dateRange = $request->get('date_range', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $reportType = $request->get('report_type', 'all');
        
        [$calculatedStartDate, $calculatedEndDate] = $this->calculateDateRange($dateRange, $startDate, $endDate);
        
        // Generate filename based on report type and date
        $dateStr = now()->format('Y-m-d');
        if ($reportType === 'reservations') {
            $filename = 'reservations_report_' . $dateStr . '.csv';
        } elseif ($reportType === 'services') {
            $filename = 'services_report_' . $dateStr . '.csv';
        } else {
            $filename = 'reports_' . $dateStr . '.csv';
        }
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        
        $callback = function() use ($reportType, $calculatedStartDate, $calculatedEndDate) {
            $file = fopen('php://output', 'w');
            
            // Write UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            if ($reportType === 'reservations' || $reportType === 'all') {
                // Reservations Report Header
                fputcsv($file, ['BARANGAY 22-C RESERVATIONS REPORT'], ',', '"');
                fputcsv($file, ['Generated: ' . now()->format('Y-m-d H:i:s')], ',', '"');
                fputcsv($file, [], ',', '"');
                
                $reservationsData = $this->getReservationsReport($calculatedStartDate, $calculatedEndDate);
                
                // Column Headers with proper spacing
                fputcsv($file, [
                    'Reference No',
                    'Resident Name',
                    'Service Name',
                    'Reservation Date',
                    'Time',
                    'Status'
                ], ',', '"');
                
                // Data rows
                foreach ($reservationsData as $reservation) {
                    $date = $reservation->reservation_date instanceof \DateTime 
                        ? $reservation->reservation_date->format('Y-m-d')
                        : \Carbon\Carbon::parse($reservation->reservation_date)->format('Y-m-d');
                    
                    $startTime = \Carbon\Carbon::parse($reservation->start_time)->format('H:i');
                    $endTime = \Carbon\Carbon::parse($reservation->end_time)->format('H:i');
                    
                    fputcsv($file, [
                        $reservation->reference_no ?? '',
                        $reservation->user ? $reservation->user->name : 'N/A',
                        $reservation->service ? $reservation->service->name : 'N/A',
                        $date,
                        $startTime . ' - ' . $endTime,
                        ucfirst($reservation->status ?? ''),
                    ], ',', '"');
                }
                fputcsv($file, [], ',', '"');
                fputcsv($file, [], ',', '"');
            }
            
            if ($reportType === 'services' || $reportType === 'all') {
                // Services Report Header
                fputcsv($file, ['BARANGAY 22-C SERVICES REPORT'], ',', '"');
                fputcsv($file, ['Generated: ' . now()->format('Y-m-d H:i:s')], ',', '"');
                fputcsv($file, [], ',', '"');
                
                $servicesData = $this->getServicesReport($calculatedStartDate, $calculatedEndDate);
                
                // Column Headers with proper spacing
                fputcsv($file, [
                    'Service Name',
                    'Description',
                    'Total Usage',
                    'Unique Users',
                    'Quantity',
                    'Status'
                ], ',', '"');
                
                // Data rows
                foreach ($servicesData as $data) {
                    fputcsv($file, [
                        $data['service']->name ?? '',
                        $data['service']->description ?? 'N/A',
                        $data['usage_count'] ?? 0,
                        $data['unique_users'] ?? 0,
                        ($data['service']->capacity_units ?? 0) . ' units',
                        $data['service']->is_active ? 'Active' : 'Inactive',
                    ], ',', '"');
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
        
        // Generate filename based on report type
        $dateStr = now()->format('Y-m-d');
        if ($reportType === 'reservations') {
            $filename = 'reservations_report_' . $dateStr . '.pdf';
        } elseif ($reportType === 'services') {
            $filename = 'services_report_' . $dateStr . '.pdf';
        } else {
            $filename = 'reports_' . $dateStr . '.pdf';
        }
        
        // Generate PDF using DomPDF
        $pdf = Pdf::loadView('admin.reports_pdf', [
            'reservationsData' => $reservationsData,
            'servicesData' => $servicesData,
            'dateRangeText' => $dateRangeText,
            'reportType' => $reportType,
        ]);
        
        // Set paper size and minimal margins
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('margin_top', 5);
        $pdf->setOption('margin_right', 5);
        $pdf->setOption('margin_bottom', 5);
        $pdf->setOption('margin_left', 5);
        
        // Download the PDF file
        return $pdf->download($filename);
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

