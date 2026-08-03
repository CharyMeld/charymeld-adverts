@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-5xl mx-auto px-4">
        <!-- Header -->
        <div class="bg-white rounded-t-lg shadow-md p-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('chatbot.index') }}" class="text-gray-600 hover:text-gray-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $conversation->title }}</h1>
                        <p class="text-sm text-gray-500">
                            @if($conversation->support_status === 'connected')
                                <span class="text-green-600">🟢 Connected to support agent</span>
                            @elseif($conversation->support_status === 'requested')
                                <span class="text-yellow-600">🟡 Waiting for support agent</span>
                            @else
                                <span class="text-blue-600">🤖 AI Assistant</span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Personality Selector -->
                @if($conversation->support_status === 'ai_only')
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">Personality:</label>
                    <select id="personality"
                            class="text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            onchange="updatePersonality(this.value)">
                        <option value="helpful" {{ $conversation->ai_personality === 'helpful' ? 'selected' : '' }}>😊 Helpful</option>
                        <option value="professional" {{ $conversation->ai_personality === 'professional' ? 'selected' : '' }}>💼 Professional</option>
                        <option value="friendly" {{ $conversation->ai_personality === 'friendly' ? 'selected' : '' }}>🤗 Friendly</option>
                        <option value="casual" {{ $conversation->ai_personality === 'casual' ? 'selected' : '' }}>😎 Casual</option>
                    </select>
                </div>
                @endif
            </div>
        </div>

        <!-- Messages Container -->
        <div class="bg-white shadow-md" style="height: 600px; overflow-y: auto;" id="messagesContainer">
            <div id="messagesList" class="p-6 space-y-4">
                @foreach($messages as $message)
                    @if($message->sender_type === 'user')
                        <!-- User Message -->
                        <div class="flex justify-end">
                            <div class="max-w-md">
                                <div class="bg-blue-600 text-white rounded-lg px-4 py-3 shadow">
                                    <p class="whitespace-pre-wrap">{{ $message->message }}</p>
                                    @if($message->attachments)
                                        <div class="mt-2 space-y-1">
                                            @foreach($message->attachments as $attachment)
                                                <a href="{{ asset('storage/' . $attachment['path']) }}"
                                                   target="_blank"
                                                   class="text-sm underline text-blue-100 hover:text-white flex items-center gap-1">
                                                    📎 {{ $attachment['name'] }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-1 text-right">
                                    {{ $message->created_at->format('g:i A') }}
                                </p>
                            </div>
                        </div>
                    @else
                        <!-- AI/Support Message -->
                        <div class="flex justify-start">
                            <div class="max-w-md">
                                <div class="flex items-start gap-2">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full {{ $message->sender_type === 'support' ? 'bg-green-500' : 'bg-gradient-to-br from-purple-500 to-blue-500' }} flex items-center justify-center text-white text-sm font-bold">
                                        {{ $message->sender_type === 'support' ? '👤' : '🤖' }}
                                    </div>
                                    <div>
                                        <div class="bg-gray-100 rounded-lg px-4 py-3 shadow">
                                            <div class="prose prose-sm max-w-none">
                                                {!! nl2br(e($message->message)) !!}
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $message->sender_type === 'support' ? 'Support Agent' : 'AI Assistant' }} ·
                                            {{ $message->created_at->format('g:i A') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Suggestions (shown when no messages or after AI response) -->
        @if($messages->count() > 0)
        <div class="bg-gray-50 border-t border-gray-200 px-6 py-3">
            <div class="flex gap-2 flex-wrap" id="suggestionsContainer">
                @foreach($suggestions as $suggestion)
                    <button onclick="sendSuggestion('{{ $suggestion }}')"
                            class="text-sm px-3 py-1 bg-white border border-gray-300 rounded-full hover:bg-gray-100 transition">
                        {{ $suggestion }}
                    </button>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Message Input -->
        <div class="bg-white rounded-b-lg shadow-md p-4 border-t border-gray-200">
            <form id="messageForm" onsubmit="sendMessage(event)" enctype="multipart/form-data">
                @csrf
                <div class="flex gap-2 items-end">
                    <div class="flex-1">
                        <textarea id="messageInput"
                                  name="message"
                                  rows="2"
                                  placeholder="Type your message..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                  required></textarea>
                        <input type="file"
                               id="attachmentInput"
                               name="attachments[]"
                               multiple
                               class="hidden"
                               onchange="showAttachments()">
                        <div id="attachmentPreview" class="mt-2 text-sm text-gray-600"></div>
                    </div>
                    <div class="flex gap-2">
                        <button type="button"
                                onclick="document.getElementById('attachmentInput').click()"
                                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg transition"
                                title="Attach file">
                            📎
                        </button>
                        <button type="submit"
                                id="sendButton"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                            Send
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Delete Conversation -->
        <div class="mt-4 text-center">
            <button onclick="deleteConversation()"
                    class="text-sm text-red-600 hover:text-red-800">
                🗑️ Delete Conversation
            </button>
        </div>
    </div>
</div>

<script>
const conversationId = {{ $conversation->id }};
let isLoading = false;

// Scroll to bottom on load
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
});

