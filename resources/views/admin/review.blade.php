@extends('layouts.admin')
@section('title', 'Review Queue')
@section('content')

<div class="topbar">
    <div>
        <div class="topbar-title">Review Queue</div>
        <p style="color:#8b95a8; font-size:0.85rem; margin:0;">Capsules submitted for public newsfeed</p>
    </div>
</div>

@if($capsules->isEmpty())
    <div class="glass-card p-5 text-center">
        <div style="font-size:3rem;">✅</div>
        <h5 style="color:#f5f0e8; margin-top:1rem; font-family:'Playfair Display',serif;">Queue is clear!</h5>
        <p style="color:#8b95a8;">No capsules pending review.</p>
    </div>
@else
    @foreach($capsules as $capsule)
    <div class="glass-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ $capsule->user->avatar_url }}" style="width:44px; height:44px; border-radius:50%; border:2px solid rgba(201,168,76,0.3);" alt="">
                <div>
                    <div style="color:#f5f0e8; font-weight:600;">{{ $capsule->user->name }}</div>
                    <div style="color:#8b95a8; font-size:0.78rem;">{{ $capsule->user->email }}</div>
                </div>
            </div>
            <span class="badge-pending">⏳ Pending</span>
        </div>

        <h4 style="font-family:'Playfair Display',serif; color:#f5f0e8; margin-bottom:1rem;">{{ $capsule->title }}</h4>
        <div class="p-3 rounded-3 mb-3" style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.06); color:#c8c0b8; line-height:1.8; white-space:pre-wrap; max-height:300px; overflow-y:auto;">{{ $capsule->content }}</div>

        <div style="color:#8b95a8; font-size:0.78rem; margin-bottom:1.5rem;">
            Written {{ $capsule->created_at->format('d M Y') }} · Unlocked {{ $capsule->unlock_date->format('d M Y') }}
        </div>

        <div class="d-flex gap-3">
            <form method="POST" action="{{ route('admin.capsules.approve', $capsule) }}">
                @csrf <button class="btn btn-gold">✅ Approve & Publish</button>
            </form>
            <button class="btn btn-danger-soft" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $capsule->id }}">❌ Reject</button>
            <form method="POST" action="{{ route('admin.capsules.destroy', $capsule) }}" onsubmit="return confirm('Delete permanently?')">
                @csrf @method('DELETE') <button class="btn btn-ghost">🗑 Delete</button>
            </form>
        </div>
    </div>

    <div class="modal fade" id="rejectModal{{ $capsule->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="background:#111827; border:1px solid rgba(255,255,255,0.1);">
                <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,0.08);">
                    <h5 class="modal-title" style="color:#f5f0e8;">Reject: {{ Str::limit($capsule->title, 30) }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.capsules.reject', $capsule) }}">
                    @csrf
                    <div class="modal-body">
                        <label class="form-label">Reason</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="e.g. Inappropriate content, hate speech..." required></textarea>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid rgba(255,255,255,0.08);">
                        <button type="button" class="btn btn-ghost btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger-soft btn-sm">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
    <div class="mt-3">{{ $capsules->links() }}</div>
@endif
@endsection