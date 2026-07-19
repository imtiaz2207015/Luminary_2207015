<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Luminary – {{ $title ?? '' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&family=Merriweather:wght@400;700&family=Lora:wght@400;600&family=Inter:wght@400;500;600&family=Poppins:wght@400;500;600&family=Roboto+Slab:wght@400;600&family=Dancing+Script:wght@500;700&display=swap" rel="stylesheet">
    <style>
        a, button, .btn-gold, .btn-ghost, .btn-danger-soft, .nav-item-custom, .btn-capsule {
    cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26"><path d="M13 0 L15.5 10.5 L26 13 L15.5 15.5 L13 26 L10.5 15.5 L0 13 L10.5 10.5 Z" fill="%23f0c060" stroke="%230a0f1e" stroke-width="0.6"/></svg>') 13 13, pointer;
}
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
  body {
    background:
        radial-gradient(circle 600px at 10% 0%, rgba(56,111,171,0.35), transparent 70%),
        radial-gradient(circle 700px at 100% 100%, rgba(201,168,76,0.22), transparent 70%),
        var(--navy);
    background-attachment: fixed;
    color: var(--text);
    font-family: 'DM Sans', sans-serif;
    margin: 0;
    min-height: 100vh;
    cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 0 L14 10 L24 12 L14 14 L12 24 L10 14 L0 12 L10 10 Z" fill="%23c9a84c" stroke="%230a0f1e" stroke-width="0.5"/></svg>') 12 12, auto;
}
        h1,h2,h3,.display-font { font-family: 'Playfair Display', serif; }

        /* Sidebar */
        .sidebar {
    position: fixed; top: 0; left: 0; width: var(--sidebar-w);
    height: 100vh;
    background:
        radial-gradient(circle 400px at 50% 0%, rgba(56,111,171,0.15), transparent 70%),
        radial-gradient(circle 400px at 50% 100%, rgba(201,168,76,0.10), transparent 70%),
        var(--navy2);
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
        .btn-gold {
    background: linear-gradient(135deg, var(--gold), var(--gold2));
    color: var(--navy); border: none; font-weight: 600; border-radius: 10px;
    padding: 0.5rem 1.25rem;
    box-shadow: 0 2px 8px rgba(201,168,76,0.15);
    transition: all 0.2s;
}
.btn-gold:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 20px rgba(201,168,76,0.35);
    color: var(--navy);
}
        .btn-ghost {
    background: rgba(255,255,255,0.06); color: var(--text);
    border: 1px solid rgba(255,255,255,0.1); border-radius: 10px;
    padding: 0.5rem 1.25rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    transition: all 0.2s;
}
.btn-ghost:hover {
    background: rgba(255,255,255,0.1); color: var(--text);
    border-color: rgba(201,168,76,0.3);
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(0,0,0,0.25);
}
        .btn-danger-soft { background: rgba(220,53,69,0.15); color: #ff6b6b; border: 1px solid rgba(220,53,69,0.2); border-radius: 8px; padding: 0.4rem 1rem; font-size: 0.85rem; transition: all 0.2s; }
        .btn-danger-soft:hover { background: rgba(220,53,69,0.25); color: #ff6b6b; }

        /* Badges */
        .badge-gold { background: rgba(201,168,76,0.15); color: var(--gold); border: 1px solid rgba(201,168,76,0.3); border-radius: 20px; padding: 3px 12px; font-size: 0.75rem; font-weight: 600; box-shadow: inset 0 1px 2px rgba(201,168,76,0.1);  }
        .badge-teal { background: rgba(78,205,196,0.15); color: var(--teal); border: 1px solid rgba(78,205,196,0.3); border-radius: 20px; padding: 3px 12px; font-size: 0.75rem; }
        .badge-locked { background: rgba(220,53,69,0.15); color: #ff6b6b; border: 1px solid rgba(220,53,69,0.25); border-radius: 20px; padding: 3px 12px; font-size: 0.75rem; }
        .badge-unlocked { background: rgba(40,167,69,0.15); color: #51cf66; border: 1px solid rgba(40,167,69,0.25); border-radius: 20px; padding: 3px 12px; font-size: 0.75rem; }
        .badge-pending { background: rgba(255,193,7,0.15); color: #ffd43b; border: 1px solid rgba(255,193,7,0.25); border-radius: 20px; padding: 3px 12px; font-size: 0.75rem; }

        /* Forms */
        .form-control, .form-select {
    background: rgba(255,255,255,0.05) !important;
    border: 1px solid rgba(255,255,255,0.1) !important;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.25) !important;
    color: var(--text) !important; border-radius: 10px; padding: 0.65rem 1rem;
    transition: box-shadow 0.2s, border-color 0.2s;
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


        .capsule-countdown {
    animation: pulse-glow 2.5s ease-in-out infinite;
}
@keyframes pulse-glow {
    0%, 100% { box-shadow: 0 0 0 rgba(201,168,76,0); }
    50% { box-shadow: 0 0 8px rgba(201,168,76,0.3); }
}


.countdown-timer {
    transition: color 0.3s;
}

.nav-item-custom.active {
    animation: subtle-breathe 3s ease-in-out infinite;
}
@keyframes subtle-breathe {
    0%, 100% { background: rgba(201,168,76,0.08); }
    50% { background: rgba(201,168,76,0.13); }
}

body { animation: drift 25s ease-in-out infinite alternate; background-size: 200% 200%; }
@keyframes drift {
    from { background-position: 0% 0%; }
    to { background-position: 10% 10%; }
}
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

        .ambient-field {
    position: fixed; inset: 0; pointer-events: none; z-index: 0; overflow: hidden;
}
.ambient-star, .ambient-capsule {
    position: absolute;
    animation: ambient-float 6s ease-in-out infinite;
}
.ambient-star.small { animation-duration: 8s; }
@keyframes ambient-float {
    0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.7; }
    50% { transform: translateY(-10px) rotate(15deg); opacity: 1; }
}

.ambient-moon, .ambient-sun {
    position: absolute;
    animation: ambient-glow 8s ease-in-out infinite;
}
@keyframes ambient-glow {
    0%, 100% { opacity: 0.7; }
    50% { opacity: 1; }
}
    </style>
    {{ $styles ?? '' }}
</head>
<body>




<div class="ambient-field" aria-hidden="true">
    <svg class="ambient-star" style="top:8%; left:15%; animation-delay:0s;" width="14" height="14" viewBox="0 0 14 14">
        <path d="M7 0 L8.5 5.5 L14 7 L8.5 8.5 L7 14 L5.5 8.5 L0 7 L5.5 5.5 Z" fill="#c9a84c" opacity="0.5"/>
    </svg>
    <svg class="ambient-star small" style="top:18%; left:88%; animation-delay:0.6s;" width="9" height="9" viewBox="0 0 14 14">
        <path d="M7 0 L8.5 5.5 L14 7 L8.5 8.5 L7 14 L5.5 8.5 L0 7 L5.5 5.5 Z" fill="#4ecdc4" opacity="0.45"/>
    </svg>
    <svg class="ambient-star" style="top:70%; left:6%; animation-delay:1.2s;" width="12" height="12" viewBox="0 0 14 14">
        <path d="M7 0 L8.5 5.5 L14 7 L8.5 8.5 L7 14 L5.5 8.5 L0 7 L5.5 5.5 Z" fill="#c9a84c" opacity="0.4"/>
    </svg>
    <svg class="ambient-star small" style="top:45%; left:95%; animation-delay:0.9s;" width="8" height="8" viewBox="0 0 14 14">
        <path d="M7 0 L8.5 5.5 L14 7 L8.5 8.5 L7 14 L5.5 8.5 L0 7 L5.5 5.5 Z" fill="#c9a84c" opacity="0.4"/>
    </svg>
    <svg class="ambient-star" style="top:32%; left:40%; animation-delay:1.6s;" width="10" height="10" viewBox="0 0 14 14">
        <path d="M7 0 L8.5 5.5 L14 7 L8.5 8.5 L7 14 L5.5 8.5 L0 7 L5.5 5.5 Z" fill="#4ecdc4" opacity="0.35"/>
    </svg>
    <svg class="ambient-star small" style="top:60%; left:60%; animation-delay:2.1s;" width="8" height="8" viewBox="0 0 14 14">
        <path d="M7 0 L8.5 5.5 L14 7 L8.5 8.5 L7 14 L5.5 8.5 L0 7 L5.5 5.5 Z" fill="#c9a84c" opacity="0.4"/>
    </svg>
    <svg class="ambient-star" style="top:88%; left:35%; animation-delay:0.4s;" width="11" height="11" viewBox="0 0 14 14">
        <path d="M7 0 L8.5 5.5 L14 7 L8.5 8.5 L7 14 L5.5 8.5 L0 7 L5.5 5.5 Z" fill="#c9a84c" opacity="0.4"/>
    </svg>
    <svg class="ambient-star small" style="top:5%; left:60%; animation-delay:1.4s;" width="9" height="9" viewBox="0 0 14 14">
        <path d="M7 0 L8.5 5.5 L14 7 L8.5 8.5 L7 14 L5.5 8.5 L0 7 L5.5 5.5 Z" fill="#4ecdc4" opacity="0.4"/>
    </svg>

    <svg class="ambient-capsule" style="top:82%; left:90%; animation-delay:0.3s;" width="20" height="26" viewBox="0 0 20 26">
        <rect x="1" y="1" width="18" height="24" rx="9" fill="none" stroke="#c9a84c" stroke-width="1" opacity="0.35"/>
        <line x1="1" y1="15" x2="19" y2="15" stroke="#4ecdc4" stroke-width="1" opacity="0.3"/>
    </svg>
    <svg class="ambient-capsule" style="top:12%; left:75%; animation-delay:1.8s;" width="16" height="21" viewBox="0 0 20 26">
        <rect x="1" y="1" width="18" height="24" rx="9" fill="none" stroke="#4ecdc4" stroke-width="1" opacity="0.3"/>
        <line x1="1" y1="17" x2="19" y2="17" stroke="#c9a84c" stroke-width="1" opacity="0.3"/>
    </svg>
    <svg class="ambient-capsule" style="top:55%; left:20%; animation-delay:0.9s;" width="18" height="23" viewBox="0 0 20 26">
        <rect x="1" y="1" width="18" height="24" rx="9" fill="none" stroke="#c9a84c" stroke-width="1" opacity="0.3"/>
        <line x1="1" y1="10" x2="19" y2="10" stroke="#4ecdc4" stroke-width="1" opacity="0.25"/>
    </svg>
    <svg class="ambient-moon" style="top:6%; left:82%;" width="46" height="46" viewBox="0 0 46 46">
        <path d="M27 4 A19 19 0 1 0 27 42 A15 15 0 1 1 27 4 Z" fill="#f0c060" opacity="0.12"/>
    </svg>

    <svg class="ambient-sun" style="top:75%; left:8%;" width="60" height="60" viewBox="0 0 60 60">
        <circle cx="30" cy="30" r="10" fill="#c9a84c" opacity="0.1"/>
        <g stroke="#c9a84c" stroke-width="1" opacity="0.08">
            <line x1="30" y1="2" x2="30" y2="12"/>
            <line x1="30" y1="48" x2="30" y2="58"/>
            <line x1="2" y1="30" x2="12" y2="30"/>
            <line x1="48" y1="30" x2="58" y2="30"/>
            <line x1="9" y1="9" x2="16" y2="16"/>
            <line x1="44" y1="44" x2="51" y2="51"/>
            <line x1="9" y1="51" x2="16" y2="44"/>
            <line x1="44" y1="16" x2="51" y2="9"/>
        </g>
    </svg>
    <svg class="ambient-horizon" width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="none" style="position:absolute; inset:0;">
        <path d="M0 70 Q 25 65 50 70 T 100 70 L 100 100 L 0 100 Z" fill="url(#skyfade)" opacity="0.06"/>
        <defs>
            <linearGradient id="skyfade" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#4ecdc4"/>
                <stop offset="100%" stop-color="#0a0f1e"/>
            </linearGradient>
        </defs>
    </svg>
</div>




<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <h2> Luminary</h2>
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
            <i class="bi bi-gear"></i> Settings
        </a>
    </nav>
    <div class="sidebar-footer">
    <div class="dropdown">
        <!-- User card acts as dropdown toggle -->
        <button class="user-card btn d-flex align-items-center w-100"
                id="userMenuDropdown"
                data-bs-toggle="dropdown"
                aria-expanded="false">
            <img src="{{ Auth::user()->avatar_url }}" class="user-avatar" alt="avatar">
            <div class="ms-2 text-start flex-grow-1">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">Member</div>
            </div>
            <i class="bi bi-chevron-up ms-auto"></i>
        </button>

        <!-- Dropdown menu -->
        <ul class="dropdown-menu dropdown-menu-dark w-100" aria-labelledby="userMenuDropdown">
            <li>
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="bi bi-person me-2"></i> Profile
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
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
    {{ $slot }}
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/reflection-quote.js') }}"></script>
{{ $scripts ?? '' }}
</body>
</html>
