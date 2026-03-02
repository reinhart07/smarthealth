<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_check.php';
requireLogin();

$db     = getDB();
$userId = $_SESSION['user_id'];

$total       = $db->query("SELECT COUNT(*) as c FROM predictions WHERE user_id=$userId")->fetch_assoc()['c'];
$diabetic    = $db->query("SELECT COUNT(*) as c FROM predictions WHERE user_id=$userId AND result=1")->fetch_assoc()['c'];
$nonDiabetic = $total - $diabetic;
$avgProb     = $db->query("SELECT AVG(probability_diabetes) as a FROM predictions WHERE user_id=$userId")->fetch_assoc()['a'];

$riskRows = $db->query("SELECT risk_level, COUNT(*) as c FROM predictions WHERE user_id=$userId GROUP BY risk_level");
$riskData = ['Rendah'=>0,'Sedang'=>0,'Tinggi'=>0,'Sangat Tinggi'=>0];
while($r=$riskRows->fetch_assoc()) $riskData[$r['risk_level']]=(int)$r['c'];

$trendRows = $db->query("SELECT DATE(created_at) as day, COUNT(*) as total, SUM(result) as positif FROM predictions WHERE user_id=$userId AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY DATE(created_at) ORDER BY day");
$trendDays=$trendTotal=$trendPositif=[];
while($r=$trendRows->fetch_assoc()){
    $trendDays[]=date('d M',strtotime($r['day']));
    $trendTotal[]=(int)$r['total'];
    $trendPositif[]=(int)$r['positif'];
}

$recentRows = $db->query("SELECT * FROM predictions WHERE user_id=$userId ORDER BY created_at DESC LIMIT 6");
$recent=[];
while($r=$recentRows->fetch_assoc()) $recent[]=$r;
$db->close();

$pageTitle='Dashboard';
include __DIR__.'/../includes/header.php';
?>

<div class="page-header fade-up" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
    <div>
        <div class="page-title">Dashboard</div>
        <div class="page-sub">Selamat datang kembali, <span style="color:var(--accent)"><?= htmlspecialchars($_SESSION['user_name']) ?></span></div>
    </div>
    <a href="/pages/predict.php" class="btn-primary" style="display:inline-flex;align-items:center;gap:8px;text-decoration:none">
        <i class="fas fa-plus"></i> Prediksi Baru
    </a>
</div>

<!-- Stat Cards -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px" class="fade-up-2">

    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
            <div style="font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--muted)">Total Prediksi</div>
            <div style="width:34px;height:34px;background:rgba(0,229,160,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center">
                <i class="fas fa-clipboard-list" style="color:var(--accent);font-size:13px"></i>
            </div>
        </div>
        <div style="font-size:32px;font-family:'Syne',sans-serif;font-weight:800;color:#fff"><?= $total ?></div>
        <div style="font-size:11px;color:var(--muted);margin-top:4px">Semua waktu</div>
    </div>

    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
            <div style="font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--muted)">Non-Diabetes</div>
            <div style="width:34px;height:34px;background:rgba(0,229,160,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center">
                <i class="fas fa-check-circle" style="color:var(--accent);font-size:13px"></i>
            </div>
        </div>
        <div style="font-size:32px;font-family:'Syne',sans-serif;font-weight:800;color:var(--accent)"><?= $nonDiabetic ?></div>
        <div style="font-size:11px;color:var(--muted);margin-top:4px"><?= $total > 0 ? round($nonDiabetic/$total*100) : 0 ?>% dari total</div>
    </div>

    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
            <div style="font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--muted)">Terdeteksi DM</div>
            <div style="width:34px;height:34px;background:rgba(255,77,109,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center">
                <i class="fas fa-exclamation-circle" style="color:var(--danger);font-size:13px"></i>
            </div>
        </div>
        <div style="font-size:32px;font-family:'Syne',sans-serif;font-weight:800;color:var(--danger)"><?= $diabetic ?></div>
        <div style="font-size:11px;color:var(--muted);margin-top:4px"><?= $total > 0 ? round($diabetic/$total*100) : 0 ?>% dari total</div>
    </div>

    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
            <div style="font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--muted)">Akurasi Model</div>
            <div style="width:34px;height:34px;background:rgba(0,184,255,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center">
                <i class="fas fa-brain" style="color:var(--accent2);font-size:13px"></i>
            </div>
        </div>
        <div style="font-size:32px;font-family:'Syne',sans-serif;font-weight:800;color:var(--accent2)">97.1%</div>
        <div style="font-size:11px;color:var(--muted);margin-top:4px">Random Forest</div>
    </div>
</div>

