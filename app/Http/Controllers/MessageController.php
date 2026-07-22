<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // List all conversations for the logged-in user
    public function index()
    {
        $conversations = Auth::user()->conversations()
            ->with(['lastMessage', 'users' => function ($q) {
                $q->where('users.id', '!=', Auth::id());
            }])
            ->get()
            ->sortByDesc(fn ($c) => optional($c->lastMessage)->created_at ?? $c->created_at);

        return view('messages.index', compact('conversations'));
    }

    // Show a single conversation thread
    public function show(Conversation $conversation)
    {
        $this->authorizeParticipant($conversation);

        $conversation->load(['messages.user', 'users']);

        // Mark as read
        $conversation->participants()
            ->where('user_id', Auth::id())
            ->update(['last_read_at' => now()]);

        $otherUser = $conversation->users->where('id', '!=', Auth::id())->first();

        return view('messages.show', compact('conversation', 'otherUser'));
    }

    // Start or open a conversation with a friend
    public function startWith(User $friend)
    {
        $existing = Conversation::where('is_group', false)
            ->whereHas('users', fn ($q) => $q->where('users.id', Auth::id()))
            ->whereHas('users', fn ($q) => $q->where('users.id', $friend->id))
            ->first();

        if ($existing) {
            return redirect()->route('messages.show', $existing);
        }

        $conversation = Conversation::create(['is_group' => false]);
        $conversation->participants()->createMany([
            ['user_id' => Auth::id()],
            ['user_id' => $friend->id],
        ]);

        return redirect()->route('messages.show', $conversation);
    }

    // Send a message
    public function store(Request $request, Conversation $conversation)
    {
        $this->authorizeParticipant($conversation);

        $validated = $request->validate([
            'body' => 'required_without:attachment|nullable|string|max:2000',
            'attachment' => 'nullable|file|max:10240',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('message-attachments', 'public');
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'body' => $validated['body'] ?? null,
            'attachment_path' => $attachmentPath,
        ]);

        $conversation->touch();

        return back();
    }

    private function authorizeParticipant(Conversation $conversation): void
    {
        abort_unless(
            $conversation->users->contains(Auth::id()),
            403
        );
    }
}