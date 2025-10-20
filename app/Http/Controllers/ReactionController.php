<?php

namespace App\Http\Controllers;

use App\Models\Reaction;
use App\Models\Video;
use App\Models\GroupMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReactionController extends Controller
{
    /**
     * Add or update a reaction
     */
    public function store(Request $request)
    {
        $request->validate([
            'reactable_type' => 'required|in:video,group_message',
            'reactable_id' => 'required|integer',
            'type' => 'required|in:like,love,laugh,wow,sad,angry',
        ]);

        // Get the reactable model
        $reactableClass = $request->reactable_type === 'video'
            ? Video::class
            : GroupMessage::class;

        $reactable = $reactableClass::findOrFail($request->reactable_id);

        // Check if user already reacted
        $existingReaction = Reaction::where([
            'user_id' => Auth::id(),
            'reactable_id' => $request->reactable_id,
            'reactable_type' => $reactableClass,
        ])->first();

        if ($existingReaction) {
            // Update reaction type if it's different
            if ($existingReaction->type !== $request->type) {
                $existingReaction->update(['type' => $request->type]);
                $message = 'Reaction updated';
            } else {
                // Same reaction - remove it (toggle off)
                $existingReaction->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Reaction removed',
                    'reaction_counts' => $reactable->getReactionCounts(),
                ]);
            }
        } else {
            // Create new reaction
            Reaction::create([
                'user_id' => Auth::id(),
                'reactable_id' => $request->reactable_id,
                'reactable_type' => $reactableClass,
                'type' => $request->type,
            ]);
            $message = 'Reaction added';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'reaction_counts' => $reactable->getReactionCounts(),
        ]);
    }

    /**
     * Get reaction counts for a reactable
     */
    public function getCounts(Request $request)
    {
        $request->validate([
            'reactable_type' => 'required|in:video,group_message',
            'reactable_id' => 'required|integer',
        ]);

        $reactableClass = $request->reactable_type === 'video'
            ? Video::class
            : GroupMessage::class;

        $reactable = $reactableClass::findOrFail($request->reactable_id);

        return response()->json([
            'success' => true,
            'reaction_counts' => $reactable->getReactionCounts(),
            'user_reaction' => Auth::check()
                ? Reaction::where([
                    'user_id' => Auth::id(),
                    'reactable_id' => $request->reactable_id,
                    'reactable_type' => $reactableClass,
                ])->value('type')
                : null,
        ]);
    }
}