<!-- Charts Row -->
<div style="display:grid;grid-template-columns:1fr 1fr 1.4fr;gap:16px;margin-bottom:24px" class="fade-up-3">

    <!-- Donut Hasil -->
    <div class="card" style="padding:20px">
        <div style="font-size:11px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:var(--muted);margin-bottom:16px">Distribusi Hasil</div>
        <?php if($total>0): ?>
        <canvas id="pieChart" height="180"></canvas>
        <?php else: ?>
        <div style="height:180px;display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:12px;flex-direction:column;gap:8px">
            <i class="fas fa-chart-pie" style="font-size:28px;opacity:0.2"></i>Belum ada data
        </div>
        <?php endif; ?>
    </div>

    <!-- Donut Risiko -->
    <div class="card" style="padding:20px">
        <div style="font-size:11px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:var(--muted);margin-bottom:16px">Level Risiko</div>
        <?php if($total>0): ?>
        <canvas id="riskChart" height="180"></canvas>
        <?php else: ?>
        <div style="height:180px;display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:12px;flex-direction:column;gap:8px">
            <i class="fas fa-chart-donut" style="font-size:28px;opacity:0.2"></i>Belum ada data
        </div>
        <?php endif; ?>
    </div>

    <!-- Trend -->
    <div class="card" style="padding:20px">
        <div style="font-size:11px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:var(--muted);margin-bottom:16px">Tren 7 Hari</div>
        <?php if(count($trendDays)>0): ?>
        <canvas id="trendChart" height="180"></canvas>
        <?php else: ?>
        <div style="height:180px;display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:12px;flex-direction:column;gap:8px">
            <i class="fas fa-chart-line" style="font-size:28px;opacity:0.2"></i>Belum ada data minggu ini
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Recent Table -->
<div class="card fade-up-4">
    <div style="padding:18px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
        <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:14px;color:#fff">Prediksi Terbaru</div>
        <a href="/pages/history.php" style="font-size:11px;color:var(--accent);text-decoration:none">Lihat semua →</a>
    </div>
    <?php if(count($recent)>0): ?>
    <table class="data-table">
        <thead>
            <tr>
                <th>Pasien</th><th>Hasil</th><th>Probabilitas</th><th>Risiko</th><th>Tanggal</th><th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($recent as $p):
            $rb=['Rendah'=>'badge-green','Sedang'=>'badge-yellow','Tinggi'=>'badge-orange','Sangat Tinggi'=>'badge-red'][$p['risk_level']];
        ?>
            <tr>
                <td style="font-weight:500;color:#fff"><?= htmlspecialchars($p['patient_name']?:'Anonim') ?></td>
                <td>
                    <span class="badge <?= $p['result']?'badge-red':'badge-green' ?>">
                        <?= $p['result']?'⚠ Diabetes':'✓ Non-DM' ?>
                    </span>
                </td>
                <td>
                    <div style="display:flex;align-items:center;gap:8px">
                        <div style="width:60px;height:4px;background:var(--surface2);border-radius:2px">
                            <div style="width:<?= round($p['probability_diabetes']*100) ?>%;height:100%;border-radius:2px;background:<?= $p['result']?'var(--danger)':'var(--accent)' ?>"></div>
                        </div>
                        <span style="color:var(--muted);font-size:12px"><?= round($p['probability_diabetes']*100,1) ?>%</span>
                    </div>
                </td>
                <td><span class="badge <?= $rb ?>"><?= $p['risk_level'] ?></span></td>
                <td style="color:var(--muted);font-size:12px"><?= date('d M Y',strtotime($p['created_at'])) ?></td>
                <td><a href="/pages/result.php?id=<?= $p['id'] ?>" style="color:var(--accent);font-size:12px;text-decoration:none">Detail →</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div style="padding:48px;text-align:center;color:var(--muted)">
        <i class="fas fa-clipboard" style="font-size:32px;opacity:0.2;margin-bottom:12px;display:block"></i>
        <div style="font-size:13px">Belum ada prediksi.</div>
        <a href="/pages/predict.php" style="color:var(--accent);font-size:12px;text-decoration:none;margin-top:6px;display:inline-block">Buat prediksi pertama →</a>
    </div>
    <?php endif; ?>
</div>

<script>
Chart.defaults.color = '#64748b';
Chart.defaults.borderColor = 'rgba(31,45,69,0.5)';

<?php if($total>0): ?>
new Chart(document.getElementById('pieChart'),{
    type:'doughnut',
    data:{
        labels:['Non-Diabetes','Diabetes'],
        datasets:[{data:[<?=$nonDiabetic?>,<?=$diabetic?>],backgroundColor:['#00e5a0','#ff4d6d'],borderWidth:0,hoverOffset:6}]
    },
    options:{responsive:true,cutout:'72%',plugins:{legend:{position:'bottom',labels:{font:{size:11},padding:14,usePointStyle:true,pointStyleWidth:8}}}}
});
new Chart(document.getElementById('riskChart'),{
    type:'doughnut',
    data:{
        labels:['Rendah','Sedang','Tinggi','Sangat Tinggi'],
        datasets:[{data:[<?=implode(',',array_values($riskData))?>],backgroundColor:['#00e5a0','#ffb627','#ff7800','#ff4d6d'],borderWidth:0,hoverOffset:6}]
    },
    options:{responsive:true,cutout:'72%',plugins:{legend:{position:'bottom',labels:{font:{size:10},padding:10,usePointStyle:true,pointStyleWidth:8}}}}
});
<?php endif; ?>

<?php if(count($trendDays)>0): ?>
new Chart(document.getElementById('trendChart'),{
    type:'line',
    data:{
        labels:<?=json_encode($trendDays)?>,
        datasets:[
            {label:'Total',data:<?=json_encode($trendTotal)?>,borderColor:'#00b8ff',backgroundColor:'rgba(0,184,255,0.08)',tension:0.4,fill:true,pointRadius:3,pointBackgroundColor:'#00b8ff'},
            {label:'Diabetes',data:<?=json_encode($trendPositif)?>,borderColor:'#ff4d6d',backgroundColor:'rgba(255,77,109,0.08)',tension:0.4,fill:true,pointRadius:3,pointBackgroundColor:'#ff4d6d'}
        ]
    },
    options:{responsive:true,scales:{y:{beginAtZero:true,ticks:{stepSize:1,font:{size:10}},grid:{color:'rgba(31,45,69,0.6)'}},x:{ticks:{font:{size:10}},grid:{color:'rgba(31,45,69,0.6)'}}},plugins:{legend:{position:'bottom',labels:{font:{size:11},padding:14,usePointStyle:true}}}}
});
<?php endif; ?>
</script>

<?php include __DIR__.'/../includes/footer.php'; ?>