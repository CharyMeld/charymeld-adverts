@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-2 text-sm text-gray-600 mb-4">
            <a href="{{ route('advertiser.analytics.index') }}" class="hover:text-primary-600">Analytics</a>
            <span>→</span>
            <span class="text-gray-900 font-medium">{{ $advert->title }}</span>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $advert->title }}</h1>
                <p class="text-gray-600 mt-2">Campaign Analytics & Performance</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('advertiser.adverts.edit', $advert) }}" class="btn btn-secondary">
                    Edit Campaign
                </a>
            </div>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <form method="GET" action="{{ route('advertiser.analytics.campaign', $advert) }}" class="flex flex-wrap gap-4 items-end">
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
            <p class="text-purple-100 text-sm font-medium mb-1">Impressions</p>
            <p class="text-3xl font-bold">{{ number_format($stats['impressions']) }}</p>
            <p class="text-purple-100 text-xs mt-2">Total views</p>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
            <p class="text-green-100 text-sm font-medium mb-1">Clicks</p>
            <p class="text-3xl font-bold">{{ number_format($stats['clicks']) }}</p>
            <p class="text-green-100 text-xs mt-2">Detail page visits</p>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
            <p class="text-orange-100 text-sm font-medium mb-1">Contact Clicks</p>
            <p class="text-3xl font-bold">{{ number_format($stats['contact_clicks']) }}</p>
            <p class="text-orange-100 text-xs mt-2">Phone, Email, WhatsApp</p>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
            <p class="text-blue-100 text-sm font-medium mb-1">Unique Visitors</p>
            <p class="text-3xl font-bold">{{ number_format($stats['unique_visitors']) }}</p>
            <p class="text-blue-100 text-xs mt-2">Distinct users</p>
        </div>

        <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl p-6 text-white shadow-lg">
            <p class="text-pink-100 text-sm font-medium mb-1">CTR</p>
            <p class="text-3xl font-bold">{{ number_format($stats['ctr'], 2) }}%</p>
            <p class="text-pink-100 text-xs mt-2">Click-through rate</p>
        </div>
    </div>

    <!-- Improvement Suggestions -->
    @if(count($suggestions) > 0)
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">💡 Improvement Suggestions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($suggestions as $suggestion)
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4
                    @if($suggestion['type'] === 'success') border-green-500
                    @elseif($suggestion['type'] === 'warning') border-yellow-500
                    @elseif($suggestion['type'] === 'danger') border-red-500
                    @else border-blue-500
                    @endif">
                    <div class="flex items-start space-x-3">
                        <span class="text-3xl">{{ $suggestion['icon'] }}</span>
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900 mb-1">{{ $suggestion['title'] }}</h3>
                            <p class="text-sm text-gray-600">{{ $suggestion['message'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Performance Chart -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Daily Performance (Last 30 Days)</h2>
        <div class="h-80">
            <canvas id="performanceChart"></canvas>
        </div>
    </div>

    <!-- Campaign Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Campaign Details</h2>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">Status</span>
                    <span class="font-medium">
                        @if($advert->is_active)
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">Active</span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-semibold rounded-full">Inactive</span>
                        @endif
                    </span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">Category</span>
                    <span class="font-medium">{{ $advert->category->name }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">Price</span>
                    <span class="font-medium">₦{{ number_format($advert->price) }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">Location</span>
                    <span class="font-medium">{{ $advert->location ?? 'Nigeria' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">Published</span>
                    <span class="font-medium">{{ $advert->created_at->format('M d, Y') }}</span>
                </div>
                @if($advert->expires_at)
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">Expires</span>
                    <span class="font-medium">{{ \Carbon\Carbon::parse($advert->expires_at)->format('M d, Y') }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
            <div class="space-y-3">
                <a href="{{ route('advert.show', $advert->slug) }}" target="_blank"
                   class="block w-full px-4 py-3 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-center font-medium transition">
                    👁️ View Live Ad
                </a>
                <a href="{{ route('advertiser.adverts.edit', $advert) }}"
                   class="block w-full px-4 py-3 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg text-center font-medium transition">
                    ✏️ Edit Campaign
                </a>
                <button onclick="shareCampaign()"
                   class="block w-full px-4 py-3 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg text-center font-medium transition">
                    🔗 Share Campaign
                </button>
                @if($advert->is_active)
                <form method="POST" action="{{ route('advertiser.adverts.pause', $advert) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="block w-full px-4 py-3 bg-yellow-50 hover:bg-yellow-100 text-yellow-700 rounded-lg text-center font-medium transition">
                        ⏸️ Pause Campaign
                    </button>
                </form>
                @else
                <form method="POST" action="{{ route('advertiser.adverts.resume', $advert) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="block w-full px-4 py-3 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg text-center font-medium transition">
                        ▶️ Resume Campaign
                    </button>
                </form>
                @endif
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
        labels: {!! json_encode($dailyPerformance->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))) !!},
        datasets: [{
            label: 'Impressions',
            data: {!! json_encode($dailyPerformance->pluck('impressions')) !!},
            borderColor: 'rgb(147, 51, 234)',
            backgroundColor: 'rgba(147, 51, 234, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Clicks',
            data: {!! json_encode($dailyPerformance->pluck('clicks')) !!},
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Contact Clicks',
            data: {!! json_encode($dailyPerformance->pluck('contact_clicks')) !!},
            borderColor: 'rgb(251, 146, 60)',
            backgroundColor: 'rgba(251, 146, 60, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                mode: 'index',
                intersect: false,
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});

function shareCampaign() {
    const url = '{{ route('advert.show', $advert->slug) }}';
    if (navigator.share) {
        navigator.share({
            title: '{{ $advert->title }}',
            text: 'Check out this ad on CharyMeld Adverts',
            url: url
        });
    } else {
        navigator.clipboard.writeText(url);
        alert('Campaign link copied to clipboard!');
    }
}
</script>
@endsection
