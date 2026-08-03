@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden" style="height: calc(100vh - 200px);">
        <div class="flex h-full">
            <!-- Chat Area -->
            <div class="flex-1 flex flex-col">
                <!-- Chat Header -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('groups.show', $group->slug) }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                            ← Back
                        </a>
                        <h2 class="font-bold text-gray-900 dark:text-white">{{ $group->name }}</h2>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $group->members->count() }} members</span>
                    </div>
                    <a href="{{ route('groups.members', $group->slug) }}" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400">
                        View Members
                    </a>
                </div>

                <!-- Messages -->
                <div id="messages" class="flex-1 overflow-y-auto p-4 space-y-4">
                    <!-- Messages loaded via JavaScript -->
                </div>

                <!-- Message Input -->
                <form id="messageForm" class="p-4 border-t border-gray-200 dark:border-gray-700">
                    @csrf
                    <div class="flex gap-2">
                        <input type="text"
                               id="messageInput"
                               placeholder="Type a message..."
                               class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <button type="submit" id="sendButton" class="btn bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            Send
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Handle reactions for group messages
function handleMessageReaction(messageId, type) {
    fetch('{{ route("reactions.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            reactable_type: 'group_message',
            reactable_id: messageId,
            type: type
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload messages to show updated reactions
            loadMessages();
        }
    })
    .catch(error => console.error('Error:', error));
}

// Load messages
async function loadMessages() {
    try {
        const response = await fetch('/groups/{{ $group->slug }}/chat/messages');
        const data = await response.json();
        const container = document.getElementById('messages');

        if (!data.data || data.data.length === 0) {
            container.innerHTML = '<div class="text-center text-gray-500 dark:text-gray-400 py-8">No messages yet. Start the conversation!</div>';
            return;
        }

        container.innerHTML = data.data.map(msg => {
            const reactionCounts = {};
            const userReaction = msg.reactions?.find(r => r.user_id == {{ auth()->id() }});

            // Count reactions by type
            msg.reactions?.forEach(r => {
                reactionCounts[r.type] = (reactionCounts[r.type] || 0) + 1;
            });

            const reactionTypes = {like: '👍', love: '❤️', laugh: '😂', wow: '😮', sad: '😢', angry: '😠'};
            const reactionsHtml = Object.entries(reactionTypes).map(([type, emoji]) => {
                const count = reactionCounts[type] || 0;
                const isActive = userReaction?.type === type;
                return count > 0 || isActive ? `
                    <button onclick="handleMessageReaction(${msg.id}, '${type}')"
                            class="px-2 py-1 text-xs rounded ${isActive ? 'bg-blue-100 dark:bg-blue-900' : 'bg-gray-100 dark:bg-gray-600'} hover:bg-gray-200 dark:hover:bg-gray-500">
                        ${emoji} ${count > 0 ? count : ''}
                    </button>
                ` : '';
            }).join('');

            return `
            <div class="flex ${msg.user_id == {{ auth()->id() }} ? 'justify-end' : 'justify-start'}">
                <div class="max-w-xs lg:max-w-md">
                    ${msg.user_id != {{ auth()->id() }} ? `
                        <div class="flex items-center gap-2 mb-1">
                            <img src="${msg.user.avatar || '{{ asset('images/default-avatar.png') }}'}" alt="${msg.user.name}" class="w-6 h-6 rounded-full">
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">${msg.user.name}</span>
                        </div>
                    ` : ''}
                    <div class="px-4 py-2 rounded-lg ${msg.user_id == {{ auth()->id() }} ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white'}">
                        ${msg.message}
                        ${msg.attachment ? `<br><a href="/storage/${msg.attachment}" target="_blank" class="text-xs underline">View attachment</a>` : ''}
                        <div class="text-xs mt-1 opacity-70">${new Date(msg.created_at).toLocaleTimeString()}</div>
                    </div>
                    ${reactionsHtml ? `<div class="flex gap-1 mt-1 flex-wrap">${reactionsHtml}</div>` : ''}
                </div>
            </div>
        `;
        }).reverse().join('');

        container.scrollTop = container.scrollHeight;
    } catch (error) {
        console.error('Error loading messages:', error);
        document.getElementById('messages').innerHTML = '<div class="text-center text-red-500 py-8">Error loading messages. Please refresh.</div>';
    }
}

// Send message
document.getElementById('messageForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    console.log('Form submitted!'); // DEBUG
    const input = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');

    if (!input.value.trim()) {
        console.log('Empty message, not sending');
        return;
    }

    console.log('Sending message:', input.value); // DEBUG
    sendButton.disabled = true;
    sendButton.textContent = 'Sending...';

    try {
        const response = await fetch('/groups/{{ $group->slug }}/chat/messages', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: input.value })
        });

        console.log('Response status:', response.status); // DEBUG
        const result = await response.json();
        console.log('Message sent:', result);

        input.value = '';
        sendButton.disabled = false;
        sendButton.textContent = 'Send';
        await loadMessages();
    } catch (error) {
        console.error('Error sending message:', error);
        alert('Failed to send message. Please try again.');
        sendButton.disabled = false;
        sendButton.textContent = 'Send';
    }
});

// Real-time messages (when Reverb is running)
// Echo.private('group.{{ $group->id }}')
//     .listen('.message.sent', (e) => {
//         loadMessages();
//     });

// Initial load
loadMessages();
setInterval(loadMessages, 3000); // Poll every 3 seconds
</script>
@endpush
@endsection
