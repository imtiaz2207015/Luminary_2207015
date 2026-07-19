<?php
namespace App\Http\Controllers;

use App\Models\Capsule;
use App\Models\Notification;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller {
 public function store(Request $request, Capsule $capsule) {
    // Block comments on private/locked capsules
    abort_if($capsule->visibility === 'only_me' && $capsule->user_id !== Auth::id(), 403);
    abort_if($capsule->is_locked, 403);

    $request->validate(['body' => 'required|string|max:1000']);
   Comment::create([
    'user_id'    => Auth::id(),
    'capsule_id' => $capsule->id,
    'body'       => $request->body,
]);

// Notify capsule owner (skip if commenting on own capsule)
if ($capsule->user_id !== Auth::id()) {
    Notification::create([
        'user_id' => $capsule->user_id,
        'type'    => 'comment',
        'data'    => json_encode([
            'actor_name'   => Auth::user()->name,
            'capsule_id'   => $capsule->id,
            'capsule_title'=> $capsule->title,
        ]),
    ]);
}
    $comment = Comment::latest()->first();
return response()->json([
    'id' => $comment->id,
    'body' => $comment->body,
    'user_name' => Auth::user()->name,
    'avatar_url' => Auth::user()->avatar_url,
    'created_at' => $comment->created_at->diffForHumans(),
    'count' => $capsule->comments()->count(),
]);
}

    public function destroy(Comment $comment) {
        abort_if($comment->user_id !== Auth::id(), 403);
        $comment->delete();
       return response()->json(['deleted' => true]);
    }
}