<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_check.php';
requireLogin();

$db=$db=getDB(); $userId=$_SESSION['user_id'];
$filterResult=$_GET['result']??''; $filterRisk=$_GET['risk']??''; $search=$_GET['q']??'';
$where=["user_id=$userId"];
if($filterResult!=='') $where[]="result=".intval($filterResult);
if($filterRisk) $where[]="risk_level='".$db->real_escape_string($filterRisk)."'";
if($search) $where[]="patient_name LIKE '%".$db->real_escape_string($search)."%'";
$wc='WHERE '.implode(' AND ',$where);
$perPage=10; $page=max(1,intval($_GET['page']??1)); $offset=($page-1)*$perPage;
$total=$db->query("SELECT COUNT(*) as c FROM predictions $wc")->fetch_assoc()['c'];
$pages=ceil($total/$perPage);
$rows=$db->query("SELECT * FROM predictions $wc ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
$predictions=[];
while($r=$rows->fetch_assoc()) $predictions[]=$r;
$db->close();
$pageTitle='Riwayat';
include __DIR__.'/../includes/header.php';
?>

<div class="page-header fade-up" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
    <div>
        <div class="page-title">Riwayat Prediksi</div>
        <div class="page-sub"><?= $total ?> total prediksi tersimpan</div>
    </div>
    <a href="/pages/predict.php" class="btn-primary" style="text-decoration:none;display:inline-flex;align-items:center;gap:8px">
        <i class="fas fa-plus"></i> Prediksi Baru
    </a>
</div>

<!-- Filter -->
<div class="card fade-up" style="padding:16px;margin-bottom:16px">
    <form method="GET" style="display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end">
        <div style="flex:1;min-width:160px">
            <label class="form-label">Cari Pasien</label>
            <div style="position:relative">
                <i class="fas fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:11px"></i>
                <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Nama pasien..." class="form-input" style="padding-left:30px">
            </div>
        </div>
        <div>
            <label class="form-label">Hasil</label>
            <select name="result" class="form-input" style="width:130px">
                <option value="">Semua</option>
                <option value="0" <?= $filterResult==='0'?'selected':'' ?>>Non-Diabetes</option>
                <option value="1" <?= $filterResult==='1'?'selected':'' ?>>Diabetes</option>
            </select>
        </div>
        <div>
            <label class="form-label">Risiko</label>
            <select name="risk" class="form-input" style="width:150px">
                <option value="">Semua</option>
                <?php foreach(['Rendah','Sedang','Tinggi','Sangat Tinggi'] as $r): ?>
                <option value="<?=$r?>" <?=$filterRisk===$r?'selected':''?>><?=$r?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn-primary" style="padding:10px 16px;font-size:12px">
            <i class="fas fa-filter"></i> Filter
        </button>
        <a href="/pages/history.php" class="btn-ghost" style="padding:10px 14px;font-size:12px">Reset</a>
    </form>
</div>

<!-- Table -->
<div class="card fade-up-2" style="overflow:hidden">
    <?php if(count($predictions)>0): ?>
    <div style="overflow-x:auto">
    <table class="data-table">
        <thead><tr>
            <th>#</th><th>Pasien</th><th>Gender / Usia</th>
            <th>BMI</th><th>HbA1c</th><th>Glukosa</th>
            <th>Hasil</th><th>Prob.</th><th>Risiko</th><th>Tanggal</th><th></th>
        </tr></thead>
        <tbody>
        <?php foreach($predictions as $i=>$p):
            $rb=['Rendah'=>'badge-green','Sedang'=>'badge-yellow','Tinggi'=>'badge-orange','Sangat Tinggi'=>'badge-red'][$p['risk_level']];
        ?>
            <tr>
                <td style="color:var(--muted)"><?=$offset+$i+1?></td>
                <td style="font-weight:600;color:#fff"><?=htmlspecialchars($p['patient_name']?:'Anonim')?></td>
                <td style="color:var(--muted)"><?=$p['gender']?>, <?=$p['age']?>th</td>
                <td style="color:var(--text)"><?=$p['bmi']?></td>
                <td style="color:var(--text)"><?=$p['hba1c_level']?>%</td>
                <td style="color:var(--text)"><?=$p['blood_glucose_level']?></td>
                <td><span class="badge <?=$p['result']?'badge-red':'badge-green'?>"><?=$p['result']?'⚠ DM':'✓ Non-DM'?></span></td>
                <td>
                    <div style="display:flex;align-items:center;gap:6px">
                        <div style="width:40px;height:3px;background:var(--surface2);border-radius:2px">
                            <div style="width:<?=round($p['probability_diabetes']*100)?>%;height:100%;background:<?=$p['result']?'var(--danger)':'var(--accent)'?>;border-radius:2px"></div>
                        </div>
                        <span style="font-size:11px;color:var(--muted)"><?=round($p['probability_diabetes']*100,1)?>%</span>
                    </div>
                </td>
                <td><span class="badge <?=$rb?>"><?=$p['risk_level']?></span></td>
                <td style="color:var(--muted);font-size:11px"><?=date('d/m/Y',strtotime($p['created_at']))?></td>
                <td><a href="/pages/result.php?id=<?=$p['id']?>" style="color:var(--accent);font-size:12px;text-decoration:none">Detail →</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php if($pages>1): ?>
    <div style="padding:14px 16px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
        <div style="font-size:11px;color:var(--muted)">Hal. <?=$page?> dari <?=$pages?></div>
        <div style="display:flex;gap:6px">
            <?php for($i=1;$i<=$pages;$i++): ?>
            <a href="?page=<?=$i?>&q=<?=urlencode($search)?>&result=<?=urlencode($filterResult)?>&risk=<?=urlencode($filterRisk)?>"
               style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;border-radius:6px;font-size:12px;text-decoration:none;
                      background:<?=$i===$page?'var(--accent)':'var(--surface2)'?>;color:<?=$i===$page?'#0a0f1a':'var(--muted)'?>;font-weight:<?=$i===$page?'700':'400'?>">
                <?=$i?>
            </a>
            <?php endfor; ?>
        </div>
    </div>
    <?php endif; ?>
    <?php else: ?>
    <div style="padding:60px;text-align:center;color:var(--muted)">
        <i class="fas fa-search" style="font-size:28px;opacity:0.2;margin-bottom:12px;display:block"></i>
        <div style="font-size:13px">Tidak ada data ditemukan.</div>
        <a href="/pages/predict.php" style="color:var(--accent);font-size:12px;text-decoration:none;margin-top:6px;display:inline-block">Buat prediksi →</a>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__.'/../includes/footer.php'; ?>