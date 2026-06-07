<?php
namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller {
   public function index() {
    $friends = Friendship::where('user_id', Auth::id())
        ->where('status', 'accepted')
        ->with('friend')
        ->get();

    $pendingRequests = Friendship::where('friend_id', Auth::id())
        ->where('status', 'pending')
        ->with('user')
        ->get();

    $friendIds = $friends->pluck('friend_id');

    $friendCapsules = Capsule::whereIn('user_id', $friendIds)
        ->where('visibility', 'friends')
        ->where('is_locked', false)
        ->with('user', 'reactions')
        ->latest()
        ->get();

    return view('friends', compact('friends', 'pendingRequests', 'friendCapsules'));
}

    public function search(Request $request) {
        $query = $request->q;
        $users = User::where('id', '!=', Auth::id())
            ->where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->take(10)->get();
        return view('friends', compact('users', 'query'));
    }

   public function sendRequest(User $user) {
    $exists = Friendship::where(function($q) use ($user) {
        $q->where('user_id', Auth::id())->where('friend_id', $user->id);
    })->orWhere(function($q) use ($user) {
        $q->where('user_id', $user->id)->where('friend_id', Auth::id());
    })->exists();

    if (!$exists) {
        Friendship::create([
            'user_id' => Auth::id(),
            'friend_id' => $user->id,
            'status' => 'pending',
        ]);
        Notification::create([
            'user_id' => $user->id,
            'type' => 'friend_request',
            'data' => json_encode(['from_user_id' => Auth::id(), 'from_user_name' => Auth::user()->name]),
        ]);
    }
    return back()->with('success', 'Friend request sent!');
}

    public function acceptRequest(Friendship $friendship) {
        abort_if($friendship->friend_id !== Auth::id(), 403);
        $friendship->update(['status' => 'accepted']);
        Friendship::firstOrCreate([
            'user_id' => $friendship->friend_id,
            'friend_id' => $friendship->user_id,
        ], ['status' => 'accepted']);
        return back()->with('success', 'Friend request accepted!');
    }

    public function declineRequest(Friendship $friendship) {
        abort_if($friendship->friend_id !== Auth::id(), 403);
        $friendship->update(['status' => 'declined']);
        return back()->with('success', 'Request declined.');
    }

    public function unfriend(User $user) {
        Friendship::where('user_id', Auth::id())->where('friend_id', $user->id)->delete();
        Friendship::where('user_id', $user->id)->where('friend_id', Auth::id())->delete();
        return back()->with('success', 'Unfriended.');
    }
}