<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_check.php';
requireAdmin();

$db  = getDB();
$msg = '';

// Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $uid    = intval($_POST['uid'] ?? 0);

    if ($uid === $_SESSION['user_id']) { $msg = 'Tidak bisa mengubah akun sendiri.'; }
    elseif ($action === 'ban') {
        $db->query("UPDATE users SET status='banned' WHERE id=$uid");
        $msg = 'User berhasil dinonaktifkan.';
    } elseif ($action === 'unban') {
        $db->query("UPDATE users SET status='active' WHERE id=$uid");
        $msg = 'User berhasil diaktifkan.';
    } elseif ($action === 'delete') {
        $db->query("DELETE FROM users WHERE id=$uid AND role!='admin'");
        $msg = 'User berhasil dihapus.';
    } elseif ($action === 'make_admin') {
        $db->query("UPDATE users SET role='admin' WHERE id=$uid");
        $msg = 'User dijadikan admin.';
    } elseif ($action === 'remove_admin') {
        $db->query("UPDATE users SET role='user' WHERE id=$uid");
        $msg = 'Role admin dicabut.';
    }
}

$search = $_GET['q'] ?? '';
$where  = $search ? "WHERE name LIKE '%".$db->real_escape_string($search)."%' OR email LIKE '%".$db->real_escape_string($search)."%'" : '';
$users  = $db->query("SELECT u.*, (SELECT COUNT(*) FROM predictions WHERE user_id=u.id) as pred_count FROM users u $where ORDER BY u.created_at DESC");
$db->close();

$pageTitle = 'Kelola User';
include __DIR__ . '/../includes/header_admin.php';
?>

<div class="page-header fade-up" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
    <div>
        <div style="font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--warning);margin-bottom:4px">⚡ Admin Panel</div>
        <div class="page-title">Kelola Pengguna</div>
    </div>
</div>

<?php if($msg): ?>
<div style="background:rgba(0,229,160,.08);border:1px solid rgba(0,229,160,.25);color:var(--accent);border-radius:10px;padding:12px 16px;margin-bottom:20px;font-size:13px;display:flex;align-items:center;gap:8px" class="fade-up">
    <i class="fas fa-check-circle"></i><?=htmlspecialchars($msg)?>
</div>
<?php endif; ?>

<!-- Search -->
<div class="card fade-up" style="padding:16px;margin-bottom:16px">
    <form method="GET" style="display:flex;gap:10px">
        <div style="position:relative;flex:1">
            <i class="fas fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:11px"></i>
            <input type="text" name="q" value="<?=htmlspecialchars($search)?>" placeholder="Cari nama atau email..." class="form-input" style="padding-left:30px">
        </div>
        <button type="submit" class="btn-primary" style="padding:10px 16px;font-size:12px">Cari</button>
        <a href="/admin/users.php" class="btn-ghost" style="padding:10px 14px;font-size:12px">Reset</a>
    </form>
</div>

<!-- Table -->
<div class="card fade-up-2" style="overflow:hidden">
    <div style="overflow-x:auto">
    <table class="data-table">
        <thead><tr>
            <th>#</th><th>Pengguna</th><th>Role</th><th>Status</th>
            <th>Prediksi</th><th>Bergabung</th><th>Login Terakhir</th><th>Aksi</th>
        </tr></thead>
        <tbody>
        <?php $i=0; while($u=$users->fetch_assoc()): $i++; ?>
        <tr>
            <td style="color:var(--muted)"><?=$i?></td>
            <td>
                <div style="display:flex;align-items:center;gap:10px">
                    <div style="width:32px;height:32px;border-radius:50%;overflow:hidden;flex-shrink:0">
                        <?php if($u['avatar']): ?>
                        <img src="/uploads/avatars/<?=htmlspecialchars($u['avatar'])?>" style="width:100%;height:100%;object-fit:cover">
                        <?php else: ?>
                        <div style="width:100%;height:100%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:800;font-size:12px;color:#0a0f1a"><?=strtoupper(substr($u['name'],0,1))?></div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <div style="font-weight:600;color:#fff;font-size:13px"><?=htmlspecialchars($u['name'])?></div>
                        <div style="font-size:11px;color:var(--muted)"><?=htmlspecialchars($u['email'])?></div>
                    </div>
                </div>
            </td>
            <td>
                <span class="badge <?=$u['role']==='admin'?'badge-yellow':'badge-green'?>"><?=$u['role']?></span>
            </td>
            <td>
                <span class="badge <?=$u['status']==='active'?'badge-green':'badge-red'?>">
                    <?=$u['status']==='active'?'✓ Aktif':'✗ Banned'?>
                </span>
            </td>
            <td style="color:var(--accent);font-weight:700"><?=$u['pred_count']?></td>
            <td style="color:var(--muted);font-size:11px"><?=date('d/m/Y',strtotime($u['created_at']))?></td>
            <td style="color:var(--muted);font-size:11px"><?=$u['last_login']?date('d/m/Y H:i',strtotime($u['last_login'])):'—'?></td>
            <td>
                <?php if($u['id'] !== $_SESSION['user_id']): ?>
                <div style="display:flex;gap:6px;flex-wrap:wrap">
                    <form method="POST" style="display:inline">
                        <input type="hidden" name="uid" value="<?=$u['id']?>">
                        <input type="hidden" name="action" value="<?=$u['status']==='active'?'ban':'unban'?>">
                        <button type="submit" style="background:<?=$u['status']==='active'?'rgba(255,77,109,.1)':'rgba(0,229,160,.1)'?>;border:1px solid <?=$u['status']==='active'?'rgba(255,77,109,.3)':'rgba(0,229,160,.3)'?>;color:<?=$u['status']==='active'?'var(--danger)':'var(--accent)'?>;border-radius:6px;padding:4px 10px;font-size:11px;cursor:pointer;font-weight:600">
                            <?=$u['status']==='active'?'Ban':'Aktifkan'?>
                        </button>
                    </form>
                    <?php if($u['role']!=='admin'): ?>
                    <form method="POST" style="display:inline">
                        <input type="hidden" name="uid" value="<?=$u['id']?>">
                        <input type="hidden" name="action" value="make_admin">
                        <button type="submit" style="background:rgba(255,182,39,.1);border:1px solid rgba(255,182,39,.3);color:var(--warning);border-radius:6px;padding:4px 10px;font-size:11px;cursor:pointer;font-weight:600">
                            +Admin
                        </button>
                    </form>
                    <form method="POST" style="display:inline" onsubmit="return confirm('Hapus user ini?')">
                        <input type="hidden" name="uid" value="<?=$u['id']?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" style="background:rgba(255,77,109,.08);border:1px solid rgba(255,77,109,.2);color:var(--danger);border-radius:6px;padding:4px 10px;font-size:11px;cursor:pointer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    <?php else: ?>
                    <form method="POST" style="display:inline">
                        <input type="hidden" name="uid" value="<?=$u['id']?>">
                        <input type="hidden" name="action" value="remove_admin">
                        <button type="submit" style="background:rgba(100,116,139,.1);border:1px solid rgba(100,116,139,.3);color:var(--muted);border-radius:6px;padding:4px 10px;font-size:11px;cursor:pointer;font-weight:600">
                            -Admin
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <span style="font-size:11px;color:var(--muted)">Akun Anda</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>