
//"Breeze handles the user-facing authentication and application. The admin panel is a completely separate section with its own login, its own access control, and different UI requirements — so it intentionally uses its own dedicated layout. This is the standard way to build admin panels in Laravel."



@extends('layouts.admin')
@section('title', 'All Capsules')
@section('content')

<div class="topbar">
    <div>
        <div class="topbar-title">All Capsules</div>
        <p style="color:#8b95a8; font-size:0.85rem; margin:0;">{{ $capsules->total() }} total capsules on the platform</p>
    </div>
</div>

<div class="glass-card p-0">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>User</th>
                    <th>Visibility</th>
                    <th>Status</th>
                    <th>Unlock Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($capsules as $capsule)
                <tr>
                    <td style="color:#8b95a8;">{{ $capsule->id }}</td>
                    <td>{{ Str::limit($capsule->title, 30) }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $capsule->user->avatar_url }}" style="width:28px; height:28px; border-radius:50%;" alt="">
                            <span style="font-size:0.85rem;">{{ $capsule->user->name }}</span>
                        </div>
                    </td>
                    <td>
                        @if($capsule->visibility === 'public') <span class="badge-gold">🌍 Public</span>
                        @elseif($capsule->visibility === 'friends') <span class="badge-gold">👥 Friends</span>
                        @else <span style="color:#8b95a8; font-size:0.78rem;">🔒 Only Me</span>
                        @endif
                    </td>
                    <td>
                        @if($capsule->status === 'approved') <span class="badge-success">✅ Approved</span>
                        @elseif($capsule->status === 'pending_review') <span class="badge-pending">⏳ Pending</span>
                        @elseif($capsule->status === 'rejected') <span class="badge-danger">❌ Rejected</span>
                        @elseif($capsule->status === 'locked') <span class="badge-danger">🔒 Locked</span>
                        @elseif($capsule->status === 'unlocked') <span class="badge-success">🔓 Unlocked</span>
                        @else <span style="color:#8b95a8; font-size:0.78rem;">Draft</span>
                        @endif
                    </td>
                    <td style="color:#8b95a8; font-size:0.82rem;">{{ $capsule->unlock_date->format('d M Y') }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.capsules.destroy', $capsule) }}"
                              onsubmit="return confirm('Delete permanently?')" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger-soft btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $capsules->links() }}</div>
</div>
@endsection