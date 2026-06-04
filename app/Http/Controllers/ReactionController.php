<?php
namespace App\Http\Controllers;

use App\Models\Capsule;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReactionController extends Controller {
    public function toggle(Request $request, Capsule $capsule) {
        $request->validate(['type' => 'required|in:inspired,goals,proud']);
        $existing = Reaction::where('user_id', Auth::id())
            ->where('capsule_id', $capsule->id)->first();
        if ($existing) {
            if ($existing->type === $request->type) {
                $existing->delete();
                $action = 'removed';
            } else {
                $existing->update(['type' => $request->type]);
                $action = 'updated';
            }
        } else {
            Reaction::create([
                'user_id' => Auth::id(),
                'capsule_id' => $capsule->id,
                'type' => $request->type,
            ]);
            $action = 'added';
        }
        return back()->with('success', 'Reaction ' . $action . '!');
    }
}