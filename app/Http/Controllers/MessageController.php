<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get unique conversations
        $conversations = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->groupBy(function ($message) use ($user) {
                return $message->sender_id === $user->id
                    ? $message->receiver_id
                    : $message->sender_id;
            })
            ->map(function ($messages) {
                return $messages->first();
            });

        return view('messages.index', compact('conversations'));
    }

    public function show(User $user)
    {
        $currentUser = auth()->user();

        // Get messages between current user and selected user
        $messages = Message::between($currentUser->id, $user->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('messages.show', compact('user', 'messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        // Broadcast message via Pusher (implement later)
        // event(new MessageSent($message));

        return back()->with('success', 'Message sent!');
    }

    public function unreadCount()
    {
        $count = Message::where('receiver_id', auth()->id())
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }
}
