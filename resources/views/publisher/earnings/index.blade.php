@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Earnings Report</h1>
        <p class="text-gray-600 mt-2">Track your ad revenue over time</p>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-600 text-sm mb-1">Total Earnings (All Time)</p>
            <p class="text-3xl font-bold text-gray-900">₦{{ number_format($summary['total'], 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-600 text-sm mb-1">Pending Balance</p>
            <p class="text-3xl font-bold text-blue-600">₦{{ number_format($summary['pending'], 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-600 text-sm mb-1">Paid Out</p>
            <p class="text-3xl font-bold text-green-600">₦{{ number_format($summary['paid'], 2) }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <form method="GET" action="{{ route('publisher.earnings.index') }}" class="flex flex-wrap gap-4">
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
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                    Filter
                </button>
                <a href="{{ route('publisher.earnings.export-csv', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                   class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Export CSV
                </a>
            </div>
        </form>
    </div>

    <!-- Period Summary -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900">Period Summary</h2>
            <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</span>
        </div>
        <p class="text-3xl font-bold text-primary-600">₦{{ number_format($summary['period_total'], 2) }}</p>
    </div>

    <!-- Top Zones -->
    @if($topZones->isNotEmpty())
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Top Performing Zones</h2>
            <div class="space-y-4">
                @foreach($topZones as $zoneEarning)
                    @if($zoneEarning->zone)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <div>
                                <p class="font-medium text-gray-900">{{ $zoneEarning->zone->zone_name }}</p>
                                <p class="text-sm text-gray-500">{{ $zoneEarning->zone->zone_code }}</p>
                            </div>
                            <p class="text-lg font-bold text-green-600">₦{{ number_format($zoneEarning->total, 2) }}</p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    <!-- Earnings Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Detailed Earnings</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Impressions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clicks</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($earnings as $earning)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($earning->date)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $earning->advert->title ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $earning->zone->zone_name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ number_format($earning->impressions) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ number_format($earning->clicks) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                ₦{{ number_format($earning->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($earning->status === 'paid')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                No earnings found for this period
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-gray-200">
            {{ $earnings->links() }}
        </div>
    </div>
</div>
@endsection
