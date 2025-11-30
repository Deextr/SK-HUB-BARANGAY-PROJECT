<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - {{ $dateRangeText }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 10px;
            padding: 0;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }
        .header h1 {
            color: #1f2937;
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            color: #333;
            margin: 2px 0;
            font-size: 11px;
        }
        .section {
            margin-bottom: 12px;
            page-break-inside: avoid;
        }
        .section-report {
            page-break-before: always;
            margin-bottom: 12px;
            margin-top: 0;
            padding-top: 0;
            page-break-after: avoid;
        }
        .executive-summary {
            margin-bottom: 12px;
            padding-bottom: 0;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 6px;
            border-bottom: 1px solid #999;
            padding-bottom: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 10px;
        }
        th, td {
            border: 1px solid #999;
            padding: 4px 6px;
            text-align: left;
        }
        th {
            background-color: #e5e7eb;
            font-weight: bold;
            color: #1f2937;
        }
        td {
            padding: 3px 5px;
        }
        .footer {
            margin-top: 8px;
            text-align: center;
            color: #666;
            font-size: 9px;
            border-top: 1px solid #999;
            padding-top: 6px;
        }
        .no-data {
            text-align: center;
            color: #6b7280;
            padding: 10px;
            font-style: italic;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BARANGAY 22-C REPORTS</h1>
        <p>{{ $dateRangeText }}</p>
        <p>Report Type: {{ ucfirst($reportType) }} Report{{ $reportType == 'all' ? 's' : '' }}</p>
        <p>Generated on: {{ now()->format('F d, Y \a\t g:i A') }}</p>
    </div>

    <!-- Executive Summary Section -->
    <div class="section executive-summary">
        <div class="section-title">EXECUTIVE SUMMARY</div>
        
        @if($reportType === 'all' || $reportType === 'reservations')
        @php
            $reservationsCol = collect($reservationsData ?? []);
            $totalReservations = $reservationsCol->count();
            $pending = $reservationsCol->where('status', 'pending')->count();
            $completed = $reservationsCol->where('status', 'completed')->count();
            $cancelled = $reservationsCol->where('status', 'cancelled')->count();
        @endphp
        <table>
            <tr>
                <td style="font-weight:bold; width:50%;">Reservations Summary</td>
                <td></td>
            </tr>
            <tr>
                <td>Total Reservations</td>
                <td>{{ $totalReservations }}</td>
            </tr>
            <tr>
                <td>Pending</td>
                <td>{{ $pending }}</td>
            </tr>
            <tr>
                <td>Completed</td>
                <td>{{ $completed }}</td>
            </tr>
            <tr>
                <td>Cancelled</td>
                <td>{{ $cancelled }}</td>
            </tr>
        </table>
        @endif

        @if($reportType === 'all' || $reportType === 'services')
        @php
            $servicesCount = count($servicesData ?? []);
            $totalUsage = 0;
            $totalUniqueUsers = 0;
            $topServiceName = null;
            $topServiceCount = 0;
            
            foreach ($servicesData ?? [] as $s) {
                $count = $s['usage_count'] ?? 0;
                $totalUsage += $count;
                $totalUniqueUsers += ($s['unique_users'] ?? 0);
                
                if ($count > $topServiceCount) {
                    $topServiceCount = $count;
                    $topServiceName = $s['service']->name ?? null;
                }
            }
            
            $avgUsage = $servicesCount > 0 ? round($totalUsage / $servicesCount, 1) : 0;
        @endphp
        <table style="margin-top:8px;">
            <tr>
                <td style="font-weight:bold; width:50%;">Services Summary</td>
                <td></td>
            </tr>
            <tr>
                <td>Total Services</td>
                <td>{{ $servicesCount }}</td>
            </tr>
            <tr>
                <td>Total Usage</td>
                <td>{{ $totalUsage }}</td>
            </tr>
            <tr>
                <td>Unique Users</td>
                <td>{{ $totalUniqueUsers }}</td>
            </tr>
            <tr>
                <td>Top Service</td>
                <td>{{ $topServiceName }} ({{ $topServiceCount }} uses)</td>
            </tr>
            <tr>
                <td>Average Usage per Service</td>
                <td>{{ $avgUsage }}</td>
            </tr>
        </table>
        @endif

        @if($reportType === 'all' || $reportType === 'peak')
        @php
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
        @endphp
        <table style="margin-top:8px;">
            <tr>
                <td style="font-weight:bold; width:50%;">Peak Usage Summary</td>
                <td></td>
            </tr>
            <tr>
                <td>Top Hour</td>
                <td>{{ $topHour }} ({{ $topHourCount }} reservations)</td>
            </tr>
            <tr>
                <td>Top Day</td>
                <td>{{ $topDay ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Total Reservations (Peak Analysis)</td>
                <td>{{ $totalPeakReservations }}</td>
            </tr>
        </table>
        @endif

        @if($reportType === 'all' || $reportType === 'engagement')
        @php
            $engagementOverview = $engagementData['engagement_overview'] ?? [];
        @endphp
        <table style="margin-top:8px;">
            <tr>
                <td style="font-weight:bold; width:50%;">User Engagement Summary</td>
                <td></td>
            </tr>
            <tr>
                <td>Total Users</td>
                <td>{{ $engagementOverview['total_users'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Active Users</td>
                <td>{{ $engagementOverview['active_users'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Engagement Rate</td>
                <td>{{ number_format($engagementOverview['engagement_rate'] ?? 0, 1) }}%</td>
            </tr>
            <tr>
                <td>Total Reservations</td>
                <td>{{ $engagementOverview['total_reservations'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Avg Reservations per User</td>
                <td>{{ number_format($engagementOverview['avg_reservations_per_user'] ?? 0, 1) }}</td>
            </tr>
        </table>
        @endif
    </div>

    @if($reportType === 'reservations' || $reportType === 'all')
    <div class="section-report">
        <div class="section-title">RESERVATIONS REPORT</div>
        
        @if(isset($reservationsData) && $reservationsData->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Reference No</th>
                        <th>Resident Name</th>
                        <th>Service Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservationsData as $reservation)
                        <tr>
                            <td>{{ $reservation->reference_no }}</td>
                            <td>{{ $reservation->user ? $reservation->user->name : 'N/A' }}</td>
                            <td>{{ $reservation->service ? $reservation->service->name : 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('M d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($reservation->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('h:i A') }}</td>
                            <td>{{ ucfirst($reservation->status) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">No reservations found for the selected period.</div>
        @endif
    </div>
    @endif

    @if($reportType === 'services' || $reportType === 'all')
    <div class="section-report">
        <div class="section-title">SERVICES REPORT</div>
        
        @if(isset($servicesData) && count($servicesData) > 0)
            <table>
                <thead>
                    <tr>
                        <th>Service Name</th>
                        <th>Description</th>
                        <th>Total Usage</th>
                        <th>Unique Users</th>
                        <th>Quantity</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($servicesData as $data)
                        <tr>
                            <td>{{ $data['service']->name }}</td>
                            <td>{{ $data['service']->description ?? 'N/A' }}</td>
                            <td>{{ number_format($data['usage_count']) }}</td>
                            <td>{{ number_format($data['unique_users']) }}</td>
                            <td>{{ $data['service']->capacity_units }} units</td>
                            <td>{{ $data['service']->is_active ? 'Active' : 'Inactive' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">No services data available for the selected period.</div>
        @endif
    </div>
    @endif

    @if($reportType === 'peak' || $reportType === 'all')
    <div class="section-report">
        <div class="section-title">PEAK USAGE ANALYSIS</div>

        @if(isset($peakData) && count((array)($peakData ?? [])) > 0)
            <!-- Top Hours (Hourly Usage) -->
            <div style="margin-bottom:8px;">
                <div style="font-weight:bold; margin-bottom:4px;">Top Hours (Hourly Usage)</div>
                <table>
                    <thead>
                        <tr>
                            <th>Hour</th>
                            <th>Total Reservations</th>
                            <th>Utilization Rate (%)</th>
                            <th>Top Services (name:count)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($peakData['hourly_usage'] ?? []) as $hour => $info)
                            @php
                                $servicesUsed = is_array($info['services_used'] ?? null) || ($info['services_used'] instanceof \Illuminate\Support\Collection) ? collect($info['services_used']) : collect([]);
                                $servicesStr = $servicesUsed->map(function($count, $name) { return $name . ':' . $count; })->values()->all();
                            @endphp
                            <tr>
                                <td>{{ $hour }}</td>
                                <td>{{ $info['total_reservations'] ?? 0 }}</td>
                                <td>{{ round($info['utilization_rate'] ?? 0, 1) }}</td>
                                <td>{{ implode(' | ', $servicesStr) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Daily Patterns -->
            <div style="margin-bottom:8px;">
                <div style="font-weight:bold; margin-bottom:4px;">Daily Patterns</div>
                <table>
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Reservations</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($peakData['daily_patterns'] ?? []) as $day => $count)
                            <tr>
                                <td>{{ $day }}</td>
                                <td>{{ $count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Service Time Preferences -->
            <div style="margin-bottom:8px;">
                <div style="font-weight:bold; margin-bottom:4px;">Service Time Preferences</div>
                <table>
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Peak Hours (hour:count)</th>
                            <th>Average Duration (mins)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($peakData['service_time_preferences'] ?? []) as $serviceName => $prefs)
                            @php
                                $peakHours = is_array($prefs['peak_hours'] ?? null) || ($prefs['peak_hours'] instanceof \Illuminate\Support\Collection) ? collect($prefs['peak_hours']) : collect([]);
                                $peakStr = $peakHours->map(function($count, $hour) { return $hour . ':' . $count; })->values()->all();
                            @endphp
                            <tr>
                                <td>{{ $serviceName }}</td>
                                <td>{{ implode(' | ', $peakStr) }}</td>
                                <td>{{ round($prefs['average_duration'] ?? 0, 1) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Seasonal Trends -->
            <div style="margin-bottom:8px;">
                <div style="font-weight:bold; margin-bottom:4px;">Seasonal Trends</div>
                <table>
                    <thead>
                        <tr>
                            <th>Period (YYYY-MM)</th>
                            <th>Reservations</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($peakData['seasonal_trends'] ?? []) as $period => $count)
                            <tr>
                                <td>{{ $period }}</td>
                                <td>{{ $count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="no-data">No peak usage data available for the selected period.</div>
        @endif
    </div>
    @endif

    @if($reportType === 'engagement' || $reportType === 'all')
    <div class="section-report">
        <div class="section-title">USER ENGAGEMENT</div>

        @if(isset($engagementData) && is_array($engagementData) || (isset($engagementData) && $engagementData instanceof \Illuminate\Support\Collection))
            @php
                $ov = $engagementData['engagement_overview'] ?? [];
                $totalUsers = $ov['total_users'] ?? 0;
                $activeUsers = $ov['active_users'] ?? 0;
                $engagementRate = round($ov['engagement_rate'] ?? 0, 2);
                $totalReservations = $ov['total_reservations'] ?? 0;
                $avgReservations = round($ov['avg_reservations_per_user'] ?? 0, 2);
            @endphp
            <div style="margin-bottom:8px; font-size:11px;">
                <p>
                    Summary: <strong>{{ $totalUsers }}</strong> total users —
                    <strong>{{ $activeUsers }}</strong> active —
                    Engagement Rate: <strong>{{ $engagementRate }}%</strong> —
                    Total Reservations: <strong>{{ $totalReservations }}</strong> —
                    Avg/User: <strong>{{ $avgReservations }}</strong>
                </p>
            </div>

            <!-- Engagement Overview -->
            <div style="margin-bottom:8px;">
                <div style="font-weight:bold; margin-bottom:4px;">Engagement Overview</div>
                <table>
                    <thead>
                        <tr>
                            <th>Metric</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $ov = $engagementData['engagement_overview'] ?? []; @endphp
                        <tr><td>Total Approved Users</td><td>{{ $ov['total_users'] ?? 0 }}</td></tr>
                        <tr><td>Active Users</td><td>{{ $ov['active_users'] ?? 0 }}</td></tr>
                        <tr><td>Engagement Rate</td><td>{{ isset($ov['engagement_rate']) ? round($ov['engagement_rate'],2) . '%' : '0%' }}</td></tr>
                        <tr><td>Total Reservations</td><td>{{ $ov['total_reservations'] ?? 0 }}</td></tr>
                        <tr><td>Avg Reservations / User</td><td>{{ isset($ov['avg_reservations_per_user']) ? round($ov['avg_reservations_per_user'],2) : 0 }}</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- User Segments -->
            <div style="margin-bottom:8px;">
                <div style="font-weight:bold; margin-bottom:4px;">User Segments</div>
                @php
                    $seg = $engagementData['user_segments'] ?? [];
                    $super = isset($seg['super_users']) ? (is_countable($seg['super_users']) ? count($seg['super_users']) : $seg['super_users']->count()) : 0;
                    $regular = isset($seg['regular_users']) ? (is_countable($seg['regular_users']) ? count($seg['regular_users']) : $seg['regular_users']->count()) : 0;
                    $occasional = isset($seg['occasional_users']) ? (is_countable($seg['occasional_users']) ? count($seg['occasional_users']) : $seg['occasional_users']->count()) : 0;
                    $inactive = isset($seg['inactive_users']) ? (is_countable($seg['inactive_users']) ? count($seg['inactive_users']) : $seg['inactive_users']->count()) : 0;
                    $totalSeg = $super + $regular + $occasional + $inactive ?: 1;
                @endphp
                <table>
                    <thead>
                        <tr><th>Segment</th><th>Count</th><th>Percent</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Super Users (5+)</td><td>{{ $super }}</td><td>{{ round(($super/$totalSeg)*100,2) }}%</td></tr>
                        <tr><td>Regular Users (2-4)</td><td>{{ $regular }}</td><td>{{ round(($regular/$totalSeg)*100,2) }}%</td></tr>
                        <tr><td>Occasional (1)</td><td>{{ $occasional }}</td><td>{{ round(($occasional/$totalSeg)*100,2) }}%</td></tr>
                        <tr><td>Inactive</td><td>{{ $inactive }}</td><td>{{ round(($inactive/$totalSeg)*100,2) }}%</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- PWD Engagement -->
            <div style="margin-bottom:8px;">
                <div style="font-weight:bold; margin-bottom:4px;">PWD Engagement</div>
                @php
                    $pwdData = $engagementData['demographic_engagement']['by_pwd_status'] ?? [];
                    $pwdInfo = $pwdData['pwd'] ?? [];
                    $nonPwdInfo = $pwdData['non_pwd'] ?? [];
                @endphp
                <table>
                    <thead><tr><th>Category</th><th>Total Users</th><th>Active Users</th><th>Engagement Rate</th></tr></thead>
                    <tbody>
                        <tr>
                            <td>PWD</td>
                            <td>{{ $pwdInfo['total_users'] ?? 0 }}</td>
                            <td>{{ $pwdInfo['active_users'] ?? 0 }}</td>
                            <td>{{ isset($pwdInfo['engagement_rate']) ? round($pwdInfo['engagement_rate'],2) . '%' : '0%' }}</td>
                        </tr>
                        <tr>
                            <td>Non-PWD</td>
                            <td>{{ $nonPwdInfo['total_users'] ?? 0 }}</td>
                            <td>{{ $nonPwdInfo['active_users'] ?? 0 }}</td>
                            <td>{{ isset($nonPwdInfo['engagement_rate']) ? round($nonPwdInfo['engagement_rate'],2) . '%' : '0%' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Top Engaged Users -->
            <div style="margin-bottom:8px;">
                <div style="font-weight:bold; margin-bottom:4px;">Top Engaged Users</div>
                <table>
                    <thead><tr><th>#</th><th>User</th><th>Total Reservations</th><th>Preferred Services</th><th>Last Activity</th></tr></thead>
                    <tbody>
                        @php $rank = 1; @endphp
                        @foreach(($engagementData['service_preferences'] ?? collect())->take(20) as $user)
                            <tr>
                                <td>{{ $rank++ }}</td>
                                <td>{{ $user['user_name'] ?? 'Unknown' }}</td>
                                <td>{{ $user['total_reservations'] ?? 0 }}</td>
                                <td>{{ is_array($user['preferred_services'] ?? null) ? implode(' | ', array_keys($user['preferred_services'])) : (isset($user['preferred_services']) && $user['preferred_services'] instanceof \Illuminate\Support\Collection ? $user['preferred_services']->keys()->join(' | ') : '') }}</td>
                                <td>{{ $user['last_activity'] ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @else
            <div class="no-data">No engagement data available for the selected period.</div>
        @endif
    </div>
    @endif

    {{-- Reason demographics section (detailed) --}}
    @if($reportType === 'reasons' || $reportType === 'all')
        @if(isset($reasonsData['reason_demographics']) && count((array)$reasonsData['reason_demographics']) > 0)
            <div class="section">
                <div class="section-title">REASON DEMOGRAPHICS</div>
                <table>
                    <thead>
                        <tr>
                            <th>Reason</th>
                            <th>Total Users</th>
                            <th>Under 18</th>
                            <th>18-29</th>
                            <th>30-49</th>
                            <th>50+</th>
                            <th>Male</th>
                            <th>Female</th>
                            <th>PWD %</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reasonsData['reason_demographics'] as $reason => $data)
                            @php
                                $demo = $data['demographics'] ?? [];
                                $age = $demo['age_groups'] ?? [];
                                $gender = $demo['gender_split'] ?? [];
                            @endphp
                            <tr>
                                <td>{{ $reason }}</td>
                                <td>{{ $data['total_users'] ?? 0 }}</td>
                                <td>{{ $age['Under 18'] ?? 0 }}</td>
                                <td>{{ $age['18-29'] ?? 0 }}</td>
                                <td>{{ $age['30-49'] ?? 0 }}</td>
                                <td>{{ $age['50+'] ?? 0 }}</td>
                                <td>{{ $gender['Male'] ?? 0 }}</td>
                                <td>{{ $gender['Female'] ?? 0 }}</td>
                                <td>{{ round($demo['pwd_percentage'] ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif

    <div class="footer">
        <p>This report was generated automatically by the Barangay 22-C Management System.</p>
        <p>For questions or concerns, please contact the system administrator.</p>
    </div>
</body>
</html>