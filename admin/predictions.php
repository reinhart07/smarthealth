<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_check.php';
requireAdmin();

$db = getDB();
$search = $_GET['q'] ?? '';
$filterResult = $_GET['result'] ?? '';
$filterRisk   = $_GET['risk'] ?? '';
$perPage = 15; $page = max(1,intval($_GET['page']??1)); $offset=($page-1)*$perPage;

$where = [];
if ($search) $where[] = "(u.name LIKE '%".$db->real_escape_string($search)."%' OR p.patient_name LIKE '%".$db->real_escape_string($search)."%')";
if ($filterResult !== '') $where[] = "p.result=".intval($filterResult);
if ($filterRisk) $where[] = "p.risk_level='".$db->real_escape_string($filterRisk)."'";
$wc = count($where) ? 'WHERE '.implode(' AND ',$where) : '';

$total = $db->query("SELECT COUNT(*) as c FROM predictions p JOIN users u ON p.user_id=u.id $wc")->fetch_assoc()['c'];
$pages = ceil($total/$perPage);
$rows  = $db->query("SELECT p.*, u.name as user_name FROM predictions p JOIN users u ON p.user_id=u.id $wc ORDER BY p.created_at DESC LIMIT $perPage OFFSET $offset");
$preds = []; while($r=$rows->fetch_assoc()) $preds[]=$r;
$db->close();

$pageTitle = 'Semua Prediksi';
include __DIR__ . '/../includes/header_admin.php';
?>

<div class="page-header fade-up">
    <div style="font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--warning);margin-bottom:4px">⚡ Admin Panel</div>
    <div class="page-title">Semua Prediksi</div>
    <div class="page-sub"><?=$total?> total prediksi dari seluruh pengguna</div>
</div>

<div class="card fade-up" style="padding:16px;margin-bottom:16px">
    <form method="GET" style="display:flex;flex-wrap:wrap;gap:10px">
        <div style="position:relative;flex:1;min-width:160px">
            <i class="fas fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:11px"></i>
            <input type="text" name="q" value="<?=htmlspecialchars($search)?>" placeholder="Cari nama user/pasien..." class="form-input" style="padding-left:30px">
        </div>
        <select name="result" class="form-input" style="width:140px">
            <option value="">Semua Hasil</option>
            <option value="0" <?=$filterResult==='0'?'selected':''?>>Non-Diabetes</option>
            <option value="1" <?=$filterResult==='1'?'selected':''?>>Diabetes</option>
        </select>
        <select name="risk" class="form-input" style="width:150px">
            <option value="">Semua Risiko</option>
            <?php foreach(['Rendah','Sedang','Tinggi','Sangat Tinggi'] as $r): ?>
            <option value="<?=$r?>" <?=$filterRisk===$r?'selected':''?>><?=$r?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn-primary" style="padding:10px 16px;font-size:12px"><i class="fas fa-filter"></i> Filter</button>
        <a href="/admin/predictions.php" class="btn-ghost" style="padding:10px 14px;font-size:12px">Reset</a>
    </form>
</div>

<div class="card fade-up-2" style="overflow:hidden">
    <div style="overflow-x:auto">
    <table class="data-table">
        <thead><tr>
            <th>#</th><th>User</th><th>Pasien</th><th>Hasil</th>
            <th>Prob.</th><th>Risiko</th><th>BMI</th><th>HbA1c</th><th>Glukosa</th><th>Tanggal</th><th></th>
        </tr></thead>
        <tbody>
        <?php foreach($preds as $i=>$p):
            $rb=['Rendah'=>'badge-green','Sedang'=>'badge-yellow','Tinggi'=>'badge-orange','Sangat Tinggi'=>'badge-red'][$p['risk_level']];
        ?>
        <tr>
            <td style="color:var(--muted)"><?=$offset+$i+1?></td>
            <td style="font-size:12px;color:var(--muted)"><?=htmlspecialchars($p['user_name'])?></td>
            <td style="font-weight:600;color:#fff"><?=htmlspecialchars($p['patient_name']?:'Anonim')?></td>
            <td><span class="badge <?=$p['result']?'badge-red':'badge-green'?>"><?=$p['result']?'⚠ DM':'✓ Non'?></span></td>
            <td>
                <div style="display:flex;align-items:center;gap:6px">
                    <div style="width:40px;height:3px;background:var(--surface2);border-radius:2px">
                        <div style="width:<?=round($p['probability_diabetes']*100)?>%;height:100%;background:<?=$p['result']?'var(--danger)':'var(--accent)'?>;border-radius:2px"></div>
                    </div>
                    <span style="font-size:11px;color:var(--muted)"><?=round($p['probability_diabetes']*100,1)?>%</span>
                </div>
            </td>
            <td><span class="badge <?=$rb?>"><?=$p['risk_level']?></span></td>
            <td style="color:var(--text)"><?=$p['bmi']?></td>
            <td style="color:var(--text)"><?=$p['hba1c_level']?>%</td>
            <td style="color:var(--text)"><?=$p['blood_glucose_level']?></td>
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
        <div style="display:flex;gap:5px">
            <?php for($i=1;$i<=$pages;$i++): ?>
            <a href="?page=<?=$i?>&q=<?=urlencode($search)?>&result=<?=urlencode($filterResult)?>&risk=<?=urlencode($filterRisk)?>"
               style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;border-radius:6px;font-size:12px;text-decoration:none;background:<?=$i===$page?'var(--accent)':'var(--surface2)'?>;color:<?=$i===$page?'#0a0f1a':'var(--muted)'?>;font-weight:<?=$i===$page?'700':'400'?>">
                <?=$i?>
            </a>
            <?php endfor; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>