<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_check.php';
requireLogin();

// Handle AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    $db  = getDB();
    $uid = $_SESSION['user_id'];

    if ($_POST['ajax'] === 'send') {
        $msg = trim($_POST['message'] ?? '');
        if ($msg && strlen($msg) <= 500) {
            $msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
            $stmt = $db->prepare("INSERT INTO chats (user_id, message) VALUES (?,?)");
            $stmt->bind_param('is', $uid, $msg);
            $stmt->execute();
            echo json_encode(['ok'=>true, 'id'=>$db->insert_id]);
        } else {
            echo json_encode(['ok'=>false]);
        }
        exit;
    }

    if ($_POST['ajax'] === 'fetch') {
        $since = intval($_POST['since'] ?? 0);
        $rows = $db->query("
            SELECT c.id, c.message, c.created_at, u.name, u.avatar, u.role
            FROM chats c JOIN users u ON c.user_id=u.id
            WHERE c.id > $since ORDER BY c.id ASC LIMIT 50
        ");
        $msgs = [];
        while ($r = $rows->fetch_assoc()) {
            $r['is_me'] = ($r['name'] === $_SESSION['user_name']);
            $msgs[] = $r;
        }
        echo json_encode(['messages'=>$msgs]);
        exit;
    }
    exit;
}

$pageTitle = 'Global Chat';
include __DIR__ . '/../includes/header.php';
?>

<div class="page-header fade-up" style="display:flex;align-items:center;justify-content:space-between">
    <div>
        <div class="page-title">Global Chat</div>
        <div class="page-sub">Obrolan langsung dengan semua pengguna SmartHealth</div>
    </div>
    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--accent)">
        <div style="width:7px;height:7px;background:var(--accent);border-radius:50%;animation:pulse 2s infinite"></div>
        Live
    </div>
</div>

<!-- Chat Container -->
<div class="card fade-up" style="display:flex;flex-direction:column;height:calc(100vh - 220px);min-height:480px">

    <!-- Messages area -->
    <div id="chatBox" style="flex:1;overflow-y:auto;padding:20px;display:flex;flex-direction:column;gap:12px">
        <div id="chatLoader" style="text-align:center;color:var(--muted);font-size:13px;padding:20px">
            <i class="fas fa-spinner fa-spin"></i> Memuat pesan...
        </div>
    </div>

    <!-- Input -->
    <div style="padding:16px;border-top:1px solid var(--border);display:flex;gap:10px;align-items:center">
        <!-- Avatar current user -->
        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:800;font-size:13px;color:#0a0f1a;flex-shrink:0">
            <?php
            $u = currentUser();
            if ($u['avatar']): ?>
            <img src="/uploads/avatars/<?=htmlspecialchars($u['avatar'])?>" style="width:100%;height:100%;object-fit:cover;border-radius:50%">
            <?php else: echo strtoupper(substr($_SESSION['user_name'],0,1)); endif; ?>
        </div>
        <input type="text" id="msgInput" placeholder="Tulis pesan... (maks 500 karakter)" maxlength="500"
               class="form-input" style="flex:1" onkeypress="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendMsg()}">
        <button onclick="sendMsg()" class="btn-primary" style="padding:10px 18px;font-size:13px;white-space:nowrap">
            <i class="fas fa-paper-plane"></i> Kirim
        </button>
    </div>
</div>

