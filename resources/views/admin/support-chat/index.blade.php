@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Live Support Chat</h1>
        <p class="mt-1 text-gray-600">Manage customer support conversations</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card bg-gradient-to-br from-yellow-50 to-orange-50 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Requests</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $pendingRequests->count() }}</p>
                </div>
            </div>
        </div>

        <div class="card bg-gradient-to-br from-green-50 to-emerald-50 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Chats</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeChats->count() }}</p>
                </div>
            </div>
        </div>

        <div class="card bg-gradient-to-br from-blue-50 to-indigo-50 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Resolved Today</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $resolvedChats->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <a href="#pending" onclick="showTab('pending')" id="tab-pending" class="tab-link active border-primary-500 text-primary-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Pending Requests ({{ $pendingRequests->count() }})
                </a>
                <a href="#active" onclick="showTab('active')" id="tab-active" class="tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Active Chats ({{ $activeChats->count() }})
                </a>
                <a href="#resolved" onclick="showTab('resolved')" id="tab-resolved" class="tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Resolved
                </a>
            </nav>
        </div>
    </div>

    <!-- Pending Requests -->
    <div id="content-pending" class="tab-content">
        <div class="card">
            @forelse($pendingRequests as $conversation)
                <div class="flex items-center justify-between p-4 border-b border-gray-200 hover:bg-gray-50 transition">
                    <div class="flex items-center space-x-4 flex-1">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-full flex items-center justify-center animate-pulse">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900">
                                {{ $conversation->user ? $conversation->user->name : 'Guest User' }}
                                @if(!$conversation->user)
                                    <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">Guest</span>
                                @endif
                            </h3>
                            <p class="text-sm text-gray-600">
                                {{ $conversation->user ? $conversation->user->email : ($conversation->guest_email ?? 'No email provided') }}
                            </p>
                            @if($conversation->latestMessage)
                                <p class="text-sm text-gray-500 mt-1 truncate">{{ Str::limit($conversation->latestMessage->message, 60) }}</p>
                            @endif
                            <p class="text-xs text-yellow-600 mt-1">⏱️ Waiting {{ $conversation->support_requested_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.support-chat.show', $conversation) }}" class="btn btn-primary btn-sm">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        Respond
                    </a>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="mt-4 text-gray-600">No pending support requests</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Active Chats -->
    <div id="content-active" class="tab-content hidden">
        <div class="card">
            @forelse($activeChats as $conversation)
                <div class="flex items-center justify-between p-4 border-b border-gray-200 hover:bg-gray-50 transition">
                    <div class="flex items-center space-x-4 flex-1">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900">
                                {{ $conversation->user ? $conversation->user->name : 'Guest User' }}
                                @if(!$conversation->user)
                                    <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">Guest</span>
                                @endif
                            </h3>
                            <p class="text-sm text-gray-600">Handled by: <span class="font-medium text-green-600">{{ $conversation->supportAgent->name }}</span></p>
                            @if($conversation->latestMessage)
                                <p class="text-sm text-gray-500 mt-1 truncate">{{ Str::limit($conversation->latestMessage->message, 60) }}</p>
                            @endif
                            <p class="text-xs text-green-600 mt-1">💬 Active since {{ $conversation->support_connected_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.support-chat.show', $conversation) }}" class="btn btn-success btn-sm">
                        View Chat
                    </a>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <p class="mt-4 text-gray-600">No active support chats</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Resolved -->
    <div id="content-resolved" class="tab-content hidden">
        <div class="card">
            @forelse($resolvedChats as $conversation)
                <div class="flex items-center justify-between p-4 border-b border-gray-200 hover:bg-gray-50 transition">
                    <div class="flex items-center space-x-4 flex-1">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900">
                                {{ $conversation->user ? $conversation->user->name : 'Guest User' }}
                                @if(!$conversation->user)
                                    <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">Guest</span>
                                @endif
                            </h3>
                            <p class="text-sm text-gray-600">Resolved by: <span class="font-medium text-blue-600">{{ $conversation->supportAgent->name ?? 'N/A' }}</span></p>
                            <p class="text-xs text-gray-500 mt-1">✅ Resolved {{ $conversation->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.support-chat.show', $conversation) }}" class="btn btn-secondary btn-sm">
                        View History
                    </a>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="mt-4 text-gray-600">No resolved conversations</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Remove active class from all tabs
    document.querySelectorAll('.tab-link').forEach(link => {
        link.classList.remove('active', 'border-primary-500', 'text-primary-600');
        link.classList.add('border-transparent', 'text-gray-500');
    });

    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');

    // Add active class to selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.add('active', 'border-primary-500', 'text-primary-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
}
</script>
@endsection
