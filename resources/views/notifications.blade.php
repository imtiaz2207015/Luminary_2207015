@extends('layouts.app')
@section('title', 'Notifications')
@section('content')

<div class="topbar">
    <div>
        <div class="topbar-title">Notifications</div>
        <p style="color:#8b95a8; font-size:0.85rem; margin:0;">Stay up to date</p>
    </div>
</div>

@if($notifications->isEmpty())
    <div class="glass-card p-5 text-center">
        <div style="font-size:3.5rem;">🔔</div>
        <h5 style="color:#f5f0e8; margin-top:1rem; font-family:'Playfair Display',serif;">All caught up!</h5>
        <p style="color:#8b95a8;">No notifications yet.</p>
    </div>
@else
    @foreach($notifications as $notif)
    @php $data = json_decode($notif->data, true) ?? []; @endphp
    <div class="glass-card p-3 mb-2 d-flex align-items-start gap-3"
         style="{{ $notif->read_at ? '' : 'border-color:rgba(201,168,76,0.3);' }}">
        <div style="font-size:1.5rem; margin-top:2px;">
            @if($notif->type === 'capsule_approved') ✅
            @elseif($notif->type === 'capsule_rejected') ❌
            @elseif($notif->type === 'friend_request') 👋
            @elseif($notif->type === 'group_invite') 👥
            @else 🔔
            @endif
        </div>
        <div style="flex:1;">
            <div style="color:#f5f0e8; font-size:0.9rem; font-weight:{{ $notif->read_at ? '400' : '600' }};">
                @if($notif->type === 'capsule_approved')
                    Your capsule <strong>"{{ $data['capsule_title'] ?? '' }}"</strong> was approved and is now live!
                @elseif($notif->type === 'capsule_rejected')
                    Your capsule <strong>"{{ $data['capsule_title'] ?? '' }}"</strong> was rejected.
                    @if(!empty($data['reason'])) <span style="color:#8b95a8; font-size:0.82rem;">Reason: {{ $data['reason'] }}</span> @endif
                @elseif($notif->type === 'friend_request')
                    <strong>{{ $data['from_user_name'] ?? 'Someone' }}</strong> sent you a friend request.
                @elseif($notif->type === 'group_invite')
                    You were invited to a group capsule: <strong>"{{ $data['capsule_title'] ?? '' }}"</strong>
                @else
                    {{ $notif->data }}
                @endif
            </div>
            <div style="color:#8b95a8; font-size:0.75rem; margin-top:4px;">{{ $notif->created_at->diffForHumans() }}</div>
        </div>
        @if(!$notif->read_at)
            <div class="notif-dot mt-2"></div>
        @endif
    </div>
    @endforeach
    <div class="mt-3">{{ $notifications->links() }}</div>
@endif
@endsection