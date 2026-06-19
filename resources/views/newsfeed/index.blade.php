@extends('layouts.app')
@section('title', 'Newsfeed')
@section('content')

<style>
/* ── Feed width constraint (scoped to this page only) ── */
.newsfeed-wrap {
    max-width: 640px;
    margin: 0 auto;
}

/* ── Composer card ── */
.composer-card {
    background: rgba(255,255,255,0.03);
    border: 1.5px solid rgba(201,168,76,0.15);
    border-radius: 16px;
    padding: 18px;
    margin-bottom: 20px;
}
.composer-trigger {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
}
.composer-avatar {
    width: 42px; height: 42px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(201,168,76,0.3);
    flex-shrink: 0;
}
.composer-placeholder {
    flex: 1;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 30px;
    padding: 10px 18px;
    color: #8b95a8;
    font-size: 0.9rem;
    cursor: pointer;
    transition: background 0.18s;
}
.composer-placeholder:hover { background: rgba(255,255,255,0.08); }

/* Expanded composer form */
.composer-form { display: none; margin-top: 14px; }
.composer-form.open { display: block; }
.composer-textarea {
    width: 100%;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 12px;
    padding: 12px 16px;
    color: #f5f0e8;
    font-size: 0.92rem;
    resize: none;
    outline: none;
    transition: border-color 0.18s;
    margin-bottom: 12px;
}
.composer-textarea:focus { border-color: rgba(201,168,76,0.4); }
.composer-select {
    width: 100%;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 10px;
    padding: 9px 14px;
    color: #c8c0b8;
    font-size: 0.85rem;
    outline: none;
    margin-bottom: 12px;
    cursor: pointer;
}
.composer-select option { background: #0d1117; }
.composer-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
}
.composer-attach-label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.82rem;
    color: #4ecdc4;
    border: 1px solid rgba(78,205,196,0.3);
    border-radius: 20px;
    padding: 6px 14px;
    cursor: pointer;
    transition: background 0.18s;
}
.composer-attach-label:hover { background: rgba(78,205,196,0.1); }
.composer-image-preview {
    margin-top: 10px;
    border-radius: 10px;
    overflow: hidden;
    display: none;
}
.composer-image-preview img {
    width: 100%;
    max-height: 220px;
    object-fit: cover;
    border-radius: 10px;
}
.composer-divider {
    border: none;
    border-top: 1px solid rgba(255,255,255,0.07);
    margin: 14px 0 12px;
}

/* ── Post card ── */
.post-card {
    background: rgba(255,255,255,0.03);
    border: 1.5px solid rgba(255,255,255,0.07);
    border-radius: 16px;
    margin-bottom: 20px;
    overflow: hidden;
}
.post-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 18px 0;
}
.post-avatar {
    width: 42px; height: 42px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(201,168,76,0.3);
    flex-shrink: 0;
}
.post-name {
    color: #f5f0e8;
    font-weight: 600;
    font-size: 0.92rem;
}
.post-date {
    color: #8b95a8;
    font-size: 0.75rem;
    margin-top: 1px;
}
.post-caption {
    color: #c8c0b8;
    font-size: 0.92rem;
    line-height: 1.7;
    padding: 12px 18px 0;
    white-space: pre-wrap;
}
.post-image {
    margin-top: 14px;
    width: 100%;
    max-height: 360px;
    object-fit: cover;
}
.post-capsule-wrap {
    padding: 14px 18px 0;
}

