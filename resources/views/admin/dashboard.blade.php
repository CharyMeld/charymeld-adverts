@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="text-gray-600">System overview and statistics</p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="card bg-green-50 border-l-4 border-[#2E6F40]">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-4xl">👥</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                    <p class="text-xs text-gray-500">+{{ $stats['new_users_this_month'] }} this month</p>
                </div>
            </div>
        </div>

        <div class="card bg-green-50 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-4xl">📢</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Adverts</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_adverts'] }}</p>
                    <p class="text-xs text-green-600">{{ $stats['active_adverts'] }} active</p>
                </div>
            </div>
        </div>

        <div class="card bg-yellow-50 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-4xl">⏳</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Approval</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_adverts'] }}</p>
                    <p class="text-xs text-gray-500">Needs review</p>
                </div>
            </div>
        </div>

        <div class="card bg-purple-50 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-4xl">💰</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Monthly Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">₦{{ number_format($stats['revenue_this_month']) }}</p>
                    <p class="text-xs text-gray-500">This month</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral Program Stats -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">🔗 Referral Program Stats</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="card bg-purple-50 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-4xl">👥</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Referrals</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_referrals'] }}</p>
                        <p class="text-xs text-purple-600">{{ $stats['active_referrals'] }} active</p>
                    </div>
                </div>
            </div>

            <div class="card bg-green-50 border-l-4 border-[#2E6F40]">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-4xl">👆</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Clicks</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_referral_clicks'] }}</p>
                        <p class="text-xs text-gray-500">Referral links clicked</p>
                    </div>
                </div>
            </div>

            <div class="card bg-green-50 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-4xl">✍️</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Signups</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_referral_signups'] }}</p>
                        <p class="text-xs text-green-600">{{ $stats['completed_referrals'] }} completed</p>
                    </div>
                </div>
            </div>

            <div class="card bg-yellow-50 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-4xl">💰</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Commissions</p>
                        <p class="text-2xl font-bold text-gray-900">₦{{ number_format($stats['total_referral_commissions'], 2) }}</p>
                        <p class="text-xs text-gray-500">Earned by referrers</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Pending Adverts -->
        <div class="card">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold">Pending Adverts</h2>
                <a href="{{ route('admin.adverts.index') }}?status=pending" class="text-[#2E6F40] hover:text-[#236030] font-medium">
                    View All →
                </a>
            </div>

            <div class="space-y-4">
                @forelse($pendingAdverts as $advert)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center flex-1">
                            @if($advert->primaryImage)
                                <img src="{{ asset('storage/' . $advert->primaryImage->image_path) }}"
                                     class="w-16 h-16 object-cover rounded">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                    <span class="text-2xl">📷</span>
                                </div>
                            @endif

                            <div class="ml-4 flex-1">
                                <h3 class="font-semibold">{{ Str::limit($advert->title, 40) }}</h3>
                                <p class="text-sm text-gray-600">by {{ $advert->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $advert->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <div class="flex gap-2 ml-4">
                            <form action="{{ route('admin.adverts.approve', $advert) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-green-600 hover:text-green-700 text-2xl" title="Approve">
                                    ✓
                                </button>
                            </form>
                            <a href="{{ route('admin.adverts.show', $advert) }}" class="text-[#2E6F40] hover:text-[#236030] text-2xl" title="Review">
                                👁️
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">No pending adverts</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold">Recent Transactions</h2>
                <a href="{{ route('admin.transactions.index') }}" class="text-[#2E6F40] hover:text-[#236030] font-medium">
                    View All →
                </a>
            </div>

            <div class="space-y-4">
                @forelse($recentTransactions as $transaction)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-semibold">₦{{ number_format($transaction->amount) }}</p>
                            <p class="text-sm text-gray-600">{{ $transaction->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $transaction->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="text-right">
                            @if($transaction->status === 'success')
                                <span class="badge badge-success">Success</span>
                            @elseif($transaction->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-danger">Failed</span>
                            @endif
                            <p class="text-xs text-gray-500 mt-1">{{ ucfirst($transaction->gateway) }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst($transaction->advert->plan ?? 'N/A') }} Plan</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">No transactions yet</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Users -->
        <div class="card">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold">Recent Users</h2>
                <a href="{{ route('admin.users.index') }}" class="text-[#2E6F40] hover:text-[#236030] font-medium">
                    View All →
                </a>
            </div>

            <div class="space-y-4">
                @forelse($recentUsers as $user)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-xl font-bold text-blue-600">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-semibold">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                <p class="text-xs text-gray-500">Joined {{ $user->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="badge {{ $user->role === 'admin' ? 'badge-info' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">{{ $user->adverts_count }} ads</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">No users yet</p>
                @endforelse
            </div>
        </div>

        <!-- System Stats -->
        <div class="card">
            <h2 class="text-xl font-bold mb-6">System Statistics</h2>

            <div class="space-y-4">
                <div class="flex justify-between items-center pb-3 border-b">
                    <span class="text-gray-600">Total Categories</span>
                    <span class="font-bold">{{ $stats['total_categories'] }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b">
                    <span class="text-gray-600">Total Blog Posts</span>
                    <span class="font-bold">{{ $stats['total_blogs'] }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b">
                    <span class="text-gray-600">Total Revenue</span>
                    <span class="font-bold">₦{{ number_format($stats['total_revenue']) }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b">
                    <span class="text-gray-600">Featured Adverts</span>
                    <span class="font-bold">{{ $stats['featured_adverts'] }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b">
                    <span class="text-gray-600">Premium Adverts</span>
                    <span class="font-bold">{{ $stats['premium_adverts'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Rejected Adverts</span>
                    <span class="font-bold text-red-600">{{ $stats['rejected_adverts'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Top Referrers -->
        <div class="card">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold">🏆 Top Referrers</h2>
            </div>

            <div class="space-y-4">
                @forelse($topReferrers as $referrer)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center flex-1">
                            <div class="flex-shrink-0 w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                <span class="text-xl">👤</span>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-medium text-gray-900">{{ $referrer->name }}</p>
                                <p class="text-sm text-gray-600">{{ $referrer->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-lg text-primary-600">{{ $referrer->successful_referrals }}</p>
                            <p class="text-xs text-gray-500">Referrals</p>
                            <p class="text-sm font-semibold text-green-600">₦{{ number_format($referrer->total_commissions ?? 0, 2) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">No referrals yet</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Referrals -->
        <div class="card">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold">🔗 Recent Referrals</h2>
            </div>

            <div class="space-y-4">
                @forelse($recentReferrals as $referral)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">
                                {{ $referral->referrer->name ?? 'N/A' }} → {{ $referral->referred->name ?? 'Pending' }}
                            </p>
                            <p class="text-sm text-gray-600">Code: <code class="bg-gray-200 px-2 py-1 rounded">{{ $referral->referral_code }}</code></p>
                            <p class="text-xs text-gray-500 mt-1">{{ $referral->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{
                                $referral->status === 'completed' ? 'bg-green-100 text-green-800' :
                                ($referral->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800')
                            }}">
                                {{ ucfirst($referral->status) }}
                            </span>
                            @if($referral->commission_earned > 0)
                                <p class="text-sm font-semibold text-green-600 mt-1">₦{{ number_format($referral->commission_earned, 2) }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">No referrals yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        <a href="{{ route('admin.adverts.index') }}?status=pending" class="card hover:shadow-lg transition text-center p-6">
            <span class="text-5xl mb-3 block">✅</span>
            <h3 class="font-bold text-lg">Review Adverts</h3>
            <p class="text-gray-600 text-sm">Approve or reject pending ads</p>
        </a>

        <a href="{{ route('admin.users.index') }}" class="card hover:shadow-lg transition text-center p-6">
            <span class="text-5xl mb-3 block">👥</span>
            <h3 class="font-bold text-lg">Manage Users</h3>
            <p class="text-gray-600 text-sm">View and manage all users</p>
        </a>

        <a href="{{ route('admin.categories.index') }}" class="card hover:shadow-lg transition text-center p-6">
            <span class="text-5xl mb-3 block">📂</span>
            <h3 class="font-bold text-lg">Categories</h3>
            <p class="text-gray-600 text-sm">Manage ad categories</p>
        </a>

        <a href="{{ route('admin.blogs.index') }}" class="card hover:shadow-lg transition text-center p-6">
            <span class="text-5xl mb-3 block">📰</span>
            <h3 class="font-bold text-lg">Blog Posts</h3>
            <p class="text-gray-600 text-sm">Create and manage blog posts</p>
        </a>
    </div>
</div>
@endsection
