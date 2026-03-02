<?php
require_once __DIR__ . '/../includes/auth_check.php';
$_sidebarUser = currentUser();
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' — SmartHealth' : 'SmartHealth' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:       #0a0f1a;
            --surface:  #111827;
            --surface2: #1a2235;
            --border:   #1f2d45;
            --accent:   #00e5a0;
            --accent2:  #00b8ff;
            --danger:   #ff4d6d;
            --warning:  #ffb627;
            --text:     #e2e8f0;
            --muted:    #64748b;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }
        h1,h2,h3,h4 { font-family: 'Syne', sans-serif; }

        /* Sidebar */
        .sidebar {
            width: 240px;
            background: var(--surface);
            border-right: 1px solid var(--border);
            position: fixed; top: 0; left: 0;
            height: 100vh;
            display: flex; flex-direction: column;
            z-index: 50;
        }
        .sidebar-logo {
            padding: 24px 20px;
            border-bottom: 1px solid var(--border);
        }
        .logo-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .logo-icon i { color: #0a0f1a; font-size: 16px; }
        .logo-text { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 16px; color: #fff; }
        .logo-sub  { font-size: 10px; color: var(--muted); letter-spacing: 0.05em; }

        .nav-section { padding: 16px 12px; flex: 1; }
        .nav-label {
            font-size: 9px; font-weight: 700; letter-spacing: 0.12em;
            color: var(--muted); text-transform: uppercase;
            padding: 0 8px; margin-bottom: 8px; margin-top: 16px;
        }
        .nav-label:first-child { margin-top: 0; }

        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 8px;
            color: var(--muted); font-size: 13px; font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            margin-bottom: 2px;
            position: relative;
        }
        .nav-link:hover { background: var(--surface2); color: var(--text); }
        .nav-link.active {
            background: rgba(0,229,160,0.1);
            color: var(--accent);
        }
        .nav-link.active::before {
            content: '';
            position: absolute; left: 0; top: 20%; bottom: 20%;
            width: 3px; border-radius: 0 2px 2px 0;
            background: var(--accent);
        }
        .nav-link i { width: 16px; text-align: center; font-size: 13px; }

        /* User footer */
        .sidebar-user {
            padding: 16px;
            border-top: 1px solid var(--border);
        }
        .user-avatar {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Syne', sans-serif; font-weight: 700;
            font-size: 12px; color: #0a0f1a;
        }
        .logout-btn {
            display: flex; align-items: center; gap-6px;
            font-size: 11px; color: var(--muted);
            text-decoration: none;
            padding: 6px 8px; border-radius: 6px;
            transition: all 0.2s;
            gap: 6px;
        }
        .logout-btn:hover { background: rgba(255,77,109,0.1); color: var(--danger); }

        /* Main content */
        .main { margin-left: 240px; padding: 32px; min-height: 100vh; }

        /* Cards */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
        }
        .card-glass {
            background: rgba(17,24,39,0.8);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border);
            border-radius: 14px;
        }

        /* Stat cards */
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 20px;
            transition: border-color 0.2s, transform 0.2s;
        }
        .stat-card:hover { border-color: var(--accent); transform: translateY(-2px); }

        /* Inputs */
        .form-input {
            width: 100%;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 14px;
            color: var(--text);
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            transition: border-color 0.2s;
            outline: none;
        }
        .form-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(0,229,160,0.08); }
        .form-input::placeholder { color: var(--muted); }
        .form-label { font-size: 11px; font-weight: 600; letter-spacing: 0.06em; color: var(--muted); text-transform: uppercase; margin-bottom: 6px; display: block; }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            color: #0a0f1a;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            border: none; border-radius: 10px;
            padding: 12px 24px;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.1s;
            font-size: 14px;
        }
        .btn-primary:hover { opacity: 0.9; }
        .btn-primary:active { transform: scale(0.98); }
        .btn-ghost {
            background: var(--surface2);
            color: var(--text);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 13px;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 8px;
            transition: border-color 0.2s;
        }
        .btn-ghost:hover { border-color: var(--accent); color: var(--accent); }

        /* Badge */
        .badge { padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-green  { background: rgba(0,229,160,0.12); color: var(--accent); }
        .badge-yellow { background: rgba(255,182,39,0.12); color: var(--warning); }
        .badge-orange { background: rgba(255,120,0,0.12);  color: #ff7800; }
        .badge-red    { background: rgba(255,77,109,0.12);  color: var(--danger); }

        /* Accent glow line */
        .glow-line {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
            opacity: 0.4;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--surface); }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }

        /* Page header */
        .page-header { margin-bottom: 28px; }
        .page-title { font-size: 24px; font-weight: 800; color: #fff; }
        .page-sub   { font-size: 13px; color: var(--muted); margin-top: 3px; }

        /* Table */
        .data-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .data-table th {
            text-align: left; padding: 10px 16px;
            font-size: 10px; font-weight: 700; letter-spacing: 0.1em;
            text-transform: uppercase; color: var(--muted);
            border-bottom: 1px solid var(--border);
        }
        .data-table td { padding: 13px 16px; border-bottom: 1px solid rgba(31,45,69,0.5); }
        .data-table tr:hover td { background: rgba(255,255,255,0.02); }
        .data-table tr:last-child td { border-bottom: none; }

        /* Animations */
        @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp 0.4s ease forwards; }
        .fade-up-2 { animation: fadeUp 0.4s 0.1s ease both; }
        .fade-up-3 { animation: fadeUp 0.4s 0.2s ease both; }
        .fade-up-4 { animation: fadeUp 0.4s 0.3s ease both; }

        /* Pulse dot */
        @keyframes pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }
        .pulse-dot { animation: pulse 2s infinite; }
    </style>
