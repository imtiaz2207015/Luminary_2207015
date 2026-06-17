@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<style>
.capsule-pill {
    position: relative;
    width: 100%;
    height: 158px;
    border-radius: 100px;
    overflow: hidden;
    background: #0d1117;
    border: 1.5px solid rgba(201,168,76,0.18);
    box-shadow: 0 4px 32px rgba(0,0,0,0.38), inset 0 1px 0 rgba(255,255,255,0.06);
    transition: transform 0.22s ease, box-shadow 0.22s ease;
}
.capsule-pill:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 40px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.08);
}

.capsule-wave {
    position: absolute;
    bottom: 0; left: 0;
    width: 1800px;
    max-width: none;
    pointer-events: none;
    z-index: 0;
}

.wave-group {
    animation: waveSlide 20s linear infinite;
    will-change: transform;
}

.wave-group-2 {
    animation: waveSlide 30s linear infinite;
    opacity: .45;
    will-change: transform;
}

@keyframes waveSlide {
    from { transform: translate3d(0, 0, 0); }
    to   { transform: translate3d(-900px, 0, 0); }
}

.capsule-pill:hover .wave-group  { animation-duration: 2s; }
.capsule-pill:hover .wave-group-2 { animation-duration: 3s; }

.capsule-sheen {
    position: absolute;
    inset: 0;
    background: linear-gradient(160deg,
        rgba(255,255,255,0.07) 0%,
        rgba(255,255,255,0.01) 40%,
        rgba(0,0,0,0.12) 100%);
    border-radius: inherit;
    pointer-events: none;
    z-index: 3;
}

.capsule-content {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    height: 100%;
    padding: 0 28px;
    gap: 18px;
}

.capsule-left {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    min-width: 52px;
}
.capsule-status-icon {
    font-size: 1.6rem;
    line-height: 1;
    filter: drop-shadow(0 0 6px rgba(201,168,76,0.5));
}

.capsule-mid {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 4px;
}
.capsule-title {
    color: #f5f0e8;
    font-weight: 700;
    font-size: 0.97rem;
    font-family: 'Playfair Display', serif;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    text-shadow: 0 1px 4px rgba(0,0,0,0.6);
}
.capsule-date {
    color: #a8b0be;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 4px;
}
.capsule-countdown {
    display: inline-block;
    margin-top: 4px;
    background: rgba(0,0,0,0.32);
    border: 1px solid rgba(201,168,76,0.25);
    border-radius: 20px;
    padding: 2px 10px;
    font-size: 0.72rem;
    color: #c9a84c;
    font-variant-numeric: tabular-nums;
    letter-spacing: 0.02em;
}

.capsule-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    justify-content: center;
    gap: 8px;
    min-width: 90px;
}

.badge-pill-gold {
    font-size: 0.68rem; font-weight: 600;
    padding: 2px 10px; border-radius: 20px;
    background: rgba(201,168,76,0.15);
    border: 1px solid rgba(201,168,76,0.35);
    color: #c9a84c; letter-spacing: 0.03em; text-transform: uppercase;
}
.badge-pill-teal {
    font-size: 0.68rem; font-weight: 600;
    padding: 2px 10px; border-radius: 20px;
    background: rgba(78,205,196,0.12);
    border: 1px solid rgba(78,205,196,0.3);
    color: #4ecdc4; letter-spacing: 0.03em; text-transform: uppercase;
}
.badge-pill-pending {
    font-size: 0.68rem; font-weight: 600;
    padding: 2px 10px; border-radius: 20px;
    background: rgba(255,193,7,0.12);
    border: 1px solid rgba(255,193,7,0.3);
    color: #ffd43b;
}
.badge-pill-live {
    font-size: 0.68rem; font-weight: 600;
    padding: 2px 10px; border-radius: 20px;
    background: rgba(81,207,102,0.12);
    border: 1px solid rgba(81,207,102,0.3);
    color: #51cf66;
}

