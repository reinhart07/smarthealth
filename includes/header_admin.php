<?php
require_once __DIR__ . '/../includes/auth_check.php';
requireAdmin();
$user = currentUser();
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' — SmartHealth Admin' : 'SmartHealth Admin' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root{--bg:#0a0d14;--surface:#0f1520;--surface2:#162030;--border:#1e2d42;--accent:#00e5a0;--accent2:#00b8ff;--danger:#ff4d6d;--warning:#ffb627;--text:#e2e8f0;--muted:#64748b}
        *{box-sizing:border-box}
        body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh}
        h1,h2,h3,h4{font-family:'Syne',sans-serif}

        .sidebar{width:240px;background:var(--surface);border-right:1px solid var(--border);position:fixed;top:0;left:0;height:100vh;display:flex;flex-direction:column;z-index:50}
        .sidebar-logo{padding:20px;border-bottom:1px solid var(--border)}
        .logo-icon{width:36px;height:36px;background:linear-gradient(135deg,var(--warning),#ff7800);border-radius:10px;display:flex;align-items:center;justify-content:center}
        .logo-icon i{color:#0a0f1a;font-size:15px}
        .logo-text{font-family:'Syne',sans-serif;font-weight:800;font-size:15px;color:#fff}
        .logo-sub{font-size:9px;color:var(--warning);letter-spacing:.08em;text-transform:uppercase}

        .nav-section{padding:12px;flex:1;overflow-y:auto}
        .nav-group-label{font-size:9px;font-weight:700;letter-spacing:.12em;color:var(--muted);text-transform:uppercase;padding:0 8px;margin:12px 0 6px}
        .nav-link{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:8px;color:var(--muted);font-size:13px;font-weight:500;text-decoration:none;transition:all .2s;margin-bottom:2px;position:relative}
        .nav-link:hover{background:var(--surface2);color:var(--text)}
        .nav-link.active{background:rgba(255,182,39,.1);color:var(--warning)}
        .nav-link.active::before{content:'';position:absolute;left:0;top:20%;bottom:20%;width:3px;border-radius:0 2px 2px 0;background:var(--warning)}
        .nav-link i{width:16px;text-align:center;font-size:13px}
        .nav-divider{height:1px;background:var(--border);margin:8px 0}

        .sidebar-user{padding:14px;border-top:1px solid var(--border)}
        .user-avatar{width:30px;height:30px;background:linear-gradient(135deg,var(--warning),#ff7800);border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:800;font-size:12px;color:#0a0f1a;overflow:hidden}
        .user-avatar img{width:100%;height:100%;object-fit:cover}
        .logout-btn{display:flex;align-items:center;gap:6px;font-size:11px;color:var(--muted);text-decoration:none;padding:6px 8px;border-radius:6px;transition:all .2s}
        .logout-btn:hover{background:rgba(255,77,109,.1);color:var(--danger)}

        .main{margin-left:240px;padding:28px;min-height:100vh}
        .card{background:var(--surface);border:1px solid var(--border);border-radius:14px}
        .stat-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:20px;transition:border-color .2s,transform .2s}
        .stat-card:hover{border-color:var(--warning);transform:translateY(-2px)}
        .form-input{width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:10px 14px;color:var(--text);font-size:13px;font-family:'DM Sans',sans-serif;transition:border-color .2s;outline:none}
        .form-input:focus{border-color:var(--warning);box-shadow:0 0 0 3px rgba(255,182,39,.08)}
        .form-input::placeholder{color:var(--muted)}
        .form-label{font-size:11px;font-weight:600;letter-spacing:.06em;color:var(--muted);text-transform:uppercase;margin-bottom:6px;display:block}
        .btn-primary{background:linear-gradient(135deg,var(--warning),#ff7800);color:#0a0f1a;font-family:'Syne',sans-serif;font-weight:700;border:none;border-radius:10px;padding:10px 20px;cursor:pointer;font-size:13px;display:inline-flex;align-items:center;gap:8px;text-decoration:none;transition:opacity .2s}
        .btn-primary:hover{opacity:.9}
        .btn-ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border);border-radius:10px;padding:9px 18px;cursor:pointer;font-size:12px;text-decoration:none;display:inline-flex;align-items:center;gap:8px;transition:border-color .2s}
        .btn-ghost:hover{border-color:var(--warning);color:var(--warning)}
        .badge{padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
        .badge-green{background:rgba(0,229,160,.12);color:var(--accent)}
        .badge-yellow{background:rgba(255,182,39,.12);color:var(--warning)}
        .badge-orange{background:rgba(255,120,0,.12);color:#ff7800}
        .badge-red{background:rgba(255,77,109,.12);color:var(--danger)}
        .data-table{width:100%;border-collapse:collapse;font-size:13px}
        .data-table th{text-align:left;padding:10px 16px;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);border-bottom:1px solid var(--border)}
        .data-table td{padding:12px 16px;border-bottom:1px solid rgba(30,45,66,.5)}
        .data-table tr:hover td{background:rgba(255,255,255,.015)}
        .data-table tr:last-child td{border-bottom:none}
        .page-header{margin-bottom:24px}
        .page-title{font-size:22px;font-weight:800;color:#fff}
        .page-sub{font-size:13px;color:var(--muted);margin-top:3px}
        @keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
        .fade-up{animation:fadeUp .4s ease both}
        .fade-up-2{animation:fadeUp .4s .1s ease both}
        .fade-up-3{animation:fadeUp .4s .2s ease both}
        .fade-up-4{animation:fadeUp .4s .3s ease both}
        ::-webkit-scrollbar{width:4px} ::-webkit-scrollbar-track{background:var(--surface)} ::-webkit-scrollbar-thumb{background:var(--border);border-radius:2px}
    </style>
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-logo">
        <div style="display:flex;align-items:center;gap:10px">
            <div class="logo-icon"><i class="fas fa-shield-halved"></i></div>
            <div>
                <div class="logo-text">SmartHealth</div>
                <div class="logo-sub">⚡ Admin Panel</div>
            </div>
        </div>
    </div>

    <nav class="nav-section">
        <div class="nav-group-label">Admin</div>
        <a href="/admin/dashboard.php" class="nav-link <?=$currentPage==='dashboard.php'?'active':''?>">
            <i class="fas fa-chart-pie"></i> Dashboard Global
        </a>
        <a href="/admin/users.php" class="nav-link <?=$currentPage==='users.php'?'active':''?>">
            <i class="fas fa-users"></i> Kelola User
        </a>
        <a href="/admin/predictions.php" class="nav-link <?=$currentPage==='predictions.php'?'active':''?>">
            <i class="fas fa-clipboard-list"></i> Semua Prediksi
        </a>

        <div class="nav-divider"></div>
        <div class="nav-group-label">User Area</div>
        <a href="/pages/dashboard.php" class="nav-link">
            <i class="fas fa-columns"></i> Dashboard Saya
        </a>
        <a href="/pages/predict.php" class="nav-link">
            <i class="fas fa-stethoscope"></i> Prediksi
        </a>
        <a href="/pages/chat.php" class="nav-link <?=$currentPage==='chat.php'?'active':''?>">
            <i class="fas fa-comments"></i> Global Chat
        </a>
        <a href="/pages/profil.php" class="nav-link <?=$currentPage==='profil.php'?'active':''?>">
            <i class="fas fa-user-cog"></i> Profil Saya
        </a>
    </nav>

    <div class="sidebar-user">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
            <div class="user-avatar">
                <?php if($user['avatar']): ?>
                <img src="/uploads/avatars/<?=htmlspecialchars($user['avatar'])?>">
                <?php else: echo strtoupper(substr($user['name'],0,1)); endif; ?>
            </div>
            <div style="flex:1;min-width:0">
                <div style="font-size:12px;font-weight:600;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?=htmlspecialchars($user['name'])?></div>
                <div style="font-size:10px;color:var(--warning)">⚡ Administrator</div>
            </div>
        </div>
        <a href="/auth/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Keluar</a>
    </div>
</aside>
<main class="main">