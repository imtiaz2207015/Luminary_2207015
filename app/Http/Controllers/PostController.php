<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Reaction;
use App\Models\Capsule;
use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $friendIds = Friendship::where(function($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhere('friend_id', $userId);
            })
            ->where('status', 'accepted')
            ->get()
            ->map(fn($f) => $f->user_id === $userId ? $f->friend_id : $f->user_id)
            ->push($userId);

        $posts = Post::with(['user', 'capsule', 'reactions', 'comments.user'])
            ->whereIn('user_id', $friendIds)
            ->where(function($q) use ($userId, $friendIds) {
                $q->where('visibility', 'public')
                  ->orWhere(function($q2) use ($userId, $friendIds) {
                      $q2->where('visibility', 'friends')
                         ->whereIn('user_id', $friendIds);
                  })
                  ->orWhere(function($q3) use ($userId) {
                      $q3->where('visibility', 'only_me')
                         ->where('user_id', $userId);
                  });
            })
            ->latest()
            ->paginate(10);

        $myCapsules = Capsule::where('user_id', $userId)
            ->where('is_locked', false)
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('newsfeed.index', compact('posts', 'myCapsules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'caption'    => ['nullable', 'string', 'max:1000'],
            'capsule_id' => ['nullable', 'exists:capsules,id'],
            'image'      => ['nullable', 'image', 'max:4096'],
            'visibility' => ['required', 'in:public,friends,only_me'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('post-images', 'public');
        }

        $post = Post::create([
            'user_id'    => Auth::id(),
            'capsule_id' => $request->capsule_id ?: null,
            'caption'    => $request->caption,
            'image'      => $imagePath,
            'visibility' => $request->visibility,
        ]);

        // Sync capsule visibility to match post visibility
        if ($post->capsule_id) {
            Capsule::where('id', $post->capsule_id)
                   ->where('user_id', Auth::id())
                   ->update(['visibility' => $request->visibility]);
        }

        return back()->with('success', 'Posted!');
    }

    public function toggleReaction(Request $request, Post $post)
    {
        $request->validate(['type' => ['required', 'in:inspired,goals,proud']]);

        $existing = Reaction::where('user_id', Auth::id())
            ->where('post_id', $post->id)
            ->where('type', $request->type)
            ->first();

        if ($existing) {
            $existing->delete();
        } else {
            Reaction::create([
                'user_id' => Auth::id(),
                'post_id' => $post->id,
                'type'    => $request->type,
            ]);
        }

        return back();
    }

    public function storeComment(Request $request, Post $post)
    {
        $request->validate(['body' => ['required', 'string', 'max:500']]);

        Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'body'    => $request->body,
        ]);

        return back();
    }
    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        if ($post->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return back()->with('success', 'Post deleted.');
    }

    public function updateVisibility(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'visibility' => ['required', 'in:public,friends,only_me'],
        ]);

        $post->update(['visibility' => $request->visibility]);

        return back()->with('success', 'Privacy updated.');
    }
}