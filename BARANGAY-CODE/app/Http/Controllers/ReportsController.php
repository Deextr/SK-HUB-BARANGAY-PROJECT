<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
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
        $peakData = null;
        $engagementData = null;
        
        // Get data based on report type
        if ($reportType === 'all' || $reportType === 'reservations') {
            $reservationsData = $this->getReservationsReport($calculatedStartDate, $calculatedEndDate);
        }
        
        if ($reportType === 'all' || $reportType === 'services') {
            $servicesData = $this->getServicesReport($calculatedStartDate, $calculatedEndDate);
        }

        if ($reportType === 'all' || $reportType === 'peak') {
            $peakData = $this->analyzePeakUsage($calculatedStartDate, $calculatedEndDate);
        }
        if ($reportType === 'all' || $reportType === 'engagement') {
            $engagementData = $this->analyzeUserEngagement($calculatedStartDate, $calculatedEndDate);
        }

        return view('admin.reports', [
            'reservationsData' => $reservationsData,
            'servicesData' => $servicesData,    
            'peakData' => $peakData,
            'engagementData' => $engagementData,
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
    private function getReservationsReport($startDate, $endDate, $paginate = true)
    {
        $query = Reservation::query()
            ->with(['user', 'service'])
            ->orderBy('created_at', 'desc');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $paginate ? $query->paginate(15) : $query->get();
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

    private function analyzeReservationReasons($startDate = null, $endDate = null)
    {
        $query = Reservation::whereNotNull('reservation_reason')
                ->with(['service', 'user']);
                //->where([  'status' => 'completed', ])

        if ($startDate && $endDate) {
            $query->whereBetween('reservation_date', [$startDate->toDateString(), $endDate->toDateString()]);
        }

        $reservations = $query->get();

        return [
            'service_reason_mapping' => $reservations->groupBy(function($r) {
                return $r->service ? $r->service->name : 'Unknown Service';
            })->map(function($serviceRes) {
                $users = $serviceRes->pluck('user')->filter();

                // Age group breakdown for users of this service
                $ageGroups = $users->whereNotNull('birth_date')
                    ->groupBy(function($user) {
                        $age = Carbon::parse($user->birth_date)->age;
                        if ($age < 18) return 'Under 18';
                        if ($age < 30) return '18-29';
                        if ($age < 50) return '30-49';
                        return '50+';
                    })->map->count();

                $genderSplit = $users->whereNotNull('sex')->groupBy('sex')->map->count();
                $totalUsers = $users->count();
                $pwdPercentage = $totalUsers > 0 ? ($users->where('is_pwd', true)->count() / $totalUsers) * 100 : 0;

                return [
                    'total_usage' => $serviceRes->count(),
                    'reason_breakdown' => $serviceRes->groupBy('reservation_reason')
                        ->map->count()
                        ->sortDesc(),
                    'other_reasons' => $serviceRes->whereNotNull('other_reason')
                        ->pluck('other_reason')
                        ->unique()
                        ->values(),
                    'age_groups' => $ageGroups,
                    'gender_split' => $genderSplit,
                    'pwd_percentage' => $pwdPercentage,
                ];
            }),

            'reason_demographics' => $reservations->groupBy('reservation_reason')
                ->map(function($reasonGroup) {
                    $users = $reasonGroup->pluck('user')->filter();
                    $totalUsers = $users->count();
                    return [
                        'total_users' => $totalUsers,
                        'demographics' => [
                            'age_groups' => $users->whereNotNull('birth_date')
                                ->groupBy(function($user) {
                                    $age = Carbon::parse($user->birth_date)->age;
                                    if ($age < 18) return 'Under 18';
                                    if ($age < 30) return '18-29';
                                    if ($age < 50) return '30-49';
                                    return '50+';
                                })->map->count(),
                            'gender_split' => $users->whereNotNull('sex')
                                ->groupBy('sex')->map->count(),
                            'pwd_percentage' => $totalUsers > 0 ? ($users->where('is_pwd', true)->count() / $totalUsers) * 100 : 0
                        ]
                    ];
                }),

           'emerging_needs' => $reservations->whereNotNull('other_reason')
                ->groupBy(function($reservation) {
                    $reason = $reservation->other_reason;
                    
                    // Normalize the string
                    $normalized = Str::of($reason)
                        ->trim()
                        ->lower()
                        ->replaceMatches('/\s+/', ' ')
                        ->__toString();
                        
                    // Optional: fix common typos or variations
                    $commonVariations = [
                        'surfing' => 'surfing',
                        'internet' => 'internet', 
                        'research' => 'research',
                        // Add more as needed
                    ];
                    
                    return $commonVariations[$normalized] ?? $normalized;
                })
                ->map->count()
                ->sortDesc()
                ->take(10)
                                
        ];
    }

    private function analyzePeakUsage($startDate = null, $endDate = null)
    {
        $query = Reservation::where('status', '!=', 'cancelled')
            ->with('service');

        if ($startDate && $endDate) {
            $query->whereBetween('reservation_date', [$startDate->toDateString(), $endDate->toDateString()]);
        }

        $reservations = $query->get();

        $hourlyUsage = $reservations->groupBy(function($res) {
            return Carbon::parse($res->start_time)->format('H:00');
        })->map(function($timeSlots, $hour) {
            $total = $timeSlots->count();
            $servicesUsed = $timeSlots->groupBy(function($r) {
                return $r->service ? $r->service->name : 'Unknown Service';
            })->map(function($g) {
                return $g->count();
            });

            // Utilization rate: using 8 as a baseline (as provided). Guard division.
            $utilizationRate = $total > 0 ? ($total / 8) * 100 : 0;

            // Format hour with AM/PM
            $formattedHour = Carbon::createFromFormat('H:00', $hour)->format('g:00 A');

            return [
                'hour' => $formattedHour,
                'total_reservations' => $total,
                'services_used' => $servicesUsed,
                'utilization_rate' => $utilizationRate,
            ];
        });

        $dailyPatterns = $reservations->groupBy(function($res) {
            return Carbon::parse($res->reservation_date)->format('l');
        })->map(function($group) {
            return $group->count();
        });

        $serviceTimePreferences = $reservations->groupBy(function($res) {
            return $res->service ? $res->service->name : 'Unknown Service';
        })->map(function($serviceRes) {
            $peakHours = $serviceRes->groupBy(function($res) {
                return Carbon::parse($res->start_time)->format('H:00');
            })->map(function($g, $hour) {
                // Format hour with AM/PM and include count
                $formattedHour = Carbon::createFromFormat('H:00', $hour)->format('g:00 A');
                return [
                    'hour' => $formattedHour,
                    'count' => $g->count()
                ];
            })->sortByDesc(function($item) {
                return $item['count'];
            })->take(3)->values();

            $averageDuration = $serviceRes->count() > 0 ? $serviceRes->average(function($res) {
                return Carbon::parse($res->start_time)->diffInMinutes(Carbon::parse($res->end_time));
            }) : 0;

            return [
                'peak_hours' => $peakHours,
                'average_duration' => $averageDuration,
            ];
        });

        $seasonalTrends = $reservations->groupBy(function($res) {
            return Carbon::parse($res->reservation_date)->format('Y-m');
        })->map(function($g) {
            return $g->count();
        });

        return [
            'hourly_usage' => $hourlyUsage,
            'daily_patterns' => $dailyPatterns,
            'service_time_preferences' => $serviceTimePreferences,
            'seasonal_trends' => $seasonalTrends,
        ];
    }

     private function analyzeUserEngagement($startDate = null, $endDate = null)
        {
            $reservationsQuery = Reservation::with(['user', 'service']);
            
            if ($startDate && $endDate) {
                $reservationsQuery->whereBetween('reservation_date', [$startDate, $endDate]);
            }
            
            $allReservations = $reservationsQuery->get();
            $allUsers = User::where('account_status', 'approved')->get();

            return [
                'engagement_overview' => [
                    'total_users' => $allUsers->count(),
                    'active_users' => $allReservations->pluck('user_id')->unique()->count(),
                    'engagement_rate' => ($allReservations->pluck('user_id')->unique()->count() / $allUsers->count()) * 100,
                    'total_reservations' => $allReservations->count(),
                    'avg_reservations_per_user' => $allReservations->pluck('user_id')->unique()->count() > 0 ? 
                        $allReservations->count() / $allReservations->pluck('user_id')->unique()->count() : 0
                ],
                
                'user_segments' => [
                    'super_users' => $this->getUserSegment($allReservations, 5, PHP_INT_MAX), // 5+ reservations
                    'regular_users' => $this->getUserSegment($allReservations, 2, 4), // 2-4 reservations
                    'occasional_users' => $this->getUserSegment($allReservations, 1, 1), // 1 reservation
                    'inactive_users' => $allUsers->whereNotIn('id', $allReservations->pluck('user_id'))->values()
                ],
                
                
                'service_preferences' => $allReservations->groupBy('user_id')
                    ->map(function($userReservations, $userId) {
                        $user = $userReservations->first()->user;
                        $lastReservation = $userReservations->sortByDesc('created_at')->first();
                        return [
                            'user_id' => $userId,
                            'user_name' => $user ? $user->first_name . ' ' . $user->last_name : 'Unknown',
                            'total_reservations' => $userReservations->count(),
                            'preferred_services' => $userReservations->groupBy('service.name')
                                ->map->count()
                                ->sortDesc()
                                ->take(3),
                            'last_activity' => $lastReservation ? $lastReservation->created_at->format('M d, Y g:i A') : 'N/A'
                        ];
                    })->sortByDesc('total_reservations')->values(),
                    
            ];
        }
        private function getUserSegment($reservations, $minReservations, $maxReservations)
        {
            return $reservations->groupBy('user_id')
                ->filter(function($userReservations) use ($minReservations, $maxReservations) {
                    $count = $userReservations->count();
                    return $count >= $minReservations && $count <= $maxReservations;
                })
                ->map(function($userReservations, $userId) {
                    $user = $userReservations->first()->user;
                    return [
                        'user_id' => $userId,
                        'name' => $user ? $user->first_name . ' ' . $user->last_name : 'Unknown',
                        'reservation_count' => $userReservations->count(),
                        'preferred_service' => $userReservations->groupBy('service.name')
                            ->map->count()
                            ->sortDesc()
                            ->keys()
                            ->first(),
                        'last_activity' => $userReservations->sortByDesc('reservation_date')->first()->reservation_date
                    ];
                })->values();
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
            
            // Add Executive Summary at the beginning
            fputcsv($file, ['BARANGAY 22-C EXECUTIVE SUMMARY'], ',', '"');
            fputcsv($file, ['Generated: ' . now()->format('Y-m-d H:i:s')], ',', '"');
            fputcsv($file, [], ',', '"');
            
            // Get all data for summary
            $reservationsData = $this->getReservationsReport($calculatedStartDate, $calculatedEndDate, false);
            $servicesData = $this->getServicesReport($calculatedStartDate, $calculatedEndDate);
            $peakData = $this->analyzePeakUsage($calculatedStartDate, $calculatedEndDate);
            $engagementData = $this->analyzeUserEngagement($calculatedStartDate, $calculatedEndDate);
            
            // Reservations Summary
            if ($reportType === 'all' || $reportType === 'reservations') {
                $reservationsCol = collect($reservationsData);
                $totalReservations = $reservationsCol->count();
                $pending = $reservationsCol->where('status', 'pending')->count();
                $completed = $reservationsCol->where('status', 'completed')->count();
                $cancelled = $reservationsCol->where('status', 'cancelled')->count();
                
                fputcsv($file, ['RESERVATIONS SUMMARY'], ',', '"');
                fputcsv($file, ['Total Reservations', $totalReservations], ',', '"');
                fputcsv($file, ['Pending', $pending], ',', '"');
                fputcsv($file, ['Completed', $completed], ',', '"');
                fputcsv($file, ['Cancelled', $cancelled], ',', '"');
                fputcsv($file, [], ',', '"');
            }
            
            // Services Summary
            if ($reportType === 'all' || $reportType === 'services') {
                $servicesCount = count($servicesData);
                $totalUsage = 0;
                $totalUniqueUsers = 0;
                $topServiceName = null;
                $topServiceCount = 0;
                
                foreach ($servicesData as $s) {
                    $count = $s['usage_count'] ?? 0;
                    $totalUsage += $count;
                    $totalUniqueUsers += ($s['unique_users'] ?? 0);
                    
                    if ($count > $topServiceCount) {
                        $topServiceCount = $count;
                        $topServiceName = $s['service']->name ?? null;
                    }
                }
                
                $avgUsage = $servicesCount > 0 ? round($totalUsage / $servicesCount, 1) : 0;
                
                fputcsv($file, ['SERVICES SUMMARY'], ',', '"');
                fputcsv($file, ['Total Services', $servicesCount], ',', '"');
                fputcsv($file, ['Total Usage', $totalUsage], ',', '"');
                fputcsv($file, ['Unique Users', $totalUniqueUsers], ',', '"');
                fputcsv($file, ['Top Service', $topServiceName . ' (' . $topServiceCount . ' uses)'], ',', '"');
                fputcsv($file, ['Average Usage per Service', $avgUsage], ',', '"');
                fputcsv($file, [], ',', '"');
            }
            
            // Peak Usage Summary
            if ($reportType === 'all' || $reportType === 'peak') {
                $hourly = $peakData['hourly_usage'] ?? null;
                $daily = $peakData['daily_patterns'] ?? null;
                $topHour = null;
                $topHourCount = 0;
                $totalPeakReservations = 0;
                
                if ($hourly) {
                    $hourCol = is_object($hourly) ? $hourly : collect($hourly);
                    $sorted = $hourCol->sortByDesc(function($v) { return $v['total_reservations'] ?? 0; });
                    $topHour = $sorted->keys()->first();
                    $topHourCount = optional($sorted->first())['total_reservations'] ?? 0;
                    $totalPeakReservations = $hourCol->reduce(function($carry, $v) { return $carry + ($v['total_reservations'] ?? 0); }, 0);
                }
                
                $topDay = null;
                if ($daily) {
                    $dailyCol = is_object($daily) ? $daily : collect($daily);
                    $topDay = $dailyCol->sortByDesc(function($v) { return $v; })->keys()->first();
                }
                
                fputcsv($file, ['PEAK USAGE SUMMARY'], ',', '"');
                fputcsv($file, ['Top Hour', $topHour . ' (' . $topHourCount . ' reservations)'], ',', '"');
                fputcsv($file, ['Top Day', $topDay ?? 'N/A'], ',', '"');
                fputcsv($file, ['Total Reservations (Peak Analysis)', $totalPeakReservations], ',', '"');
                fputcsv($file, [], ',', '"');
            }
            
            // User Engagement Summary
            if ($reportType === 'all' || $reportType === 'engagement') {
                $overview = $engagementData['engagement_overview'] ?? [];
                $segments = $engagementData['user_segments'] ?? [];
                
                fputcsv($file, ['USER ENGAGEMENT SUMMARY'], ',', '"');
                fputcsv($file, ['Total Approved Users', $overview['total_users'] ?? 0], ',', '"');
                fputcsv($file, ['Active Users', $overview['active_users'] ?? 0], ',', '"');
                fputcsv($file, ['Engagement Rate (%)', round($overview['engagement_rate'] ?? 0, 2)], ',', '"');
                fputcsv($file, ['Total Reservations', $overview['total_reservations'] ?? 0], ',', '"');
                fputcsv($file, ['Avg Reservations per User', round($overview['avg_reservations_per_user'] ?? 0, 2)], ',', '"');
                fputcsv($file, [], ',', '"');
            }
            
            fputcsv($file, [], ',', '"');
            fputcsv($file, ['DETAILED REPORTS'], ',', '"');
            fputcsv($file, [], ',', '"');
            
            if ($reportType === 'reservations' || $reportType === 'all') {
                // Reservations Report Header
                fputcsv($file, ['BARANGAY 22-C RESERVATIONS REPORT'], ',', '"');
                fputcsv($file, ['Generated: ' . now()->format('Y-m-d H:i:s')], ',', '"');
                fputcsv($file, [], ',', '"');
                
                // use non-paginated full collection for CSV export
                $reservationsData = $this->getReservationsReport($calculatedStartDate, $calculatedEndDate, false);
                
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


            // Peak usage section (hourly/daily/service preferences)
            if ($reportType === 'peak' || $reportType === 'all') {
                fputcsv($file, [], ',', '"');
                fputcsv($file, ['BARANGAY 22-C PEAK USAGE REPORT'], ',', '"');
                fputcsv($file, ['Generated: ' . now()->format('Y-m-d H:i:s')], ',', '"');
                fputcsv($file, [], ',', '"');

                $peakData = $this->analyzePeakUsage($calculatedStartDate, $calculatedEndDate);

                // Hourly Usage
                fputcsv($file, ['Hourly Usage: Hour', 'Total Reservations', 'Utilization Rate (%)', 'Top Services (name:count)'], ',', '"');
                foreach (($peakData['hourly_usage'] ?? []) as $hour => $info) {
                    $servicesUsed = is_array($info['services_used']) || $info['services_used'] instanceof \Illuminate\Support\Collection ? $info['services_used'] : collect($info['services_used']);
                    $servicesStr = $servicesUsed->map(function($count, $name) { return $name . ':' . $count; })->values()->all();
                    fputcsv($file, [$hour, $info['total_reservations'] ?? 0, round($info['utilization_rate'] ?? 0, 1), implode(' | ', $servicesStr)], ',', '"');
                }
                fputcsv($file, [], ',', '"');

                // Daily Patterns
                fputcsv($file, ['Daily Patterns: Day', 'Reservations'], ',', '"');
                foreach (($peakData['daily_patterns'] ?? []) as $day => $count) {
                    fputcsv($file, [$day, $count], ',', '"');
                }
                fputcsv($file, [], ',', '"');

                // Service Time Preferences
                fputcsv($file, ['Service Time Preferences: Service', 'Peak Hours (hour:count)', 'Average Duration (mins)'], ',', '"');
                foreach (($peakData['service_time_preferences'] ?? []) as $serviceName => $prefs) {
                    $peakHours = is_array($prefs['peak_hours']) || $prefs['peak_hours'] instanceof \Illuminate\Support\Collection ? $prefs['peak_hours'] : collect($prefs['peak_hours']);
                    $peakStr = $peakHours->map(function($count, $hour) { return $hour . ':' . $count; })->values()->all();
                    fputcsv($file, [$serviceName, implode(' | ', $peakStr), round($prefs['average_duration'] ?? 0, 1)], ',', '"');
                }
                fputcsv($file, [], ',', '"');

                // Seasonal Trends
                fputcsv($file, ['Seasonal Trends: YYYY-MM', 'Reservations'], ',', '"');
                foreach (($peakData['seasonal_trends'] ?? []) as $period => $count) {
                    fputcsv($file, [$period, $count], ',', '"');
                }
            }

            // User Engagement Report
            if ($reportType === 'engagement' || $reportType === 'all') {
                fputcsv($file, [], ',', '"');
                fputcsv($file, ['BARANGAY 22-C USER ENGAGEMENT REPORT'], ',', '"');
                fputcsv($file, ['Generated: ' . now()->format('Y-m-d H:i:s')], ',', '"');
                fputcsv($file, [], ',', '"');

                $engagementData = $this->analyzeUserEngagement($calculatedStartDate, $calculatedEndDate);
                $overview = $engagementData['engagement_overview'] ?? [];

                // Engagement Summary
                fputcsv($file, ['ENGAGEMENT SUMMARY'], ',', '"');
                fputcsv($file, ['Total Approved Users', $overview['total_users'] ?? 0], ',', '"');
                fputcsv($file, ['Active Users', $overview['active_users'] ?? 0], ',', '"');
                fputcsv($file, ['Engagement Rate (%)', round($overview['engagement_rate'] ?? 0, 2)], ',', '"');
                fputcsv($file, ['Total Reservations', $overview['total_reservations'] ?? 0], ',', '"');
                fputcsv($file, ['Avg Reservations per User', round($overview['avg_reservations_per_user'] ?? 0, 2)], ',', '"');
                fputcsv($file, [], ',', '"');

                // User Segments
                $segments = $engagementData['user_segments'] ?? [];
                fputcsv($file, ['USER SEGMENTS'], ',', '"');
                fputcsv($file, ['Segment', 'Count'], ',', '"');
                fputcsv($file, ['Super Users (5+)', isset($segments['super_users']) ? count($segments['super_users']) : 0], ',', '"');
                fputcsv($file, ['Regular Users (2-4)', isset($segments['regular_users']) ? count($segments['regular_users']) : 0], ',', '"');
                fputcsv($file, ['Occasional (1)', isset($segments['occasional_users']) ? count($segments['occasional_users']) : 0], ',', '"');
                fputcsv($file, ['Inactive', isset($segments['inactive_users']) ? count($segments['inactive_users']) : 0], ',', '"');
                fputcsv($file, [], ',', '"');

                // PWD Engagement
                $demographics = $engagementData['demographic_engagement'] ?? [];
                $pwdData = $demographics['by_pwd_status'] ?? [];
                $pwdInfo = $pwdData['pwd'] ?? [];
                $nonPwdInfo = $pwdData['non_pwd'] ?? [];
                
                fputcsv($file, ['PWD ENGAGEMENT'], ',', '"');
                fputcsv($file, ['Category', 'Total Users', 'Active Users', 'Engagement Rate (%)'], ',', '"');
                fputcsv($file, ['PWD', $pwdInfo['total_users'] ?? 0, $pwdInfo['active_users'] ?? 0, round($pwdInfo['engagement_rate'] ?? 0, 2)], ',', '"');
                fputcsv($file, ['Non-PWD', $nonPwdInfo['total_users'] ?? 0, $nonPwdInfo['active_users'] ?? 0, round($nonPwdInfo['engagement_rate'] ?? 0, 2)], ',', '"');
                fputcsv($file, [], ',', '"');

                // Top Engaged Users
                $topUsers = $engagementData['service_preferences'] ?? collect();
                fputcsv($file, ['TOP ENGAGED USERS'], ',', '"');
                fputcsv($file, ['#', 'User', 'Reservations', 'Preferred Service', 'Last Activity'], ',', '"');
                
                if ($topUsers && count($topUsers) > 0) {
                    $rank = 1;
                    foreach ($topUsers->take(20) as $u) {
                        $preferredService = is_array($u['preferred_services']) ? array_keys($u['preferred_services'])[0] ?? 'N/A' : (method_exists($u['preferred_services'], 'keys') ? $u['preferred_services']->keys()->first() : 'N/A');
                        fputcsv($file, [$rank++, $u['user_name'] ?? 'Unknown', $u['total_reservations'] ?? 0, $preferredService, $u['last_activity'] ?? 'N/A'], ',', '"');
                    }
                }
                fputcsv($file, [], ',', '"');
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
        $peakData = null;
        $engagementData = null;

        // Get data based on report type
        if ($reportType === 'all' || $reportType === 'reservations') {
            // For PDF export we want the full (non-paginated) dataset so the PDF contains all records.
            $reservationsData = $this->getReservationsReport($calculatedStartDate, $calculatedEndDate, false);
        }

        if ($reportType === 'all' || $reportType === 'services') {
            $servicesData = $this->getServicesReport($calculatedStartDate, $calculatedEndDate);
        }

        if ($reportType === 'all' || $reportType === 'peak') {
            $peakData = $this->analyzePeakUsage($calculatedStartDate, $calculatedEndDate);
        }

        if ($reportType === 'all' || $reportType === 'engagement') {
            $engagementData = $this->analyzeUserEngagement($calculatedStartDate, $calculatedEndDate);
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
        
            $pdf = Pdf::loadView('admin.reports_pdf', [
                'reservationsData' => $reservationsData,
                'servicesData' => $servicesData,
                'peakData' => $peakData,
                'engagementData' => $engagementData,
                'dateRangeText' => $dateRangeText,
                'reportType' => $reportType,
            ]);
        
        // Enable remote assets (images/CSS) and set paper size and minimal margins
        $pdf->setOption('isRemoteEnabled', true);
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