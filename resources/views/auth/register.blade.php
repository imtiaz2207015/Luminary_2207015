<x-guest-layout>
<form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" placeholder="Your name" value="{{ old('name') }}" required autofocus>
        @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="you@example.com" value="{{ old('email') }}" required>
        @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Min 8 characters" required>
        @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>
    <div class="mb-4">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
    </div>
    <button type="submit" class="btn-gold mb-3">Create Account</button>
</form>
<hr class="divider">
<div class="text-center" style="color:#8b95a8; font-size:0.85rem;">
    Already have an account? <a href="{{ route('login') }}" class="auth-link">Sign in</a>
</div>
</x-guest-layout>