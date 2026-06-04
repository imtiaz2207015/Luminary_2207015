<?php
namespace App\Http\Controllers;

use App\Models\Capsule;
use App\Models\User;
use App\Models\Notification;

class AdminController extends Controller {
    public function dashboard() {
        $totalUsers = User::where('role', 'user')->count();
        $totalCapsules = Capsule::count();
        $pendingReviews = Capsule::where('status', 'pending_review')->count();
        $approvedCapsules = Capsule::where('status', 'approved')->count();
        $pendingCapsules = Capsule::where('status', 'pending_review')
            ->with('user')->latest()->get();
        return view('admin.dashboard', compact(
            'totalUsers','totalCapsules','pendingReviews','approvedCapsules','pendingCapsules'
        ));
    }

    public function showLogin() {
        return view('admin.login');
    }

    public function login(\Illuminate\Http\Request $request) {
        $request->validate(['email' => 'required|email', 'password' => 'required']);
        if (\Illuminate\Support\Facades\Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            if (\Illuminate\Support\Facades\Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            \Illuminate\Support\Facades\Auth::logout();
        }
        return back()->with('error', 'Invalid credentials or not an admin.');
    }

    public function logout() {
        \Illuminate\Support\Facades\Auth::logout();
        return redirect()->route('admin.login');
    }
}