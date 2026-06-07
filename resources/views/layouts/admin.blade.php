<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luminary Admin – @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --navy:#0a0f1e; --navy2:#111827; --gold:#c9a84c; --gold2:#f0c060; --text:#f5f0e8; --muted:#8b95a8; --sidebar-w:250px; }
        body { background:#0a0f1e; color:#f5f0e8; font-family:'DM Sans',sans-serif; margin:0; }
        .sidebar { position:fixed; top:0; left:0; width:var(--sidebar-w); height:100vh; background:#111827; border-right:1px solid rgba(201,168,76,0.15); display:flex; flex-direction:column; z-index:100; }
        .sidebar-brand { padding:1.5rem; border-bottom:1px solid rgba(201,168,76,0.1); }
        .sidebar-brand h2 { font-family:'Playfair Display',serif; color:#c9a84c; font-size:1.3rem; margin:0; }
        .sidebar-brand p { color:#8b95a8; font-size:0.75rem; margin:2px 0 0; }
        .nav-item-custom { display:flex; align-items:center; gap:10px; padding:0.65rem 1.5rem; color:#8b95a8; text-decoration:none; font-size:0.88rem; border-left:3px solid transparent; transition:all 0.2s; }
        .nav-item-custom:hover { color:#f5f0e8; background:rgba(255,255,255,0.04); border-left-color:rgba(201,168,76,0.4); }
        .nav-item-custom.active { color:#c9a84c; background:rgba(201,168,76,0.08); border-left-color:#c9a84c; }
        .main-content { margin-left:var(--sidebar-w); padding:2rem; min-height:100vh; }
        .topbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem; }
        .topbar-title { font-family:'Playfair Display',serif; font-size:1.6rem; }
        .glass-card { background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08); border-radius:14px; }
        .glass-card:hover { border-color:rgba(201,168,76,0.2); }
        .stat-card { padding:1.25rem; }
        .stat-num { font-size:2rem; font-weight:700; font-family:'Playfair Display',serif; }
        .btn-gold { background:linear-gradient(135deg,#c9a84c,#f0c060); color:#0a0f1e; border:none; font-weight:700; border-radius:8px; padding:0.45rem 1.1rem; transition:all 0.2s; }
        .btn-ghost { background:rgba(255,255,255,0.06); color:#f5f0e8; border:1px solid rgba(255,255,255,0.1); border-radius:8px; padding:0.45rem 1.1rem; transition:all 0.2s; }
        .btn-ghost:hover { background:rgba(255,255,255,0.1); color:#f5f0e8; }
        .btn-danger-soft { background:rgba(220,53,69,0.15); color:#ff6b6b; border:1px solid rgba(220,53,69,0.2); border-radius:8px; padding:0.4rem 1rem; transition:all 0.2s; }
        .badge-gold { background:rgba(201,168,76,0.15); color:#c9a84c; border:1px solid rgba(201,168,76,0.3); border-radius:20px; padding:3px 10px; font-size:0.75rem; }
        .badge-pending { background:rgba(255,193,7,0.15); color:#ffd43b; border:1px solid rgba(255,193,7,0.25); border-radius:20px; padding:3px 10px; font-size:0.75rem; }
        .badge-success { background:rgba(40,167,69,0.15); color:#51cf66; border:1px solid rgba(40,167,69,0.25); border-radius:20px; padding:3px 10px; font-size:0.75rem; }
        .badge-danger { background:rgba(220,53,69,0.15); color:#ff6b6b; border:1px solid rgba(220,53,69,0.2); border-radius:20px; padding:3px 10px; font-size:0.75rem; }
        table { color:#f5f0e8; }
        .table { --bs-table-bg: transparent; --bs-table-color: #f5f0e8; }
        .table thead th { color:#8b95a8; font-size:0.78rem; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid rgba(255,255,255,0.08); font-weight:500; padding:0.75rem 1rem; }
        .table td { border-bottom:1px solid rgba(255,255,255,0.05); padding:0.75rem 1rem; font-size:0.88rem; vertical-align:middle; }
        .alert-success-custom { background:rgba(40,167,69,0.1); border:1px solid rgba(40,167,69,0.2); color:#51cf66; border-radius:10px; }
        .alert-error-custom { background:rgba(220,53,69,0.1); border:1px solid rgba(220,53,69,0.2); color:#ff6b6b; border-radius:10px; }
        .form-control, .form-select { background:rgba(255,255,255,0.05)!important; border:1px solid rgba(255,255,255,0.1)!important; color:#f5f0e8!important; border-radius:8px; }
        .form-control:focus, .form-select:focus { border-color:rgba(201,168,76,0.5)!important; box-shadow:0 0 0 0.2rem rgba(201,168,76,0.1)!important; }
        ::-webkit-scrollbar { width:6px; } ::-webkit-scrollbar-track { background:#111827; } ::-webkit-scrollbar-thumb { background:rgba(201,168,76,0.3); border-radius:3px; }
    </style>
    @yield('styles')
</head>
<body>
<div class="sidebar">
    <div class="sidebar-brand">
        <h2>✨ Luminary</h2>
        <p>Admin Control Panel</p>
    </div>
    <nav style="flex:1; padding:1rem 0; overflow-y:auto;">
        <a href="{{ route('admin.dashboard') }}" class="nav-item-custom {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <a href="{{ route('admin.review') }}" class="nav-item-custom {{ request()->routeIs('admin.review') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i> Review Queue
            @php $pending = \App\Models\Capsule::where('status','pending_review')->count(); @endphp
            @if($pending > 0) <span class="ms-auto badge-pending">{{ $pending }}</span> @endif
        </a>
        <a href="{{ route('admin.capsules.index') }}" class="nav-item-custom {{ request()->routeIs('admin.capsules.index') ? 'active' : '' }}">
            <i class="bi bi-hourglass-split"></i> All Capsules
        </a>
        <a href="{{ route('admin.users.index') }}" class="nav-item-custom {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Users
        </a>
    </nav>
    <div style="padding:1rem 1.5rem; border-top:1px solid rgba(255,255,255,0.06);">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="nav-item-custom w-100" style="background:none; border:none; text-align:left; cursor:pointer;">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
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