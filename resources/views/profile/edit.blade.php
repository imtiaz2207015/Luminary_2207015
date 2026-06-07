@extends('layouts.app')
@section('title', 'Profile')
@section('content')

<div class="topbar">
    <div>
        <div class="topbar-title">Profile Settings</div>
        <p style="color:#8b95a8; font-size:0.85rem; margin:0;">Manage your account</p>
    </div>
</div>

<div class="row justify-content-center g-4">
    <div class="col-lg-7">

        {{-- Update Profile --}}
        <div class="glass-card p-4 mb-4">
            <h5 style="font-family:'Playfair Display',serif; color:#f5f0e8; margin-bottom:1.5rem;">Profile Information</h5>
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf @method('PATCH')
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
                    <textarea name="bio" class="form-control" rows="3" placeholder="Tell others a little about yourself...">{{ old('bio', $user->bio) }}</textarea>
                </div>
                <button type="submit" class="btn btn-gold">Save Changes</button>
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