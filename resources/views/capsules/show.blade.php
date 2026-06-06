@extends('layouts.app')
@section('title', $capsule->title)
@section('content')

<div class="topbar">
    <div>
        <a href="{{ route('capsules.index') }}" style="color:#8b95a8; text-decoration:none; font-size:0.85rem;">
            <i class="bi bi-arrow-left me-1"></i> Back to Capsules
        </a>
    </div>
    <div class="d-flex gap-2">
        @if($capsule->status !== 'locked')
            <a href="{{ route('capsules.edit', $capsule) }}" class="btn btn-ghost btn-sm">Edit</a>
        @endif
        <form method="POST" action="{{ route('capsules.destroy', $capsule) }}" onsubmit="return confirm('Delete permanently?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger-soft btn-sm">Delete</button>
        </form>
    </div>
</div>

@if($capsule->is_locked)
{{-- LOCKED STATE --}}
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="glass-card text-center p-5" style="border-color: rgba(201,168,76,0.3);">
            <div style="font-size:5rem; margin-bottom:1rem;">🔒</div>
            <h2 style="font-family:'Playfair Display',serif; color:#f5f0e8;">{{ $capsule->title }}</h2>
            <p style="color:#8b95a8; margin:1rem 0;">This capsule is sealed until</p>
            <h4 style="color:#c9a84c;">{{ $capsule->unlock_date->format('d F Y') }}</h4>
            <div class="countdown-badge mt-3 d-inline-block" style="font-size:1rem; padding:8px 20px;">
                <span class="countdown-timer" data-unlock="{{ $capsule->unlock_date->format('Y-m-d\TH:i:s') }}">
                    {{ $capsule->countdown() }}
                </span>
            </div>
            <hr style="border-color:rgba(255,255,255,0.08); margin:1.5rem 0;">
            <div class="d-flex justify-content-center gap-3" style="font-size:0.82rem; color:#8b95a8;">
                <span><i class="bi bi-eye me-1"></i>{{ ucfirst($capsule->visibility) }}</span>
                <span><i class="bi bi-calendar me-1"></i>Sealed {{ $capsule->sealed_at?->format('d M Y') ?? $capsule->created_at->format('d M Y') }}</span>
                @if($capsule->is_group) <span><i class="bi bi-people me-1"></i>Group Capsule</span> @endif
            </div>
        </div>
    </div>
</div>

