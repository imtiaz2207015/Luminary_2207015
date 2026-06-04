<?php
namespace App\Http\Controllers;

use App\Models\Capsule;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller {
    public function index() {
        $user = Auth::user();
        $totalCapsules = $user->capsules()->count();
        $lockedCapsules = $user->capsules()->where('is_locked', true)->count();
        $unlockedCapsules = $user->capsules()->where('is_locked', false)->count();
        $pendingReview = $user->capsules()->where('status', 'pending_review')->count();
        $upcomingCapsules = $user->capsules()
            ->where('is_locked', true)
            ->where('unlock_date', '>=', now())
            ->orderBy('unlock_date')
            ->take(5)
            ->get();
        $recentCapsules = $user->capsules()
            ->with('files')
            ->latest()
            ->take(6)
            ->get();
        return view('dashboard', compact(
            'totalCapsules','lockedCapsules','unlockedCapsules',
            'pendingReview','upcomingCapsules','recentCapsules'
        ));
    }
}