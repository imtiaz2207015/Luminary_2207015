<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller {
    public function index() {
        $notifications = Auth::user()->notifications()
            ->latest()->paginate(20);
        Auth::user()->notifications()
            ->whereNull('read_at')->update(['read_at' => now()]);
        return view('notifications', compact('notifications'));
    }
}