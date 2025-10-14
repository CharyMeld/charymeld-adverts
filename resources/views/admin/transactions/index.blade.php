@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Transactions</h1>
            <p class="mt-1 text-gray-600">Manage all payment transactions</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
            ← Back to Dashboard
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="card bg-gradient-to-br from-green-50 to-emerald-50 border-l-4 border-green-500">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-600 mb-1">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-900">₦{{ number_format($stats['total_revenue']) }}</p>
            </div>
        </div>
        <div class="card bg-gradient-to-br from-blue-50 to-cyan-50 border-l-4 border-blue-500">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-600 mb-1">Total</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_transactions'] }}</p>
            </div>
        </div>
        <div class="card bg-gradient-to-br from-green-50 to-emerald-50 border-l-4 border-green-500">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-600 mb-1">Successful</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['successful'] }}</p>
            </div>
        </div>
        <div class="card bg-gradient-to-br from-yellow-50 to-amber-50 border-l-4 border-yellow-500">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-600 mb-1">Pending</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
            </div>
        </div>
        <div class="card bg-gradient-to-br from-red-50 to-rose-50 border-l-4 border-red-500">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-600 mb-1">Failed</p>
                <p class="text-2xl font-bold text-red-600">{{ $stats['failed'] }}</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Search by reference..."
                       class="input">
            </div>
            <div class="w-48">
                <select name="status" class="input">
                    <option value="">All Status</option>
                    <option value="successful" {{ request('status') === 'successful' ? 'selected' : '' }}>Successful</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="w-48">
                <select name="gateway" class="input">
                    <option value="">All Gateways</option>
                    <option value="paystack" {{ request('gateway') === 'paystack' ? 'selected' : '' }}>Paystack</option>
                    <option value="flutterwave" {{ request('gateway') === 'flutterwave' ? 'selected' : '' }}>Flutterwave</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary btn-sm">Clear</a>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Reference</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Advert</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Gateway</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-mono text-gray-900">{{ $transaction->reference }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">{{ $transaction->user->name }}</div>
                                    <div class="text-gray-500">{{ $transaction->user->email }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($transaction->advert)
                                    <div class="text-sm text-gray-900">{{ Str::limit($transaction->advert->title, 30) }}</div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-gray-900">₦{{ number_format($transaction->amount) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="badge badge-info">{{ ucfirst($transaction->gateway) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($transaction->status === 'successful')
                                    <span class="badge badge-success">Successful</span>
                                @elseif($transaction->status === 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-danger">Failed</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $transaction->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $transaction->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.transactions.show', $transaction) }}"
                                   class="text-primary-600 hover:text-primary-800 font-medium">
                                    View Details →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">No transactions found</h3>
                                <p class="mt-2 text-gray-500">Try adjusting your filters</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
