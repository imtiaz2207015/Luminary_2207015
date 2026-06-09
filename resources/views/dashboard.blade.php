@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h2 style="font-family:'Playfair Display',serif; color:#f5f0e8; margin:0;">Welcome back, {{ Auth::user()->name }} ✨</h2>
        <p style="color:#8b95a8; font-size:0.85rem; margin:4px 0 0;">Here's what's happening with your capsules</p>
    </div>
    <a href="{{ route('capsules.create') }}" class="btn btn-gold">
        <i class="bi bi-plus-lg me-1"></i> New Capsule
    </a>
</div>

{{-- Stats --}}
<div class="row g-3 mb-5">
    <div class="col-6 col-md-3">
        <div class="glass-card stat-card text-center">
            <div class="stat-num" style="color:#c9a84c;">{{ $totalCapsules }}</div>
            <div class="stat-label"><i class="bi bi-hourglass-split me-1"></i> Total</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="glass-card stat-card text-center">
            <div class="stat-num" style="color:#ff6b6b;">{{ $lockedCapsules }}</div>
            <div class="stat-label"><i class="bi bi-lock me-1"></i> Locked</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="glass-card stat-card text-center">
            <div class="stat-num" style="color:#51cf66;">{{ $unlockedCapsules }}</div>
            <div class="stat-label"><i class="bi bi-unlock me-1"></i> Unlocked</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="glass-card stat-card text-center">
            <div class="stat-num" style="color:#ffd43b;">{{ $pendingReview }}</div>
            <div class="stat-label"><i class="bi bi-clock me-1"></i> In Review</div>
        </div>
    </div>
</div>

{{-- Search --}}
<div class="glass-card p-3 mb-5">
    <form method="GET" action="{{ route('dashboard') }}" class="d-flex gap-2">
        <input type="text" name="search" class="form-control"
               placeholder="🔍 Search capsules by title..."
               value="{{ request('search') }}">
        @if(request('search'))
            <a href="{{ route('dashboard') }}" class="btn btn-ghost">Clear</a>
        @endif
        <button type="submit" class="btn btn-gold">Search</button>
    </form>
</div>

{{-- Capsules --}}
<h5 class="section-title mb-3">
    @if(request('search'))
        Results for "{{ request('search') }}"
    @else
        My Capsules
    @endif
</h5>

@if($recentCapsules->isEmpty())
    <div class="glass-card p-5 text-center mb-5">
        <div style="font-size:3.5rem;">⏳</div>
        <h5 style="color:#f5f0e8; margin-top:1rem; font-family:'Playfair Display',serif;">
            {{ request('search') ? 'No capsules found' : 'No capsules yet' }}
        </h5>
        <p style="color:#8b95a8;">
            {{ request('search') ? 'Try a different search term.' : 'Start by sealing your first time capsule.' }}
        </p>
        @if(!request('search'))
            <a href="{{ route('capsules.create') }}" class="btn btn-gold mt-2">Create First Capsule</a>
        @endif
    </div>
@else
    <div class="row g-3 mb-3">
        @foreach($recentCapsules->take(3) as $capsule)
        <div class="col-md-4">
            <div class="glass-card capsule-card h-100 d-flex flex-column">
                <div class="capsule-card-header d-flex justify-content-between align-items-center flex-wrap gap-1">
                    <div class="d-flex gap-2 align-items-center">
                        <span class="badge-gold">{{ ucfirst($capsule->visibility) }}</span>
                        @if($capsule->is_group) <span class="badge-teal">Group</span> @endif
                    </div>
                    <div>
                        @if($capsule->is_locked)
                            <span class="badge-locked">🔒 Locked</span>
                        @elseif($capsule->status === 'pending_review')
                            <span class="badge-pending">⏳ Review</span>
                        @elseif($capsule->status === 'approved')
                            <span class="badge-unlocked">✅ Live</span>
                        @else
                            <span class="badge-unlocked">🔓 Unlocked</span>
                        @endif
                    </div>
                </div>
                <div class="capsule-card-body" style="flex:1;">
                    <h6 style="color:#f5f0e8; font-weight:600; margin-bottom:0.4rem;">{{ $capsule->title }}</h6>
                    <p style="color:#8b95a8; font-size:0.82rem; margin-bottom:0.6rem;">
                        <i class="bi bi-calendar3 me-1"></i> {{ $capsule->unlock_date->format('d M Y') }}
                    </p>
                    @if($capsule->is_locked)
                        <div class="countdown-badge">
                            <span class="countdown-timer" data-unlock="{{ $capsule->unlock_date->format('Y-m-d\TH:i:s') }}">
                                {{ $capsule->countdown() }}
                            </span>
                        </div>
                    @endif
                </div>
                <div class="capsule-card-footer d-flex gap-2">
                    <a href="{{ route('capsules.show', $capsule) }}" class="btn btn-ghost btn-sm flex-fill">View</a>
                    <form method="POST" action="{{ route('capsules.destroy', $capsule) }}" onsubmit="return confirm('Delete permanently?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger-soft btn-sm">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- See More Button --}}
    <div class="text-center mb-5">
        <a href="{{ route('capsules.index') }}" class="btn btn-ghost px-4">
            <i class="bi bi-grid me-1"></i> See More
        </a>
    </div>
@endif

<hr class="section-divider my-5">

{{-- Upcoming Deadlines --}}
<h5 class="section-title mb-3">⏰ Upcoming Deadlines</h5>

@if($upcomingCapsules->isEmpty())
    <div class="glass-card p-4 text-center">
        <p style="color:#8b95a8; margin:0; font-size:0.88rem;">No upcoming deadlines.</p>
    </div>
@else
    @foreach($upcomingCapsules as $capsule)
    <div class="glass-card p-3 mb-3 d-flex align-items-center gap-3">
        <div style="font-size:1.75rem;">⏳</div>
        <div style="flex:1; min-width:0;">
            <div style="color:#f5f0e8; font-weight:600; font-size:0.9rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                {{ $capsule->title }}
            </div>
            <div style="color:#8b95a8; font-size:0.78rem; margin-top:2px;">
                Unlocks {{ $capsule->unlock_date->format('d M Y') }}
            </div>
        </div>
        <div class="countdown-badge">
            <span class="countdown-timer" data-unlock="{{ $capsule->unlock_date->format('Y-m-d\TH:i:s') }}">
                {{ $capsule->countdown() }}
            </span>
        </div>
        <a href="{{ route('capsules.show', $capsule) }}" class="btn btn-ghost btn-sm">View</a>
    </div>
    @endforeach
@endif

@endsection

@section('scripts')
<script>
function updateCountdowns() {
    document.querySelectorAll('.countdown-timer').forEach(function(el) {
        var diff = new Date(el.dataset.unlock) - new Date();
        if (diff <= 0) { el.textContent = 'Ready to unlock!'; return; }
        var d=Math.floor(diff/864e5), h=Math.floor((diff%864e5)/36e5);
        var m=Math.floor((diff%36e5)/6e4), s=Math.floor((diff%6e4)/1000);
        el.textContent = d+'d '+h+'h '+m+'m '+s+'s';
    });
}
updateCountdowns(); setInterval(updateCountdowns, 1000);
</script>
@endsection