<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class SupportChatController extends Controller
{
    public function index()
    {
        $pendingRequests = ChatConversation::where('support_status', 'requested')
            ->with(['user', 'latestMessage'])
            ->orderBy('support_requested_at', 'desc')
            ->get();

        $activeChats = ChatConversation::where('support_status', 'connected')
            ->with(['user', 'supportAgent', 'latestMessage'])
            ->orderBy('support_connected_at', 'desc')
            ->get();

        $resolvedChats = ChatConversation::where('support_status', 'resolved')
            ->with(['user', 'supportAgent'])
            ->orderBy('updated_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.support-chat.index', compact('pendingRequests', 'activeChats', 'resolvedChats'));
    }

    public function show(ChatConversation $conversation)
    {
        $conversation->load(['user', 'supportAgent', 'messages']);

        $messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.support-chat.show', compact('conversation', 'messages'));
    }

    public function connect(ChatConversation $conversation)
    {
        $conversation->update([
            'support_status' => 'connected',
            'support_user_id' => auth()->id(),
            'support_connected_at' => now(),
        ]);

        // Send a system message
        ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'support',
            'message' => '👋 Hi! I\'m ' . auth()->user()->name . ' from the CharyMeld support team. I\'m here to help you. How can I assist you today?',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully connected to conversation',
        ]);
    }

    public function sendMessage(Request $request, ChatConversation $conversation)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        // Check if admin is the assigned support agent or conversation is not yet assigned
        if ($conversation->support_user_id && $conversation->support_user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'This conversation is handled by another support agent.',
            ], 403);
        }

        // If not connected yet, connect automatically
        if ($conversation->support_status !== 'connected') {
            $conversation->update([
                'support_status' => 'connected',
                'support_user_id' => auth()->id(),
                'support_connected_at' => now(),
            ]);
        }

        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => auth()->id(),
            'sender_type' => 'support',
            'message' => $request->message,
        ]);

        $conversation->update([
            'last_message_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'message' => $message->message,
                'sender' => auth()->user()->name,
                'created_at' => $message->created_at->toISOString(),
            ],
        ]);
    }

    public function resolve(ChatConversation $conversation)
    {
        $conversation->update([
            'support_status' => 'resolved',
        ]);

        // Send closing message
        ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'support',
            'message' => '✅ This support ticket has been marked as resolved. If you need further assistance, feel free to start a new conversation. Thank you for using CharyMeld!',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Conversation marked as resolved',
        ]);
    }

    public function getMessages(ChatConversation $conversation)
    {
        $messages = $conversation->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_type' => $message->sender_type,
                    'sender_name' => $message->user ? $message->user->name : 'AI Assistant',
                    'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }
}
