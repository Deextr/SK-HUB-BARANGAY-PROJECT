<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - {{ $dateRangeText }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #e5e7eb;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #1f2937;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            color: #6b7280;
            margin: 5px 0;
        }
        .section {
            margin-bottom: 40px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #1f2937;
        }
        .stat-box {
            display: inline-block;
            margin: 10px;
            padding: 15px;
            border: 2px solid #e5e7eb;
            border-radius: 5px;
            min-width: 200px;
            text-align: center;
        }
        .stat-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
        }
        .service-section {
            margin-bottom: 30px;
            border: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 5px;
        }
        .service-title {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
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

    @if($reportType === 'reservations' || $reportType === 'all')
    <div class="section">
        <div class="section-title">RESERVATIONS REPORT</div>
        
        <div style="text-align: center; margin-bottom: 20px;">
            <div class="stat-box">
                <div class="stat-label">Total Reservations</div>
                <div class="stat-value">{{ isset($reservationsData) ? number_format($reservationsData['total']) : 0 }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Cancelled</div>
                <div class="stat-value">{{ isset($reservationsData) ? number_format($reservationsData['cancelled']) : 0 }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Completed</div>
                <div class="stat-value">{{ isset($reservationsData) ? number_format($reservationsData['completed']) : 0 }}</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Count</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Reservations</td>
                    <td>{{ isset($reservationsData) ? number_format($reservationsData['total']) : 0 }}</td>
                    <td>100%</td>
                </tr>
                <tr>
                    <td>Cancelled</td>
                    <td>{{ isset($reservationsData) ? number_format($reservationsData['cancelled']) : 0 }}</td>
                    <td>{{ isset($reservationsData) && $reservationsData['total'] > 0 ? number_format(($reservationsData['cancelled'] / $reservationsData['total']) * 100, 2) : 0 }}%</td>
                </tr>
                <tr>
                    <td>Completed</td>
                    <td>{{ isset($reservationsData) ? number_format($reservationsData['completed']) : 0 }}</td>
                    <td>{{ isset($reservationsData) && $reservationsData['total'] > 0 ? number_format(($reservationsData['completed'] / $reservationsData['total']) * 100, 2) : 0 }}%</td>
                </tr>
                <tr>
                    <td>Other Status</td>
                    <td>{{ isset($reservationsData) ? number_format($reservationsData['total'] - $reservationsData['cancelled'] - $reservationsData['completed']) : 0 }}</td>
                    <td>{{ isset($reservationsData) && $reservationsData['total'] > 0 ? number_format((($reservationsData['total'] - $reservationsData['cancelled'] - $reservationsData['completed']) / $reservationsData['total']) * 100, 2) : 0 }}%</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    @if($reportType === 'services' || $reportType === 'all')
    <div class="section">
        <div class="section-title">SERVICES REPORT</div>
        
        @if(count($servicesData) > 0)
            @foreach($servicesData as $data)
                <div class="service-section">
                    <div class="service-title">{{ $data['service']->name }}</div>
                    <p style="color: #6b7280; margin-bottom: 15px;">{{ $data['service']->description }}</p>
                    
                    <table style="margin-bottom: 15px;">
                        <tr>
                            <th>Total Reservations</th>
                            <td>{{ number_format($data['usage_count']) }}</td>
                        </tr>
                        <tr>
                            <th>Unique Users</th>
                            <td>{{ number_format($data['unique_users']) }}</td>
                        </tr>
                    </table>

                    <h4 style="font-size: 16px; font-weight: bold; margin-bottom: 10px; color: #1f2937;">Age Range Breakdown</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Age Range</th>
                                <th>Count</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['age_breakdown'] as $range => $count)
                                <tr>
                                    <td>{{ $range }} years</td>
                                    <td>{{ number_format($count) }}</td>
                                    <td>{{ $data['usage_count'] > 0 ? number_format(($count / $data['usage_count']) * 100, 2) : 0 }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        @else
            <p style="color: #6b7280; text-align: center; padding: 20px;">No services data available for the selected period.</p>
        @endif
    </div>
    @endif

    <div class="footer">
        <p>This report was generated automatically by the Barangay 22-C Management System.</p>
        <p>For questions or concerns, please contact the system administrator.</p>
    </div>
</body>
</html>

