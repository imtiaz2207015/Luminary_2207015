<?php
namespace App\Http\Controllers;

use App\Models\Capsule;

class NewsfeedController extends Controller {
    public function index() {
        $capsules = Capsule::where('status', 'approved')
            ->where('visibility', 'public')
            ->with('user', 'reactions', 'comments')
            ->latest()
            ->paginate(10);
        return view('newsfeed', compact('capsules'));
    }
}