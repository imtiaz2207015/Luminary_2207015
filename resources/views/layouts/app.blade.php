<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luminary – @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #0a0f1e;
            --navy2: #111827;
            --navy3: #1a2236;
            --gold: #c9a84c;
            --gold2: #f0c060;
            --teal: #4ecdc4;
            --text: #f5f0e8;
            --muted: #8b95a8;
            --sidebar-w: 260px;
        }
        * { box-sizing: border-box; }
        body { background: var(--navy); color: var(--text); font-family: 'DM Sans', sans-serif; margin: 0; min-height: 100vh; }
        h1,h2,h3,.display-font { font-family: 'Playfair Display', serif; }

        /* Sidebar */
        .sidebar {
            position: fixed; top: 0; left: 0; width: var(--sidebar-w);
            height: 100vh; background: var(--navy2);
            border-right: 1px solid rgba(201,168,76,0.15);
            display: flex; flex-direction: column;
            z-index: 100; transition: transform 0.3s;
        }
        .sidebar-brand {
            padding: 1.5rem 1.5rem 1rem;
            border-bottom: 1px solid rgba(201,168,76,0.1);
        }
        .sidebar-brand h2 {
            font-family: 'Playfair Display', serif;
            color: var(--gold); font-size: 1.5rem; margin: 0;
        }
        .sidebar-brand p { color: var(--muted); font-size: 0.78rem; margin: 2px 0 0; }
        .sidebar-nav { flex: 1; padding: 1rem 0; overflow-y: auto; }
        .nav-item-custom { display: flex; align-items: center; gap: 12px; padding: 0.7rem 1.5rem;
            color: var(--muted); text-decoration: none; font-size: 0.9rem;
            border-left: 3px solid transparent; transition: all 0.2s; cursor: pointer; }
        .nav-item-custom:hover { color: var(--text); background: rgba(255,255,255,0.04); border-left-color: rgba(201,168,76,0.4); }
        .nav-item-custom.active { color: var(--gold); background: rgba(201,168,76,0.08); border-left-color: var(--gold); }
        .nav-item-custom i { font-size: 1.1rem; width: 20px; }
        .nav-section-title { padding: 0.5rem 1.5rem; font-size: 0.7rem; text-transform: uppercase;
            letter-spacing: 1.5px; color: var(--muted); margin-top: 0.5rem; }
        .sidebar-footer { padding: 1rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.06); }
        .user-card { display: flex; align-items: center; gap: 10px; }
        .user-avatar { width: 36px; height: 36px; border-radius: 50%; border: 2px solid var(--gold); object-fit: cover; }
        .user-name { font-size: 0.85rem; font-weight: 600; color: var(--text); }
        .user-role { font-size: 0.72rem; color: var(--muted); }

        /* Main content */
        .main-content { margin-left: var(--sidebar-w); min-height: 100vh; padding: 2rem; }

        /* Top bar */
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .topbar-title { font-family: 'Playfair Display', serif; font-size: 1.8rem; color: var(--text); }
        .topbar-actions { display: flex; align-items: center; gap: 1rem; }

        /* Cards */
        .glass-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px; backdrop-filter: blur(10px);
            transition: border-color 0.2s, transform 0.2s;
        }
        .glass-card:hover { border-color: rgba(201,168,76,0.25); transform: translateY(-2px); }

        .stat-card { padding: 1.5rem; border-radius: 16px; }
        .stat-card .stat-num { font-size: 2.5rem; font-weight: 700; font-family: 'Playfair Display', serif; }
        .stat-card .stat-label { font-size: 0.82rem; color: var(--muted); margin-top: 4px; }

        /* Buttons */
        .btn-gold { background: linear-gradient(135deg, var(--gold), var(--gold2)); color: var(--navy); border: none; font-weight: 600; border-radius: 10px; padding: 0.5rem 1.25rem; transition: all 0.2s; }
        .btn-gold:hover { transform: translateY(-1px); box-shadow: 0 4px 20px rgba(201,168,76,0.35); color: var(--navy); }
        .btn-ghost { background: rgba(255,255,255,0.06); color: var(--text); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 0.5rem 1.25rem; transition: all 0.2s; }
        .btn-ghost:hover { background: rgba(255,255,255,0.1); color: var(--text); border-color: rgba(201,168,76,0.3); }
        .btn-danger-soft { background: rgba(220,53,69,0.15); color: #ff6b6b; border: 1px solid rgba(220,53,69,0.2); border-radius: 8px; padding: 0.4rem 1rem; font-size: 0.85rem; transition: all 0.2s; }
        .btn-danger-soft:hover { background: rgba(220,53,69,0.25); color: #ff6b6b; }

        /* Badges */
        .badge-gold { background: rgba(201,168,76,0.15); color: var(--gold); border: 1px solid rgba(201,168,76,0.3); border-radius: 20px; padding: 3px 12px; font-size: 0.75rem; font-weight: 600; }
        .badge-teal { background: rgba(78,205,196,0.15); color: var(--teal); border: 1px solid rgba(78,205,196,0.3); border-radius: 20px; padding: 3px 12px; font-size: 0.75rem; }
        .badge-locked { background: rgba(220,53,69,0.15); color: #ff6b6b; border: 1px solid rgba(220,53,69,0.25); border-radius: 20px; padding: 3px 12px; font-size: 0.75rem; }
        .badge-unlocked { background: rgba(40,167,69,0.15); color: #51cf66; border: 1px solid rgba(40,167,69,0.25); border-radius: 20px; padding: 3px 12px; font-size: 0.75rem; }
        .badge-pending { background: rgba(255,193,7,0.15); color: #ffd43b; border: 1px solid rgba(255,193,7,0.25); border-radius: 20px; padding: 3px 12px; font-size: 0.75rem; }

        /* Forms */
        .form-control, .form-select {
            background: rgba(255,255,255,0.05) !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            color: var(--text) !important; border-radius: 10px; padding: 0.65rem 1rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: rgba(201,168,76,0.5) !important;
            box-shadow: 0 0 0 0.2rem rgba(201,168,76,0.1) !important;
            background: rgba(255,255,255,0.07) !important;
        }
        .form-control::placeholder { color: var(--muted) !important; }
        .form-label { color: var(--muted); font-size: 0.85rem; font-weight: 500; margin-bottom: 6px; }
        textarea.form-control { resize: vertical; min-height: 120px; }

        /* Alerts */
        .alert-success-custom { background: rgba(40,167,69,0.1); border: 1px solid rgba(40,167,69,0.2); color: #51cf66; border-radius: 10px; }
        .alert-error-custom { background: rgba(220,53,69,0.1); border: 1px solid rgba(220,53,69,0.2); color: #ff6b6b; border-radius: 10px; }

        /* Capsule card */
        .capsule-card { border-radius: 16px; overflow: hidden; }
        .capsule-card-header { padding: 1rem 1.25rem; }
        .capsule-card-body { padding: 1rem 1.25rem; }
        .capsule-card-footer { padding: 0.75rem 1.25rem; border-top: 1px solid rgba(255,255,255,0.06); }
        .countdown-badge { background: rgba(201,168,76,0.1); color: var(--gold); border: 1px solid rgba(201,168,76,0.2); border-radius: 8px; padding: 4px 10px; font-size: 0.78rem; font-family: monospace; }

        /* Notification dot */
        .notif-dot { width: 8px; height: 8px; background: var(--gold); border-radius: 50%; display: inline-block; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--navy2); }
        ::-webkit-scrollbar-thumb { background: rgba(201,168,76,0.3); border-radius: 3px; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 1rem; }
        }
    </style>
    @yield('styles')
</head>
<body>
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <h2>✨ Luminary</h2>
        <p>Your time capsule platform</p>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section-title">Main</div>
        <a href="{{ route('dashboard') }}" class="nav-item-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <a href="{{ route('capsules.index') }}" class="nav-item-custom {{ request()->routeIs('capsules.*') ? 'active' : '' }}">
            <i class="bi bi-hourglass-split"></i> My Capsules
        </a>
        <a href="{{ route('friends.index') }}" class="nav-item-custom {{ request()->routeIs('friends.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Friends
        </a>
        <a href="{{ route('newsfeed') }}" class="nav-item-custom {{ request()->routeIs('newsfeed') ? 'active' : '' }}">
            <i class="bi bi-globe2"></i> Newsfeed
        </a>
        <div class="nav-section-title">Account</div>
        <a href="{{ route('notifications') }}" class="nav-item-custom {{ request()->routeIs('notifications') ? 'active' : '' }}">
            <i class="bi bi-bell"></i> Notifications
            @php $unread = Auth::user()->notifications()->whereNull('read_at')->count(); @endphp
            @if($unread > 0) <span class="ms-auto badge-gold">{{ $unread }}</span> @endif
        </a>
        <a href="{{ route('profile.edit') }}" class="nav-item-custom {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="bi bi-person"></i> Profile
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-card">
            <img src="{{ Auth::user()->avatar_url }}" class="user-avatar" alt="avatar">
            <div>
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">Member</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="ms-auto">
                @csrf
                <button type="submit" class="btn p-0 text-muted" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<div class="main-content">
    @if(session('success'))
        <div class="alert alert-success-custom alert-dismissible fade show d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error-custom alert-dismissible fade show d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>