@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Messages</h1>
        <p class="mt-1 text-gray-600">Communicate with buyers and sellers</p>
    </div>

    @if($conversations->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="divide-y divide-gray-200">
                @foreach($conversations as $message)
                    @php
                        $otherUser = $message->sender_id === auth()->id() ? $message->receiver : $message->sender;
                    @endphp
                    <a href="{{ route('advertiser.messages.show', $otherUser->id) }}"
                       class="block hover:bg-gray-50 transition duration-150">
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center flex-1 min-w-0">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                                            <span class="text-primary-600 font-semibold text-lg">
                                                {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $otherUser->name }}
                                            </p>
                                            <p class="text-xs text-gray-500 ml-2">
                                                {{ $message->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <p class="text-sm text-gray-600 truncate mt-1">
                                            {{ Str::limit($message->message, 60) }}
                                        </p>
                                        @if($message->advert)
                                            <p class="text-xs text-gray-500 mt-1">
                                                Re: {{ $message->advert->title }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                @if(!$message->is_read && $message->receiver_id === auth()->id())
                                    <div class="ml-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            New
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No messages yet</h3>
            <p class="mt-2 text-gray-500">Start a conversation by contacting sellers on their ads.</p>
        </div>
    @endif
</div>
@endsection
