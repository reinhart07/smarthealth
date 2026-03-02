<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_check.php';
requireLogin();
$pageTitle='Prediksi Risiko';
include __DIR__.'/../includes/header.php';
?>

<div class="page-header fade-up">
    <div class="page-title">Prediksi Risiko Diabetes</div>
    <div class="page-sub">Masukkan data klinis pasien untuk analisa berbasis AI</div>
</div>

<!-- Info banner -->
<div style="background:rgba(0,184,255,0.06);border:1px solid rgba(0,184,255,0.2);border-radius:10px;padding:12px 16px;margin-bottom:24px;display:flex;align-items:center;gap:10px;font-size:12px;color:var(--accent2)" class="fade-up">
    <i class="fas fa-info-circle"></i>
    Hasil prediksi bersifat indikatif. Selalu konsultasikan hasil dengan tenaga medis profesional.
</div>

<form id="predictForm">
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px" class="fade-up-2">

    <!-- Identitas -->
    <div class="card" style="padding:24px">
        <div style="font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--accent);margin-bottom:18px;display:flex;align-items:center;gap:8px">
            <i class="fas fa-user"></i> Identitas Pasien
        </div>
        <div style="display:grid;gap:14px">
            <div>
                <label class="form-label">Nama Pasien</label>
                <input type="text" name="patient_name" placeholder="Opsional" class="form-input">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div>
                    <label class="form-label">Jenis Kelamin <span style="color:var(--danger)">*</span></label>
                    <select name="gender" required class="form-input">
                        <option value="">Pilih</option>
                        <option value="Male">Laki-laki</option>
                        <option value="Female">Perempuan</option>
                        <option value="Other">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Usia (tahun) <span style="color:var(--danger)">*</span></label>
                    <input type="number" name="age" min="1" max="120" step="0.1" required placeholder="45" class="form-input">
                </div>
            </div>
            <div>
                <label class="form-label">Riwayat Merokok <span style="color:var(--danger)">*</span></label>
                <select name="smoking_history" required class="form-input">
                    <option value="">Pilih</option>
                    <option value="never">Tidak Pernah</option>
                    <option value="former">Mantan Perokok</option>
                    <option value="current">Perokok Aktif</option>
                    <option value="not current">Tidak Aktif</option>
                    <option value="ever">Pernah</option>
                    <option value="No Info">Tidak Ada Info</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Data Klinis -->
    <div class="card" style="padding:24px">
        <div style="font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--accent);margin-bottom:18px;display:flex;align-items:center;gap:8px">
            <i class="fas fa-flask"></i> Data Klinis
        </div>
        <div style="display:grid;gap:14px">
            <div>
                <label class="form-label">BMI (Body Mass Index) <span style="color:var(--danger)">*</span></label>
                <input type="number" name="bmi" min="10" max="70" step="0.1" required placeholder="27.5" class="form-input">
                <div style="font-size:10px;color:var(--muted);margin-top:4px">Normal: 18.5–24.9 · Overweight: 25–29.9 · Obesitas: ≥30</div>
            </div>
            <div>
                <label class="form-label">Kadar HbA1c (%) <span style="color:var(--danger)">*</span></label>
                <input type="number" name="hba1c_level" min="3" max="15" step="0.1" required placeholder="6.5" class="form-input">
                <div style="font-size:10px;color:var(--muted);margin-top:4px">Normal: &lt;5.7% · Pre-DM: 5.7–6.4% · Diabetes: ≥6.5%</div>
            </div>
            <div>
                <label class="form-label">Kadar Glukosa Darah (mg/dL) <span style="color:var(--danger)">*</span></label>
                <input type="number" name="blood_glucose_level" min="50" max="400" required placeholder="126" class="form-input">
                <div style="font-size:10px;color:var(--muted);margin-top:4px">Normal: &lt;100 · Pre-DM: 100–125 · Diabetes: ≥126</div>
            </div>
        </div>
    </div>

</div>

