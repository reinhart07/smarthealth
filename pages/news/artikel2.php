<?php $pageTitle = 'Indonesia Darurat Diabetes'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$pageTitle?> — SmartHealth</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        :root{--bg:#0a0f1a;--surface:#111827;--surface2:#1a2235;--border:#1f2d45;--accent:#00e5a0;--accent2:#00b8ff;--danger:#ff4d6d;--warning:#ffb627;--text:#e2e8f0;--muted:#64748b}
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh}
        nav{position:sticky;top:0;z-index:100;padding:0 40px;height:60px;display:flex;align-items:center;justify-content:space-between;background:rgba(10,15,26,.9);backdrop-filter:blur(12px);border-bottom:1px solid var(--border)}
        .logo{display:flex;align-items:center;gap:10px;text-decoration:none}
        .logo-icon{width:32px;height:32px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:8px;display:flex;align-items:center;justify-content:center;color:#0a0f1a;font-size:13px}
        .logo-name{font-family:'Syne',sans-serif;font-weight:800;font-size:15px;color:#fff}
        .back-btn{font-size:12px;color:var(--muted);text-decoration:none;display:flex;align-items:center;gap:6px;transition:color .2s}
        .back-btn:hover{color:var(--accent)}
        .article-wrap{max-width:760px;margin:0 auto;padding:60px 24px}
        .article-cat{display:inline-flex;align-items:center;gap:6px;background:rgba(255,77,109,.08);border:1px solid rgba(255,77,109,.2);border-radius:20px;padding:5px 14px;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--danger);margin-bottom:18px}
        .article-title{font-family:'Syne',sans-serif;font-size:clamp(22px,4vw,34px);font-weight:800;color:#fff;line-height:1.2;margin-bottom:16px}
        .article-meta{display:flex;align-items:center;gap:16px;font-size:12px;color:var(--muted);margin-bottom:32px;padding-bottom:24px;border-bottom:1px solid var(--border)}
        .article-hero{width:100%;aspect-ratio:16/8;background:linear-gradient(135deg,#1a0a0f,#2d1015,#3d1520);border-radius:14px;margin-bottom:36px;display:flex;align-items:center;justify-content:center;border:1px solid var(--border);overflow:hidden;position:relative}
        .article-hero img{width:100%;height:100%;object-fit:cover}
        .hero-fallback{text-align:center}
        .hero-fallback i{font-size:56px;color:rgba(255,77,109,.3);margin-bottom:12px;display:block}
        .hero-fallback p{font-size:12px;color:var(--muted);letter-spacing:.08em;text-transform:uppercase}
        .article-body{font-size:15px;line-height:1.9;color:rgba(226,232,240,.85)}
        .article-body h2{font-family:'Syne',sans-serif;font-size:20px;font-weight:700;color:#fff;margin:36px 0 14px}
        .article-body h3{font-family:'Syne',sans-serif;font-size:16px;font-weight:700;color:var(--warning);margin:28px 0 10px}
        .article-body p{margin-bottom:18px}
        .article-body strong{color:#fff;font-weight:600}
        .article-body em{color:var(--accent2);font-style:italic}
        .pullquote{background:rgba(255,77,109,.05);border-left:3px solid var(--danger);border-radius:0 10px 10px 0;padding:20px 24px;margin:28px 0;font-size:16px;font-style:italic;color:rgba(226,232,240,.8);line-height:1.7}
        .stat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin:28px 0}
        .stat-box{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:18px;text-align:center}
        .stat-val{font-family:'Syne',sans-serif;font-size:26px;font-weight:800;background:linear-gradient(135deg,var(--danger),var(--warning));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:4px}
        .stat-lbl{font-size:11px;color:var(--muted);line-height:1.5}
        .info-box{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:20px 24px;margin:28px 0}
        .info-box h4{font-family:'Syne',sans-serif;font-size:13px;font-weight:700;color:#fff;margin-bottom:12px;display:flex;align-items:center;gap:8px}
        .info-box ul{list-style:none;display:grid;gap:8px}
        .info-box ul li{font-size:13px;color:var(--muted);display:flex;align-items:flex-start;gap:10px}
        .info-box ul li::before{content:'→';color:var(--warning);flex-shrink:0;margin-top:1px}
        .tag-list{display:flex;flex-wrap:wrap;gap:8px;margin-top:36px;padding-top:24px;border-top:1px solid var(--border)}
        .tag{background:var(--surface2);border:1px solid var(--border);border-radius:6px;padding:4px 12px;font-size:11px;color:var(--muted)}
        .related{margin-top:60px;padding-top:40px;border-top:1px solid var(--border)}
        .related-title{font-family:'Syne',sans-serif;font-size:16px;font-weight:700;color:#fff;margin-bottom:20px}
        .related-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .related-card{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:16px;text-decoration:none;transition:border-color .2s}
        .related-card:hover{border-color:var(--accent)}
        .related-cat{font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--accent);margin-bottom:6px}
        .related-ttl{font-family:'Syne',sans-serif;font-size:13px;font-weight:700;color:#fff;line-height:1.4}
        footer{text-align:center;padding:24px;font-size:11px;color:var(--muted);border-top:1px solid var(--border);margin-top:60px}
    </style>
</head>
<body>
<nav>
    <a href="/" class="logo"><div class="logo-icon"><i class="fas fa-heartbeat"></i></div><span class="logo-name">SmartHealth</span></a>
    <a href="/#news" class="back-btn"><i class="fas fa-arrow-left"></i> Kembali ke Berita</a>
</nav>

<div class="article-wrap">
    <div class="article-cat"><i class="fas fa-exclamation-triangle"></i> Kesehatan</div>
    <h1 class="article-title">Indonesia Darurat Diabetes: 34.7 Juta Penderita dan Terus Meningkat</h1>
    <div class="article-meta">
        <span><i class="fas fa-calendar" style="margin-right:5px;color:var(--danger)"></i>20 Februari 2026</span>
        <span><i class="fas fa-clock" style="margin-right:5px;color:var(--danger)"></i>6 menit baca</span>
        <span><i class="fas fa-user" style="margin-right:5px;color:var(--danger)"></i>Tim SmartHealth</span>
    </div>

    <div class="article-hero">
        <img src="/assets/img/news2.png" alt="Diabetes Indonesia" onerror="this.style.display='none'">
        <div class="hero-fallback">
            <i class="fas fa-heartbeat"></i>
            <p>Krisis Kesehatan Nasional</p>
        </div>
    </div>

    <div class="article-body">
        <p>Indonesia kini menghadapi krisis kesehatan yang tidak bisa lagi diabaikan. Data terbaru dari Riset Kesehatan Dasar (Riskesdas) mengungkap fakta yang mengejutkan: jumlah penderita diabetes di Indonesia telah mencapai <strong>34,7 juta jiwa</strong> — menjadikan Indonesia sebagai negara dengan prevalensi diabetes tertinggi ke-6 di dunia.</p>

        <div class="stat-grid">
            <div class="stat-box"><div class="stat-val">34.7 Jt</div><div class="stat-lbl">Penderita Diabetes di Indonesia</div></div>
            <div class="stat-box"><div class="stat-val">#6</div><div class="stat-lbl">Tertinggi di Dunia</div></div>
            <div class="stat-box"><div class="stat-val">+28%</div><div class="stat-lbl">Peningkatan dalam 5 Tahun</div></div>
        </div>

        <p>Yang lebih mengkhawatirkan, sebagian besar dari penderita tersebut — diperkirakan sekitar <strong>60–70%</strong> — belum terdiagnosis dan tidak mengetahui kondisi kesehatan mereka. Mereka hidup dengan penyakit yang diam-diam merusak organ vital, tanpa penanganan yang tepat.</p>

        <div class="pullquote">"Fenomena gunung es — hanya sebagian kecil yang terlihat di permukaan. Jutaan penderita diabetes berjalan di antara kita tanpa menyadari kondisi mereka sendiri."</div>

        <h2>Akar Permasalahan: Gaya Hidup dan Kurangnya Skrining</h2>
        <p>Para ahli kesehatan menunjuk dua faktor utama sebagai penyebab lonjakan drastis ini: <strong>perubahan gaya hidup</strong> yang semakin tidak sehat, dan <strong>kurangnya akses terhadap skrining</strong> yang terjangkau dan mudah dijangkau.</p>

        <div class="info-box">
            <h4><i class="fas fa-warning" style="color:var(--warning)"></i> Faktor Risiko Utama di Indonesia</h4>
            <ul>
                <li>Konsumsi karbohidrat dan gula berlebih — nasi putih, minuman manis, makanan olahan</li>
                <li>Tingkat aktivitas fisik yang rendah, terutama di perkotaan</li>
                <li>Tingkat obesitas yang meningkat pesat — 21.8% populasi dewasa</li>
                <li>Kurangnya kesadaran akan pentingnya pemeriksaan kesehatan rutin</li>
                <li>Akses terbatas ke fasilitas kesehatan di daerah terpencil</li>
                <li>Faktor genetik — populasi Asia Tenggara lebih rentan terhadap diabetes</li>
            </ul>
        </div>

        <h2>Dampak Ekonomi yang Menghancurkan</h2>
        <p>Diabetes bukan sekadar masalah kesehatan — ini adalah bencana ekonomi. Biaya penanganan diabetes dan komplikasinya (penyakit jantung, gagal ginjal, kebutaan, amputasi) diperkirakan mencapai <strong>Rp 50 triliun per tahun</strong> dalam sistem kesehatan Indonesia.</p>

        <h3>Beban Ganda bagi Pasien dan Keluarga</h3>
        <p>Selain beban pada sistem kesehatan nasional, diabetes juga menghancurkan ekonomi keluarga penderita. Biaya pengobatan rutin, insulin, dan penanganan komplikasi dapat menguras tabungan seumur hidup. Banyak keluarga terpaksa menjual aset atau terlilit hutang demi membiayai perawatan anggota keluarga yang menderita diabetes.</p>

        <h2>Solusi: Deteksi Dini adalah Kunci</h2>
        <p>Para ahli kesehatan sepakat: solusi paling efektif dan efisien adalah <strong>deteksi dini</strong>. Diabetes yang terdeteksi pada stadium awal — bahkan pada fase pre-diabetes — jauh lebih mudah dikelola dan bahkan bisa dicegah berkembang lebih lanjut melalui perubahan gaya hidup.</p>

        <p>Inilah mengapa platform seperti <strong>SmartHealth</strong> hadir sebagai solusi yang tepat waktu. Dengan memanfaatkan kecerdasan buatan untuk prediksi risiko diabetes, SmartHealth memungkinkan setiap orang — di manapun mereka berada — untuk mengetahui profil risiko mereka dan mengambil tindakan pencegahan sebelum terlambat.</p>

        <div class="pullquote">"Mencegah lebih baik dari mengobati. Dan dengan teknologi AI yang tepat, mencegah diabetes kini bukan lagi kemewahan — ini hak setiap warga Indonesia."</div>

        <h2>Harapan di Horizon</h2>
        <p>Di tengah angka yang mencengangkan ini, ada secercah harapan. Pemerintah Indonesia telah mulai memasukkan skrining diabetes dalam program Jaminan Kesehatan Nasional (JKN). Ditambah dengan inovasi teknologi seperti SmartHealth, akses terhadap deteksi dini diabetes diharapkan dapat menjangkau seluruh lapisan masyarakat Indonesia dalam waktu dekat.</p>
    </div>

    <div class="tag-list">
        <span class="tag">Diabetes Indonesia</span>
        <span class="tag">Kesehatan Masyarakat</span>
        <span class="tag">Riskesdas</span>
        <span class="tag">Deteksi Dini</span>
        <span class="tag">Gaya Hidup Sehat</span>
    </div>

    <div class="related">
        <div class="related-title">Artikel Terkait</div>
        <div class="related-grid">
            <a href="/pages/news/artikel1.php" class="related-card">
                <div class="related-cat">Teknologi</div>
                <div class="related-ttl">AI Mampu Deteksi Diabetes Lebih Awal dari Pemeriksaan Konvensional</div>
            </a>
            <a href="/pages/news/artikel3.php" class="related-card">
                <div class="related-cat">Inovasi</div>
                <div class="related-ttl">SmartHealth: Solusi Prediksi Risiko Diabetes Berbasis AI</div>
            </a>
        </div>
    </div>
</div>
<footer>© <?=date('Y')?> SmartHealth — INFEST Hackathon 2026 | AI & Data Track</footer>
</body>
</html>