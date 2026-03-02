<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_check.php';
requireLogin();

$id   = intval($_GET['id'] ?? 0);
if (!$id) { header('Location: /pages/predict.php'); exit; }

$db   = getDB();
$stmt = $db->prepare("SELECT * FROM predictions WHERE id=? AND user_id=?");
$stmt->bind_param('ii', $id, $_SESSION['user_id']);
$stmt->execute();
$pred = $stmt->get_result()->fetch_assoc();
$db->close();

if (!$pred) { header('Location: /pages/history.php'); exit; }

$isDiabetes = $pred['result'] == 1;
$prob       = round($pred['probability_diabetes'] * 100, 1);
$risk       = $pred['risk_level'];

$riskConfig = [
    'Rendah'       => ['color'=>'#00e5a0','bg'=>'rgba(0,229,160,0.08)','border'=>'rgba(0,229,160,0.25)','label'=>'RENDAH'],
    'Sedang'       => ['color'=>'#ffb627','bg'=>'rgba(255,182,39,0.08)','border'=>'rgba(255,182,39,0.25)','label'=>'SEDANG'],
    'Tinggi'       => ['color'=>'#ff7800','bg'=>'rgba(255,120,0,0.08)', 'border'=>'rgba(255,120,0,0.25)', 'label'=>'TINGGI'],
    'Sangat Tinggi'=> ['color'=>'#ff4d6d','bg'=>'rgba(255,77,109,0.08)','border'=>'rgba(255,77,109,0.25)','label'=>'SANGAT TINGGI'],
];
$rc = $riskConfig[$risk];

$recommendations = [
    'Rendah'       => [['🥗','Pertahankan Pola Makan Sehat','Konsumsi sayuran, buah, dan protein rendah lemak. Batasi gula dan karbohidrat olahan.'],['🏃','Tetap Aktif Berolahraga','Minimal 150 menit aktivitas fisik per minggu. Jalan kaki, bersepeda, atau berenang.'],['🩺','Pemeriksaan Rutin','Cek gula darah minimal 1x per tahun untuk memantau kesehatan metabolik.'],['😴','Istirahat Cukup','Tidur 7–9 jam per malam membantu regulasi hormon insulin.']],
    'Sedang'       => [['🍚','Kurangi Karbohidrat Sederhana','Ganti nasi putih dengan nasi merah. Hindari minuman manis dan makanan olahan.'],['⚖️','Turunkan Berat Badan 5–7%','Penurunan berat badan moderat terbukti mengurangi risiko diabetes secara signifikan.'],['🏋️','Tingkatkan Aktivitas Fisik','30 menit olahraga intensitas sedang setiap hari. Kombinasikan kardio dan kekuatan.'],['🩺','Konsultasi Dokter','Jadwalkan pemeriksaan HbA1c dan glukosa darah puasa secepatnya.']],
    'Tinggi'       => [['🚨','Segera Konsultasi Dokter','Risiko tinggi. Konsultasikan segera dengan dokter untuk rencana pengelolaan.'],['🥦','Diet Rendah Glikemik Ketat','Hindari makanan tinggi gula. Fokus pada sayuran non-tepung dan protein tanpa lemak.'],['📉','Monitor Gula Darah Harian','Gunakan glukometer untuk memantau kadar gula darah setiap hari.'],['🚭','Berhenti Merokok','Merokok meningkatkan resistensi insulin. Cari bantuan untuk berhenti merokok.']],
    'Sangat Tinggi'=> [['🏥','SEGERA ke Dokter/RS','Risiko sangat tinggi. Jangan tunda — periksakan diri ke dokter secepatnya.'],['💊','Diskusikan Opsi Pengobatan','Dokter mungkin merekomendasikan Metformin untuk mencegah progresi diabetes.'],['📋','Program Gaya Hidup Intensif','Ikuti program pencegahan diabetes dengan bimbingan dietitian dan fisioterapis.'],['👨‍👩‍👧','Libatkan Keluarga','Dukungan keluarga sangat penting dalam perubahan gaya hidup.']],
];
$recs = $recommendations[$risk];

$pageTitle='Hasil Prediksi';
include __DIR__.'/../includes/header.php';
?>

<!-- Breadcrumb -->
<div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--muted);margin-bottom:24px" class="fade-up">
    <a href="/pages/predict.php" style="color:var(--muted);text-decoration:none;hover:color:var(--accent)">Prediksi</a>
    <i class="fas fa-chevron-right" style="font-size:9px"></i>
    <span style="color:var(--text)">Hasil #<?= $pred['id'] ?></span>
</div>

<!-- Main Result Card -->
<div class="card fade-up" style="background:<?= $rc['bg'] ?>;border-color:<?= $rc['border'] ?>;padding:28px;margin-bottom:16px">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:20px;flex-wrap:wrap">
        <div>
            <div style="font-size:11px;color:var(--muted);margin-bottom:6px"><?= date('d M Y, H:i', strtotime($pred['created_at'])) ?></div>
            <div style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:#fff"><?= htmlspecialchars($pred['patient_name']?:'Anonim') ?></div>
            <div style="font-size:13px;color:var(--muted);margin-top:3px">
                <?= $pred['gender']==='Male'?'Laki-laki':($pred['gender']==='Female'?'Perempuan':'Lainnya') ?>, <?= $pred['age'] ?> tahun
            </div>
        </div>
        <div style="text-align:center">
            <!-- Big probability circle -->
            <div style="position:relative;width:110px;height:110px;margin:0 auto 10px">
                <svg width="110" height="110" style="transform:rotate(-90deg)">
                    <circle cx="55" cy="55" r="48" fill="none" stroke="rgba(255,255,255,0.06)" stroke-width="8"/>
                    <circle cx="55" cy="55" r="48" fill="none" stroke="<?= $rc['color'] ?>" stroke-width="8"
                        stroke-dasharray="<?= round(301.6 * $prob / 100, 1) ?> 301.6"
                        stroke-linecap="round"/>
                </svg>
                <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center">
                    <div style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:<?= $rc['color'] ?>"><?= $prob ?>%</div>
                    <div style="font-size:9px;color:var(--muted)">RISIKO</div>
                </div>
            </div>
            <div style="font-family:'Syne',sans-serif;font-size:13px;font-weight:700;letter-spacing:0.1em;color:<?= $rc['color'] ?>"><?= $rc['label'] ?></div>
            <div style="font-size:12px;color:<?= $isDiabetes?'var(--danger)':'var(--accent)' ?>;margin-top:6px;font-weight:600">
                <?= $isDiabetes?'⚠ DIABETES TERDETEKSI':'✓ NON-DIABETES' ?>
            </div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px" class="fade-up-2">

    <!-- Data Klinis -->
    <div class="card" style="padding:20px">
        <div style="font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--muted);margin-bottom:16px">Data Klinis Input</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
            <?php
            $dp=[
                ['BMI','bmi',''],['HbA1c','hba1c_level','%'],
                ['Glukosa','blood_glucose_level',' mg/dL'],
                ['Hipertensi','hypertension','','bool'],
                ['P. Jantung','heart_disease','','bool'],
                ['Merokok','smoking_history','','text'],
            ];
            foreach($dp as $d):
                $val = $pred[$d[1]];
                if(isset($d[3])&&$d[3]==='bool') $val=$val?'Ya':'Tidak';
                elseif(isset($d[3])&&$d[3]==='text') $val=ucfirst($val);
            ?>
            <div style="background:var(--surface2);border-radius:8px;padding:12px;text-align:center">
                <div style="font-size:10px;color:var(--muted);margin-bottom:4px"><?= $d[0] ?></div>
                <div style="font-weight:700;color:#fff;font-size:14px"><?= $val ?><?= $d[2] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Risk Meter -->
    <div class="card" style="padding:20px">
        <div style="font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--muted);margin-bottom:16px">Level Risiko</div>
        <div style="margin-bottom:20px">
            <?php foreach(['Rendah'=>[0,20,'#00e5a0'],'Sedang'=>[20,50,'#ffb627'],'Tinggi'=>[50,75,'#ff7800'],'Sangat Tinggi'=>[75,100,'#ff4d6d']] as $rn=>$rv): ?>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
                <div style="width:80px;font-size:10px;color:<?= $rn===$risk?'#fff':'var(--muted)' ?>;font-weight:<?= $rn===$risk?'700':'400' ?>"><?= $rn ?></div>
                <div style="flex:1;height:6px;background:var(--surface2);border-radius:3px;overflow:hidden">
                    <div style="width:<?= $rv[1]-$rv[0] ?>%;height:100%;background:<?= $rv[2] ?>;opacity:<?= $rn===$risk?'1':'0.3' ?>;border-radius:3px"></div>
                </div>
                <div style="font-size:10px;color:var(--muted);width:40px;text-align:right"><?= $rv[0] ?>–<?= $rv[1] ?>%</div>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="height:1px;background:var(--border);margin-bottom:14px"></div>
        <div style="font-size:11px;color:var(--muted)">Probabilitas Anda:</div>
        <div style="margin-top:6px;height:8px;background:var(--surface2);border-radius:4px;overflow:hidden">
            <div style="width:<?= $prob ?>%;height:100%;background:<?= $rc['color'] ?>;border-radius:4px;transition:width 1s ease"></div>
        </div>
        <div style="font-size:20px;font-family:'Syne',sans-serif;font-weight:800;color:<?= $rc['color'] ?>;margin-top:8px"><?= $prob ?>%</div>
    </div>
