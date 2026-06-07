@extends('layouts.app')
@section('title', 'Newsfeed')
@section('content')

<div class="topbar">
    <div>
        <div class="topbar-title">Newsfeed</div>
        <p style="color:#8b95a8; font-size:0.85rem; margin:0;">Inspiring journeys from people around the world</p>
    </div>
</div>

@if($capsules->isEmpty())
    <div class="glass-card p-5 text-center">
        <div style="font-size:4rem;">🌍</div>
        <h4 style="color:#f5f0e8; margin-top:1rem; font-family:'Playfair Display',serif;">Nothing here yet</h4>
        <p style="color:#8b95a8;">Be the first to share your unlocked capsule with the world.</p>
    </div>
@else
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @foreach($capsules as $capsule)
            <div class="glass-card p-4 mb-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="{{ $capsule->user->avatar_url }}" style="width:44px; height:44px; border-radius:50%; border:2px solid rgba(201,168,76,0.3);" alt="">
                    <div>
                        <div style="color:#f5f0e8; font-weight:600;">{{ $capsule->user->name }}</div>
                        <div style="color:#8b95a8; font-size:0.78rem;">
                            Written {{ $capsule->created_at->format('d M Y') }} · Unlocked {{ $capsule->unlock_date->format('d M Y') }}
                        </div>
                    </div>
                </div>

                <h4 style="font-family:'Playfair Display',serif; color:#f5f0e8; margin-bottom:1rem;">{{ $capsule->title }}</h4>

                <div style="color:#c8c0b8; line-height:1.8; margin-bottom:1.5rem; white-space:pre-wrap;">{{ Str::limit($capsule->content, 400) }}</div>

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex gap-2">
                        @foreach(['inspired' => '❤️', 'goals' => '🔥', 'proud' => '🙌'] as $type => $emoji)
                        <form method="POST" action="{{ route('reactions.toggle', $capsule) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="type" value="{{ $type }}">
                            <button type="submit" class="btn btn-sm d-flex align-items-center gap-1"
                                style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); color:#f5f0e8; border-radius:20px; padding:4px 12px;">
                                {{ $emoji }} <span style="font-size:0.78rem;">{{ $capsule->reactionCount($type) }}</span>
                            </button>
                        </form>
                        @endforeach
                    </div>
                    <span style="color:#8b95a8; font-size:0.78rem;">
                        <i class="bi bi-chat me-1"></i> {{ $capsule->comments->count() }} comments
                    </span>
                </div>

                @if($capsule->comments->count() > 0)
                <hr style="border-color:rgba(255,255,255,0.06); margin:1rem 0;">
                @foreach($capsule->comments->take(2) as $comment)
                <div class="d-flex gap-2 mb-2">
                    <img src="{{ $comment->user->avatar_url }}" style="width:28px; height:28px; border-radius:50%; flex-shrink:0;" alt="">
                    <div style="background:rgba(255,255,255,0.04); border-radius:10px; padding:6px 12px; flex:1;">
                        <span style="color:#f5f0e8; font-weight:600; font-size:0.8rem;">{{ $comment->user->name }}</span>
                        <p style="color:#c8c0b8; font-size:0.82rem; margin:2px 0 0;">{{ $comment->body }}</p>
                    </div>
                </div>
                @endforeach
                @endif

                <form method="POST" action="{{ route('comments.store', $capsule) }}" class="mt-3 d-flex gap-2">
                    @csrf
                    <img src="{{ Auth::user()->avatar_url }}" style="width:32px; height:32px; border-radius:50%; flex-shrink:0;" alt="">
                    <div class="d-flex gap-2" style="flex:1;">
                        <input type="text" name="body" class="form-control form-control-sm" placeholder="Write a comment...">
                        <button type="submit" class="btn btn-gold btn-sm">Post</button>
                    </div>
                </form>
            </div>
            @endforeach
            <div class="mt-2">{{ $capsules->links() }}</div>
        </div>
    </div>
@endif
@endsection