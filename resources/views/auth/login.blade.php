<x-guest-layout>
@if(session('error'))
    <div class="alert-error">{{ session('error') }}</div>
@endif
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="you@example.com" value="{{ old('email') }}" required autofocus>
        @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>
    <div class="mb-4">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <label class="d-flex align-items-center gap-2" style="color:#8b95a8; font-size:0.85rem; cursor:pointer;">
            <input type="checkbox" name="remember" class="form-check-input" style="background:transparent; border-color:rgba(255,255,255,0.2);"> Remember me
        </label>
        @if(Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="auth-link">Forgot password?</a>
        @endif
    </div>
    <button type="submit" class="btn-gold mb-3">Sign In</button>
</form>
<hr class="divider">
<div class="text-center mb-3" style="color:#8b95a8; font-size:0.85rem;">
    Don't have an account? <a href="{{ route('register') }}" class="auth-link">Create one</a>
</div>
<a href="{{ route('admin.login') }}" class="admin-btn">Admin Login</a>
</x-guest-layout>