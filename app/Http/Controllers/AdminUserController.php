<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller {
    public function index() {
        $users = User::withCount('capsules')->latest()->paginate(15);
        return view('admin.users', compact('users'));
    }

    public function destroy(User $user) {
        abort_if($user->isAdmin(), 403);
        $user->delete();
        return back()->with('success', 'User deleted.');
    }

    public function toggleRole(User $user) {
        $newRole = $user->role === 'admin' ? 'user' : 'admin';
        $user->update(['role' => $newRole]);
        return back()->with('success', 'Role updated.');
    }
}