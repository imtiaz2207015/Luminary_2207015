<x-app-layout>
<x-slot name="title">Messages</x-slot>

<div class="topbar">
    <div>
        <div class="topbar-title">Messages</div>
        <p style="color:#8b95a8; font-size:0.85rem; margin:0;">Your conversations</p>
    </div>
</div>

<div class="glass-card p-3">
    @forelse($conversations as $conversation)
        @php $other = $conversation->users->first(); @endphp
        <a href="{{ route('messages.show', $conversation) }}"
           class="d-flex align-items-center gap-3 p-3 mb-2 rounded-3"
           style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.07); text-decoration:none;">
            <img src="{{ $other->avatar_url }}" style="width:48px; height:48px; border-radius:50%;" alt="">
            <div style="flex:1;">
                <div style="color:#f5f0e8; font-weight:600; font-size:0.9rem;">{{ $other->name }}</div>
                <div style="color:#8b95a8; font-size:0.8rem;">
                    {{ Str::limit(optional($conversation->lastMessage)->body ?? 'No messages yet', 50) }}
                </div>
            </div>
            @if($conversation->lastMessage)
                <small style="color:#8b95a8;">{{ $conversation->lastMessage->created_at->diffForHumans() }}</small>
            @endif
        </a>
    @empty
        <p style="color:#8b95a8; font-size:0.85rem; margin:0;">No conversations yet. Message a friend to start one.</p>
    @endforelse
</div>

</x-app-layout>