</head>
<body>

<?php if (isLoggedIn()): ?>
<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <div style="display:flex;align-items:center;gap:10px">
            <div class="logo-icon"><i class="fas fa-heartbeat"></i></div>
            <div>
                <div class="logo-text">SmartHealth</div>
                <div class="logo-sub">AI HEALTH PREDICTOR</div>
            </div>
        </div>
    </div>

    <nav class="nav-section">
        <?php if(isAdmin()): ?>
        <div style="margin-bottom:8px">
            <a href="/admin/dashboard.php" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;background:linear-gradient(135deg,rgba(255,182,39,.15),rgba(255,120,0,.1));border:1px solid rgba(255,182,39,.3);text-decoration:none;transition:opacity .2s" onmouseover="this.style.opacity='.8'" onmouseout="this.style.opacity='1'">
                <div style="width:28px;height:28px;background:linear-gradient(135deg,var(--warning),#ff7800);border-radius:7px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="fas fa-shield-halved" style="color:#0a0f1a;font-size:12px"></i>
                </div>
                <div>
                    <div style="font-family:'Syne',sans-serif;font-size:12px;font-weight:700;color:var(--warning)">Admin Panel</div>
                    <div style="font-size:10px;color:rgba(255,182,39,.6)">Kelola sistem</div>
                </div>
                <i class="fas fa-chevron-right" style="color:var(--warning);font-size:10px;margin-left:auto;opacity:.6"></i>
            </a>
        </div>
        <div style="height:1px;background:var(--border);margin-bottom:8px"></div>
        <?php endif; ?>

        <div class="nav-label">Menu</div>
        <a href="/pages/dashboard.php" class="nav-link <?= $currentPage==='dashboard.php'?'active':'' ?>">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>
        <a href="/pages/predict.php" class="nav-link <?= $currentPage==='predict.php'?'active':'' ?>">
            <i class="fas fa-stethoscope"></i> Prediksi Risiko
        </a>
        <a href="/pages/history.php" class="nav-link <?= $currentPage==='history.php'?'active':'' ?>">
            <i class="fas fa-history"></i> Riwayat
        </a>
        <a href="/pages/chat.php" class="nav-link <?= $currentPage==='chat.php'?'active':'' ?>">
            <i class="fas fa-comments"></i> Global Chat
        </a>
        <a href="/pages/profil.php" class="nav-link <?= $currentPage==='profil.php'?'active':'' ?>">
            <i class="fas fa-user-cog"></i> Profil Saya
        </a>
    </nav>

    <div class="sidebar-user">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
            <div class="user-avatar" style="background:<?= isAdmin() ? 'linear-gradient(135deg,var(--warning),#ff7800)' : 'linear-gradient(135deg,var(--accent),var(--accent2))' ?>">
                <?php if($_sidebarUser['avatar']): ?>
                <img src="/uploads/avatars/<?= htmlspecialchars($_sidebarUser['avatar']) ?>" style="width:100%;height:100%;object-fit:cover;border-radius:50%">
                <?php else: echo strtoupper(substr($_sidebarUser['name'],0,1)); endif; ?>
            </div>
            <div style="flex:1;min-width:0">
                <div style="font-size:12px;font-weight:600;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= htmlspecialchars($_sidebarUser['name']) ?></div>
                <div style="font-size:10px;color:<?= isAdmin() ? 'var(--warning)' : 'var(--muted)' ?>"><?= isAdmin() ? '⚡ Administrator' : 'User' ?></div>
            </div>
            <div class="pulse-dot" style="width:7px;height:7px;background:<?= isAdmin() ? 'var(--warning)' : 'var(--accent)' ?>;border-radius:50%"></div>
        </div>
        <a href="/auth/logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Keluar
        </a>
    </div>
</aside>

<main class="main">
<?php else: ?>
<main>
<?php endif; ?>