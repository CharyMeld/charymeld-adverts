<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DirectMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DirectChatController extends Controller
{
    /**
     * List all conversations
     */
    public function index()
    {
        $userId = auth()->id();

        // Get unique conversations with last message
        $conversations = DirectMessage::where(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
            })
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->groupBy(function ($message) use ($userId) {
                // Group by the other user's ID
                return $message->sender_id == $userId
                    ? $message->receiver_id
                    : $message->sender_id;
            })
            ->map(function ($messages, $otherUserId) use ($userId) {
                $lastMessage = $messages->first();
                $otherUser = $lastMessage->sender_id == $userId
                    ? $lastMessage->receiver
                    : $lastMessage->sender;

                // Count unread messages
                $unreadCount = $messages->where('receiver_id', $userId)
                    ->where('is_read', false)
                    ->count();

                return (object) [
                    'id' => $otherUser->id,
                    'name' => $otherUser->name,
                    'avatar' => $otherUser->avatar,
                    'last_message' => $lastMessage,
                    'unread_count' => $unreadCount,
                ];
            })
            ->values(); // Reset keys

        return view('chat.index', compact('conversations'));
    }

    /**
     * Show chat with specific user
     */
    public function show($userId)
    {
        $otherUser = User::findOrFail($userId);

        if ($otherUser->id === auth()->id()) {
            return redirect()->route('chat.index')
                ->with('error', 'You cannot chat with yourself.');
        }

        return view('chat.show', compact('otherUser'));
    }

    /**
     * Send a message
     */
    public function sendMessage(Request $request, $userId)
    {
        $otherUser = User::findOrFail($userId);

        if ($otherUser->id === auth()->id()) {
            return response()->json(['error' => 'Cannot send message to yourself'], 400);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $message = DirectMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $userId,
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        $message->load('sender');

        // Broadcast message via WebSocket (will implement event later)
        // broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Get messages with a specific user
     */
    public function getMessages($userId)
    {
        $otherUser = User::findOrFail($userId);

        $messages = DirectMessage::where(function($query) use ($userId) {
                $query->where('sender_id', auth()->id())
                      ->where('receiver_id', $userId);
            })
            ->orWhere(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->where('receiver_id', auth()->id());
            })
            ->with(['sender', 'receiver'])
            ->latest()
            ->paginate(50);

        return response()->json($messages);
    }

    /**
     * Mark messages as read
     */
    public function markAsRead($userId)
    {
        DirectMessage::where('sender_id', $userId)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Upload attachment
     */
    public function uploadAttachment(Request $request, $userId)
    {
        $otherUser = User::findOrFail($userId);

        if ($otherUser->id === auth()->id()) {
            return response()->json(['error' => 'Cannot send to yourself'], 400);
        }

        $validated = $request->validate([
            'attachment' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,zip|max:10240', // 10MB max
            'message' => 'nullable|string|max:1000',
        ]);

        $path = $request->file('attachment')->store('chat/attachments', 'public');

        $message = DirectMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $userId,
            'message' => $validated['message'] ?? 'Sent an attachment',
            'attachment' => $path,
            'is_read' => false,
        ]);

        $message->load('sender');

        // Broadcast message via WebSocket
        // broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Get unread message count
     */
    public function unreadCount()
    {
        $count = DirectMessage::where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
