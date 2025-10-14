@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Analytics Dashboard</h1>
            <p class="text-gray-600 mt-2">Track your campaign performance</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('advertiser.reports.all-campaigns.csv', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-secondary">
                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export CSV
            </a>
            <a href="{{ route('advertiser.reports.all-campaigns.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-secondary">
                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Export PDF
            </a>
            <a href="{{ route('advertiser.campaigns.create') }}" class="btn btn-primary">
                Create New Campaign
            </a>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <form method="GET" action="{{ route('advertiser.analytics.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
            </div>
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                Apply Filter
            </button>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Campaigns</p>
                    <p class="text-4xl font-bold">{{ $stats['total_campaigns'] }}</p>
                    <p class="text-blue-100 text-xs mt-2">{{ $stats['active_campaigns'] }} active</p>
                </div>
                <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Total Impressions</p>
                    <p class="text-4xl font-bold">{{ number_format($stats['total_impressions']) }}</p>
                </div>
                <svg class="w-12 h-12 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Total Clicks</p>
                    <p class="text-4xl font-bold">{{ number_format($stats['total_clicks']) }}</p>
                    <p class="text-green-100 text-xs mt-2">CTR: {{ number_format($stats['average_ctr'], 2) }}%</p>
                </div>
                <svg class="w-12 h-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium mb-1">Total Spent</p>
                    <p class="text-4xl font-bold">₦{{ number_format($stats['total_spent'], 2) }}</p>
                </div>
                <svg class="w-12 h-12 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Performance Chart -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Performance Over Time</h2>
        <div class="h-80">
            <canvas id="performanceChart"></canvas>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Device Breakdown -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Device Distribution</h2>
            <div class="h-64">
                <canvas id="deviceChart"></canvas>
            </div>
        </div>

        <!-- Hourly Performance -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Hourly Click Pattern</h2>
            <div class="h-64">
                <canvas id="hourlyChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Campaigns & Country Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top Performing Campaigns -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Top Performing Campaigns</h2>
            <div class="space-y-4">
                @forelse($topCampaigns as $campaign)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div class="flex-1">
                            <a href="{{ route('advertiser.analytics.campaign', $campaign) }}" class="font-medium text-gray-900 hover:text-primary-600">
                                {{ $campaign->title }}
                            </a>
                            <div class="flex items-center space-x-4 mt-1">
                                <span class="text-xs text-gray-500">{{ number_format($campaign->impressions) }} views</span>
                                <span class="text-xs text-gray-500">{{ number_format($campaign->clicks) }} clicks</span>
                                <span class="text-xs text-primary-600 font-medium">{{ number_format($campaign->ctr, 2) }}% CTR</span>
                                <a href="{{ route('advertiser.reports.campaign.pdf', ['advert' => $campaign->id]) }}" class="text-xs text-blue-600 hover:underline">📄 PDF</a>
                            </div>
                        </div>
                        <div class="text-right">
                            @if($campaign->is_active)
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">Paused</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">No campaigns yet</p>
                @endforelse
            </div>
        </div>

        <!-- Country Distribution -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Top Countries</h2>
            <div class="space-y-3">
                @forelse($countryStats as $country)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="text-2xl">{{ $country->country_code === 'NG' ? '🇳🇬' : ($country->country_code === 'GH' ? '🇬🇭' : ($country->country_code === 'US' ? '🇺🇸' : '🌍')) }}</span>
                            <span class="font-medium text-gray-900">{{ $country->country_code ?? 'Unknown' }}</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="text-gray-600">{{ number_format($country->count) }}</span>
                            <div class="w-32 bg-gray-200 rounded-full h-2">
                                <div class="bg-primary-600 h-2 rounded-full" style="width: {{ ($country->count / $countryStats->max('count')) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">No data available</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Performance Chart
const performanceCtx = document.getElementById('performanceChart').getContext('2d');
new Chart(performanceCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($dailyStats->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))) !!},
        datasets: [{
            label: 'Impressions',
            data: {!! json_encode($dailyStats->pluck('impressions')) !!},
            borderColor: 'rgb(147, 51, 234)',
            backgroundColor: 'rgba(147, 51, 234, 0.1)',
            tension: 0.4
        }, {
            label: 'Clicks',
            data: {!! json_encode($dailyStats->pluck('clicks')) !!},
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Device Chart
const deviceCtx = document.getElementById('deviceChart').getContext('2d');
new Chart(deviceCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($deviceStats->pluck('device_type')->map(fn($d) => ucfirst($d ?? 'Unknown'))) !!},
        datasets: [{
            data: {!! json_encode($deviceStats->pluck('count')) !!},
            backgroundColor: [
                'rgb(59, 130, 246)',
                'rgb(34, 197, 94)',
                'rgb(251, 146, 60)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Hourly Chart
const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
new Chart(hourlyCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($hourlyStats->pluck('hour')->map(fn($h) => $h . ':00')) !!},
        datasets: [{
            label: 'Clicks',
            data: {!! json_encode($hourlyStats->pluck('count')) !!},
            backgroundColor: 'rgb(34, 197, 94)',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection
