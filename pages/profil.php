<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_check.php';
requireLogin();

$db     = getDB();
$userId = (int)$_SESSION['user_id'];

// Coba query lengkap dulu, fallback SELECT * jika kolom baru belum ada
$result = $db->query("SELECT id, name, email, role, avatar, bio, status, created_at, last_login FROM users WHERE id=$userId");
if (!$result) {
    $result = $db->query("SELECT * FROM users WHERE id=$userId");
}
$user = ($result && $result->num_rows > 0) ? $result->fetch_assoc() : array();

// Null-safe pakai isset (kompatibel PHP 7+)
if (!isset($user['id']))         $user['id']         = $userId;
if (!isset($user['name']))       $user['name']       = 'User';
if (!isset($user['email']))      $user['email']      = '';
if (!isset($user['role']))       $user['role']       = 'user';
if (!isset($user['avatar']))     $user['avatar']     = null;
if (!isset($user['bio']))        $user['bio']        = '';
if (!isset($user['status']))     $user['status']     = 'active';
if (!isset($user['created_at'])) $user['created_at'] = date('Y-m-d H:i:s');
if (!isset($user['last_login'])) $user['last_login'] = null;

$totalPred = (int)$db->query("SELECT COUNT(*) as c FROM predictions WHERE user_id=$userId")->fetch_assoc()['c'];

$msg = ''; $msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'profile') {
        $name = trim(isset($_POST['name']) ? $_POST['name'] : '');
        $bio  = trim(isset($_POST['bio'])  ? $_POST['bio']  : '');

        if (!$name) {
            $msg = 'Nama tidak boleh kosong.'; $msgType = 'error';
        } else {
            $avatarPath = $user['avatar'];

            if (!empty($_FILES['avatar']['name'])) {
                $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, array('jpg','jpeg','png','webp'))) {
                    $uploadDir = __DIR__ . '/../uploads/avatars/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                    $filename = 'avatar_' . $userId . '_' . time() . '.' . $ext;
                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $filename)) {
                        if ($avatarPath && file_exists($uploadDir . $avatarPath)) unlink($uploadDir . $avatarPath);
                        $avatarPath = $filename;
                    }
                }
            }

            // Cek apakah kolom bio & avatar sudah ada di DB
            $hasNewCols = $db->query("SHOW COLUMNS FROM users LIKE 'bio'");
            if ($hasNewCols && $hasNewCols->num_rows > 0) {
                $stmt = $db->prepare("UPDATE users SET name=?, bio=?, avatar=? WHERE id=?");
                $stmt->bind_param('sssi', $name, $bio, $avatarPath, $userId);
            } else {
                $stmt = $db->prepare("UPDATE users SET name=? WHERE id=?");
                $stmt->bind_param('si', $name, $userId);
            }
            $stmt->execute();

            $_SESSION['user_name']   = $name;
            $_SESSION['user_avatar'] = $avatarPath;

            $msg = 'Profil berhasil diperbarui!'; $msgType = 'success';
            $user['name']   = $name;
            $user['bio']    = $bio;
            $user['avatar'] = $avatarPath;
        }
    }

    if ($action === 'password') {
        $current = isset($_POST['current_password']) ? $_POST['current_password'] : '';
        $new     = isset($_POST['new_password'])     ? $_POST['new_password']     : '';
        $confirm = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

        if (!$current || !$new || !$confirm) {
            $msg = 'Harap isi semua field password.'; $msgType = 'error';
        } elseif (strlen($new) < 6) {
            $msg = 'Password baru minimal 6 karakter.'; $msgType = 'error';
        } elseif ($new !== $confirm) {
            $msg = 'Konfirmasi password tidak cocok.'; $msgType = 'error';
        } else {
            $row = $db->query("SELECT password FROM users WHERE id=$userId")->fetch_assoc();
            if (!password_verify($current, $row['password'])) {
                $msg = 'Password saat ini salah.'; $msgType = 'error';
            } else {
                $hashed = password_hash($new, PASSWORD_BCRYPT);
                $stmt = $db->prepare("UPDATE users SET password=? WHERE id=?");
                $stmt->bind_param('si', $hashed, $userId);
                $stmt->execute();
                $msg = 'Password berhasil diubah!'; $msgType = 'success';
            }
        }
    }
}

$db->close();
$pageTitle = 'Profil Saya';
include __DIR__ . '/../includes/header.php';
?>

<div class="page-header fade-up">
    <div class="page-title">Profil Saya</div>
    <div class="page-sub">Kelola informasi akun dan keamanan Anda</div>
</div>

<?php if ($msg): ?>
<div style="background:<?php echo $msgType==='success' ? 'rgba(0,229,160,.08)' : 'rgba(255,77,109,.08)'; ?>;border:1px solid <?php echo $msgType==='success' ? 'rgba(0,229,160,.25)' : 'rgba(255,77,109,.25)'; ?>;color:<?php echo $msgType==='success' ? 'var(--accent)' : 'var(--danger)'; ?>;border-radius:10px;padding:12px 16px;margin-bottom:20px;font-size:13px;display:flex;align-items:center;gap:8px" class="fade-up">
    <i class="fas fa-<?php echo $msgType==='success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
    <?php echo htmlspecialchars($msg); ?>
