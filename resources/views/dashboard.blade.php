@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<div class="topbar">
    <div>
        <div class="topbar-title">Dashboard</div>
        <p style="color:#8b95a8; font-size:0.85rem; margin:0;">Welcome back, {{ Auth::user()->name }} ✨</p>
    </div>
    <div class="topbar-actions">
        <a href="{{ route('capsules.create') }}" class="btn btn-gold">
            <i class="bi bi-plus-lg me-1"></i> New Capsule
        </a>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="glass-card stat-card">
            <div class="stat-num" style="color:#c9a84c;">{{ $totalCapsules }}</div>
            <div class="stat-label"><i class="bi bi-hourglass-split me-1"></i> Total Capsules</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="glass-card stat-card">
            <div class="stat-num" style="color:#ff6b6b;">{{ $lockedCapsules }}</div>
            <div class="stat-label"><i class="bi bi-lock me-1"></i> Locked</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="glass-card stat-card">
            <div class="stat-num" style="color:#51cf66;">{{ $unlockedCapsules }}</div>
            <div class="stat-label"><i class="bi bi-unlock me-1"></i> Unlocked</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="glass-card stat-card">
            <div class="stat-num" style="color:#ffd43b;">{{ $pendingReview }}</div>
            <div class="stat-label"><i class="bi bi-clock me-1"></i> Pending Review</div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Recent Capsules --}}
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 style="font-family:'Playfair Display',serif; color:#f5f0e8; margin:0;">Recent Capsules</h5>
            <a href="{{ route('capsules.index') }}" class="auth-link" style="font-size:0.85rem;">View all →</a>
        </div>
        @if($recentCapsules->isEmpty())
            <div class="glass-card p-5 text-center">
                <div style="font-size:3rem;">⏳</div>
                <h5 style="color:#f5f0e8; margin-top:1rem;">No capsules yet</h5>
                <p style="color:#8b95a8;">Start by sealing your first time capsule.</p>
                <a href="{{ route('capsules.create') }}" class="btn btn-gold mt-2">Create First Capsule</a>
            </div>
        @else
            <div class="row g-3">
                @foreach($recentCapsules as $capsule)
                <div class="col-md-6">
                    <div class="glass-card capsule-card h-100">
                        <div class="capsule-card-header d-flex justify-content-between align-items-start"
                             style="background: {{ $capsule->is_locked ? 'rgba(201,168,76,0.05)' : 'rgba(40,167,69,0.05)' }};">
                            <span class="badge-gold">{{ ucfirst($capsule->visibility) }}</span>
                            @if($capsule->is_locked)
                                <span class="badge-locked">🔒 Locked</span>
                            @else
                                <span class="badge-unlocked">🔓 Unlocked</span>
                            @endif
                        </div>
                        <div class="capsule-card-body">
                            <h6 style="color:#f5f0e8; font-weight:600; margin-bottom:0.5rem;">{{ $capsule->title }}</h6>
                            <p style="color:#8b95a8; font-size:0.82rem; margin-bottom:0.75rem;">
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
                            <form method="POST" action="{{ route('capsules.destroy', $capsule) }}" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger-soft btn-sm">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Upcoming Unlocks --}}
    <div class="col-md-4">
        <h5 style="font-family:'Playfair Display',serif; color:#f5f0e8; margin-bottom:1rem;">Upcoming Unlocks</h5>
        @if($upcomingCapsules->isEmpty())
            <div class="glass-card p-4 text-center">
                <p style="color:#8b95a8; font-size:0.85rem; margin:0;">No upcoming unlocks</p>
            </div>
        @else
            @foreach($upcomingCapsules as $capsule)
            <div class="glass-card p-3 mb-2 d-flex align-items-center gap-3">
                <div style="font-size:1.5rem;">⏳</div>
                <div style="flex:1; min-width:0;">
                    <div style="color:#f5f0e8; font-size:0.85rem; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $capsule->title }}</div>
                    <div class="countdown-badge mt-1" style="display:inline-block;">
                        <span class="countdown-timer" data-unlock="{{ $capsule->unlock_date->format('Y-m-d\TH:i:s') }}">
                            {{ $capsule->countdown() }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
function updateCountdowns() {
    document.querySelectorAll('.countdown-timer').forEach(function(el) {
        var unlockDate = new Date(el.dataset.unlock);
        var now = new Date();
        var diff = unlockDate - now;
        if (diff <= 0) { el.textContent = 'Ready to unlock!'; return; }
        var d = Math.floor(diff/(1000*60*60*24));
        var h = Math.floor((diff%(1000*60*60*24))/(1000*60*60));
        var m = Math.floor((diff%(1000*60*60))/(1000*60));
        var s = Math.floor((diff%(1000*60))/1000);
        el.textContent = d+'d '+h+'h '+m+'m '+s+'s';
    });
}
updateCountdowns();
setInterval(updateCountdowns, 1000);
</script>
@endsection