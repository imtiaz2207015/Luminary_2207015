@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')

<div class="topbar">
    <div>
        <div class="topbar-title">Admin Dashboard</div>
        <p style="color:#8b95a8; font-size:0.85rem; margin:0;">Platform overview</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="glass-card stat-card">
            <div class="stat-num" style="color:#c9a84c;">{{ $totalUsers }}</div>
            <div style="color:#8b95a8; font-size:0.82rem; margin-top:4px;"><i class="bi bi-people me-1"></i>Total Users</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="glass-card stat-card">
            <div class="stat-num" style="color:#4ecdc4;">{{ $totalCapsules }}</div>
            <div style="color:#8b95a8; font-size:0.82rem; margin-top:4px;"><i class="bi bi-hourglass-split me-1"></i>Total Capsules</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="glass-card stat-card">
            <div class="stat-num" style="color:#ffd43b;">{{ $pendingReviews }}</div>
            <div style="color:#8b95a8; font-size:0.82rem; margin-top:4px;"><i class="bi bi-clock me-1"></i>Pending Review</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="glass-card stat-card">
            <div class="stat-num" style="color:#51cf66;">{{ $approvedCapsules }}</div>
            <div style="color:#8b95a8; font-size:0.82rem; margin-top:4px;"><i class="bi bi-check-circle me-1"></i>Approved</div>
        </div>
    </div>
</div>

<div class="glass-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 style="font-family:'Playfair Display',serif; color:#f5f0e8; margin:0;">Pending Review Queue</h5>
        <a href="{{ route('admin.review') }}" style="color:#c9a84c; font-size:0.85rem; text-decoration:none;">View all →</a>
    </div>
    @if($pendingCapsules->isEmpty())
        <p style="color:#8b95a8; font-size:0.85rem; text-align:center; padding:2rem 0;">No capsules pending review.</p>
    @else
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Title</th><th>User</th><th>Submitted</th><th>Actions</th></tr></thead>
                <tbody>
                    @foreach($pendingCapsules as $capsule)
                    <tr>
                        <td>{{ Str::limit($capsule->title, 35) }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $capsule->user->avatar_url }}" style="width:28px; height:28px; border-radius:50%;" alt="">
                                {{ $capsule->user->name }}
                            </div>
                        </td>
                        <td style="color:#8b95a8;">{{ $capsule->updated_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <form method="POST" action="{{ route('admin.capsules.approve', $capsule) }}">
                                    @csrf <button class="btn btn-gold btn-sm">Approve</button>
                                </form>
                                <button class="btn btn-danger-soft btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $capsule->id }}">Reject</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@foreach($pendingCapsules as $capsule)
<div class="modal fade" id="rejectModal{{ $capsule->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background:#111827; border:1px solid rgba(255,255,255,0.1);">
            <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,0.08);">
                <h5 class="modal-title" style="color:#f5f0e8;">Reject Capsule</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.capsules.reject', $capsule) }}">
                @csrf
                <div class="modal-body">
                    <label class="form-label">Reason for rejection</label>
                    <textarea name="reason" class="form-control" rows="3" placeholder="e.g. Inappropriate content..." required></textarea>
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
@endsection