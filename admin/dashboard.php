<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_check.php';
requireAdmin();

$db = getDB();

// Stats global
$totalUsers   = $db->query("SELECT COUNT(*) as c FROM users WHERE role='user'")->fetch_assoc()['c'];
$totalPred    = $db->query("SELECT COUNT(*) as c FROM predictions")->fetch_assoc()['c'];
$totalDM      = $db->query("SELECT COUNT(*) as c FROM predictions WHERE result=1")->fetch_assoc()['c'];
$totalChats   = $db->query("SELECT COUNT(*) as c FROM chats")->fetch_assoc()['c'];

// Distribusi risk
$riskRows = $db->query("SELECT risk_level, COUNT(*) as c FROM predictions GROUP BY risk_level");
$riskData = ['Rendah'=>0,'Sedang'=>0,'Tinggi'=>0,'Sangat Tinggi'=>0];
while($r=$riskRows->fetch_assoc()) $riskData[$r['risk_level']]=(int)$r['c'];

// Trend 14 hari
$trendRows = $db->query("SELECT DATE(created_at) as day, COUNT(*) as total, SUM(result) as positif FROM predictions WHERE created_at >= DATE_SUB(NOW(), INTERVAL 14 DAY) GROUP BY DATE(created_at) ORDER BY day");
$tDays=$tTotal=$tPositif=[];
while($r=$trendRows->fetch_assoc()){$tDays[]=date('d/m',strtotime($r['day']));$tTotal[]=(int)$r['total'];$tPositif[]=(int)$r['positif'];}

// Top users by prediksi
$topUsers = $db->query("SELECT u.name, u.email, COUNT(p.id) as pred_count, SUM(p.result) as dm_count FROM users u LEFT JOIN predictions p ON u.id=p.user_id GROUP BY u.id ORDER BY pred_count DESC LIMIT 5");

// Recent users
$recentUsers = $db->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5");

$db->close();
$pageTitle = 'Admin Dashboard';

// Override sidebar untuk admin
$isAdmin = true;
include __DIR__ . '/../includes/header_admin.php';
?>

<div class="page-header fade-up" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
    <div>
        <div style="font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--warning);margin-bottom:4px">⚡ Admin Panel</div>
        <div class="page-title">Dashboard Global</div>
        <div class="page-sub">Overview seluruh aktivitas SmartHealth</div>
    </div>
    <a href="/pages/dashboard.php" class="btn-ghost" style="text-decoration:none;font-size:12px">
        <i class="fas fa-user"></i> Mode User
    </a>
</div>

<!-- Stats -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px" class="fade-up-2">
    <?php
    $stats=[
        ['Total User','fas fa-users',$totalUsers,'rgba(0,229,160,.1)','var(--accent)','Pengguna terdaftar'],
        ['Total Prediksi','fas fa-clipboard-list',$totalPred,'rgba(0,184,255,.1)','var(--accent2)','Semua waktu'],
        ['Diabetes Terdeteksi','fas fa-exclamation-circle',$totalDM,'rgba(255,77,109,.1)','var(--danger)',$totalPred>0?round($totalDM/$totalPred*100).'% dari total':'0%'],
        ['Pesan Chat','fas fa-comments',$totalChats,'rgba(255,182,39,.1)','var(--warning)','Global chat'],
    ];
    foreach($stats as $s): ?>
    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
            <div style="font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted)"><?=$s[0]?></div>
            <div style="width:34px;height:34px;background:<?=$s[3]?>;border-radius:8px;display:flex;align-items:center;justify-content:center">
                <i class="<?=$s[1]?>" style="color:<?=$s[4]?>;font-size:13px"></i>
            </div>
        </div>
        <div style="font-size:32px;font-family:'Syne',sans-serif;font-weight:800;color:<?=$s[4]?>"><?=$s[2]?></div>
        <div style="font-size:11px;color:var(--muted);margin-top:4px"><?=$s[5]?></div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Charts -->
<div style="display:grid;grid-template-columns:1.6fr 1fr;gap:16px;margin-bottom:24px" class="fade-up-3">
    <div class="card" style="padding:20px">
        <div style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:16px">Tren Prediksi 14 Hari</div>
        <?php if(count($tDays)>0): ?>
        <canvas id="trendChart" height="160"></canvas>
        <?php else: ?>
        <div style="height:160px;display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:12px">Belum ada data</div>
        <?php endif; ?>
    </div>
    <div class="card" style="padding:20px">
        <div style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:16px">Distribusi Risiko</div>
        <canvas id="riskChart" height="160"></canvas>
    </div>