</div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:260px 1fr;gap:20px;align-items:start">

    <!-- Kartu kiri -->
    <div class="fade-up">
        <div class="card" style="padding:24px;text-align:center">
            <div style="width:100px;height:100px;margin:0 auto 14px">
                <?php if ($user['avatar']): ?>
                <img src="/uploads/avatars/<?php echo htmlspecialchars($user['avatar']); ?>"
                     style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid var(--accent)">
                <?php else: ?>
                <div style="width:100px;height:100px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:800;font-size:34px;color:#0a0f1a">
                    <?php echo strtoupper(substr($user['name'],0,1)); ?>
                </div>
                <?php endif; ?>
            </div>

            <div style="font-family:'Syne',sans-serif;font-weight:800;font-size:15px;color:#fff;margin-bottom:3px"><?php echo htmlspecialchars($user['name']); ?></div>
            <div style="font-size:12px;color:var(--muted);margin-bottom:10px"><?php echo htmlspecialchars($user['email']); ?></div>
            <span style="background:<?php echo $user['role']==='admin' ? 'rgba(255,182,39,.12)' : 'rgba(0,229,160,.1)'; ?>;border:1px solid <?php echo $user['role']==='admin' ? 'rgba(255,182,39,.3)' : 'rgba(0,229,160,.2)'; ?>;color:<?php echo $user['role']==='admin' ? 'var(--warning)' : 'var(--accent)'; ?>;border-radius:20px;padding:3px 14px;font-size:10px;font-weight:700;letter-spacing:.06em">
                <?php echo $user['role']==='admin' ? '&#9889; Admin' : '&#128100; User'; ?>
            </span>

            <div style="height:1px;background:var(--border);margin:16px 0"></div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;text-align:center">
                <div>
                    <div style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:var(--accent)"><?php echo $totalPred; ?></div>
                    <div style="font-size:10px;color:var(--muted)">Prediksi</div>
                </div>
                <div>
                    <div style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:var(--accent2)"><?php echo date('Y', strtotime($user['created_at'])); ?></div>
                    <div style="font-size:10px;color:var(--muted)">Bergabung</div>
                </div>
            </div>

            <?php if ($user['last_login']): ?>
            <div style="margin-top:12px;font-size:10px;color:var(--muted)">
                Login terakhir: <?php echo date('d M Y H:i', strtotime($user['last_login'])); ?>
            </div>
            <?php endif; ?>
        </div>

        <?php if ($user['bio']): ?>
        <div class="card" style="padding:16px;margin-top:12px">
            <div style="font-size:10px;color:var(--muted);margin-bottom:6px;font-weight:700;letter-spacing:.06em;text-transform:uppercase">Bio</div>
            <div style="font-size:13px;color:var(--text);line-height:1.6"><?php echo nl2br(htmlspecialchars($user['bio'])); ?></div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Form kanan -->
    <div style="display:flex;flex-direction:column;gap:16px">

        <div class="card fade-up-2" style="padding:24px">
            <div style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--accent);margin-bottom:18px;display:flex;align-items:center;gap:8px">
                <i class="fas fa-user-edit"></i> Edit Profil
            </div>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="profile">
                <div style="display:grid;gap:14px">
                    <div>
                        <label style="display:block;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:7px">Foto Profil</label>
                        <div style="display:flex;align-items:center;gap:12px">
                            <div id="avatarPreviewWrap" style="width:50px;height:50px;border-radius:50%;overflow:hidden;border:2px solid var(--border);flex-shrink:0">
                                <?php if ($user['avatar']): ?>
                                <img src="/uploads/avatars/<?php echo htmlspecialchars($user['avatar']); ?>" style="width:100%;height:100%;object-fit:cover">
                                <?php else: ?>
                                <div style="width:100%;height:100%;background:var(--surface2);display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:800;font-size:16px;color:var(--accent)"><?php echo strtoupper(substr($user['name'],0,1)); ?></div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <input type="file" name="avatar" accept="image/*" id="avatarInput" style="display:none" onchange="previewAvatar(this)">
                                <label for="avatarInput" style="display:inline-flex;align-items:center;gap:6px;background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:8px 14px;font-size:12px;color:var(--text);cursor:pointer">
                                    <i class="fas fa-upload" style="color:var(--accent)"></i> Pilih Foto
                                </label>
                                <div style="font-size:10px;color:var(--muted);margin-top:4px">JPG, PNG, WEBP. Maks 2MB.</div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label style="display:block;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:7px">Nama Lengkap</label>
                        <input type="text" name="name" required value="<?php echo htmlspecialchars($user['name']); ?>" class="form-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:7px">Bio (opsional)</label>
                        <textarea name="bio" rows="3" class="form-input" style="resize:vertical" placeholder="Ceritakan sedikit tentang Anda..."><?php echo htmlspecialchars($user['bio']); ?></textarea>
                    </div>
                    <div>
                        <label style="display:block;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:7px">Email</label>
                        <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="form-input" disabled style="opacity:.5;cursor:not-allowed">
                        <div style="font-size:10px;color:var(--muted);margin-top:4px">Email tidak dapat diubah</div>
                    </div>
                    <div>
                        <button type="submit" class="btn-primary" style="padding:10px 20px;font-size:13px">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card fade-up-3" style="padding:24px">
            <div style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--warning);margin-bottom:18px;display:flex;align-items:center;gap:8px">
                <i class="fas fa-lock"></i> Ganti Password
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="password">
                <div style="display:grid;gap:14px">
                    <div>
                        <label style="display:block;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:7px">Password Saat Ini</label>
                        <input type="password" name="current_password" required placeholder="Min. 6 karakter" class="form-input">
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                        <div>
                            <label style="display:block;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:7px">Password Baru</label>
                            <input type="password" name="new_password" required placeholder="Min. 6 karakter" class="form-input">
                        </div>
                        <div>
                            <label style="display:block;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:7px">Konfirmasi</label>
                            <input type="password" name="confirm_password" required placeholder="Ulangi password" class="form-input">
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn-primary" style="padding:10px 20px;font-size:13px;background:linear-gradient(135deg,var(--warning),#ff7800)">
                            <i class="fas fa-key"></i> Ubah Password
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var wrap = document.getElementById('avatarPreviewWrap');
            wrap.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>