<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>All Campaigns Report</title>
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
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        .stat-box {
            background: #f9fafb;
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
        <h1>All Campaigns Performance Report</h1>
        <p>Account: {{ $user->name }}</p>
        <p>Report Period: {{ \Carbon\Carbon::parse($startDate)->format('F j, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('F j, Y') }}</p>
        <p>Generated: {{ $generatedAt }}</p>
    </div>

    <!-- Overall Stats -->
    <div class="stats-grid">
        <div class="stat-box">
            <div class="label">Total Campaigns</div>
            <div class="value">{{ $stats['total_campaigns'] }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Active Campaigns</div>
            <div class="value">{{ $stats['active_campaigns'] }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Total Impressions</div>
            <div class="value">{{ number_format($stats['total_impressions']) }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Total Clicks</div>
            <div class="value">{{ number_format($stats['total_clicks']) }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Average CTR</div>
            <div class="value">{{ number_format($stats['average_ctr'], 2) }}%</div>
        </div>
        <div class="stat-box">
            <div class="label">Total Spent</div>
            <div class="value">₦{{ number_format($stats['total_spent'], 2) }}</div>
        </div>
    </div>

    <!-- Top Campaigns -->
    <h3 class="section-title">Top Performing Campaigns</h3>
    <table>
        <thead>
            <tr>
                <th>Campaign</th>
                <th>Type</th>
                <th>Impressions</th>
                <th>Clicks</th>
                <th>CTR</th>
                <th>Spent</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topCampaigns as $campaign)
                <tr>
                    <td>{{ $campaign->title }}</td>
                    <td>{{ ucfirst($campaign->ad_type ?? 'classified') }}</td>
                    <td>{{ number_format($campaign->impressions) }}</td>
                    <td>{{ number_format($campaign->clicks) }}</td>
                    <td>{{ number_format($campaign->ctr, 2) }}%</td>
                    <td>₦{{ number_format($campaign->spent, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #6b7280;">No campaigns available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Daily Performance -->
    <h3 class="section-title">Daily Performance Summary</h3>
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
            @forelse($dailyStats as $day)
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

    <!-- Footer -->
    <div class="footer">
        <p>This report was automatically generated by CharyMeld Adverts</p>
        <p>© {{ date('Y') }} CharyMeld. All rights reserved.</p>
    </div>
</body>
</html>