function scrollToBottom() {
    const container = document.getElementById('messagesContainer');
    container.scrollTop = container.scrollHeight;
}

function showAttachments() {
    const input = document.getElementById('attachmentInput');
    const preview = document.getElementById('attachmentPreview');

    if (input.files.length > 0) {
        const fileNames = Array.from(input.files).map(f => f.name).join(', ');
        preview.textContent = '📎 Attached: ' + fileNames;
    } else {
        preview.textContent = '';
    }
}

async function sendMessage(event) {
    event.preventDefault();

    if (isLoading) return;

    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const attachmentInput = document.getElementById('attachmentInput');
    const message = messageInput.value.trim();

    if (!message && attachmentInput.files.length === 0) return;

    isLoading = true;
    sendButton.disabled = true;
    sendButton.textContent = 'Sending...';

    // Create FormData for file upload
    const formData = new FormData();
    formData.append('message', message);
    formData.append('_token', '{{ csrf_token() }}');

    // Add attachments
    for (let i = 0; i < attachmentInput.files.length; i++) {
        formData.append('attachments[]', attachmentInput.files[i]);
    }

    try {
        const response = await fetch(`/assistant/conversations/${conversationId}/messages`, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            // Add user message
            addMessageToUI(data.user_message, 'user');

            // Add AI/support response if available
            if (data.ai_message) {
                setTimeout(() => {
                    addMessageToUI(data.ai_message, 'ai');
                }, 500);
            }

            // Clear inputs
            messageInput.value = '';
            attachmentInput.value = '';
            document.getElementById('attachmentPreview').textContent = '';

            // Update support status if changed
            if (data.support_status === 'requested' || data.support_status === 'connected') {
                location.reload(); // Reload to show updated status
            }
        } else {
            // Show server error message
            alert(data.message || 'Failed to send message. Please try again.');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to send message: ' + (error.message || 'Please check your connection and try again.'));
    } finally {
        isLoading = false;
        sendButton.disabled = false;
        sendButton.textContent = 'Send';
        scrollToBottom();
    }
}

function addMessageToUI(message, type) {
    const messagesList = document.getElementById('messagesList');
    const time = new Date().toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });

    const messageHtml = type === 'user'
        ? `
        <div class="flex justify-end">
            <div class="max-w-md">
                <div class="bg-blue-600 text-white rounded-lg px-4 py-3 shadow">
                    <p class="whitespace-pre-wrap">${escapeHtml(message.message)}</p>
                </div>
                <p class="text-xs text-gray-500 mt-1 text-right">${time}</p>
            </div>
        </div>
        `
        : `
        <div class="flex justify-start">
            <div class="max-w-md">
                <div class="flex items-start gap-2">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white text-sm font-bold">
                        🤖
                    </div>
                    <div>
                        <div class="bg-gray-100 rounded-lg px-4 py-3 shadow">
                            <div class="prose prose-sm max-w-none">
                                ${message.message.replace(/\n/g, '<br>')}
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">AI Assistant · ${time}</p>
                    </div>
                </div>
            </div>
        </div>
        `;

    messagesList.insertAdjacentHTML('beforeend', messageHtml);
    scrollToBottom();
}

function sendSuggestion(suggestion) {
    document.getElementById('messageInput').value = suggestion;
    document.getElementById('messageInput').focus();
}

async function updatePersonality(personality) {
    try {
        const response = await fetch(`/assistant/conversations/${conversationId}/personality`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ personality })
        });

        const data = await response.json();
        if (data.success) {
            // Show success message
            const messagesList = document.getElementById('messagesList');
            messagesList.insertAdjacentHTML('beforeend', `
                <div class="text-center py-2">
                    <span class="text-sm text-gray-500">✓ AI personality updated to ${personality}</span>
                </div>
            `);
            scrollToBottom();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function deleteConversation() {
    if (!confirm('Are you sure you want to delete this conversation? This action cannot be undone.')) {
        return;
    }

    try {
        const response = await fetch(`/assistant/conversations/${conversationId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const data = await response.json();
        if (data.success) {
            window.location.href = '{{ route('chatbot.index') }}';
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to delete conversation. Please try again.');
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Auto-refresh for support messages (when connected to support)
@if($conversation->support_status === 'connected' || $conversation->support_status === 'requested')
setInterval(async function() {
    try {
        const response = await fetch(`/assistant/conversations/${conversationId}/messages`);
        const data = await response.json();

        if (data.success && data.messages.length > {{ $messages->count() }}) {
            location.reload(); // Reload to show new messages
        }
    } catch (error) {
        console.error('Error checking for new messages:', error);
    }
}, 5000); // Check every 5 seconds
@endif
</script>
@endsection
