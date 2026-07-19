<x-app-layout>
<x-slot name="title">Notifications</x-slot>

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
@php
    $data = is_array($notif->data) ? $notif->data : (json_decode($notif->data, true) ?? []);

    // Build icon
    $icon = match($notif->type) {
        'reaction'          => '🔔',
        'comment'           => '💬',
        'capsule_approved'  => '✅',
        'capsule_rejected'  => '❌',
        'friend_request'    => '👋',
        'group_invite'      => '👥',
        default             => '🔔',
    };

    // Build the capsule link (only for reaction/comment)
    $capsuleLink = (isset($data['capsule_id']) && isset($data['capsule_title']))
        ? '<a href="' . route('capsules.show', $data['capsule_id']) . '"
              style="color:#c9a84c; font-weight:600; text-decoration:none;"
              onmouseover="this.style.textDecoration=\'underline\'"
              onmouseout="this.style.textDecoration=\'none\'">'
          . e($data['capsule_title']) . '</a>'
        : null;

    // Build the message text
    $message = match($notif->type) {
        'reaction' => ($data['actor_name'] ?? 'Someone')
            . ' reacted ✨ <span style="color:#4ecdc4; font-weight:600;">'
            . ucfirst($data['reaction_type'] ?? '') . '</span>'
            . ' to your capsule &rarr; ' . $capsuleLink,

        'comment' => ($data['actor_name'] ?? 'Someone')
            . ' commented on your capsule &rarr; ' . $capsuleLink,

        'capsule_approved' => 'Your capsule has been <span style="color:#4ecdc4; font-weight:600;">approved</span> by the admin.',
        'capsule_rejected' => 'Your capsule was <span style="color:#e74c3c; font-weight:600;">rejected</span> by the admin.',
        'friend_request'   => ($data['actor_name'] ?? 'Someone') . ' sent you a friend request.',
        'group_invite'     => 'You have been invited to join a group.',
        default            => $data['message'] ?? 'You have a new notification.',
    };
@endphp

<div class="glass-card p-3 mb-2 d-flex align-items-start gap-3"
     style="{{ $notif->read_at ? '' : 'border-color:rgba(201,168,76,0.3);' }}">

    {{-- Icon --}}
    <div style="font-size:1.5rem; margin-top:2px;">{{ $icon }}</div>

    {{-- Message + timestamp --}}
    <div style="flex:1;">
        <div style="color:#f5f0e8; font-size:0.9rem; font-weight:{{ $notif->read_at ? '400' : '600' }};">
            {!! $message !!}
        </div>
        <div style="color:#8b95a8; font-size:0.75rem; margin-top:4px;">
            {{ $notif->created_at->diffForHumans() }}
        </div>
    </div>

    {{-- Mark as read --}}
    @if(!$notif->read_at)
        <form method="POST" action="{{ route('notifications.read', $notif->id) }}" class="ms-auto">
            @csrf
            <button type="submit" class="btn btn-sm btn-gold">Mark as Read</button>
        </form>
    @endif
</div>
@endforeach

    <div class="mt-3">{{ $notifications->links() }}</div>
@endif
</x-app-layout>