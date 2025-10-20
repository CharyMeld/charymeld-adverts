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
                        <a href="{{ route('chat.index') }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                            ← Back
                        </a>
                        <a href="{{ route('profile.show', $otherUser->name) }}" class="flex items-center gap-2">
                            @if($otherUser->avatar)
                                <img src="{{ asset('storage/' . $otherUser->avatar) }}" alt="{{ $otherUser->name }}" class="w-12 h-12 rounded-full object-cover">
                            @else
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-lg font-bold">
                                    {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <h2 class="font-bold text-gray-900 dark:text-white">{{ $otherUser->name }}</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Active now</p>
                            </div>
                        </a>
                    </div>
                    <a href="{{ route('profile.show', $otherUser->name) }}" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400">
                        View Profile
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
                        <button type="submit" class="btn bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
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
// Load messages
async function loadMessages() {
    const response = await fetch('/chat/{{ $otherUser->id }}/messages');
    const data = await response.json();
    const container = document.getElementById('messages');

    container.innerHTML = data.data.map(msg => `
        <div class="flex ${msg.sender_id == {{ auth()->id() }} ? 'justify-end' : 'justify-start'}">
            <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg ${msg.sender_id == {{ auth()->id() }} ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white'}">
                ${msg.message}
                ${msg.attachment ? `<br><a href="/storage/${msg.attachment}" target="_blank" class="text-xs underline">View attachment</a>` : ''}
                <div class="text-xs mt-1 opacity-70">${new Date(msg.created_at).toLocaleTimeString()}</div>
            </div>
        </div>
    `).reverse().join('');

    container.scrollTop = container.scrollHeight;
}

// Send message
document.getElementById('messageForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const input = document.getElementById('messageInput');

    if (!input.value.trim()) return;

    await fetch('/chat/{{ $otherUser->id }}/messages', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message: input.value })
    });

    input.value = '';
    loadMessages();
});

// Mark as read
fetch('/chat/{{ $otherUser->id }}/read', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
});

// Real-time messages (when Reverb is running)
// Echo.private('user.{{ auth()->id() }}')
//     .listen('.message.sent', (e) => {
//         loadMessages();
//     });

// Initial load
loadMessages();
setInterval(loadMessages, 3000); // Poll every 3 seconds
</script>
@endpush
@endsection
