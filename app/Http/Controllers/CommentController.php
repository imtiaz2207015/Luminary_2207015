<?php
namespace App\Http\Controllers;

use App\Models\Capsule;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller {
    public function store(Request $request, Capsule $capsule) {
        $request->validate(['body' => 'required|string|max:1000']);
        Comment::create([
            'user_id' => Auth::id(),
            'capsule_id' => $capsule->id,
            'body' => $request->body,
        ]);
        return back()->with('success', 'Comment added!');
    }

    public function destroy(Comment $comment) {
        abort_if($comment->user_id !== Auth::id(), 403);
        $comment->delete();
        return back()->with('success', 'Comment deleted.');
    }
}