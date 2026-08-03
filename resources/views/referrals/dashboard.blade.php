@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
            {{ __('messages.referral.dashboard_title') }}
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            {{ __('messages.referral.dashboard_subtitle') }}
        </p>
    </div>

    <!-- Referral Link Card -->
    <div class="card mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
            {{ __('messages.referral.your_link') }}
        </h2>
        <p class="text-gray-600 dark:text-gray-400 mb-4">
            {{ __('messages.referral.share_description') }}
        </p>

        <div class="flex gap-3 mb-4">
            <input type="text"
                   id="referral-link"
                   value="{{ route('home') }}?ref={{ $referralCode }}"
                   readonly
                   class="input flex-1 bg-gray-50 dark:bg-gray-700">
            <button onclick="copyReferralLink()"
                    class="btn btn-primary whitespace-nowrap">
                📋 {{ __('messages.referral.copy_link') }}
            </button>
        </div>

        <div class="flex gap-3">
            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('messages.referral.your_code') }}:</span>
            <code class="text-lg font-bold text-primary-600 dark:text-primary-400">{{ $referralCode }}</code>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Clicks -->
        <div class="card">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">
                    {{ __('messages.referral.total_clicks') }}
                </h3>
                <span class="text-2xl">👆</span>
            </div>
            <div class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ number_format($stats['total_clicks']) }}
            </div>
        </div>

        <!-- Total Registrations -->
        <div class="card">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">
                    {{ __('messages.referral.total_registrations') }}
                </h3>
                <span class="text-2xl">✍️</span>
            </div>
            <div class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ number_format($stats['total_registrations']) }}
            </div>
        </div>

        <!-- Total Conversions -->
        <div class="card">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">
                    {{ __('messages.referral.total_conversions') }}
                </h3>
                <span class="text-2xl">🎯</span>
            </div>
            <div class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ number_format($stats['total_conversions']) }}
            </div>
        </div>

        <!-- Total Earnings -->
        <div class="card bg-gradient-to-br from-primary-500 to-purple-500">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-white/90">
                    {{ __('messages.referral.total_earnings') }}
                </h3>
                <span class="text-2xl">💰</span>
            </div>
            <div class="text-3xl font-bold text-white">
                ₦{{ number_format($stats['total_earnings'], 2) }}
            </div>
        </div>
    </div>

    <!-- Referral History -->
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ __('messages.referral.history_title') }}
            </h2>
            @if($referrals->total() > 0)
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('messages.referral.showing_results', ['from' => $referrals->firstItem(), 'to' => $referrals->lastItem(), 'total' => $referrals->total()]) }}
                </span>
            @endif
        </div>

        @if($referrals->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ __('messages.referral.table.user') }}
                            </th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ __('messages.referral.table.clicked') }}
                            </th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ __('messages.referral.table.registered') }}
                            </th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ __('messages.referral.table.converted') }}
                            </th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ __('messages.referral.table.commission') }}
                            </th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ __('messages.referral.table.status') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($referrals as $referral)
                            <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="py-4 px-4">
                                    @if($referral->referred)
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ $referral->referred->name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $referral->referred->email }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500 italic">
                                            {{ __('messages.referral.table.pending_signup') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-600 dark:text-gray-400">
                                    @if($referral->clicked_at)
                                        <div>{{ $referral->clicked_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-400">{{ $referral->clicked_at->format('h:i A') }}</div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-600 dark:text-gray-400">
                                    @if($referral->registered_at)
                                        <div>{{ $referral->registered_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-400">{{ $referral->registered_at->format('h:i A') }}</div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-600 dark:text-gray-400">
                                    @if($referral->converted_at)
                                        <div>{{ $referral->converted_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-400">{{ $referral->converted_at->format('h:i A') }}</div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-sm font-semibold text-gray-900 dark:text-white">
                                    @if($referral->commission_earned > 0)
                                        ₦{{ number_format($referral->commission_earned, 2) }}
                                    @else
                                        <span class="text-gray-400">₦0.00</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                            'active' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$referral->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($referral->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $referrals->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">🔗</div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                    {{ __('messages.referral.no_referrals_title') }}
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    {{ __('messages.referral.no_referrals_description') }}
                </p>
                <button onclick="copyReferralLink()" class="btn btn-primary">
                    {{ __('messages.referral.copy_and_share') }}
                </button>
            </div>
        @endif
    </div>
</div>

<script>
function copyReferralLink() {
    const input = document.getElementById('referral-link');
    const btn = event.target;
    const originalText = btn.innerHTML;

    // Modern approach - try navigator.clipboard first
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(input.value).then(() => {
            // Show success notification
            btn.innerHTML = '✓ {{ __("messages.referral.copied") }}';
            btn.classList.add('bg-green-600');

            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove('bg-green-600');
            }, 2000);
        }).catch(err => {
            console.error('Clipboard API failed:', err);
            fallbackCopy();
        });
    } else {
        // Fallback for older browsers or non-HTTPS
        fallbackCopy();
    }

    function fallbackCopy() {
        try {
            input.select();
            input.setSelectionRange(0, 99999); // For mobile devices
            const successful = document.execCommand('copy');

            if (successful) {
                btn.innerHTML = '✓ {{ __("messages.referral.copied") }}';
                btn.classList.add('bg-green-600');

                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('bg-green-600');
                }, 2000);
            } else {
                throw new Error('execCommand failed');
            }
        } catch (err) {
            console.error('Failed to copy:', err);
            // Select the text so user can manually copy
            input.select();
            input.setSelectionRange(0, 99999);
            alert('Please press Ctrl+C (Cmd+C on Mac) to copy the link');
        }
    }
}
</script>
@endsection
