@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.support-chat.index') }}" class="text-primary-600 hover:text-primary-700 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Support Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Customer Info Sidebar -->
        <div class="lg:col-span-1">
            <div class="card sticky top-6">
                <div class="text-center mb-4">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg text-gray-900">{{ $conversation->user->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $conversation->user->email }}</p>
                </div>

                <div class="space-y-3 border-t border-gray-200 pt-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Status</p>
                        @if($conversation->support_status === 'requested')
                            <span class="badge badge-warning mt-1">Waiting</span>
                        @elseif($conversation->support_status === 'connected')
                            <span class="badge badge-success mt-1">Connected</span>
                        @elseif($conversation->support_status === 'resolved')
                            <span class="badge badge-info mt-1">Resolved</span>
                        @endif
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Requested</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $conversation->support_requested_at?->format('M d, Y h:i A') }}</p>
                    </div>

                    @if($conversation->supportAgent)
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Assigned to</p>
                            <p class="text-sm text-gray-900 mt-1">{{ $conversation->supportAgent->name }}</p>
                        </div>
                    @endif

                    @if($conversation->support_connected_at)
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Connected</p>
                            <p class="text-sm text-gray-900 mt-1">{{ $conversation->support_connected_at->format('M d, Y h:i A') }}</p>
                        </div>
                    @endif
                </div>

                @if($conversation->support_status !== 'resolved')
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <button onclick="resolveConversation()" class="btn btn-success btn-sm w-full">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Mark as Resolved
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Chat Area -->
        <div class="lg:col-span-3">
            <div class="card h-[700px] flex flex-col">
                <!-- Chat Header -->
                <div class="bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 text-white p-5 rounded-t-2xl flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-11 h-11 bg-white rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Support Chat with {{ $conversation->user->name }}</h3>
                            <p class="text-xs text-primary-100">Conversation ID: #{{ $conversation->id }}</p>
                        </div>
                    </div>
                </div>

                <!-- Messages Area -->
                <div id="chat-messages" class="flex-1 overflow-y-auto p-5 space-y-4 bg-gradient-to-br from-gray-50 via-blue-50/30 to-gray-50">
                    @foreach($messages as $message)
                        @if($message->sender_type === 'user')
                            <!-- User Message -->
                            <div class="flex items-start space-x-2.5 justify-end">
                                <div class="bg-gradient-to-br from-gray-600 to-gray-700 text-white rounded-2xl rounded-tr-sm px-5 py-3 shadow-md max-w-[80%]">
                                    <p class="leading-relaxed">{{ $message->message }}</p>
                                    <p class="text-xs text-gray-300 mt-1">{{ $message->created_at->format('h:i A') }}</p>
                                </div>
                                <div class="w-9 h-9 bg-gradient-to-br from-gray-300 to-gray-400 rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            </div>
                        @elseif($message->sender_type === 'ai')
                            <!-- AI Message -->
                            <div class="flex items-start space-x-2.5">
                                <div class="w-9 h-9 bg-gradient-to-br from-purple-500 to-purple-700 rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <div class="bg-white rounded-2xl rounded-tl-sm px-5 py-3 shadow-md max-w-[80%] border border-gray-100">
                                    <p class="text-xs font-semibold text-purple-600 mb-1">AI Assistant</p>
                                    <p class="text-gray-800 leading-relaxed">{{ $message->message }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $message->created_at->format('h:i A') }}</p>
                                </div>
                            </div>
                        @elseif($message->sender_type === 'support')
                            <!-- Support Message -->
                            <div class="flex items-start space-x-2.5">
                                <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <div class="bg-gradient-to-br from-primary-500 to-primary-600 text-white rounded-2xl rounded-tl-sm px-5 py-3 shadow-md max-w-[80%]">
                                    <p class="text-xs font-semibold text-primary-100 mb-1">{{ $message->user->name ?? 'Support Team' }}</p>
                                    <p class="leading-relaxed">{{ $message->message }}</p>
                                    <p class="text-xs text-primary-100 mt-1">{{ $message->created_at->format('h:i A') }}</p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Message Input -->
                @if($conversation->support_status !== 'resolved')
                    <div class="p-4 bg-white border-t border-gray-200 rounded-b-2xl">
                        <form id="support-message-form" class="flex space-x-2">
                            <input type="text" id="support-message-input" placeholder="Type your message..."
                                   class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:outline-none transition-all duration-200 text-sm"
                                   autocomplete="off">
                            <button type="submit" class="bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 text-white px-6 py-3 rounded-xl hover:shadow-lg transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="p-4 bg-gray-100 border-t border-gray-200 rounded-b-2xl text-center">
                        <p class="text-gray-600">✅ This conversation has been resolved</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    const conversationId = {{ $conversation->id }};
    const messagesContainer = document.getElementById('chat-messages');

    // Scroll to bottom on load
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    // Handle message sending
    document.getElementById('support-message-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('support-message-input');
        const message = input.value.trim();

        if (!message) return;

        // Add message to chat immediately
        addSupportMessage(message, '{{ auth()->user()->name }}', 'Just now');
        input.value = '';

        // Send to server
        fetch(`/admin/support-chat/${conversationId}/messages`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert('Error sending message');
            }
        });
    });

    function addSupportMessage(message, sender, time) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'flex items-start space-x-2.5 animate-fade-in';
        messageDiv.innerHTML = `
            <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div class="bg-gradient-to-br from-primary-500 to-primary-600 text-white rounded-2xl rounded-tl-sm px-5 py-3 shadow-md max-w-[80%]">
                <p class="text-xs font-semibold text-primary-100 mb-1">${escapeHtml(sender)}</p>
                <p class="leading-relaxed">${escapeHtml(message)}</p>
                <p class="text-xs text-primary-100 mt-1">${time}</p>
            </div>
        `;
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function resolveConversation() {
        if (!confirm('Are you sure you want to mark this conversation as resolved?')) return;

        fetch(`/admin/support-chat/${conversationId}/resolve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route('admin.support-chat.index') }}';
            }
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Track last message ID to prevent duplicates
    let lastMessageId = {{ $messages->max('id') ?? 0 }};

    function addUserMessage(message, time) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'flex items-start space-x-2.5 justify-end animate-fade-in';
        messageDiv.innerHTML = `
            <div class="bg-gradient-to-br from-gray-600 to-gray-700 text-white rounded-2xl rounded-tr-sm px-5 py-3 shadow-md max-w-[80%]">
                <p class="leading-relaxed">${escapeHtml(message)}</p>
                <p class="text-xs text-gray-300 mt-1">${time}</p>
            </div>
            <div class="w-9 h-9 bg-gradient-to-br from-gray-300 to-gray-400 rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        `;
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Auto-refresh messages every 3 seconds
    setInterval(() => {
        fetch(`/admin/support-chat/${conversationId}/messages`)
            .then(response => response.json())
            .then(data => {
                console.log('Admin polling response:', data);
                console.log('Last message ID:', lastMessageId);

                if (data.success && data.messages) {
                    // Get new messages that we haven't displayed yet
                    const newMessages = data.messages.filter(msg => msg.id > lastMessageId);
                    console.log('New messages to display:', newMessages);

                    newMessages.forEach(msg => {
                        // Only add user messages (admin messages already shown)
                        if (msg.sender_type === 'user') {
                            console.log('Adding user message:', msg);
                            const time = new Date(msg.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                            addUserMessage(msg.message, time);
                        } else {
                            console.log('Skipping non-user message:', msg.sender_type);
                        }
                        lastMessageId = Math.max(lastMessageId, msg.id);
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching messages:', error);
            });
    }, 3000);
</script>
@endsection
