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

    @if($reportType === 'reservations' || $reportType === 'all')
    <div class="section">
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
    <div class="section">
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

    <div class="footer">
        <p>This report was generated automatically by the Barangay 22-C Management System.</p>
        <p>For questions or concerns, please contact the system administrator.</p>
    </div>
</body>
</html>

