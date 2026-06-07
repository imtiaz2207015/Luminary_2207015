<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luminary Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { min-height:100vh; background:#0a0f1e; font-family:'DM Sans',sans-serif; display:flex; align-items:center; justify-content:center; background-image:radial-gradient(ellipse at 30% 50%, rgba(201,168,76,0.07) 0%, transparent 60%); }
        .card { background:rgba(255,255,255,0.04); border:1px solid rgba(201,168,76,0.2); border-radius:20px; padding:2.5rem; width:100%; max-width:420px; }
        .brand { font-family:'Playfair Display',serif; color:#c9a84c; font-size:1.8rem; text-align:center; }
        .brand-sub { color:#8b95a8; font-size:0.8rem; text-align:center; margin-bottom:2rem; }
        .admin-badge { background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.2); color:#c9a84c; border-radius:20px; padding:4px 14px; font-size:0.78rem; display:inline-block; margin-bottom:1.5rem; }
        .form-label { color:#8b95a8; font-size:0.85rem; }
        .form-control { background:rgba(255,255,255,0.05)!important; border:1px solid rgba(255,255,255,0.1)!important; color:#f5f0e8!important; border-radius:10px; padding:0.7rem 1rem; }
        .form-control:focus { border-color:rgba(201,168,76,0.5)!important; box-shadow:0 0 0 0.2rem rgba(201,168,76,0.1)!important; }
        .form-control::placeholder { color:#8b95a8!important; }
        .btn-gold { background:linear-gradient(135deg,#c9a84c,#f0c060); color:#0a0f1e; border:none; font-weight:700; border-radius:10px; padding:0.65rem; width:100%; font-size:1rem; transition:all 0.2s; }
        .btn-gold:hover { transform:translateY(-1px); box-shadow:0 4px 20px rgba(201,168,76,0.35); color:#0a0f1e; }
        .back-link { color:#8b95a8; text-decoration:none; font-size:0.82rem; }
        .back-link:hover { color:#c9a84c; }
        .alert-error { background:rgba(220,53,69,0.1); border:1px solid rgba(220,53,69,0.2); color:#ff6b6b; border-radius:10px; padding:0.75rem; font-size:0.85rem; margin-bottom:1rem; }
    </style>
</head>
<body>
<div class="card">
    <div class="text-center mb-2">
        <div style="font-size:2.5rem;">🧠</div>
        <div class="brand mt-1">Luminary</div>
        <div class="brand-sub">Control Panel Access</div>
        <div class="admin-badge">🔐 Admin Only</div>
    </div>
    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Admin Email</label>
            <input type="email" name="email" class="form-control" placeholder="admin@luminary.app" value="{{ old('email') }}" required autofocus>
            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn-gold mb-3">Enter Admin Panel</button>
    </form>
    <div class="text-center">
        <a href="{{ route('login') }}" class="back-link">← Back to User Login</a>
    </div>
</div>
</body>
</html>