<!-- Riwayat Penyakit -->
<div class="card fade-up-3" style="padding:24px;margin-top:16px">
    <div style="font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--accent);margin-bottom:18px;display:flex;align-items:center;gap:8px">
        <i class="fas fa-heartbeat"></i> Riwayat Penyakit
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
        <label style="display:flex;align-items:center;gap:14px;padding:16px;background:var(--surface2);border:1px solid var(--border);border-radius:10px;cursor:pointer;transition:border-color 0.2s" class="checkbox-card">
            <input type="checkbox" name="hypertension" value="1" style="display:none" class="cb-input">
            <div class="cb-box" style="width:20px;height:20px;border:2px solid var(--border);border-radius:5px;display:flex;align-items:center;justify-content:center;transition:all 0.2s;flex-shrink:0">
                <i class="fas fa-check" style="font-size:10px;color:#0a0f1a;display:none"></i>
            </div>
            <div>
                <div style="font-weight:600;font-size:13px;color:#fff">Hipertensi</div>
                <div style="font-size:11px;color:var(--muted)">Tekanan darah ≥140/90 mmHg</div>
            </div>
        </label>
        <label style="display:flex;align-items:center;gap:14px;padding:16px;background:var(--surface2);border:1px solid var(--border);border-radius:10px;cursor:pointer;transition:border-color 0.2s" class="checkbox-card">
            <input type="checkbox" name="heart_disease" value="1" style="display:none" class="cb-input">
            <div class="cb-box" style="width:20px;height:20px;border:2px solid var(--border);border-radius:5px;display:flex;align-items:center;justify-content:center;transition:all 0.2s;flex-shrink:0">
                <i class="fas fa-check" style="font-size:10px;color:#0a0f1a;display:none"></i>
            </div>
            <div>
                <div style="font-weight:600;font-size:13px;color:#fff">Penyakit Jantung</div>
                <div style="font-size:11px;color:var(--muted)">Riwayat penyakit kardiovaskular</div>
            </div>
        </label>
    </div>
</div>

<!-- Submit -->
<div style="margin-top:20px" class="fade-up-4">
    <button type="submit" id="submitBtn" class="btn-primary" style="width:100%;padding:15px;font-size:15px;display:flex;align-items:center;justify-content:center;gap:10px">
        <i class="fas fa-brain"></i> Analisa dengan AI
    </button>
</div>
</form>

<!-- Loading -->
<div id="loadingOverlay" style="display:none;position:fixed;inset:0;background:rgba(10,15,26,0.85);backdrop-filter:blur(8px);display:none;align-items:center;justify-content:center;z-index:100">
    <div style="text-align:center">
        <div style="width:56px;height:56px;border:3px solid var(--border);border-top-color:var(--accent);border-radius:50%;animation:spin 0.8s linear infinite;margin:0 auto 16px"></div>
        <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:16px;color:#fff">Menganalisa Data</div>
        <div style="font-size:12px;color:var(--muted);margin-top:6px">Model AI sedang bekerja...</div>
    </div>
</div>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
.checkbox-card:hover { border-color: var(--accent) !important; }
.checkbox-card.checked { border-color: var(--accent) !important; background: rgba(0,229,160,0.05) !important; }
.cb-box.checked { background: var(--accent) !important; border-color: var(--accent) !important; }
.cb-box.checked i { display: block !important; }
</style>

<script>
// Checkbox custom styling
document.querySelectorAll('.checkbox-card').forEach(card => {
    const input = card.querySelector('.cb-input');
    const box   = card.querySelector('.cb-box');
    card.addEventListener('click', () => {
        input.checked = !input.checked;
        card.classList.toggle('checked', input.checked);
        box.classList.toggle('checked', input.checked);
    });
});

document.getElementById('predictForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn     = document.getElementById('submitBtn');
    const overlay = document.getElementById('loadingOverlay');
    btn.disabled  = true;
    overlay.style.display = 'flex';

    const fd   = new FormData(this);
    const data = {
        patient_name:        fd.get('patient_name') || 'Anonim',
        gender:              fd.get('gender'),
        age:                 parseFloat(fd.get('age')),
        hypertension:        fd.get('hypertension') ? 1 : 0,
        heart_disease:       fd.get('heart_disease') ? 1 : 0,
        smoking_history:     fd.get('smoking_history'),
        bmi:                 parseFloat(fd.get('bmi')),
        hba1c_level:         parseFloat(fd.get('hba1c_level')),
        blood_glucose_level: parseInt(fd.get('blood_glucose_level')),
    };

    try {
        const res    = await fetch('/api/predict_handler.php', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(data) });
        const result = await res.json();
        if (result.success) {
            window.location.href = '/pages/result.php?id=' + result.prediction_id;
        } else {
            alert('Error: ' + (result.message || 'Terjadi kesalahan.'));
            btn.disabled = false;
            overlay.style.display = 'none';
        }
    } catch(err) {
        alert('Gagal menghubungi server. Pastikan Flask API berjalan di port 5000.');
        btn.disabled = false;
        overlay.style.display = 'none';
    }
});
</script>

<?php include __DIR__.'/../includes/footer.php'; ?>