/* ── Capsule pill (same as dashboard/index) ── */
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
.capsule-pill:hover .wave-group   { animation-duration: 2s; }
.capsule-pill:hover .wave-group-2 { animation-duration: 3s; }
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
    animation: waveSlide 30s linear infinite reverse;
    opacity: 0.45;
    will-change: transform;
}
@keyframes waveSlide {
    from { transform: translate3d(0, 0, 0); }
    to   { transform: translate3d(-900px, 0, 0); }
}
.capsule-sheen {
    position: absolute; inset: 0;
    background: linear-gradient(160deg,
        rgba(255,255,255,0.07) 0%,
        rgba(255,255,255,0.01) 40%,
        rgba(0,0,0,0.12) 100%);
    border-radius: inherit;
    pointer-events: none;
    z-index: 3;
}
.capsule-content {
    position: relative; z-index: 2;
    display: flex; align-items: center;
    height: 100%; padding: 0 28px; gap: 18px;
}
.capsule-left {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 6px; min-width: 52px;
}
.capsule-status-icon {
    font-size: 1.6rem; line-height: 1;
    filter: drop-shadow(0 0 6px rgba(201,168,76,0.5));
}
.capsule-mid {
    flex: 1; min-width: 0;
    display: flex; flex-direction: column;
    justify-content: center; gap: 4px;
}
.capsule-title {
    color: #f5f0e8; font-weight: 700;
    font-size: 0.97rem;
    font-family: 'Playfair Display', serif;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    text-shadow: 0 1px 4px rgba(0,0,0,0.6);
}
.capsule-date {
    color: #a8b0be; font-size: 0.75rem;
    display: flex; align-items: center; gap: 4px;
}
.capsule-right {
    display: flex; flex-direction: column;
    align-items: flex-end; justify-content: center;
    gap: 8px; min-width: 90px;
}
.badge-pill-gold {
    font-size: 0.68rem; font-weight: 600;
    padding: 2px 10px; border-radius: 20px;
    background: rgba(201,168,76,0.15);
    border: 1px solid rgba(201,168,76,0.35);
    color: #c9a84c; text-transform: uppercase;
}
.badge-pill-live {
    font-size: 0.68rem; font-weight: 600;
    padding: 2px 10px; border-radius: 20px;
    background: rgba(81,207,102,0.12);
    border: 1px solid rgba(81,207,102,0.3);
    color: #51cf66;
}

/* ── Reactions + comments ── */
.post-reactions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
    padding: 14px 18px;
    border-top: 1px solid rgba(255,255,255,0.06);
    margin-top: 14px;
}
.reaction-btn {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    color: #f5f0e8; border-radius: 20px;
    padding: 5px 13px; font-size: 0.82rem;
    cursor: pointer; transition: background 0.18s;
}
.reaction-btn:hover { background: rgba(201,168,76,0.15); border-color: rgba(201,168,76,0.3); }
.post-comments { padding: 0 18px 14px; }
.comment-item {
    display: flex; gap: 10px; margin-bottom: 10px;
}
.comment-avatar {
    width: 30px; height: 30px;
    border-radius: 50%; flex-shrink: 0;
    object-fit: cover;
}
.comment-bubble {
    background: rgba(255,255,255,0.05);
    border-radius: 12px;
    padding: 7px 12px; flex: 1;
}
.comment-author {
    color: #f5f0e8; font-weight: 600; font-size: 0.8rem;
}
.comment-body {
    color: #c8c0b8; font-size: 0.82rem; margin: 2px 0 0;
}
.comment-form {
    display: flex; gap: 10px;
    align-items: center;
    padding: 10px 18px 16px;
    border-top: 1px solid rgba(255,255,255,0.05);
}
.comment-input {
    flex: 1;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 20px;
    padding: 7px 16px;
    color: #f5f0e8; font-size: 0.85rem;
    outline: none;
    transition: border-color 0.18s;
}
.comment-input:focus { border-color: rgba(201,168,76,0.4); }
</style>

<div class="topbar">
    <div>
        <div class="topbar-title">Newsfeed</div>
        <p style="color:#8b95a8; font-size:0.85rem; margin:0;">What's happening in your circle</p>
    </div>
</div>

