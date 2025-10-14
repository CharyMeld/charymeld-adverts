@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">AI Assistant</h1>
        <p class="mt-1 text-gray-600">Get help with CharyMeld Adverts</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Conversations List -->
        <div class="lg:col-span-1">
            <div class="card">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Conversations</h2>
                    <button onclick="startNewChat()" class="btn btn-primary btn-sm">
                        + New Chat
                    </button>
                </div>

                <div class="space-y-2" id="conversations-list">
                    @forelse($conversations as $conversation)
                        <a href="{{ route('chatbot.show', $conversation) }}"
                           class="block p-4 rounded-xl hover:bg-gray-50 transition-colors border-2 border-transparent hover:border-primary-500">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-gray-900 truncate">
                                        {{ $conversation->title }}
                                    </h3>
                                    @if($conversation->latestMessage)
                                        <p class="text-sm text-gray-600 truncate mt-1">
                                            {{ Str::limit($conversation->latestMessage->message, 50) }}
                                        </p>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $conversation->last_message_at?->diffForHumans() }}
                                    </p>
                                </div>
                                @if($conversation->unreadMessages->count() > 0)
                                    <span class="badge badge-primary ml-2">
                                        {{ $conversation->unreadMessages->count() }}
                                    </span>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p class="mt-4 text-gray-600">No conversations yet</p>
                            <button onclick="startNewChat()" class="mt-4 btn btn-primary btn-sm">
                                Start Chatting
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Welcome / Getting Started -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center mx-auto mb-6 shadow-glow">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Welcome to CharyMeld AI Assistant!</h2>
                    <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                        I'm here to help you with anything related to CharyMeld Adverts. Ask me questions about posting ads, pricing, safety tips, and more!
                    </p>

                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Choose an AI Personality:</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-3xl mx-auto">
                            <button onclick="startNewChat('helpful')" class="feature-card group">
                                <div class="text-4xl mb-2">😊</div>
                                <h4 class="font-semibold text-gray-900 group-hover:text-primary-600">Helpful</h4>
                                <p class="text-xs text-gray-600 mt-1">Friendly & informative</p>
                            </button>
                            <button onclick="startNewChat('professional')" class="feature-card group">
                                <div class="text-4xl mb-2">👔</div>
                                <h4 class="font-semibold text-gray-900 group-hover:text-primary-600">Professional</h4>
                                <p class="text-xs text-gray-600 mt-1">Formal & detailed</p>
                            </button>
                            <button onclick="startNewChat('friendly')" class="feature-card group">
                                <div class="text-4xl mb-2">🤗</div>
                                <h4 class="font-semibold text-gray-900 group-hover:text-primary-600">Friendly</h4>
                                <p class="text-xs text-gray-600 mt-1">Warm & casual</p>
                            </button>
                            <button onclick="startNewChat('casual')" class="feature-card group">
                                <div class="text-4xl mb-2">😎</div>
                                <h4 class="font-semibold text-gray-900 group-hover:text-primary-600">Casual</h4>
                                <p class="text-xs text-gray-600 mt-1">Relaxed & conversational</p>
                            </button>
                        </div>
                    </div>

                    <div class="max-w-2xl mx-auto">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Popular Questions:</h3>
                        <div class="space-y-2">
                            @foreach($suggestions as $suggestion)
                                <button onclick="startNewChatWithMessage('{{ addslashes($suggestion) }}')"
                                        class="w-full text-left px-6 py-3 bg-gray-50 hover:bg-primary-50 rounded-xl border-2 border-transparent hover:border-primary-500 transition-all">
                                    <span class="text-gray-700">{{ $suggestion }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function startNewChat(personality = 'helpful') {
    fetch('{{ route('chatbot.store') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ personality })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        }
    });
}

function startNewChatWithMessage(message) {
    // Create conversation then redirect with message as query param
    fetch('{{ route('chatbot.store') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ personality: 'helpful' })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect + '?message=' + encodeURIComponent(message);
        }
    });
}
</script>
@endsection
