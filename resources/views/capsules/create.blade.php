@extends('layouts.app')
@section('title', 'New Capsule')
@section('content')

<div class="topbar">
    <div>
        <div class="topbar-title">Seal a Capsule</div>
        <p style="color:#8b95a8; font-size:0.85rem; margin:0;">Write to your future self — locked until you're ready.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="glass-card p-4">
            <form method="POST" action="{{ route('capsules.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="form-label">Capsule Title</label>
                    <input type="text" name="title" class="form-control form-control-lg"
                           placeholder="e.g. My dream startup, Letter to 2030 me..." value="{{ old('title') }}" required>
                    @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Write to Your Future Self</label>
                    <textarea name="content" class="form-control" rows="10"
                              placeholder="Pour your heart out — your goals, dreams, fears, hopes. Your future self will thank you...">{{ old('content') }}</textarea>
                    @error('content') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Unlock Date</label>
                        <input type="date" name="unlock_date" class="form-control"
                               value="{{ old('unlock_date') }}" min="{{ now()->addDay()->format('Y-m-d') }}" required>
                        @error('unlock_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Visibility</label>
                        <select name="visibility" class="form-select">
                            <option value="only_me" {{ old('visibility') == 'only_me' ? 'selected' : '' }}>🔒 Only Me</option>
                            <option value="friends" {{ old('visibility') == 'friends' ? 'selected' : '' }}>👥 Friends Only</option>
                            <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>🌍 Public (after review)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Lock Type</label>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="d-block p-3 rounded-3 cursor-pointer" style="border:1px solid rgba(220,53,69,0.3); background:rgba(220,53,69,0.05); cursor:pointer;">
                                <input type="radio" name="lock_type" value="locked" {{ old('lock_type','locked') == 'locked' ? 'checked' : '' }} class="me-2">
                                <span style="color:#ff6b6b; font-weight:600;">🔐 Sealed</span>
                                <p style="color:#8b95a8; font-size:0.78rem; margin:4px 0 0 20px;">Cannot be edited after creation</p>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="d-block p-3 rounded-3" style="border:1px solid rgba(78,205,196,0.3); background:rgba(78,205,196,0.05); cursor:pointer;">
                                <input type="radio" name="lock_type" value="draft" {{ old('lock_type') == 'draft' ? 'checked' : '' }} class="me-2">
                                <span style="color:#4ecdc4; font-weight:600;">🔓 Draft</span>
                                <p style="color:#8b95a8; font-size:0.78rem; margin:4px 0 0 20px;">You can still edit before deadline</p>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Attach Files <span style="color:#8b95a8; font-weight:400;">(optional)</span></label>
                    <input type="file" name="files[]" class="form-control" multiple accept="image/*,.pdf,.doc,.docx">
                </div>

                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <input type="checkbox" name="is_group" id="isGroup" class="form-check-input" style="background:transparent; border-color:rgba(201,168,76,0.4);" {{ old('is_group') ? 'checked' : '' }}>
                        <label for="isGroup" class="form-label mb-0" style="cursor:pointer;">Make this a Group Capsule</label>
                    </div>
                    <div id="groupFields" style="{{ old('is_group') ? '' : 'display:none;' }}">
                        <input type="text" name="group_name" class="form-control mb-2" placeholder="Group name" value="{{ old('group_name') }}">
                        <input type="text" name="invite_emails" class="form-control" placeholder="Invite by email (comma separated): a@x.com, b@x.com" value="{{ old('invite_emails') }}">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('capsules.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-gold"><i class="bi bi-lock-fill me-1"></i> Seal Capsule</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('isGroup').addEventListener('change', function() {
    document.getElementById('groupFields').style.display = this.checked ? 'block' : 'none';
});
</script>
@endsection