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

/* ── Font picker (quiet, dropdown-based) ── */
.font-field-row {
    display: flex;
    gap: 16px;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}
.font-field-col { flex: 1; min-width: 200px; }
.font-preview-box {
    border: 1px solid rgba(255,255,255,0.08);
    background: rgba(0,0,0,0.15);
    border-radius: 12px;
    padding: 20px 22px;
    margin-bottom: 1.75rem;
    color: #f5f0e8;
}
.font-preview-box .ppv-eyebrow {
    color: #8b95a8;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 10px;
}
.font-preview-box .ppv-title { font-weight: 700; font-size: 1.1rem; margin-bottom: 6px; }
.font-preview-box .ppv-body { color: #c8c0b8; }

/* ── Custom dark dropdown (replaces native <select> for theme consistency) ── */
.custom-select { position: relative; }
.custom-select-trigger {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    color: #f5f0e8;
    border-radius: 10px;
    padding: 0.65rem 1rem;
    font-size: 0.92rem;
    text-align: left;
    cursor: pointer;
    transition: border-color 0.18s, background 0.18s;
}
.custom-select-trigger:hover { background: rgba(255,255,255,0.07); }
.custom-select.open .custom-select-trigger {
    border-color: rgba(201,168,76,0.5);
    box-shadow: 0 0 0 0.2rem rgba(201,168,76,0.1);
}
.custom-select.open .custom-select-trigger i { transform: rotate(180deg); }
.custom-select-trigger i { color: #8b95a8; font-size: 0.85rem; transition: transform 0.18s; }
.custom-select-list {
    display: none;
    position: absolute;
    top: calc(100% + 6px);
    left: 0; right: 0;
    background: #11151f;
    border: 1px solid rgba(201,168,76,0.2);
    border-radius: 12px;
    box-shadow: 0 10px 32px rgba(0,0,0,0.5);
    padding: 6px;
    max-height: 260px;
    overflow-y: auto;
    z-index: 30;
}
.custom-select.open .custom-select-list { display: block; }
.custom-select-option {
    padding: 9px 12px;
    border-radius: 8px;
    color: #c8c0b8;
    font-size: 0.88rem;
    cursor: pointer;
    transition: background 0.15s;
}
.custom-select-option:hover { background: rgba(255,255,255,0.06); }
.custom-select-option.selected { color: #c9a84c; background: rgba(201,168,76,0.1); }

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
        <div class="settings-nav-item active" data-panel="panel-font" onclick="switchSettingsPanel('panel-font', this)">
            <i class="bi bi-fonts"></i> Font
        </div>
        <div class="settings-nav-item" data-panel="panel-profile" onclick="switchSettingsPanel('panel-profile', this)">
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

        {{-- ══════════════ FONT PANEL ══════════════ --}}
        <div class="settings-panel active" id="panel-font">
            <div class="glass-card p-4">
                <h5 style="font-family:'Playfair Display',serif; color:#f5f0e8; margin-bottom:0.25rem;">Font</h5>
                <p style="color:#8b95a8; font-size:0.85rem; margin-bottom:1.5rem;">Choose how text looks across Luminary.</p>

                <form method="POST" action="{{ route('profile.font') }}" id="fontForm">
                    @csrf @method('PATCH')

                    <div class="font-preview-box font-true-preview" id="fontPreviewBox">
                        <div class="ppv-eyebrow">Preview</div>
                        <div class="ppv-title">A letter to my future self</div>
                        <div class="ppv-body">This is how your posts, capsules, and the whole app will read.</div>
                    </div>

                    <div class="font-field-row">
                        <div class="font-field-col">
                            <label class="form-label">Style</label>
                            @php
                                $fontOptions = [
                                    'default'      => 'Default (DM Sans)',
                                    'playfair'     => 'Playfair Display',
                                    'dm_sans'      => 'DM Sans',
                                    'serif'        => 'Classic Serif',
                                    'georgia'      => 'Georgia',
                                    'merriweather' => 'Merriweather',
                                    'lora'         => 'Lora',
                                    'inter'        => 'Inter',
                                    'poppins'      => 'Poppins',
                                    'roboto_slab'  => 'Roboto Slab',
                                    'dancing_script' => 'Dancing Script (Cursive)',
                                ];
                                $fontFamilyMap = [
                                    'default'      => "'DM Sans', sans-serif",
                                    'playfair'     => "'Playfair Display', serif",
                                    'dm_sans'      => "'DM Sans', sans-serif",
                                    'serif'        => "Georgia, 'Times New Roman', serif",
                                    'georgia'      => "Georgia, serif",
                                    'merriweather' => "'Merriweather', serif",
                                    'lora'         => "'Lora', serif",
                                    'inter'        => "'Inter', sans-serif",
                                    'poppins'      => "'Poppins', sans-serif",
                                    'roboto_slab'  => "'Roboto Slab', serif",
                                    'dancing_script' => "'Dancing Script', cursive",
                                ];
                                $currentStyle = old('font_style', $user->font_style ?? 'default');
                            @endphp
                            <div class="custom-select" id="fontStyleDropdown">
                                <button type="button" class="custom-select-trigger" onclick="toggleCustomSelect('fontStyleDropdown')">
                                    <span id="fontStyleTriggerLabel" class="font-true-preview" style="font-family: {{ $fontFamilyMap[$currentStyle] ?? $fontFamilyMap['default'] }};">{{ $fontOptions[$currentStyle] ?? $fontOptions['default'] }}</span>
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                                <div class="custom-select-list">
                                    @foreach($fontOptions as $key => $label)
                                    <div class="custom-select-option font-true-preview {{ $currentStyle === $key ? 'selected' : '' }}"
                                         data-value="{{ $key }}"
                                         data-label="{{ $label }}"
                                         data-font-family="{{ $fontFamilyMap[$key] }}"
                                         style="font-family: {{ $fontFamilyMap[$key] }}; {{ $key === 'dancing_script' ? 'font-size:1.05rem;' : '' }}"
                                         onclick="pickFontStyle(this)">
                                        {{ $label }}
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="font-field-col">
                            <label class="form-label">Size</label>
                            @php
                                $sizeOptions = [
                                    'xs'     => 'Extra Small',
                                    'sm'     => 'Small',
                                    'medium' => 'Medium (Default)',
                                    'lg'     => 'Large',
                                    'xl'     => 'Extra Large',
                                ];
                                $sizePxMap = [
                                    'xs' => '14px', 'sm' => '15px', 'medium' => '16px', 'lg' => '18px', 'xl' => '20px',
                                ];
                                $currentSize = old('font_size', $user->font_size ?? 'medium');
                            @endphp
                            <div class="custom-select" id="fontSizeDropdown">
                                <button type="button" class="custom-select-trigger" onclick="toggleCustomSelect('fontSizeDropdown')">
                                    <span id="fontSizeTriggerLabel">{{ $sizeOptions[$currentSize] ?? $sizeOptions['medium'] }}</span>
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                                <div class="custom-select-list">
                                    @foreach($sizeOptions as $key => $label)
                                    <div class="custom-select-option {{ $currentSize === $key ? 'selected' : '' }}"
                                         data-value="{{ $key }}"
                                         data-label="{{ $label }}"
                                         data-size-px="{{ $sizePxMap[$key] }}"
                                         onclick="pickFontSize(this)">
                                        {{ $label }}
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="font_style" id="fontStyleInput" value="{{ $currentStyle }}">
                    <input type="hidden" name="font_size" id="fontSizeInput" value="{{ $currentSize }}">

                    <button type="submit" class="btn btn-gold" id="fontSaveBtn" disabled>Save Font Preferences</button>
                </form>
            </div>
        </div>

        {{-- ══════════════ PROFILE PANEL (existing content, unchanged) ══════════════ --}}
        <div class="settings-panel" id="panel-profile">

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

// ── Custom dropdown: open/close + selection + live preview ──
function toggleCustomSelect(id) {
    var el = document.getElementById(id);
    var wasOpen = el.classList.contains('open');
    document.querySelectorAll('.custom-select').forEach(function(s) { s.classList.remove('open'); });
    if (!wasOpen) el.classList.add('open');
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.custom-select')) {
        document.querySelectorAll('.custom-select').forEach(function(s) { s.classList.remove('open'); });
    }
});

function pickFontStyle(optEl) {
    document.querySelectorAll('#fontStyleDropdown .custom-select-option').forEach(function(o) { o.classList.remove('selected'); });
    optEl.classList.add('selected');
    var triggerLabel = document.getElementById('fontStyleTriggerLabel');
    triggerLabel.textContent = optEl.dataset.label;
    triggerLabel.style.fontFamily = optEl.dataset.fontFamily;
    document.getElementById('fontStyleInput').value = optEl.dataset.value;
    document.getElementById('fontPreviewBox').style.fontFamily = optEl.dataset.fontFamily;
    document.getElementById('fontStyleDropdown').classList.remove('open');
    checkFontFormDirty();
}
function pickFontSize(optEl) {
    document.querySelectorAll('#fontSizeDropdown .custom-select-option').forEach(function(o) { o.classList.remove('selected'); });
    optEl.classList.add('selected');
    document.getElementById('fontSizeTriggerLabel').textContent = optEl.dataset.label;
    document.getElementById('fontSizeInput').value = optEl.dataset.value;
    document.getElementById('fontPreviewBox').style.fontSize = optEl.dataset.sizePx;
    document.getElementById('fontSizeDropdown').classList.remove('open');
    checkFontFormDirty();
}

// Initialize preview box with current selection on load
(function() {
    var selectedStyle = document.querySelector('#fontStyleDropdown .custom-select-option.selected');
    var selectedSize  = document.querySelector('#fontSizeDropdown .custom-select-option.selected');
    var box = document.getElementById('fontPreviewBox');
    if (selectedStyle) box.style.fontFamily = selectedStyle.dataset.fontFamily;
    if (selectedSize)  box.style.fontSize   = selectedSize.dataset.sizePx;
})();

// ── Save button stays disabled until the user actually changes something ──
var originalFontStyle = document.getElementById('fontStyleInput').value;
var originalFontSize  = document.getElementById('fontSizeInput').value;

function checkFontFormDirty() {
    var nowStyle = document.getElementById('fontStyleInput').value;
    var nowSize  = document.getElementById('fontSizeInput').value;
    var isDirty  = (nowStyle !== originalFontStyle) || (nowSize !== originalFontSize);
    document.getElementById('fontSaveBtn').disabled = !isDirty;
}
</script>
@endsection