<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller {
    public function index() {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        // Mark unread as read
        Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('notifications', compact('notifications'));
    }

    public function markAsRead($id) {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->update(['read_at' => now()]);
        return back();
    }
}
