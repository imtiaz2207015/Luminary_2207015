<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luminary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: #0a0f1e;
            font-family: 'DM Sans', sans-serif;
            display: flex; align-items: center; justify-content: center;
            background-image: radial-gradient(ellipse at 20% 50%, rgba(201,168,76,0.07) 0%, transparent 60%),
                              radial-gradient(ellipse at 80% 20%, rgba(78,205,196,0.05) 0%, transparent 50%);
        }
        .auth-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(201,168,76,0.15);
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%; max-width: 440px;
            backdrop-filter: blur(10px);
        }
        .brand { font-family: 'Playfair Display', serif; color: #c9a84c; font-size: 2rem; text-align: center; margin-bottom: 0.25rem; }
        .brand-sub { color: #8b95a8; font-size: 0.85rem; text-align: center; margin-bottom: 2rem; }
        .form-control {
            background: rgba(255,255,255,0.05) !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            color: #f5f0e8 !important; border-radius: 10px; padding: 0.7rem 1rem;
        }
        .form-control:focus {
            border-color: rgba(201,168,76,0.5) !important;
            box-shadow: 0 0 0 0.2rem rgba(201,168,76,0.1) !important;
        }
        .form-control::placeholder { color: #8b95a8 !important; }
        .form-label { color: #8b95a8; font-size: 0.85rem; margin-bottom: 6px; }
        .btn-gold { background: linear-gradient(135deg, #c9a84c, #f0c060); color: #0a0f1e; border: none; font-weight: 700; border-radius: 10px; padding: 0.65rem 1.5rem; width: 100%; font-size: 1rem; transition: all 0.2s; }
        .btn-gold:hover { transform: translateY(-1px); box-shadow: 0 4px 20px rgba(201,168,76,0.35); color: #0a0f1e; }
        .divider { border-color: rgba(255,255,255,0.08); margin: 1.5rem 0; }
        .auth-link { color: #c9a84c; text-decoration: none; font-size: 0.85rem; }
        .auth-link:hover { color: #f0c060; }
        .admin-btn { display: block; text-align: center; padding: 0.5rem; background: rgba(201,168,76,0.08); border: 1px solid rgba(201,168,76,0.2); border-radius: 10px; color: #c9a84c; text-decoration: none; font-size: 0.85rem; transition: all 0.2s; }
        .admin-btn:hover { background: rgba(201,168,76,0.15); color: #f0c060; }
        .alert-error { background: rgba(220,53,69,0.1); border: 1px solid rgba(220,53,69,0.2); color: #ff6b6b; border-radius: 10px; padding: 0.75rem 1rem; font-size: 0.85rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
<div class="auth-card">
    <div class="brand">✨ Luminary</div>
    <div class="brand-sub">Your personal time capsule platform</div>
    {{ $slot }}
</div>
</body>
</html>
