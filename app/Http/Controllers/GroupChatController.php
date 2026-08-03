<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GroupChatController extends Controller
{
    /**
     * Show group chat interface
     */
    public function index($slug)
    {
        $group = Group::where('slug', $slug)->firstOrFail();

        // Check membership
        if (!$group->isMember(auth()->id())) {
            abort(403, 'You must be a member to access group chat.');
        }

        return view('groups.chat', compact('group'));
    }

    /**
     * Send a message to group
     */
    public function sendMessage(Request $request, $slug)
    {
        $group = Group::where('slug', $slug)->firstOrFail();

        // Check membership
        if (!$group->isMember(auth()->id())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $message = GroupMessage::create([
            'group_id' => $group->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        $message->load('user');

        // Broadcast message via WebSocket (will implement event later)
        // broadcast(new GroupMessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Get messages for a group
     */
    public function getMessages($slug)
    {
        $group = Group::where('slug', $slug)->firstOrFail();

        // Check membership
        if (!$group->isMember(auth()->id())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $group->messages()
            ->with(['user', 'reactions.user'])
            ->latest()
            ->paginate(50);

        return response()->json($messages);
    }

    /**
     * Upload attachment
     */
    public function uploadAttachment(Request $request, $slug)
    {
        $group = Group::where('slug', $slug)->firstOrFail();

        // Check membership
        if (!$group->isMember(auth()->id())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'attachment' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,zip|max:10240', // 10MB max
            'message' => 'nullable|string|max:1000',
        ]);

        $path = $request->file('attachment')->store('chat/attachments', 'public');

        $message = GroupMessage::create([
            'group_id' => $group->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'] ?? 'Sent an attachment',
            'attachment' => $path,
        ]);

        $message->load('user');

        // Broadcast message via WebSocket
        // broadcast(new GroupMessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}