</div>

<!-- Rekomendasi -->
<div class="card fade-up-3" style="padding:20px;margin-bottom:20px">
    <div style="font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--muted);margin-bottom:16px">
        <i class="fas fa-lightbulb" style="color:var(--warning);margin-right:6px"></i>Rekomendasi Gaya Hidup — Risiko <?= $risk ?>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
        <?php foreach($recs as $rec): ?>
        <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:14px;display:flex;gap:12px;align-items:flex-start">
            <span style="font-size:20px;line-height:1"><?= $rec[0] ?></span>
            <div>
                <div style="font-weight:600;font-size:13px;color:#fff;margin-bottom:3px"><?= $rec[1] ?></div>
                <div style="font-size:11px;color:var(--muted);line-height:1.6"><?= $rec[2] ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Actions -->
<div style="display:flex;gap:10px;flex-wrap:wrap" class="fade-up-4">
    <a href="/pages/predict.php" class="btn-primary" style="text-decoration:none;display:inline-flex;align-items:center;gap:8px">
        <i class="fas fa-plus"></i> Prediksi Baru
    </a>
    <button onclick="exportPDF()" class="btn-ghost" style="background:rgba(0,229,160,0.08);border-color:rgba(0,229,160,0.3);color:var(--accent)">
        <i class="fas fa-file-pdf"></i> Export PDF
    </button>
    <a href="/pages/history.php" class="btn-ghost" style="text-decoration:none">
        <i class="fas fa-history"></i> Riwayat
    </a>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
function exportPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.setFillColor(0, 229, 160);
    doc.rect(0, 0, 210, 30, 'F');
    doc.setTextColor(10, 15, 26);
    doc.setFontSize(18); doc.setFont('helvetica','bold');
    doc.text('SmartHealth', 15, 13);
    doc.setFontSize(9); doc.setFont('helvetica','normal');
    doc.text('Laporan Prediksi Risiko Diabetes', 15, 21);
    doc.text('<?= date('d/m/Y H:i') ?>', 155, 21);
    doc.setTextColor(30,30,30);
    doc.setFontSize(13); doc.setFont('helvetica','bold');
    doc.text('Informasi Pasien', 15, 44);
    doc.setFontSize(10); doc.setFont('helvetica','normal');
    doc.text('Nama    : <?= addslashes($pred['patient_name']?:'Anonim') ?>', 15, 53);
    doc.text('Gender  : <?= $pred['gender']==='Male'?'Laki-laki':'Perempuan' ?>', 15, 60);
    doc.text('Usia    : <?= $pred['age'] ?> tahun', 15, 67);
    doc.setFontSize(13); doc.setFont('helvetica','bold');
    doc.text('Hasil Prediksi', 15, 80);
    doc.setFontSize(10); doc.setFont('helvetica','normal');
    doc.text('Hasil         : <?= $isDiabetes?'DIABETES TERDETEKSI':'NON-DIABETES' ?>', 15, 89);
    doc.text('Probabilitas  : <?= $prob ?>%', 15, 96);
    doc.text('Tingkat Risiko: <?= $risk ?>', 15, 103);
    doc.setFontSize(13); doc.setFont('helvetica','bold');
    doc.text('Data Klinis', 15, 116);
    doc.setFontSize(10); doc.setFont('helvetica','normal');
    doc.text('BMI           : <?= $pred['bmi'] ?>', 15, 125);
    doc.text('HbA1c         : <?= $pred['hba1c_level'] ?>%', 15, 132);
    doc.text('Glukosa Darah : <?= $pred['blood_glucose_level'] ?> mg/dL', 15, 139);
    doc.text('Hipertensi    : <?= $pred['hypertension']?'Ya':'Tidak' ?>', 15, 146);
    doc.text('P. Jantung    : <?= $pred['heart_disease']?'Ya':'Tidak' ?>', 15, 153);
    doc.setFontSize(8); doc.setTextColor(150,150,150);
    doc.text('* Hasil ini bersifat indikatif dan tidak menggantikan diagnosis medis profesional.', 15, 170);
    doc.setFillColor(0, 229, 160);
    doc.rect(0, 282, 210, 15, 'F');
    doc.setTextColor(10,15,26); doc.setFontSize(8);
    doc.text('SmartHealth — INFEST Hackathon 2026 | AI & Data Track', 15, 291);
    doc.save('SmartHealth_#<?= $pred['id'] ?>.pdf');
}
</script>

<?php include __DIR__.'/../includes/footer.php'; ?>