@extends('layouts.app')
@section('title', 'Friends')
@section('content')

<div class="topbar">
    <div>
        <div class="topbar-title">Friends</div>
        <p style="color:#8b95a8; font-size:0.85rem; margin:0;">Connect with people on their journey</p>
    </div>
</div>

{{-- Search --}}
<div class="glass-card p-4 mb-4">
    <form method="GET" action="{{ route('friends.search') }}" class="d-flex gap-2">
        <input type="text" name="q" class="form-control" placeholder="Search by name or email..." value="{{ request('q') }}">
        <button type="submit" class="btn btn-gold">Search</button>
    </form>
    @if(isset($users))
    <div class="mt-3">
        @forelse($users as $user)
        <div class="d-flex align-items-center justify-content-between p-3 rounded-3 mb-2" style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.07);">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ $user->avatar_url }}" style="width:40px; height:40px; border-radius:50%;" alt="">
                <div>
                    <div style="color:#f5f0e8; font-weight:600; font-size:0.9rem;">{{ $user->name }}</div>
                    <div style="color:#8b95a8; font-size:0.78rem;">{{ $user->email }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('friends.request', $user) }}">
                @csrf
                <button class="btn btn-gold btn-sm">+ Add Friend</button>
            </form>
        </div>
        @empty
        <p style="color:#8b95a8; font-size:0.85rem; margin:0;">No users found for "{{ request('q') }}"</p>
        @endforelse
    </div>
    @endif
</div>

<div class="row g-4">
    {{-- Pending Requests --}}
    @if(isset($pendingRequests) && $pendingRequests->count() > 0)
    <div class="col-12">
        <h5 style="font-family:'Playfair Display',serif; color:#f5f0e8; margin-bottom:1rem;">
            Pending Requests <span class="badge-pending ms-2">{{ $pendingRequests->count() }}</span>
        </h5>
        <div class="row g-3">
            @foreach($pendingRequests as $request)
            <div class="col-md-6">
                <div class="glass-card p-3 d-flex align-items-center gap-3">
                    <img src="{{ $request->user->avatar_url }}" style="width:44px; height:44px; border-radius:50%;" alt="">
                    <div style="flex:1;">
                        <div style="color:#f5f0e8; font-weight:600;">{{ $request->user->name }}</div>
                        <div style="color:#8b95a8; font-size:0.78rem;">Wants to be friends</div>
                    </div>
                    <div class="d-flex gap-2">
                        <form method="POST" action="{{ route('friends.accept', $request) }}">
                            @csrf <button class="btn btn-gold btn-sm">Accept</button>
                        </form>
                        <form method="POST" action="{{ route('friends.decline', $request) }}">
                            @csrf <button class="btn btn-ghost btn-sm">Decline</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Friends List --}}
    @if(isset($friends))
    <div class="col-md-4">
        <h5 style="font-family:'Playfair Display',serif; color:#f5f0e8; margin-bottom:1rem;">
            My Friends @if($friends->count() > 0) <span class="badge-gold ms-2">{{ $friends->count() }}</span> @endif
        </h5>
        @forelse($friends as $friendship)
        <div class="glass-card p-3 d-flex align-items-center gap-3 mb-2">
            <img src="{{ $friendship->friend->avatar_url }}" style="width:40px; height:40px; border-radius:50%;" alt="">
            <div style="flex:1;">
                <div style="color:#f5f0e8; font-weight:600; font-size:0.9rem;">{{ $friendship->friend->name }}</div>
            </div>
            <form method="POST" action="{{ route('friends.unfriend', $friendship->friend) }}">
                @csrf @method('DELETE')
                <button class="btn btn-danger-soft btn-sm">Unfriend</button>
            </form>
        </div>
        @empty
        <div class="glass-card p-4 text-center">
            <p style="color:#8b95a8; font-size:0.85rem; margin:0;">No friends yet. Search above!</p>
        </div>
        @endforelse
    </div>

    {{-- Friends Capsules --}}
    <div class="col-md-8">
        <h5 style="font-family:'Playfair Display',serif; color:#f5f0e8; margin-bottom:1rem;">Friends' Capsules</h5>
        @forelse($friendCapsules as $capsule)
        <div class="glass-card p-4 mb-3">
            <div class="d-flex align-items-center gap-3 mb-3">
                <img src="{{ $capsule->user->avatar_url }}" style="width:36px; height:36px; border-radius:50%;" alt="">
                <div>
                    <div style="color:#f5f0e8; font-weight:600; font-size:0.88rem;">{{ $capsule->user->name }}</div>
                    <div style="color:#8b95a8; font-size:0.75rem;">{{ $capsule->unlock_date->format('d M Y') }}</div>
                </div>
            </div>
            <h6 style="color:#f5f0e8;">{{ $capsule->title }}</h6>
            <p style="color:#c8c0b8; font-size:0.85rem;">{{ Str::limit($capsule->content, 200) }}</p>
            <div class="d-flex gap-2">
                @foreach(['inspired' => '❤️', 'goals' => '🔥', 'proud' => '🙌'] as $type => $emoji)
                <form method="POST" action="{{ route('reactions.toggle', $capsule) }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <button type="submit" class="btn btn-sm" style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); color:#f5f0e8; border-radius:20px; padding:3px 10px; font-size:0.78rem;">
                        {{ $emoji }} {{ $capsule->reactionCount($type) }}
                    </button>
                </form>
                @endforeach
            </div>
        </div>
        @empty
        <div class="glass-card p-4 text-center">
            <p style="color:#8b95a8; font-size:0.85rem; margin:0;">No unlocked capsules from friends yet.</p>
        </div>
        @endforelse
    </div>
    @endif
</div>
@endsection