<?php
namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use App\Models\Notification;
use App\Models\Capsule;
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

   return view('friends.index', compact('friends', 'pendingRequests'));
}

    public function search(Request $request) {
    $query = $request->q;
    $users = User::where('id', '!=', Auth::id())
        ->where(function($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('email', 'like', "%{$query}%");
        })
        ->take(10)->get();

    // Build a map of user_id => ['status' => ..., 'friendship_id' => ...]
    $friendshipMap = [];
    foreach ($users as $user) {
        $friendship = Friendship::where('user_id', Auth::id())
            ->where('friend_id', $user->id)
            ->first();
        if ($friendship) {
            $friendshipMap[$user->id] = [
                'status' => $friendship->status,
                'friendship_id' => $friendship->id,
            ];
        } else {
            // Check if they sent us a request
            $reverse = Friendship::where('user_id', $user->id)
                ->where('friend_id', Auth::id())
                ->first();
            if ($reverse) {
                $friendshipMap[$user->id] = [
                    'status' => 'received',
                    'friendship_id' => $reverse->id,
                ];
            }
        }
    }

    return view('friends.index', compact('users', 'query', 'friendshipMap'));
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
            'data' => ['from_user_id' => Auth::id(), 'from_user_name' => Auth::user()->name],
        ]);
    }
    return back()->with('success', 'Friend request sent!');
}

    public function cancelRequest(User $user) {
    Friendship::where('user_id', Auth::id())
        ->where('friend_id', $user->id)
        ->where('status', 'pending')
        ->delete();
    return back()->with('success', 'Friend request cancelled.');
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
  public function showCapsules($friendId)
{
    $friend = User::findOrFail($friendId);

    // Verify an accepted friendship exists in either direction
    $isFriend = Friendship::where('status', 'accepted')
        ->where(function ($q) use ($friendId) {
            $q->where(function ($q2) use ($friendId) {
                $q2->where('user_id', Auth::id())->where('friend_id', $friendId);
            })->orWhere(function ($q2) use ($friendId) {
                $q2->where('user_id', $friendId)->where('friend_id', Auth::id());
            });
        })
        ->exists();

    abort_unless($isFriend, 403);

    $capsules = Capsule::where('user_id', $friendId)
        ->where('visibility', 'friends')
        ->where('unlock_date', '<=', now())   // see fix #3 below
        ->with('reactions')
        ->latest()
        ->paginate(5);

    return view('friends.profile', compact('friend', 'capsules'));
}
}

