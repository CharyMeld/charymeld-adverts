@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Publisher Payouts</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Manage withdrawal requests and process payments</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium mb-1">Pending Payouts</p>
                    <p class="text-3xl font-bold">₦{{ number_format($stats['pending'], 2) }}</p>
                </div>
                <svg class="w-12 h-12 text-yellow-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Processing</p>
                    <p class="text-3xl font-bold">₦{{ number_format($stats['processing'], 2) }}</p>
                </div>
                <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Completed</p>
                    <p class="text-3xl font-bold">₦{{ number_format($stats['completed'], 2) }}</p>
                </div>
                <svg class="w-12 h-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
        <form method="GET" action="{{ route('admin.payouts.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-gray-200">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Statuses</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $status === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                Apply Filter
            </button>
        </form>
    </div>

    <!-- Payouts Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Publisher</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Payment Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Requested</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($payouts as $payout)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $payout->publisher->name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $payout->publisher->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900 dark:text-gray-100">₦{{ number_format($payout->amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ ucfirst(str_replace('_', ' ', $payout->payment_method)) }}
                            </td>
                            <td class="px-6 py-4">
                                @if($payout->payment_details)
                                    <div class="text-xs text-gray-600 dark:text-gray-400">
                                        @if($payout->payment_method === 'bank_transfer')
                                            <div><strong>Bank:</strong> {{ $payout->payment_details['bank_name'] ?? 'N/A' }}</div>
                                            <div><strong>Account:</strong> {{ $payout->payment_details['account_number'] ?? 'N/A' }}</div>
                                            <div><strong>Name:</strong> {{ $payout->payment_details['account_name'] ?? 'N/A' }}</div>
                                        @elseif($payout->payment_method === 'paypal')
                                            <div><strong>PayPal:</strong> {{ $payout->payment_details['paypal_email'] ?? 'N/A' }}</div>
                                        @elseif($payout->payment_method === 'mobile_money')
                                            <div><strong>Provider:</strong> {{ $payout->payment_details['provider'] ?? 'N/A' }}</div>
                                            <div><strong>Phone:</strong> {{ $payout->payment_details['phone_number'] ?? 'N/A' }}</div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-gray-500 dark:text-gray-400">No details</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $payout->requested_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payout->status === 'completed')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Completed
                                    </span>
                                @elseif($payout->status === 'processing')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        Processing
                                    </span>
                                @elseif($payout->status === 'cancelled')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        Cancelled
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center space-x-2">
                                    @if($payout->status === 'pending')
                                        <form method="POST" action="{{ route('admin.payouts.process', $payout) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 font-medium" onclick="return confirm('Mark as processing?')">
                                                Process
                                            </button>
                                        </form>
                                        <span class="text-gray-300">|</span>
                                    @endif

                                    @if(in_array($payout->status, ['pending', 'processing']))
                                        <button onclick="showCompleteModal({{ $payout->id }}, '{{ $payout->publisher->name }}', {{ $payout->amount }})" class="text-green-600 hover:text-green-800 dark:text-green-400 font-medium">
                                            Complete
                                        </button>
                                        <span class="text-gray-300">|</span>
                                    @endif

                                    @if($payout->status !== 'completed')
                                        <form method="POST" action="{{ route('admin.payouts.cancel', $payout) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 font-medium" onclick="return confirm('Cancel this payout?')">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif

                                    @if($payout->status === 'completed' && $payout->payment_proof)
                                        <a href="{{ Storage::url($payout->payment_proof) }}" target="_blank" class="text-primary-600 hover:text-primary-800 dark:text-primary-400">
                                            View Proof
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                No payout requests found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payouts->hasPages())
            <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                {{ $payouts->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Complete Payout Modal -->
<div id="completeModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">Complete Payout</h3>
            <form id="completePayoutForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Publisher: <span id="modalPublisher" class="font-semibold"></span></p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Amount: <span id="modalAmount" class="font-semibold"></span></p>
                </div>

                <div class="mb-4">
                    <label for="payment_proof" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Payment Proof (Optional)
                    </label>
                    <input type="file" name="payment_proof" id="payment_proof" accept="image/*,.pdf"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-gray-200">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Upload receipt, transaction screenshot, or confirmation PDF</p>
                </div>

                <div class="mb-4">
                    <label for="admin_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Notes (Optional)
                    </label>
                    <textarea name="admin_notes" id="admin_notes" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-gray-200"
                        placeholder="Transaction ID, reference number, etc."></textarea>
                </div>

                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeCompleteModal()" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Complete Payout
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showCompleteModal(payoutId, publisherName, amount) {
    document.getElementById('modalPublisher').textContent = publisherName;
    document.getElementById('modalAmount').textContent = '₦' + parseFloat(amount).toLocaleString('en-NG', {minimumFractionDigits: 2});
    document.getElementById('completePayoutForm').action = `/admin/payouts/${payoutId}/complete`;
    document.getElementById('completeModal').classList.remove('hidden');
}

function closeCompleteModal() {
    document.getElementById('completeModal').classList.add('hidden');
    document.getElementById('completePayoutForm').reset();
}

// Close modal when clicking outside
document.getElementById('completeModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCompleteModal();
    }
});
</script>
@endsection
