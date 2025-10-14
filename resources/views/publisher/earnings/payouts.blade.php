@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Payouts</h1>
        <p class="text-gray-600 mt-2">Request withdrawals and view payout history</p>
    </div>

    <!-- Balance Card -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-8 text-white mb-8 shadow-xl">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 mb-2">Available Balance</p>
                <p class="text-5xl font-bold mb-4">₦{{ number_format($pendingBalance, 2) }}</p>
                <p class="text-green-100 text-sm">Minimum payout: ₦{{ number_format($profile->minimum_payout, 2) }}</p>
            </div>
            <div>
                @if($canRequestPayout)
                    <form method="POST" action="{{ route('publisher.payouts.request') }}" onsubmit="return confirm('Request payout of ₦{{ number_format($pendingBalance, 2) }}?');">
                        @csrf
                        <button type="submit" class="px-8 py-4 bg-white text-green-600 rounded-xl font-bold hover:bg-green-50 transition-colors shadow-lg">
                            Request Payout
                        </button>
                    </form>
                @else
                    <div class="bg-green-400 bg-opacity-30 rounded-xl px-6 py-4 text-center">
                        <p class="text-sm font-medium">Need ₦{{ number_format($profile->minimum_payout - $pendingBalance, 2) }} more</p>
                        <p class="text-xs mt-1">to reach minimum payout</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Details -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900">Payment Method</h2>
            <a href="{{ route('publisher.dashboard') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                Update Payment Details
            </a>
        </div>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm text-gray-600 mb-2"><strong>Method:</strong> {{ ucfirst(str_replace('_', ' ', $profile->payment_method)) }}</p>
            @if($profile->payment_details)
                @if($profile->payment_method === 'bank_transfer')
                    <p class="text-sm text-gray-600"><strong>Bank:</strong> {{ $profile->payment_details['bank_name'] ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600"><strong>Account:</strong> {{ $profile->payment_details['account_number'] ?? 'N/A' }}</p>
                @elseif($profile->payment_method === 'paypal')
                    <p class="text-sm text-gray-600"><strong>PayPal:</strong> {{ $profile->payment_details['paypal_email'] ?? 'N/A' }}</p>
                @elseif($profile->payment_method === 'mobile_money')
                    <p class="text-sm text-gray-600"><strong>Provider:</strong> {{ $profile->payment_details['provider'] ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600"><strong>Phone:</strong> {{ $profile->payment_details['phone_number'] ?? 'N/A' }}</p>
                @endif
            @endif
        </div>
    </div>

    <!-- Payout History -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Payout History</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Processed</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payouts as $payout)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $payout->requested_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                ₦{{ number_format($payout->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ ucfirst(str_replace('_', ' ', $payout->payment_method)) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payout->status === 'completed')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Completed
                                    </span>
                                @elseif($payout->status === 'processing')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Processing
                                    </span>
                                @elseif($payout->status === 'cancelled')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Cancelled
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @if($payout->paid_at)
                                    {{ $payout->paid_at->format('M d, Y') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                No payout requests yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payouts->hasPages())
            <div class="p-6 border-t border-gray-200">
                {{ $payouts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
