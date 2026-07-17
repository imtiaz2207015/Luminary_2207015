@extends('layouts.app')
@section('title', 'Settings')
@section('content')

<style>
.profile-view-row {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 14px 0;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}
.profile-view-row:last-child { border-bottom: none; }
.profile-view-label {
    color: #8b95a8;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    min-width: 110px;
}
.profile-view-value {
    color: #f5f0e8;
    font-size: 0.95rem;
    flex: 1;
}
.profile-view-value.bio-text {
    white-space: pre-wrap;
    color: #c8c0b8;
}
.profile-avatar-current {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(201,168,76,0.3);
}
.profile-avatar-placeholder {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: rgba(201,168,76,0.12);
    border: 2px solid rgba(201,168,76,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #c9a84c;
    font-family: 'Playfair Display', serif;
    font-size: 1.4rem;
    font-weight: 700;
}
.profile-edit-toggle-btn {
    font-size: 0.78rem;
    padding: 5px 16px;
    border-radius: 20px;
    border: 1px solid rgba(201,168,76,0.35);
    background: rgba(201,168,76,0.1);
    color: #c9a84c;
    cursor: pointer;
    transition: background 0.18s, color 0.18s;
}
.profile-edit-toggle-btn:hover {
    background: rgba(201,168,76,0.2);
}
#profileEditForm { display: none; }
.avatar-edit-row {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 1.25rem;
}
.avatar-file-label {
    font-size: 0.8rem;
    color: #4ecdc4;
    cursor: pointer;
    border: 1px solid rgba(78,205,196,0.3);
    border-radius: 20px;
    padding: 6px 14px;
    display: inline-block;
    transition: background 0.18s;
}
.avatar-file-label:hover { background: rgba(78,205,196,0.1); }
.avatar-file-input { display: none; }
.avatar-filename {
    font-size: 0.78rem;
    color: #8b95a8;
    margin-left: 8px;
}

/* ── Settings layout ── */
.settings-shell {
    display: flex;
    gap: 28px;
    align-items: flex-start;
}
.settings-nav {
    width: 210px;
    flex-shrink: 0;
    position: sticky;
    top: 2rem;
}
.settings-nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 16px;
    border-radius: 10px;
    color: #8b95a8;
    font-size: 0.88rem;
    cursor: pointer;
    transition: all 0.18s;
    margin-bottom: 4px;
    border: 1px solid transparent;
}
.settings-nav-item:hover { background: rgba(255,255,255,0.05); color: #f5f0e8; }
.settings-nav-item.active {
    background: rgba(201,168,76,0.1);
    border-color: rgba(201,168,76,0.25);
    color: #c9a84c;
}
.settings-nav-item i { font-size: 1rem; width: 18px; }
.settings-panel { flex: 1; min-width: 0; display: none; }
.settings-panel.active { display: block; }

.btn-gold:disabled {
    opacity: 0.35;
    cursor: not-allowed;
    filter: grayscale(0.4);
}
.btn-gold:disabled:hover { transform: none; box-shadow: none; }
</style>

<div class="topbar">
    <div>
        <div class="topbar-title">Settings</div>
        <p style="color:#8b95a8; font-size:0.85rem; margin:0;">Manage your account and preferences</p>
    </div>
</div>

<div class="settings-shell">

    {{-- ── Settings side nav ── --}}
    <div class="settings-nav">
        <div class="settings-nav-item active" data-panel="panel-profile" onclick="switchSettingsPanel('panel-profile', this)">
            <i class="bi bi-person"></i> Profile
        </div>
        <div class="settings-nav-item" data-panel="panel-privacy" onclick="switchSettingsPanel('panel-privacy', this)">
            <i class="bi bi-shield-lock"></i> Privacy
        </div>
        <div class="settings-nav-item" data-panel="panel-policy" onclick="switchSettingsPanel('panel-policy', this)">
            <i class="bi bi-file-earmark-text"></i> Privacy Policy
        </div>
    </div>

    <div style="flex:1; min-width:0;">

        {{-- ══════════════ PROFILE PANEL ══════════════ --}}
        <div class="settings-panel active" id="panel-profile">

            {{-- Profile Information --}}
            <div class="glass-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 style="font-family:'Playfair Display',serif; color:#f5f0e8; margin:0;">Profile Information</h5>
                    <button type="button" id="profileEditToggle" class="profile-edit-toggle-btn">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </button>
                </div>

                {{-- VIEW MODE --}}
                <div id="profileViewMode">
                    <div class="profile-view-row">
                        <span class="profile-view-label">Photo</span>
                        <span class="profile-view-value">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" class="profile-avatar-current" alt="Avatar">
                            @else
                                <div class="profile-avatar-placeholder">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            @endif
                        </span>
                    </div>
                    <div class="profile-view-row">
                        <span class="profile-view-label">Name</span>
                        <span class="profile-view-value">{{ $user->name }}</span>
                    </div>
                    <div class="profile-view-row">
                        <span class="profile-view-label">Email</span>
                        <span class="profile-view-value">{{ $user->email }}</span>
                    </div>
                    <div class="profile-view-row">
                        <span class="profile-view-label">Bio</span>
                        <span class="profile-view-value bio-text">{{ $user->bio ?: 'No bio added yet.' }}</span>
                    </div>
                </div>

                {{-- EDIT MODE --}}
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileEditForm">
                    @csrf @method('PATCH')

                    <div class="avatar-edit-row">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" class="profile-avatar-current" id="avatarPreview" alt="Avatar">
                        @else
                            <div class="profile-avatar-placeholder" id="avatarPreviewPlaceholder">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            <img src="" class="profile-avatar-current" id="avatarPreview" alt="Avatar" style="display:none;">
                        @endif
                        <div>
                            <label for="avatarInput" class="avatar-file-label">
                                <i class="bi bi-upload me-1"></i> Choose photo
                            </label>
                            <input type="file" name="avatar" id="avatarInput" class="avatar-file-input" accept="image/*">
                            <span class="avatar-filename" id="avatarFilename"></span>
                            @error('avatar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Bio <span style="color:#8b95a8; font-weight:400;">(optional)</span></label>
                        <textarea name="bio" class="form-control" rows="3" maxlength="500" placeholder="Tell others a little about yourself...">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-gold">Save Changes</button>
                        <button type="button" id="profileEditCancel" class="btn btn-ghost">Cancel</button>
                    </div>
                </form>
            </div>

            {{-- Update Password --}}
            <div class="glass-card p-4 mb-4">
                <h5 style="font-family:'Playfair Display',serif; color:#f5f0e8; margin-bottom:1.5rem;">Change Password</h5>
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                        @error('current_password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" required>
                        @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-gold">Update Password</button>
                </form>
            </div>

            {{-- Delete Account --}}
            <div class="glass-card p-4" style="border-color:rgba(220,53,69,0.2);">
                <h5 style="font-family:'Playfair Display',serif; color:#ff6b6b; margin-bottom:0.5rem;">Danger Zone</h5>
                <p style="color:#8b95a8; font-size:0.85rem; margin-bottom:1.5rem;">Once deleted, your account and all capsules are permanently gone.</p>
                <button class="btn btn-danger-soft" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete Account</button>
            </div>
        </div>

        {{-- ══════════════ PRIVACY PANEL (placeholder) ══════════════ --}}
        <div class="settings-panel" id="panel-privacy">
            <div class="glass-card p-4">
                <h5 style="font-family:'Playfair Display',serif; color:#f5f0e8; margin-bottom:0.5rem;">Privacy</h5>
                <p style="color:#8b95a8; font-size:0.88rem; margin:0;">Privacy controls are coming soon. This is where you'll manage who can see your capsules, posts, and profile by default.</p>
            </div>
        </div>

        {{-- ══════════════ PRIVACY POLICY PANEL ══════════════ --}}
        <div class="settings-panel" id="panel-policy">
            <div class="glass-card p-4">
                <h5 style="font-family:'Playfair Display',serif; color:#f5f0e8; margin-bottom:0.5rem;">Privacy Policy</h5>
                <p style="color:#8b95a8; font-size:0.85rem; line-height:1.8;">
                    Luminary stores the content you create — capsules, posts, comments, and profile information —
                    to provide the time capsule experience. We do not sell your data to third parties.
                    Uploaded images are stored securely and are only visible according to the privacy settings
                    you choose for each capsule or post. You may delete your account at any time from the
                    Profile tab, which permanently removes your data from our systems.
                </p>
                <p style="color:#8b95a8; font-size:0.78rem; margin-top:1rem;">This is placeholder policy text — replace with your actual policy before going live.</p>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background:#111827; border:1px solid rgba(255,255,255,0.1);">
            <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,0.08);">
                <h5 class="modal-title" style="color:#f5f0e8;">Delete Account</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf @method('DELETE')
                <div class="modal-body">
                    <p style="color:#8b95a8; font-size:0.88rem;">Please enter your password to confirm deletion.</p>
                    <input type="password" name="password" class="form-control" placeholder="Your password" required>
                </div>
                <div class="modal-footer" style="border-top:1px solid rgba(255,255,255,0.08);">
                    <button type="button" class="btn btn-ghost btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger-soft btn-sm">Delete Forever</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
(function() {
    var viewMode   = document.getElementById('profileViewMode');
    var editForm   = document.getElementById('profileEditForm');
    var editToggle = document.getElementById('profileEditToggle');
    var cancelBtn  = document.getElementById('profileEditCancel');

    var hasErrors = {{ $errors->any() ? 'true' : 'false' }};

    function enterEditMode() {
        viewMode.style.display = 'none';
        editForm.style.display = 'block';
        editToggle.style.display = 'none';
    }
    function exitEditMode() {
        viewMode.style.display = 'block';
        editForm.style.display = 'none';
        editToggle.style.display = 'inline-block';
    }

    editToggle.addEventListener('click', enterEditMode);
    cancelBtn.addEventListener('click', exitEditMode);

    if (hasErrors) {
        enterEditMode();
        switchSettingsPanel('panel-profile', document.querySelector('[data-panel="panel-profile"]'));
    }

    // Live avatar preview when a new file is chosen
    var avatarInput = document.getElementById('avatarInput');
    var avatarPreview = document.getElementById('avatarPreview');
    var avatarPlaceholder = document.getElementById('avatarPreviewPlaceholder');
    var avatarFilename = document.getElementById('avatarFilename');

    avatarInput.addEventListener('change', function() {
        var file = this.files && this.files[0];
        if (!file) return;
        avatarFilename.textContent = file.name;
        var reader = new FileReader();
        reader.onload = function(e) {
            avatarPreview.src = e.target.result;
            avatarPreview.style.display = 'block';
            if (avatarPlaceholder) avatarPlaceholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });
})();

// ── Settings tab switching ──
function switchSettingsPanel(panelId, navEl) {
    document.querySelectorAll('.settings-panel').forEach(function(p) { p.classList.remove('active'); });
    document.getElementById(panelId).classList.add('active');
    document.querySelectorAll('.settings-nav-item').forEach(function(n) { n.classList.remove('active'); });
    navEl.classList.add('active');
}

</script>
@endsection