<div class="newsfeed-wrap">

    {{-- ── COMPOSER ── --}}
    <div class="composer-card">
        <div class="composer-trigger" id="composerTrigger">
            <img src="{{ Auth::user()->avatar_url }}" class="composer-avatar" alt="">
            <div class="composer-placeholder">Share a capsule or a thought...</div>
        </div>

        <div class="composer-form" id="composerForm">
            <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                @csrf

                <textarea name="caption" class="composer-textarea" rows="3"
                          placeholder="What's on your mind?">{{ old('caption') }}</textarea>

                {{-- Attach a capsule --}}
                <select name="capsule_id" class="composer-select">
                    <option value="">📦 Attach a capsule (optional)</option>
                    @foreach($myCapsules as $cap)
                        <option value="{{ $cap->id }}" {{ old('capsule_id') == $cap->id ? 'selected' : '' }}>
                            {{ $cap->title }} · Unlocked {{ $cap->unlock_date->format('d M Y') }}
                        </option>
                    @endforeach
                </select>

                <hr class="composer-divider">

                <div class="composer-actions">
                    <div class="d-flex align-items-center gap-2">
                        <label for="postImage" class="composer-attach-label">
                            <i class="bi bi-image"></i> Photo
                        </label>
                        <input type="file" name="image" id="postImage"
                               accept="image/*" style="display:none;"
                               onchange="previewPostImage(this)">
                        <span id="postImageName" style="color:#8b95a8; font-size:0.78rem;"></span>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-ghost btn-sm" id="composerCancel">Cancel</button>
                        <button type="submit" class="btn btn-gold btn-sm">Post</button>
                    </div>
                </div>

                <div class="composer-image-preview" id="postImagePreview">
                    <img id="postImagePreviewImg" src="" alt="">
                </div>

                @error('caption') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                @error('image')   <div class="text-danger small mt-2">{{ $message }}</div> @enderror
            </form>
        </div>
    </div>

    {{-- ── POSTS FEED ── --}}
    @if($posts->isEmpty())
        <div class="glass-card p-5 text-center">
            <div style="font-size:4rem;">🌍</div>
            <h4 style="color:#f5f0e8; margin-top:1rem; font-family:'Playfair Display',serif;">Nothing here yet</h4>
            <p style="color:#8b95a8;">Be the first to share a capsule with the world.</p>
        </div>
    @else
        @foreach($posts as $post)
        @php
            $cap = $post->capsule;
            if ($cap) {
                if ($cap->is_locked) {
                    $waveColor1 = 'rgba(30,120,180,0.55)';
                    $waveColor2 = 'rgba(20,90,150,0.38)';
                    $statusIcon = '🔒';
                } elseif ($cap->status === 'approved') {
                    $waveColor1 = 'rgba(40,160,80,0.5)';
                    $waveColor2 = 'rgba(25,120,55,0.35)';
                    $statusIcon = '✅';
                } else {
                    $waveColor1 = 'rgba(201,168,76,0.45)';
                    $waveColor2 = 'rgba(160,130,50,0.3)';
                    $statusIcon = '🔓';
                }
                $created    = $cap->created_at;
                $unlock     = $cap->unlock_date;
                $total      = max($unlock->diffInSeconds($created), 1);
                $elapsed    = now()->diffInSeconds($created, false);
                $fillPct    = $cap->is_locked
                    ? max(15, min(88, round(100 - ($elapsed / $total * 100))))
                    : 90;
                $waveHeight = round(158 * $fillPct / 100);
            }
        @endphp

        <div class="post-card">

            {{-- Header --}}
            <div class="post-header">
                <img src="{{ $post->user->avatar_url }}" class="post-avatar" alt="">
                <div>
                    <div class="post-name">{{ $post->user->name }}</div>
                    <div class="post-date">{{ $post->created_at->diffForHumans() }}</div>
                </div>
            </div>

            {{-- Caption --}}
            @if($post->caption)
                <div class="post-caption">{{ $post->caption }}</div>
            @endif

            {{-- Capsule pill --}}
            @if($cap)
            <div class="post-capsule-wrap">
                <div class="capsule-pill">
                    <svg class="capsule-wave" xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 1800 158" preserveAspectRatio="none"
                         style="height:{{ $waveHeight }}px;">
                        <g class="wave-group">
                            <path fill="{{ $waveColor1 }}"
                                  d="M0,40 C100,10 200,70 300,40 C400,10 500,70 600,40 C700,10 800,70 900,40
                                     C1000,10 1100,70 1200,40 C1300,10 1400,70 1500,40 C1600,10 1700,70 1800,40
                                     L1800,158 L0,158 Z"/>
                            <path fill="{{ $waveColor1 }}" transform="translate(1800,0)"
                                  d="M0,40 C100,10 200,70 300,40 C400,10 500,70 600,40 C700,10 800,70 900,40
                                     C1000,10 1100,70 1200,40 C1300,10 1400,70 1500,40 C1600,10 1700,70 1800,40
                                     L1800,158 L0,158 Z"/>
                        </g>
                        <g class="wave-group-2">
                            <path fill="{{ $waveColor2 }}"
                                  d="M0,55 C120,25 240,80 360,55 C480,25 600,80 720,55 C840,25 960,80 1080,55
                                     C1200,25 1320,80 1440,55 C1560,25 1680,80 1800,55
                                     L1800,158 L0,158 Z"/>
                            <path fill="{{ $waveColor2 }}" transform="translate(1800,0)"
                                  d="M0,55 C120,25 240,80 360,55 C480,25 600,80 720,55 C840,25 960,80 1080,55
                                     C1200,25 1320,80 1440,55 C1560,25 1680,80 1800,55
                                     L1800,158 L0,158 Z"/>
                        </g>
                    </svg>
                    <div class="capsule-sheen"></div>
                    <div class="capsule-content">
                        <div class="capsule-left">
                            <div class="capsule-status-icon">{{ $statusIcon }}</div>
                        </div>
                        <div class="capsule-mid">
                            <div class="capsule-title">{{ $cap->title }}</div>
                            <div class="capsule-date">
                                <i class="bi bi-calendar3"></i>
                                {{ $cap->unlock_date->format('d M Y') }}
                            </div>
                        </div>
                        <div class="capsule-right">
                            <span class="badge-pill-gold">{{ ucfirst($cap->visibility) }}</span>
                            @if($cap->status === 'approved')
                                <span class="badge-pill-live">✅ Live</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Attached image --}}
            @if($post->image)
                <img src="{{ asset('storage/' . $post->image) }}" class="post-image" alt="">
            @endif

            {{-- Reactions --}}
            <div class="post-reactions">
                <div class="d-flex gap-2 flex-wrap">
                    @foreach(['inspired' => '❤️', 'goals' => '🔥', 'proud' => '🙌'] as $type => $emoji)
                    <form method="POST" action="{{ route('posts.reactions.toggle', $post) }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
                        <button type="submit" class="reaction-btn">
                            {{ $emoji }} {{ $post->reactionCount($type) }}
                        </button>
                    </form>
                    @endforeach
                </div>
                <span style="color:#8b95a8; font-size:0.78rem;">
                    <i class="bi bi-chat me-1"></i>{{ $post->comments->count() }}
                </span>
            </div>

            {{-- Comments --}}
            @if($post->comments->count() > 0)
            <div class="post-comments">
                @foreach($post->comments->take(2) as $comment)
                <div class="comment-item">
                    <img src="{{ $comment->user->avatar_url }}" class="comment-avatar" alt="">
                    <div class="comment-bubble">
                        <div class="comment-author">{{ $comment->user->name }}</div>
                        <div class="comment-body">{{ $comment->body }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Comment form --}}
            <form method="POST" action="{{ route('posts.comments.store', $post) }}" class="comment-form">
                @csrf
                <img src="{{ Auth::user()->avatar_url }}" class="comment-avatar" alt="">
                <input type="text" name="body" class="comment-input" placeholder="Write a comment...">
                <button type="submit" class="btn btn-gold btn-sm">Post</button>
            </form>

        </div>
        @endforeach

        <div class="mt-2 mb-4">{{ $posts->links() }}</div>
    @endif

</div>

@endsection

@section('scripts')
<script>
// Composer open/close
var trigger  = document.getElementById('composerTrigger');
var form     = document.getElementById('composerForm');
var placeholder = trigger.querySelector('.composer-placeholder');
var cancel   = document.getElementById('composerCancel');

trigger.addEventListener('click', function() {
    form.classList.add('open');
    placeholder.style.display = 'none';
});
cancel.addEventListener('click', function() {
    form.classList.remove('open');
    placeholder.style.display = 'block';
});

// Open composer automatically if there were validation errors
@if($errors->any())
    form.classList.add('open');
    placeholder.style.display = 'none';
@endif

// Image preview
function previewPostImage(input) {
    var preview = document.getElementById('postImagePreview');
    var img     = document.getElementById('postImagePreviewImg');
    var name    = document.getElementById('postImageName');
    if (input.files && input.files[0]) {
        name.textContent = input.files[0].name;
        var reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection