<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Services\AIAssistantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatbotController extends Controller
{
    protected $aiService;

    public function __construct(AIAssistantService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        $conversations = auth()->user()->hasMany(ChatConversation::class, 'user_id')
            ->with(['latestMessage', 'unreadMessages'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        $suggestions = $this->aiService->suggestQuestions();

        return view('chatbot.index', compact('conversations', 'suggestions'));
    }

    public function show(ChatConversation $conversation)
    {
        // Check ownership
        if ($conversation->user_id !== auth()->id()) {
            abort(403);
        }

        $messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark AI messages as read
        $conversation->unreadMessages()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        $suggestions = $this->aiService->suggestQuestions();

        return view('chatbot.chat', compact('conversation', 'messages', 'suggestions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'personality' => 'nullable|in:helpful,professional,friendly,casual',
        ]);

        $conversation = ChatConversation::create([
            'user_id' => auth()->id(),
            'title' => 'New Conversation',
            'ai_personality' => $request->personality ?? 'helpful',
            'last_message_at' => now(),
        ]);

        // Send greeting message
        $greeting = $this->aiService->getGreeting($conversation->ai_personality);

        ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'ai',
            'message' => $greeting,
        ]);

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
            'redirect' => route('chatbot.show', $conversation),
        ]);
    }

    public function sendMessage(Request $request, ChatConversation $conversation)
    {
        // Check ownership
        if ($conversation->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|max:5000',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max
        ]);

        // Handle file uploads
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('chat-attachments', 'public');
                $attachments[] = [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ];
            }
        }

        // Save user message
        $userMessage = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => auth()->id(),
            'sender_type' => 'user',
            'message' => $request->message,
            'attachments' => !empty($attachments) ? $attachments : null,
        ]);

        // Update conversation
        $conversation->update([
            'last_message_at' => now(),
            'title' => $conversation->title === 'New Conversation'
                ? \Str::limit($request->message, 50)
                : $conversation->title,
        ]);

        // Refresh conversation to get updated support_status
        $conversation->refresh();

        // If connected to support, don't send AI response
        if ($conversation->support_status === 'connected') {
            return response()->json([
                'success' => true,
                'user_message' => [
                    'id' => $userMessage->id,
                    'message' => $userMessage->message,
                    'created_at' => $userMessage->created_at->toISOString(),
                    'attachments' => $userMessage->attachments,
                ],
                'support_connected' => true,
                'message' => 'Message sent to support team',
            ]);
        }

        // Generate AI response (simulate thinking delay)
        sleep(1);

        $aiResponse = $this->aiService->generateResponse($conversation, $request->message);

        $aiMessage = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'ai',
            'message' => $aiResponse,
        ]);

        return response()->json([
            'success' => true,
            'user_message' => [
                'id' => $userMessage->id,
                'message' => $userMessage->message,
                'created_at' => $userMessage->created_at->toISOString(),
                'attachments' => $userMessage->attachments,
            ],
            'ai_message' => [
                'id' => $aiMessage->id,
                'message' => $aiMessage->message,
                'created_at' => $aiMessage->created_at->toISOString(),
            ],
            'support_status' => $conversation->support_status,
        ]);
    }

    public function getMessages(ChatConversation $conversation)
    {
        // Check ownership
        if ($conversation->user_id !== auth()->id()) {
            abort(403);
        }

        $messages = $conversation->messages()
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        return response()->json([
            'success' => true,
            'messages' => $messages,
            'support_status' => $conversation->support_status,
        ]);
    }

    public function updatePersonality(Request $request, ChatConversation $conversation)
    {
        // Check ownership
        if ($conversation->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'personality' => 'required|in:helpful,professional,friendly,casual',
        ]);

        $conversation->update([
            'ai_personality' => $request->personality,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'AI personality updated successfully!',
        ]);
    }

    public function destroy(ChatConversation $conversation)
    {
        // Check ownership
        if ($conversation->user_id !== auth()->id()) {
            abort(403);
        }

        // Delete attachments
        foreach ($conversation->messages as $message) {
            if ($message->attachments) {
                foreach ($message->attachments as $attachment) {
                    Storage::disk('public')->delete($attachment['path']);
                }
            }
        }

        $conversation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Conversation deleted successfully!',
        ]);
    }
}
