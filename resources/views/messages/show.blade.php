<x-app-layout>
<x-slot name="title">{{ $otherUser->name }}</x-slot>

<div class="topbar">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('messages.index') }}" style="color:#c9a84c; font-size:1.3rem; text-decoration:none;">&#8592;</a>
        <div class="topbar-title">{{ $otherUser->name }}</div>
    </div>
</div>

<div class="glass-card p-3 mb-3" style="height:60vh; overflow-y:auto;" id="message-thread">
    @foreach($conversation->messages as $message)
        <div class="d-flex mb-2 {{ $message->user_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
            <div class="p-2 rounded-3" style="max-width:70%;
                background:{{ $message->user_id === auth()->id() ? '#c9a84c' : 'rgba(255,255,255,0.07)' }};
                color:{{ $message->user_id === auth()->id() ? '#0a0f1e' : '#f5f0e8' }};">
                @if($message->body)
                    <div>{{ $message->body }}</div>
                @endif
                @if($message->attachment_path)
                    <a href="{{ asset('storage/'.$message->attachment_path) }}" target="_blank"
                       style="color:inherit; display:block; font-size:0.8rem; margin-top:4px;">📎 Attachment</a>
                @endif
                <div style="font-size:0.7rem; opacity:0.6; margin-top:2px;">{{ $message->created_at->format('g:i A') }}</div>
            </div>
        </div>
    @endforeach
</div>

<form action="{{ route('messages.store', $conversation) }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
    @csrf
    <input type="text" name="body" class="form-control" placeholder="Type a message...">
    <label class="btn btn-ghost mb-0">
        📎<input type="file" name="attachment" hidden>
    </label>
    <button type="submit" class="btn btn-gold">Send</button>
</form>

<script>
    const thread = document.getElementById('message-thread');
    thread.scrollTop = thread.scrollHeight;
</script>

</x-app-layout>