@extends('layouts.app')
@section('title', $friend->name . "'s Profile")
@section('content')

{{-- Back Arrow Header --}}
<div class="topbar">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('friends.index') }}" 
           style="color:#c9a84c; font-size:1.3rem; text-decoration:none; line-height:1;" 
           title="Back to Friends">
            &#8592;
        </a>
        <div>
            <div class="topbar-title">{{ $friend->name }}</div>
            <p style="color:#8b95a8; font-size:0.85rem; margin:0;">Friend's capsules</p>
        </div>
    </div>
</div>

{{-- Friend Info Card --}}
<div class="glass-card p-4 mb-4 d-flex align-items-center gap-3">
    <img src="{{ $friend->avatar_url }}" 
         style="width:60px; height:60px; border-radius:50%; border:2px solid #c9a84c;" alt="">
    <div>
        <div style="color:#f5f0e8; font-family:'Playfair Display',serif; font-size:1.2rem; font-weight:600;">
            {{ $friend->name }}
        </div>
        <div style="color:#8b95a8; font-size:0.82rem;">{{ $friend->email }}</div>
        <div style="color:#4ecdc4; font-size:0.78rem; margin-top:2px;">
            Member since {{ $friend->created_at->format('M Y') }}
        </div>
    </div>
</div>

{{-- Capsules --}}
<h5 style="font-family:'Playfair Display',serif; color:#f5f0e8; margin-bottom:1rem;">
    Shared Capsules
    <span class="badge-gold ms-2">{{ $capsules->total() }}</span>
</h5>

@forelse($capsules as $capsule)
<div class="glass-card p-4 mb-3">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <h6 style="color:#f5f0e8; margin:0;">{{ $capsule->title }}</h6>
        <span style="color:#8b95a8; font-size:0.75rem;">
            Unlocks: {{ $capsule->unlock_date->format('d M Y') }}
        </span>
    </div>
    <p style="color:#c8c0b8; font-size:0.85rem; margin-bottom:1rem;">
        {{ Str::limit($capsule->content, 200) }}
    </p>
    <div class="d-flex gap-2">
        @foreach(['inspired' => '❤️', 'goals' => '🔥', 'proud' => '🙌'] as $type => $emoji)
        <form method="POST" action="{{ route('reactions.toggle', $capsule) }}" class="d-inline">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
            <button type="submit" class="btn btn-sm" 
                style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); 
                       color:#f5f0e8; border-radius:20px; padding:3px 10px; font-size:0.78rem;">
                {{ $emoji }} {{ $capsule->reactionCount($type) }}
            </button>
        </form>
        @endforeach
    </div>
</div>
@empty
<div class="glass-card p-4 text-center">
    <p style="color:#8b95a8; font-size:0.85rem; margin:0;">
        {{ $friend->name }} hasn't shared any capsules yet.
    </p>
</div>
@endforelse

{{-- Pagination --}}
@if($capsules->hasPages())
<div class="d-flex justify-content-center mt-3">
    {{ $capsules->links() }}
</div>
@endif

@endsection