@else
{{-- UNLOCKED STATE --}}
<div class="row g-4">
    <div class="col-lg-8">
        <div class="glass-card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="d-flex gap-2 mb-2">
                        <span class="badge-gold">{{ ucfirst($capsule->visibility) }}</span>
                        <span class="badge-unlocked">🔓 Unlocked</span>
                        @if($capsule->status === 'approved') <span class="badge-teal">✅ Live</span> @endif
                        @if($capsule->status === 'pending_review') <span class="badge-pending">⏳ In Review</span> @endif
                    </div>
                    <h2 style="font-family:'Playfair Display',serif; color:#f5f0e8; margin:0;">{{ $capsule->title }}</h2>
                </div>
            </div>
            <div style="color:#8b95a8; font-size:0.82rem; margin-bottom:1.5rem;">
                <i class="bi bi-calendar me-1"></i> Written {{ $capsule->created_at->format('d M Y') }}
                &nbsp;·&nbsp; Unlocked {{ $capsule->unlock_date->format('d M Y') }}
            </div>
            <div class="p-4 rounded-3" style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.06); white-space:pre-wrap; line-height:1.9; color:#e8e0d5; font-size:1.02rem;">{{ $capsule->content }}</div>

            @if($capsule->files->count() > 0)
            <div class="mt-4">
                <h6 style="color:#8b95a8; font-size:0.82rem; text-transform:uppercase; letter-spacing:1px; margin-bottom:0.75rem;">Attachments</h6>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($capsule->files as $file)
                    <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="btn btn-ghost btn-sm">
                        <i class="bi bi-paperclip me-1"></i> {{ $file->original_name ?? basename($file->file_path) }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            @if($capsule->status === 'unlocked' && $capsule->visibility === 'public')
            <div class="mt-4 p-3 rounded-3" style="background:rgba(78,205,196,0.07); border:1px solid rgba(78,205,196,0.2);">
                <p style="color:#4ecdc4; font-size:0.85rem; margin-bottom:0.5rem;">🌍 Want to inspire others? Share this to the public newsfeed.</p>
                <form method="POST" action="{{ route('capsules.submit', $capsule) }}">
                    @csrf
                    <button class="btn btn-sm" style="background:rgba(78,205,196,0.15); color:#4ecdc4; border:1px solid rgba(78,205,196,0.3); border-radius:8px;">
                        Submit for Review
                    </button>
                </form>
            </div>
            @endif

            @if($capsule->status === 'rejected')
            <div class="mt-4 p-3 rounded-3" style="background:rgba(220,53,69,0.08); border:1px solid rgba(220,53,69,0.2);">
                <p style="color:#ff6b6b; font-size:0.85rem; margin:0;">❌ Rejected: {{ $capsule->reject_reason }}</p>
            </div>
            @endif
        </div>

        {{-- Comments --}}
        <div class="glass-card p-4">
            <h5 style="font-family:'Playfair Display',serif; color:#f5f0e8; margin-bottom:1.5rem;">
                Comments <span style="color:#8b95a8; font-size:0.85rem; font-family:'DM Sans',sans-serif;">({{ $capsule->comments->count() }})</span>
            </h5>
            <form method="POST" action="{{ route('comments.store', $capsule) }}" class="mb-4">
                @csrf
                <div class="d-flex gap-2">
                    <img src="{{ Auth::user()->avatar_url }}" style="width:36px; height:36px; border-radius:50%; border:2px solid rgba(201,168,76,0.3);" alt="">
                    <div style="flex:1;">
                        <textarea name="body" class="form-control" rows="2" placeholder="Add a comment..."></textarea>
                        <button type="submit" class="btn btn-gold btn-sm mt-2">Comment</button>
                    </div>
                </div>
            </form>
            @forelse($capsule->comments as $comment)
            <div class="d-flex gap-3 mb-3">
                <img src="{{ $comment->user->avatar_url }}" style="width:36px; height:36px; border-radius:50%; flex-shrink:0;" alt="">
                <div style="flex:1;">
                    <div class="d-flex justify-content-between align-items-start">
                        <span style="color:#f5f0e8; font-weight:600; font-size:0.85rem;">{{ $comment->user->name }}</span>
                        <span style="color:#8b95a8; font-size:0.75rem;">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p style="color:#c8c0b8; font-size:0.88rem; margin:4px 0 0;">{{ $comment->body }}</p>
                    @if($comment->user_id === Auth::id())
                    <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn p-0 mt-1" style="color:#8b95a8; font-size:0.75rem;">Delete</button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <p style="color:#8b95a8; font-size:0.85rem; text-align:center; padding:1rem 0;">No comments yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Reactions sidebar --}}
    <div class="col-lg-4">
        <div class="glass-card p-4">
            <h6 style="color:#8b95a8; font-size:0.82rem; text-transform:uppercase; letter-spacing:1px; margin-bottom:1rem;">Reactions</h6>
            @foreach(['inspired' => '❤️ Inspired', 'goals' => '🔥 Goals', 'proud' => '🙌 Proud'] as $type => $label)
            <form method="POST" action="{{ route('reactions.toggle', $capsule) }}" class="mb-2">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                <button type="submit" class="w-100 d-flex align-items-center justify-content-between p-3 rounded-3 {{ $capsule->userReaction(Auth::id())?->type === $type ? 'border-gold' : '' }}"
                    style="background:rgba(255,255,255,0.04); border:1px solid {{ $capsule->userReaction(Auth::id())?->type === $type ? 'rgba(201,168,76,0.5)' : 'rgba(255,255,255,0.08)' }}; color:#f5f0e8; transition:all 0.2s;">
                    <span>{{ $label }}</span>
                    <span class="badge-gold">{{ $capsule->reactionCount($type) }}</span>
                </button>
            </form>
            @endforeach
        </div>

        @if($capsule->is_group && $capsule->members->count() > 0)
        <div class="glass-card p-4 mt-3">
            <h6 style="color:#8b95a8; font-size:0.82rem; text-transform:uppercase; letter-spacing:1px; margin-bottom:1rem;">Group Members</h6>
            @foreach($capsule->members as $member)
            <div class="d-flex align-items-center gap-2 mb-2">
                <img src="{{ $member->user->avatar_url }}" style="width:32px; height:32px; border-radius:50%;" alt="">
                <div>
                    <div style="color:#f5f0e8; font-size:0.85rem;">{{ $member->user->name }}</div>
                    <div style="color:#8b95a8; font-size:0.75rem;">{{ ucfirst($member->role) }}</div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
function updateCountdowns() {
    document.querySelectorAll('.countdown-timer').forEach(function(el) {
        var diff = new Date(el.dataset.unlock) - new Date();
        if (diff <= 0) { el.textContent = 'Ready to unlock!'; return; }
        var d=Math.floor(diff/(864e5)), h=Math.floor((diff%864e5)/36e5);
        var m=Math.floor((diff%36e5)/6e4), s=Math.floor((diff%6e4)/1000);
        el.textContent = d+'d '+h+'h '+m+'m '+s+'s';
    });
}
updateCountdowns(); setInterval(updateCountdowns, 1000);
</script>
@endsection