<x-app-layout>
<x-slot name="title">Friends</x-slot>

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
           @php
    $fs = $friendshipMap[$user->id] ?? null;
@endphp

@if($fs && $fs['status'] === 'accepted')
    {{-- Already friends --}}
    <button class="btn btn-sm" style="background:rgba(78,205,196,0.15); border:1px solid #4ecdc4; color:#4ecdc4;" disabled>
        Friends ✓
    </button>

@elseif($fs && $fs['status'] === 'pending')
    {{-- We sent a request — show Requested, click to cancel --}}
    <form method="POST" action="{{ route('friends.cancel', $user) }}" id="cancel-form-{{ $user->id }}">
        @csrf @method('DELETE')
        <button type="button" class="btn btn-sm btn-requested"
            onclick="confirmCancel({{ $user->id }}, '{{ $user->name }}')"
            style="background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.2); color:#8b95a8;">
            Requested
        </button>
    </form>

@elseif($fs && $fs['status'] === 'received')
    {{-- They sent us a request --}}
    <button class="btn btn-sm" style="background:rgba(201,168,76,0.15); border:1px solid #c9a84c; color:#c9a84c;" disabled>
        Respond in Requests ↑
    </button>

@else
    {{-- No relationship --}}
    <form method="POST" action="{{ route('friends.request', $user) }}">
        @csrf
        <button class="btn btn-gold btn-sm">+ Add Friend</button>
    </form>
@endif
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
    <div class="col-md-12">
        <h5 style="font-family:'Playfair Display',serif; color:#f5f0e8; margin-bottom:1rem;">
            My Friends @if($friends->count() > 0) <span class="badge-gold ms-2">{{ $friends->count() }}</span> @endif
        </h5>
        @forelse($friends as $friendship)
        <div class="glass-card p-3 d-flex align-items-center gap-3 mb-2">
            <img src="{{ $friendship->friend->avatar_url }}" style="width:40px; height:40px; border-radius:50%;" alt="">
            <div style="flex:1;">
            <a href="{{route('friends.capsules', $friendship->friend->id)}}" 
   style="color:#f5f0e8; font-weight:600; font-size:0.9rem; text-decoration:none;">
    {{ $friendship->friend->name }}
</a>

            </div>
            <form method="POST" action="{{ route('friends.unfriend', $friendship->friend->id)}}">
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
    @endif
</div>
<script>
function confirmCancel(userId, userName) {
    if (confirm('Cancel your friend request to ' + userName + '?')) {
        document.getElementById('cancel-form-' + userId).submit();
    }
}
</script>
</x-app-layout>