<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Campaign Report - {{ $advert->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #3b82f6;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            color: #666;
            margin: 5px 0;
        }
        .summary {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .summary h2 {
            color: #3b82f6;
            font-size: 18px;
            margin-top: 0;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        .stat-box {
            background: white;
            border: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 8px;
        }
        .stat-box .label {
            color: #6b7280;
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .stat-box .value {
            color: #111827;
            font-size: 24px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        table th {
            background: #3b82f6;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        table tr:nth-child(even) {
            background: #f9fafb;
        }
        .section-title {
            color: #3b82f6;
            font-size: 16px;
            margin-top: 25px;
            margin-bottom: 15px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Campaign Performance Report</h1>
        <p><strong>{{ $advert->title }}</strong></p>
        <p>Report Period: {{ \Carbon\Carbon::parse($startDate)->format('F j, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('F j, Y') }}</p>
        <p>Generated: {{ $generatedAt }}</p>
    </div>

    <!-- Campaign Summary -->
    <div class="summary">
        <h2>Campaign Summary</h2>
        <p><strong>Campaign Type:</strong> {{ ucfirst($advert->ad_type ?? 'classified') }}</p>
        <p><strong>Status:</strong> {{ ucfirst($advert->status) }}</p>
        <p><strong>Pricing Model:</strong> {{ strtoupper($advert->pricing_model ?? 'flat') }}</p>
        @if($advert->start_date)
            <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($advert->start_date)->format('F j, Y') }}</p>
        @endif
        @if($advert->end_date)
            <p><strong>End Date:</strong> {{ \Carbon\Carbon::parse($advert->end_date)->format('F j, Y') }}</p>
        @endif
    </div>

    <!-- Key Metrics -->
    <div class="stats-grid">
        <div class="stat-box">
            <div class="label">Total Impressions</div>
            <div class="value">{{ number_format($stats['impressions']) }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Total Clicks</div>
            <div class="value">{{ number_format($stats['clicks']) }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Click-Through Rate</div>
            <div class="value">{{ number_format($stats['ctr'], 2) }}%</div>
        </div>
        <div class="stat-box">
            <div class="label">Total Spent</div>
            <div class="value">₦{{ number_format($stats['spent'], 2) }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Budget</div>
            <div class="value">₦{{ number_format($stats['budget'] ?? 0, 2) }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Avg Cost Per Click</div>
            <div class="value">₦{{ number_format($stats['average_cost_per_click'], 2) }}</div>
        </div>
    </div>

    <!-- Daily Performance -->
    <h3 class="section-title">Daily Performance</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Impressions</th>
                <th>Clicks</th>
                <th>CTR (%)</th>
                <th>Cost</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dailyPerformance as $day)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($day->date)->format('M j, Y') }}</td>
                    <td>{{ number_format($day->impressions) }}</td>
                    <td>{{ number_format($day->clicks) }}</td>
                    <td>{{ $day->impressions > 0 ? number_format(($day->clicks / $day->impressions) * 100, 2) : '0.00' }}</td>
                    <td>₦{{ number_format($day->cost, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #6b7280;">No performance data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Geographic Distribution -->
    <h3 class="section-title">Geographic Distribution</h3>
    <table>
        <thead>
            <tr>
                <th>Country</th>
                <th>Impressions</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @php $totalGeo = $geoDistribution->sum('impressions'); @endphp
            @forelse($geoDistribution as $geo)
                <tr>
                    <td>{{ $geo->country_code ?? 'Unknown' }}</td>
                    <td>{{ number_format($geo->impressions) }}</td>
                    <td>{{ $totalGeo > 0 ? number_format(($geo->impressions / $totalGeo) * 100, 1) : 0 }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: #6b7280;">No geographic data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Device Distribution -->
    <h3 class="section-title">Device Distribution</h3>
    <table>
        <thead>
            <tr>
                <th>Device Type</th>
                <th>Impressions</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @php $totalDevice = $deviceDistribution->sum('impressions'); @endphp
            @forelse($deviceDistribution as $device)
                <tr>
                    <td>{{ ucfirst($device->device_type ?? 'Unknown') }}</td>
                    <td>{{ number_format($device->impressions) }}</td>
                    <td>{{ $totalDevice > 0 ? number_format(($device->impressions / $totalDevice) * 100, 1) : 0 }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: #6b7280;">No device data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>This report was automatically generated by CharyMeld Adverts</p>
        <p>© {{ date('Y') }} CharyMeld. All rights reserved.</p>
    </div>
</body>
</html>