.capsule-actions { display: flex; gap: 5px; flex-wrap: wrap; justify-content: flex-end; }
.btn-capsule {
    font-size: 0.7rem; padding: 3px 12px;
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.12);
    background: rgba(255,255,255,0.06);
    color: #c8c0b8; cursor: pointer;
    transition: background 0.18s, color 0.18s;
    text-decoration: none; line-height: 1.6;
}
.btn-capsule:hover {
    background: rgba(201,168,76,0.18);
    color: #c9a84c;
    border-color: rgba(201,168,76,0.4);
}
.btn-capsule-del {
    background: rgba(220,53,69,0.1);
    border-color: rgba(220,53,69,0.25);
    color: #ff6b6b;
}
.btn-capsule-del:hover { background: rgba(220,53,69,0.22); color: #ff6b6b; }
</style>

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h2 style="font-family:'Playfair Display',serif; color:#f5f0e8; margin:0;">
            Welcome back, {{ Auth::user()->name }} ✨
        </h2>
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

{{-- Capsules Grid --}}
<h5 class="section-title mb-3">
    @if(request('search')) Results for "{{ request('search') }}"
    @else My Capsules
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
    <div class="row g-4 mb-3">
        @foreach($recentCapsules->take(3) as $capsule)

        @php
            if ($capsule->is_locked) {
                $waveColor1 = 'rgba(30,120,180,0.55)';
                $waveColor2 = 'rgba(20,90,150,0.38)';
                $statusIcon = '🔒';
            } elseif ($capsule->status === 'pending_review') {
                $waveColor1 = 'rgba(200,140,20,0.5)';
                $waveColor2 = 'rgba(160,100,10,0.35)';
                $statusIcon = '⏳';
            } elseif ($capsule->status === 'approved') {
                $waveColor1 = 'rgba(40,160,80,0.5)';
                $waveColor2 = 'rgba(25,120,55,0.35)';
                $statusIcon = '✅';
            } else {
                $waveColor1 = 'rgba(201,168,76,0.45)';
                $waveColor2 = 'rgba(160,130,50,0.3)';
                $statusIcon = '🔓';
            }

            $created  = $capsule->created_at;
            $unlock   = $capsule->unlock_date;
            $total    = max($unlock->diffInSeconds($created), 1);
            $elapsed  = now()->diffInSeconds($created, false);
            $fillPct  = $capsule->is_locked
                ? max(15, min(88, round(100 - ($elapsed / $total * 100))))
                : ($capsule->status === 'approved' ? 90 : 30);
            $waveHeight = round(158 * $fillPct / 100);
        @endphp

        <div class="col-lg-4 col-md-6">
            <div class="capsule-pill">

                <svg class="capsule-wave" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 1800 158" preserveAspectRatio="none"
                     style="height:{{ $waveHeight }}px;">
                    <g class="wave-group">
                        <path fill="{{ $waveColor1 }}"
                              d="M0,40 C100,10 200,70 300,40 C400,10 500,70 600,40 C700,10 800,70 900,40
                                 C1000,10 1100,70 1200,40 C1300,10 1400,70 1500,40 C1600,10 1700,70 1800,40
                                 L1800,158 L0,158 Z"/>
                           <path fill="{{ $waveColor1 }}"
                                 transform="translate(1800,0)"
                              d="M0,40 C100,10 200,70 300,40 C400,10 500,70 600,40 C700,10 800,70 900,40
                                 C1000,10 1100,70 1200,40 C1300,10 1400,70 1500,40 C1600,10 1700,70 1800,40
                                 L1800,158 L0,158 Z"/>
                    </g>
                    <g class="wave-group-2">
                        <path fill="{{ $waveColor2 }}"
                              d="M0,55 C120,25 240,80 360,55 C480,25 600,80 720,55 C840,25 960,80 1080,55
                                 C1200,25 1320,80 1440,55 C1560,25 1680,80 1800,55
                                 L1800,158 L0,158 Z"/>
                              <path fill="{{ $waveColor2 }}"
                               transform="translate(1800,0)"
                                d="M0,55 C120,25 240,80 360,55 C480,25 600,80 720,55 C840,25 960,80 1080,55
                                     C1200,25 1320,80 1440,55 C1560,25 1680,80 1800,55
                                     L1800,158 L0,158 Z"/>
                    </g>
                </svg>

                <div class="capsule-sheen"></div>

                <div class="capsule-content">
                    <div class="capsule-left">
                        <div class="capsule-status-icon">{{ $statusIcon }}</div>
                        @if($capsule->is_group)
                            <span class="badge-pill-teal">Group</span>
                        @endif
                    </div>

                    <div class="capsule-mid">
                        <div class="capsule-title">{{ $capsule->title }}</div>
                        <div class="capsule-date">
                            <i class="bi bi-calendar3"></i>
                            {{ $capsule->unlock_date->format('d M Y') }}
                        </div>
                        @if($capsule->is_locked)
                            <span class="capsule-countdown countdown-timer"
                                  data-unlock="{{ $capsule->unlock_date->format('Y-m-d\TH:i:s') }}">
                                {{ $capsule->countdown() }}
                            </span>
                        @endif
                    </div>

                    <div class="capsule-right">
                        <div class="d-flex gap-1 flex-wrap justify-content-end">
                            <span class="badge-pill-gold">{{ ucfirst($capsule->visibility) }}</span>
                            @if($capsule->status === 'pending_review')
                                <span class="badge-pill-pending">⏳ Review</span>
                            @elseif($capsule->status === 'approved')
                                <span class="badge-pill-live">✅ Live</span>
                            @endif
                        </div>
                        <div class="capsule-actions">
                            <a href="{{ route('capsules.show', $capsule) }}" class="btn-capsule">View</a>
                            <form method="POST" action="{{ route('capsules.destroy', $capsule) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Delete permanently?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-capsule btn-capsule-del">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

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
        var d=Math.floor(diff/864e5),
            h=Math.floor((diff%864e5)/36e5),
            m=Math.floor((diff%36e5)/6e4),
            s=Math.floor((diff%6e4)/1000);
        el.textContent = d+'d '+h+'h '+m+'m '+s+'s';
    });
}
updateCountdowns();
setInterval(updateCountdowns, 1000);
</script>
@endsection