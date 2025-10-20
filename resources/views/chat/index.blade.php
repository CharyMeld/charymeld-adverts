@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Messages</h1>

    @if($conversations->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($conversations as $conversation)
                <a href="{{ route('chat.show', $conversation->id) }}" class="p-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            @if($conversation->avatar)
                                <img src="{{ asset('storage/' . $conversation->avatar) }}" alt="{{ $conversation->name }}" class="w-14 h-14 rounded-full object-cover">
                            @else
                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-lg font-bold">
                                    {{ strtoupper(substr($conversation->name, 0, 1)) }}
                                </div>
                            @endif
                            @if($conversation->unread_count > 0)
                                <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center">
                                    {{ $conversation->unread_count }}
                                </span>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $conversation->name }}</h3>
                            @if($conversation->last_message)
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ Str::limit($conversation->last_message->message, 60) }}
                                </p>
                            @endif
                        </div>
                    </div>
                    @if($conversation->last_message)
                        <span class="text-xs text-gray-400 dark:text-gray-500">
                            {{ $conversation->last_message->created_at->diffForHumans() }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-12 text-center">
            <p class="text-gray-500 dark:text-gray-400 mb-4">No conversations yet</p>
            <p class="text-sm text-gray-400 dark:text-gray-500">Start chatting with people from their profiles</p>
        </div>
    @endif
</div>
@endsection
