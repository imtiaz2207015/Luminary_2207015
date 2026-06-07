<?php
namespace App\Http\Controllers;

use App\Models\Capsule;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminCapsuleController extends Controller {
    public function index() {
        $capsules = Capsule::with('user')->latest()->paginate(15);
        return view('admin.capsules', compact('capsules'));
    }

    public function reviewQueue() {
        $capsules = Capsule::where('status', 'pending_review')
            ->with('user')->latest()->paginate(15);
        return view('admin.review', compact('capsules'));
    }

    public function approve(Capsule $capsule) {
        $capsule->update(['status' => 'approved']);
        Notification::create([
            'user_id' => $capsule->user_id,
            'type' => 'capsule_approved',
            'data' => json_encode(['capsule_id' => $capsule->id, 'capsule_title' => $capsule->title]),
        ]);
        return back()->with('success', 'Capsule approved!');
    }

    public function reject(Request $request, Capsule $capsule) {
    $request->validate([
        'reason' => 'required|string|min:10|max:500',
    ]);

    $cleanReason = strip_tags($request->reason);

    $capsule->update([
        'status' => 'rejected',
        'reject_reason' => $cleanReason,
    ]);

    Notification::create([
        'user_id' => $capsule->user_id,
        'type' => 'capsule_rejected',
        'data' => json_encode([
            'capsule_id' => $capsule->id,
            'capsule_title' => $capsule->title,
            'reason' => $cleanReason,
        ]),
    ]);
    return back()->with('success', 'Capsule rejected.');
}

    public function destroy(Capsule $capsule) {
        $capsule->delete();
        return back()->with('success', 'Capsule deleted.');
    }
}