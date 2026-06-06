@extends('layouts.app')
@section('title', 'My Capsules')
@section('content')

<div class="topbar">
    <div>
        <div class="topbar-title">My Capsules</div>
        <p style="color:#8b95a8; font-size:0.85rem; margin:0;">All your time capsules in one place</p>
    </div>
    <a href="{{ route('capsules.create') }}" class="btn btn-gold">
        <i class="bi bi-plus-lg me-1"></i> New Capsule
    </a>
</div>

@if($capsules->isEmpty())
    <div class="glass-card p-5 text-center">
        <div style="font-size:4rem;">⏳</div>
        <h4 style="color:#f5f0e8; margin-top:1rem; font-family:'Playfair Display',serif;">No capsules yet</h4>
        <p style="color:#8b95a8;">Seal your first memory, dream, or decision for your future self.</p>
        <a href="{{ route('capsules.create') }}" class="btn btn-gold mt-2">Create Your First Capsule</a>
    </div>
@else
    <div class="row g-3">
        @foreach($capsules as $capsule)
        <div class="col-md-6 col-lg-4">
            <div class="glass-card capsule-card h-100">
                <div class="capsule-card-header d-flex justify-content-between align-items-start"
                     style="background: rgba(201,168,76,0.04);">
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge-gold">{{ ucfirst($capsule->visibility) }}</span>
                        @if($capsule->is_group) <span class="badge-teal">Group</span> @endif
                    </div>
                    @if($capsule->is_locked)
                        <span class="badge-locked">🔒</span>
                    @elseif($capsule->status === 'pending_review')
                        <span class="badge-pending">⏳ Review</span>
                    @elseif($capsule->status === 'approved')
                        <span class="badge-unlocked">✅ Live</span>
                    @else
                        <span class="badge-unlocked">🔓</span>
                    @endif
                </div>
                <div class="capsule-card-body" style="flex:1;">
                    <h6 style="color:#f5f0e8; font-weight:600; margin-bottom:0.4rem;">{{ $capsule->title }}</h6>
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
                    @if($capsule->status === 'rejected' && $capsule->reject_reason)
                        <div class="mt-2" style="background:rgba(220,53,69,0.1); border-radius:8px; padding:6px 10px; font-size:0.78rem; color:#ff6b6b;">
                            ❌ Rejected: {{ $capsule->reject_reason }}
                        </div>
                    @endif
                </div>
                <div class="capsule-card-footer d-flex gap-2 flex-wrap">
                    <a href="{{ route('capsules.show', $capsule) }}" class="btn btn-ghost btn-sm flex-fill">View</a>
                    @if($capsule->status === 'unlocked' && $capsule->visibility === 'public')
                        <form method="POST" action="{{ route('capsules.submit', $capsule) }}">
                            @csrf <button class="btn btn-sm" style="background:rgba(78,205,196,0.15); color:#4ecdc4; border:1px solid rgba(78,205,196,0.3); border-radius:8px;">Share</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('capsules.destroy', $capsule) }}" onsubmit="return confirm('Delete permanently?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger-soft btn-sm">Del</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $capsules->links() }}</div>
@endif
@endsection

@section('scripts')
<script>
function updateCountdowns() {
    document.querySelectorAll('.countdown-timer').forEach(function(el) {
        var unlockDate = new Date(el.dataset.unlock);
        var diff = new Date() - unlockDate < 0 ? unlockDate - new Date() : 0;
        if (!diff) { el.textContent = 'Ready to unlock!'; return; }
        var d=Math.floor(diff/(1000*60*60*24)), h=Math.floor((diff%(1000*60*60*24))/(1000*60*60));
        var m=Math.floor((diff%(1000*60*60))/(1000*60)), s=Math.floor((diff%(1000*60))/1000);
        el.textContent = d+'d '+h+'h '+m+'m '+s+'s';
    });
}
updateCountdowns(); setInterval(updateCountdowns, 1000);
</script>
@endsection