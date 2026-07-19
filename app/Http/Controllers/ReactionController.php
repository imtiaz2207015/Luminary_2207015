<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Capsule;
use App\Models\Reaction;
use Illuminate\Http\Request;


class ReactionController extends Controller {
    public function toggle(Request $request, Capsule $capsule) {
        // Authorization: User can only react to unlocked capsules they can view
        abort_if($capsule->is_locked, 403);
        
        // Check visibility permissions
        if ($capsule->visibility === 'only_me' && $capsule->user_id !== Auth::id()) {
            abort(403);
        }
        if ($capsule->visibility === 'friends') {
            $isFriend = Auth::user()->friendships()
                ->where('friend_id', $capsule->user_id)
                ->where('status', 'accepted')
                ->exists();
            abort_if(!$isFriend && $capsule->user_id !== Auth::id(), 403);
        }

        $request->validate(['type' => 'required|in:like,love,wow,sad,goals']);
        $existing = Reaction::where('user_id', Auth::id())
            ->where('capsule_id', $capsule->id)->first();
        if ($existing) {
            if ($existing->type === $request->type) {
                $existing->delete();
                $action = 'removed';
            } else {
                $existing->update(['type' => $request->type]);
                $action = 'updated';
            }
        } else {
    Reaction::create([
        'user_id'    => Auth::id(),
        'capsule_id' => $capsule->id,
        'type'       => $request->type,
    ]);
    $action = 'added';

    // Notify capsule owner (skip if reacting to own capsule)
    if ($capsule->user_id !== Auth::id()) {
        Notification::create([
            'user_id' => $capsule->user_id,
            'type'    => 'reaction',
            'data'    => json_encode([
                'actor_name'   => Auth::user()->name,
                'reaction_type'=> $request->type,
                'capsule_id'   => $capsule->id,
                'capsule_title'=> $capsule->title,
            ]),
        ]);
    }
}
       return response()->json([
    'action' => $action,
    'type' => $request->type,
    'counts' => [
        'like' => $capsule->reactionCount('like'),
        'love' => $capsule->reactionCount('love'),
        'wow' => $capsule->reactionCount('wow'),
        'sad' => $capsule->reactionCount('sad'),
        'goals' => $capsule->reactionCount('goals'),
    ],
]);
    }
}