<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_check.php';
if (isLoggedIn()) { header('Location: ' . (isAdmin() ? '/admin/dashboard.php' : '/pages/dashboard.php')); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($email && $password) {
        $db = getDB();
        $stmt = $db->prepare("SELECT id,name,email,password,role,avatar,status FROM users WHERE email=?");
        $stmt->bind_param('s',$email); $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] === 'banned') {
                $error = 'Akun Anda telah dinonaktifkan. Hubungi admin.';
            } else {
                $_SESSION['user_id']     = $user['id'];
                $_SESSION['user_name']   = $user['name'];
                $_SESSION['user_role']   = $user['role'];
                $_SESSION['user_avatar'] = $user['avatar'];
                $db->query("UPDATE users SET last_login=NOW() WHERE id=".$user['id']);
                header('Location: ' . ($user['role']==='admin' ? '/admin/dashboard.php' : '/pages/dashboard.php')); exit;
            }
        } else { $error = 'Email atau password salah.'; }
        $db->close();
    } else { $error = 'Harap isi semua field.'; }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — SmartHealth</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        :root{--bg:#0a0f1a;--surface:#111827;--surface2:#1a2235;--border:#1f2d45;--accent:#00e5a0;--accent2:#00b8ff;--danger:#ff4d6d;--text:#e2e8f0;--muted:#64748b}
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden}
        .bg-glow{position:fixed;inset:0;pointer-events:none}
        .bg-glow::before{content:'';position:absolute;top:-20%;left:-10%;width:600px;height:600px;background:radial-gradient(circle,rgba(0,229,160,.05),transparent 60%)}
        .bg-glow::after{content:'';position:absolute;bottom:-20%;right:-10%;width:500px;height:500px;background:radial-gradient(circle,rgba(0,184,255,.05),transparent 60%)}
        .grid-bg{position:fixed;inset:0;background-image:linear-gradient(rgba(31,45,69,.2) 1px,transparent 1px),linear-gradient(90deg,rgba(31,45,69,.2) 1px,transparent 1px);background-size:40px 40px;mask-image:radial-gradient(ellipse at center,black 30%,transparent 70%);pointer-events:none}
        .card{background:var(--surface);border:1px solid var(--border);border-radius:20px;width:420px;overflow:hidden;position:relative;z-index:1;animation:fadeUp .4s ease}
        .card-header{padding:32px 32px 24px;text-align:center;border-bottom:1px solid var(--border);background:linear-gradient(135deg,rgba(0,229,160,.04),rgba(0,184,255,.03))}
        .logo-icon{width:52px;height:52px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:22px;color:#0a0f1a}
        .card-title{font-family:'Syne',sans-serif;font-size:20px;font-weight:800;color:#fff;margin-bottom:4px}
        .card-sub{font-size:12px;color:var(--muted)}
        .card-body{padding:28px 32px}
        .form-group{margin-bottom:16px}
        .form-label{display:block;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:7px}
        .input-wrap{position:relative}
        .input-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:12px;pointer-events:none}
        .form-input{width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:9px;padding:11px 14px 11px 36px;color:var(--text);font-size:13px;font-family:'DM Sans',sans-serif;transition:border-color .2s,box-shadow .2s;outline:none}
        .form-input:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(0,229,160,.08)}
        .form-input::placeholder{color:var(--muted)}
        .btn-submit{width:100%;padding:13px;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#0a0f1a;font-family:'Syne',sans-serif;font-weight:700;font-size:14px;border:none;border-radius:10px;cursor:pointer;transition:opacity .2s,transform .1s;display:flex;align-items:center;justify-content:center;gap:8px;margin-top:6px}
        .btn-submit:hover{opacity:.9} .btn-submit:active{transform:scale(.98)}
        .error{background:rgba(255,77,109,.08);border:1px solid rgba(255,77,109,.25);color:var(--danger);font-size:12px;border-radius:8px;padding:10px 14px;margin-bottom:16px;display:flex;align-items:center;gap:8px}
        .card-footer{padding:16px 32px;border-top:1px solid var(--border);text-align:center;font-size:12px;color:var(--muted)}
        .card-footer a{color:var(--accent);text-decoration:none;font-weight:600}
        .back-link{position:fixed;top:20px;left:24px;font-size:12px;color:var(--muted);text-decoration:none;display:flex;align-items:center;gap:6px;transition:color .2s;z-index:10}
        .back-link:hover{color:var(--accent)}
        @keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
    </style>
</head>
<body>
<div class="bg-glow"></div><div class="grid-bg"></div>
<a href="/" class="back-link"><i class="fas fa-arrow-left"></i> Kembali</a>
<div class="card">
    <div class="card-header">
        <div class="logo-icon"><i class="fas fa-heartbeat"></i></div>
        <div class="card-title">Masuk ke SmartHealth</div>
        <div class="card-sub">Selamat datang kembali</div>
    </div>
    <div class="card-body">
        <?php if($error): ?><div class="error"><i class="fas fa-exclamation-circle"></i><?=htmlspecialchars($error)?></div><?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label class="form-label">Email</label>
                <div class="input-wrap"><i class="fas fa-envelope input-icon"></i>
                <input type="email" name="email" required placeholder="email@example.com" class="form-input" value="<?=htmlspecialchars($_POST['email']??'')?>"></div>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrap"><i class="fas fa-lock input-icon"></i>
                <input type="password" name="password" required placeholder="••••••••" class="form-input"></div>
            </div>
            <button type="submit" class="btn-submit"><i class="fas fa-sign-in-alt"></i> Masuk</button>
        </form>
    </div>
    <div class="card-footer">Belum punya akun? <a href="/auth/register.php">Daftar sekarang</a></div>
</div>
</body></html>