<style>
.msg-row{display:flex;gap:10px;align-items:flex-end;animation:fadeUp .25s ease;max-width:80%}
.msg-row.me{flex-direction:row-reverse;margin-left:auto}
.msg-avatar{width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:800;font-size:11px;color:#0a0f1a;flex-shrink:0;overflow:hidden}
.msg-avatar img{width:100%;height:100%;object-fit:cover}
.msg-bubble{max-width:68%;min-width:60px;padding:10px 14px;border-radius:14px;font-size:13px;line-height:1.5;word-break:break-word;display:inline-block;width:auto}
.msg-bubble.other{background:var(--surface2);border:1px solid var(--border);border-bottom-left-radius:4px;color:var(--text)}
.msg-bubble.me{background:linear-gradient(135deg,rgba(0,229,160,.2),rgba(0,184,255,.15));border:1px solid rgba(0,229,160,.25);border-bottom-right-radius:4px;color:#fff}
.msg-name{font-size:10px;font-weight:700;color:var(--muted);margin-bottom:3px}
.msg-name.me{text-align:right}
.msg-time{font-size:9px;color:var(--muted);margin-top:3px;opacity:.7}
.msg-time.me{text-align:right}
.admin-badge{font-size:9px;background:rgba(255,182,39,.15);color:var(--warning);border-radius:4px;padding:1px 5px;margin-left:4px}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.3}}
</style>

<script>
let lastId = 0;
let polling;

function avatarHTML(name, avatar, role) {
    const initial = name.charAt(0).toUpperCase();
    const color   = role === 'admin' ? 'var(--warning)' : 'var(--accent)';
    if (avatar) return `<img src="/uploads/avatars/${avatar}" onerror="this.style.display='none'">`;
    return `<span style="color:#0a0f1a;font-family:'Syne',sans-serif;font-weight:800;font-size:11px">${initial}</span>`;
}

function timeStr(dt) {
    const d = new Date(dt.replace(' ','T'));
    return d.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'});
}

function renderMsg(m) {
    const isMine = m.is_me;
    const adminB = m.role === 'admin' ? `<span class="admin-badge">Admin</span>` : '';
    return `
    <div class="msg-row ${isMine?'me':''}" id="msg-${m.id}">
        <div class="msg-avatar" style="background:${m.role==='admin'?'linear-gradient(135deg,var(--warning),#ff7800)':'linear-gradient(135deg,var(--accent),var(--accent2))'}">
            ${avatarHTML(m.name, m.avatar, m.role)}
        </div>
        <div>
            <div class="msg-name ${isMine?'me':''}">${isMine?'Anda':m.name}${adminB}</div>
            <div class="msg-bubble ${isMine?'me':'other'}">${m.message}</div>
            <div class="msg-time ${isMine?'me':''}">${timeStr(m.created_at)}</div>
        </div>
    </div>`;
}

function scrollBottom() {
    const box = document.getElementById('chatBox');
    box.scrollTop = box.scrollHeight;
}

async function fetchMsgs() {
    try {
        const res  = await fetch('', {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:`ajax=fetch&since=${lastId}`});
        const data = await res.json();
        const box  = document.getElementById('chatBox');

        if (data.messages.length > 0) {
            const loader = document.getElementById('chatLoader');
            if (loader) loader.remove();

            data.messages.forEach(m => {
                if (!document.getElementById('msg-'+m.id)) {
                    box.insertAdjacentHTML('beforeend', renderMsg(m));
                    lastId = Math.max(lastId, parseInt(m.id));
                }
            });
            scrollBottom();
        } else if (lastId === 0) {
            const loader = document.getElementById('chatLoader');
            if (loader) loader.innerHTML = '<i class="fas fa-comments" style="font-size:28px;opacity:.2;display:block;margin-bottom:8px"></i><div style="font-size:12px">Belum ada pesan. Jadilah yang pertama!<br><span style="color:var(--accent)">Mulai obrolan sekarang ↓</span></div>';
        }
    } catch(e) {}
}

async function sendMsg() {
    const input = document.getElementById('msgInput');
    const msg   = input.value.trim();
    if (!msg) return;
    input.value = '';

    try {
        const res  = await fetch('', {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:`ajax=send&message=${encodeURIComponent(msg)}`});
        const data = await res.json();
        if (data.ok) fetchMsgs();
    } catch(e) { input.value = msg; }
}

// Init
fetchMsgs();
polling = setInterval(fetchMsgs, 2000);
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>