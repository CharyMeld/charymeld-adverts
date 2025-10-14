@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $zone->zone_name }}</h1>
                <p class="text-gray-600 mt-2">Zone Code: <span class="font-mono font-semibold">{{ $zone->zone_code }}</span></p>
            </div>
            <div class="flex space-x-3">
                <form method="POST" action="{{ route('publisher.zones.toggle-status', $zone) }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 {{ $zone->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg">
                        {{ $zone->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
                <a href="{{ route('publisher.zones.edit', $zone) }}" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                    Edit Zone
                </a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-600 text-sm mb-1">Total Impressions</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_impressions']) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-600 text-sm mb-1">Total Clicks</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_clicks']) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-600 text-sm mb-1">CTR</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['ctr'], 2) }}%</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-600 text-sm mb-1">Total Earnings</p>
            <p class="text-3xl font-bold text-green-600">₦{{ number_format($totalEarnings, 2) }}</p>
        </div>
    </div>

    <!-- Embed Codes -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Embed Code</h2>

        <!-- Basic Embed -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-gray-900">Basic Embed (Recommended)</h3>
                <button onclick="copyToClipboard('basic-code')" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm">
                    Copy Code
                </button>
            </div>
            <pre id="basic-code" class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{{ $embedCode['basic'] }}</code></pre>
        </div>

        <!-- Advanced Embed -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-gray-900">Multiple Ads (3 ads)</h3>
                <button onclick="copyToClipboard('advanced-code')" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm">
                    Copy Code
                </button>
            </div>
            <pre id="advanced-code" class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{{ $embedCode['advanced'] }}</code></pre>
        </div>

        <!-- Manual Init -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-gray-900">Manual JavaScript</h3>
                <button onclick="copyToClipboard('manual-code')" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm">
                    Copy Code
                </button>
            </div>
            <pre id="manual-code" class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{{ $embedCode['manual'] }}</code></pre>
        </div>

        <!-- API URL -->
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-blue-900 mb-2"><strong>Direct API URL:</strong></p>
            <code class="text-blue-700">{{ $embedCode['api_url'] }}</code>
        </div>
    </div>
</div>

<script>
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    const text = element.textContent;

    navigator.clipboard.writeText(text).then(() => {
        // Show success message
        const btn = event.target;
        const originalText = btn.textContent;
        btn.textContent = 'Copied!';
        btn.classList.add('bg-green-600');
        btn.classList.remove('bg-primary-600');

        setTimeout(() => {
            btn.textContent = originalText;
            btn.classList.remove('bg-green-600');
            btn.classList.add('bg-primary-600');
        }, 2000);
    });
}
</script>
@endsection