</div>

<!-- Tables -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px" class="fade-up-4">
    <!-- Top Users -->
    <div class="card">
        <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:14px;color:#fff">Top Pengguna Aktif</div>
        </div>
        <table class="data-table">
            <thead><tr><th>Pengguna</th><th>Prediksi</th><th>DM+</th></tr></thead>
            <tbody>
            <?php while($r=$topUsers->fetch_assoc()): ?>
            <tr>
                <td>
                    <div style="font-weight:600;color:#fff;font-size:13px"><?=htmlspecialchars($r['name'])?></div>
                    <div style="font-size:11px;color:var(--muted)"><?=htmlspecialchars($r['email'])?></div>
                </td>
                <td><span style="font-family:'Syne',sans-serif;font-weight:700;color:var(--accent)"><?=$r['pred_count']?></span></td>
                <td><span style="font-family:'Syne',sans-serif;font-weight:700;color:var(--danger)"><?=$r['dm_count']?:0?></span></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <div style="padding:12px 20px;border-top:1px solid var(--border)">
            <a href="/admin/users.php" style="font-size:12px;color:var(--accent);text-decoration:none">Kelola semua user →</a>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="card">
        <div style="padding:16px 20px;border-bottom:1px solid var(--border)">
            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:14px;color:#fff">User Terbaru</div>
        </div>
        <table class="data-table">
            <thead><tr><th>Nama</th><th>Role</th><th>Bergabung</th></tr></thead>
            <tbody>
            <?php while($r=$recentUsers->fetch_assoc()): ?>
            <tr>
                <td>
                    <div style="font-weight:600;color:#fff;font-size:13px"><?=htmlspecialchars($r['name'])?></div>
                    <div style="font-size:11px;color:var(--muted)"><?=htmlspecialchars($r['email'])?></div>
                </td>
                <td>
                    <span class="badge <?=$r['role']==='admin'?'badge-yellow':'badge-green'?>"><?=$r['role']?></span>
                </td>
                <td style="color:var(--muted);font-size:11px"><?=date('d/m/Y',strtotime($r['created_at']))?></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <div style="padding:12px 20px;border-top:1px solid var(--border)">
            <a href="/admin/users.php" style="font-size:12px;color:var(--accent);text-decoration:none">Lihat semua →</a>
        </div>
    </div>
</div>

<script>
Chart.defaults.color='#64748b'; Chart.defaults.borderColor='rgba(31,45,69,.5)';
<?php if(count($tDays)>0): ?>
new Chart(document.getElementById('trendChart'),{type:'line',data:{labels:<?=json_encode($tDays)?>,datasets:[{label:'Total',data:<?=json_encode($tTotal)?>,borderColor:'#00b8ff',backgroundColor:'rgba(0,184,255,.08)',tension:.4,fill:true,pointRadius:3},{label:'Diabetes',data:<?=json_encode($tPositif)?>,borderColor:'#ff4d6d',backgroundColor:'rgba(255,77,109,.08)',tension:.4,fill:true,pointRadius:3}]},options:{responsive:true,scales:{y:{beginAtZero:true,ticks:{stepSize:1,font:{size:10}},grid:{color:'rgba(31,45,69,.6)'}},x:{ticks:{font:{size:9}},grid:{color:'rgba(31,45,69,.6)'}}},plugins:{legend:{position:'bottom',labels:{font:{size:11},padding:12,usePointStyle:true}}}}});
<?php endif; ?>
new Chart(document.getElementById('riskChart'),{type:'doughnut',data:{labels:['Rendah','Sedang','Tinggi','Sangat Tinggi'],datasets:[{data:[<?=implode(',',array_values($riskData))?>],backgroundColor:['#00e5a0','#ffb627','#ff7800','#ff4d6d'],borderWidth:0,hoverOffset:6}]},options:{responsive:true,cutout:'70%',plugins:{legend:{position:'bottom',labels:{font:{size:10},padding:10,usePointStyle:true,pointStyleWidth:8}}}}});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>