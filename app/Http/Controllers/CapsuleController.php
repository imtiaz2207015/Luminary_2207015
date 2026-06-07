<?php
namespace App\Http\Controllers;

use App\Models\Capsule;
use App\Models\CapsuleFile;
use App\Models\CapsuleMember;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;

class CapsuleController extends Controller {
    public function index() {
        $capsules = Auth::user()->capsules()->with('files')->latest()->paginate(12);
        return view('capsules.index', compact('capsules'));
    }

    public function create() {
        return view('capsules.create');
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'unlock_date' => ['required', 'date', 'date_format:Y-m-d', 'after:today'],
            'visibility' => 'required|in:only_me,friends,public',
            'lock_type' => 'required|in:locked,draft',
        ]);

        $status = $request->lock_type === 'locked' ? 'locked' : 'draft';
        $isLocked = $request->lock_type === 'locked';

        $capsule = Capsule::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'unlock_date' => $request->unlock_date . ' ' . now()->format('H:i:s'),
            'is_locked' => $isLocked,
            'visibility' => $request->visibility,
            'status' => $status,
            'is_group' => $request->has('is_group'),
            'group_name' => $request->group_name,
            'sealed_at' => $isLocked ? now() : null,
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('capsule_files', 'public');
                CapsuleFile::create([
                    'capsule_id' => $capsule->id,
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType(),
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        if ($request->has('invite_emails') && $request->invite_emails) {
            $emails = explode(',', $request->invite_emails);
            foreach ($emails as $email) {
                $invitedUser = \App\Models\User::where('email', trim($email))->first();
                if ($invitedUser) {
                    CapsuleMember::create([
                        'capsule_id' => $capsule->id,
                        'user_id' => $invitedUser->id,
                        'role' => 'contributor',
                        'status' => 'pending',
                    ]);
                    Notification::create([
                        'user_id' => $invitedUser->id,
                        'type' => 'group_invite',
                        'data' => json_encode(['capsule_id' => $capsule->id, 'capsule_title' => $capsule->title]),
                    ]);
                }
            }
        }

        return redirect()->route('capsules.index')->with('success', 'Capsule created successfully!');
    }

    public function show(Capsule $capsule) {
        abort_if($capsule->user_id !== Auth::id(), 403);
        if ($capsule->isUnlockable()) {
            $capsule->update(['is_locked' => false, 'status' => 'unlocked']);
        }
        $capsule->load('files', 'comments.user', 'reactions', 'members.user');
        return view('capsules.show', compact('capsule'));
    }

    public function edit(Capsule $capsule) {
        abort_if($capsule->user_id !== Auth::id(), 403);
        abort_if($capsule->is_locked, 403);
        return view('capsules.edit', compact('capsule'));
    }

    public function update(Request $request, Capsule $capsule) {
        abort_if($capsule->user_id !== Auth::id(), 403);
        abort_if($capsule->is_locked, 403);
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'unlock_date' => 'required|date|after:today',
            'visibility' => 'required|in:only_me,friends,public',
        ]);
        $capsule->update($request->only('title','content','unlock_date','visibility'));
        return redirect()->route('capsules.show', $capsule)->with('success', 'Capsule updated!');
    }

    public function destroy(Capsule $capsule) {
        abort_if($capsule->user_id !== Auth::id(), 403);
        $capsule->delete();
        return redirect()->route('capsules.index')->with('success', 'Capsule deleted.');
    }

    public function submitToNewsfeed(Capsule $capsule) {
        abort_if($capsule->user_id !== Auth::id(), 403);
        abort_if($capsule->visibility !== 'public', 403);
        abort_if($capsule->status !== 'unlocked', 403);
        $capsule->update(['status' => 'pending_review']);
        return back()->with('success', 'Submitted for admin review!');
    }
}