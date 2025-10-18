@extends('layouts.app')

@section('title', 'Publisher Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.publishers.index') }}" class="text-primary-600 hover:text-primary-800 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Publishers
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Publisher Header -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16">
                    <div class="h-16 w-16 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                        <span class="text-primary-600 dark:text-primary-400 font-bold text-2xl">{{ substr($publisher->user->name, 0, 1) }}</span>
                    </div>
                </div>
                <div class="ml-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $publisher->user->name }}</h1>
                    <p class="text-gray-600 dark:text-gray-400">{{ $publisher->user->email }}</p>
                </div>
            </div>
            <div>
                @if($publisher->status === 'pending')
                    <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending Review</span>
                @elseif($publisher->status === 'approved')
                    <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                @elseif($publisher->status === 'rejected')
                    <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Website Information -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Website Information</h2>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Website Name</label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $publisher->website_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Website URL</label>
                        <p class="text-gray-900 dark:text-gray-100">
                            <a href="{{ $publisher->website_url }}" target="_blank" class="text-primary-600 hover:underline">
                                {{ $publisher->website_url }}
                                <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Category</label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $publisher->website_category ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Monthly Visitors</label>
                        <p class="text-gray-900 dark:text-gray-100">{{ number_format($publisher->monthly_visitors) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $publisher->website_description ?? 'No description provided' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Applied On</label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $publisher->created_at->format('F d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Payment Information</h2>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Revenue Share</label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $publisher->revenue_share }}%</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Minimum Payout</label>
                        <p class="text-gray-900 dark:text-gray-100">₦{{ number_format($publisher->minimum_payout, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Method</label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $publisher->payment_method ?? 'Not specified' }}</p>
                    </div>
                </div>

                @if($publisher->status === 'approved')
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <form method="POST" action="{{ route('admin.publishers.update-revenue-share', $publisher) }}">
                            @csrf
                            @method('PUT')
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Update Revenue Share (%)</label>
                            <div class="mt-2 flex space-x-3">
                                <input type="number" name="revenue_share" value="{{ $publisher->revenue_share }}" min="0" max="100" step="0.01"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">Update</button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

            @if($publisher->status === 'rejected' && $publisher->rejection_reason)
                <!-- Rejection Reason -->
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-2">Rejection Reason</h2>
                    <p class="text-red-800 dark:text-red-200">{{ $publisher->rejection_reason }}</p>
                </div>
            @endif
        </div>

        <!-- Right Column - Actions & Stats -->
        <div class="space-y-6">
            <!-- Action Buttons -->
            @if($publisher->status === 'pending')
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Actions</h2>
                    <div class="space-y-3">
                        <form method="POST" action="{{ route('admin.publishers.approve', $publisher) }}" onsubmit="return confirm('Approve this publisher application?')">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Approve Publisher
                            </button>
                        </form>

                        <button onclick="showRejectModal()" class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Reject Application
                        </button>
                    </div>
                </div>
            @endif

            <!-- Stats -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Statistics</h2>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Zones</label>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total_zones'] ?? 0 }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Zones</label>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['active_zones'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Reject Publisher Application</h3>
            <form method="POST" action="{{ route('admin.publishers.reject', $publisher) }}" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rejection Reason</label>
                    <textarea id="rejection_reason" name="rejection_reason" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                              placeholder="Please provide a reason for rejection..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('rejectModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
@endsection
