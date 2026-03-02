<?php $pageTitle = 'SmartHealth: Solusi AI untuk Diabetes'; ?>
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
        .article-cat{display:inline-flex;align-items:center;gap:6px;background:rgba(0,184,255,.08);border:1px solid rgba(0,184,255,.2);border-radius:20px;padding:5px 14px;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--accent2);margin-bottom:18px}
        .article-title{font-family:'Syne',sans-serif;font-size:clamp(22px,4vw,34px);font-weight:800;color:#fff;line-height:1.2;margin-bottom:16px}
        .article-meta{display:flex;align-items:center;gap:16px;font-size:12px;color:var(--muted);margin-bottom:32px;padding-bottom:24px;border-bottom:1px solid var(--border)}
        .article-hero{width:100%;aspect-ratio:16/8;background:linear-gradient(135deg,#0a1520,#0d2035,#102840);border-radius:14px;margin-bottom:36px;display:flex;align-items:center;justify-content:center;border:1px solid var(--border);overflow:hidden}
        .article-hero img{width:100%;height:100%;object-fit:cover}
        .hero-fallback{text-align:center}
        .hero-fallback i{font-size:56px;color:rgba(0,184,255,.3);margin-bottom:12px;display:block}
        .hero-fallback p{font-size:12px;color:var(--muted);letter-spacing:.08em;text-transform:uppercase}
        .article-body{font-size:15px;line-height:1.9;color:rgba(226,232,240,.85)}
        .article-body h2{font-family:'Syne',sans-serif;font-size:20px;font-weight:700;color:#fff;margin:36px 0 14px}
        .article-body h3{font-family:'Syne',sans-serif;font-size:16px;font-weight:700;color:var(--accent2);margin:28px 0 10px}
        .article-body p{margin-bottom:18px}
        .article-body strong{color:#fff;font-weight:600}
        .article-body em{color:var(--accent);font-style:italic}
        .pullquote{background:rgba(0,184,255,.05);border-left:3px solid var(--accent2);border-radius:0 10px 10px 0;padding:20px 24px;margin:28px 0;font-size:16px;font-style:italic;color:rgba(226,232,240,.8);line-height:1.7}
        .team-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin:28px 0}
        .team-card{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:18px;text-align:center}
        .team-avatar{width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));margin:0 auto 10px;display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:800;font-size:18px;color:#0a0f1a}
        .team-name{font-family:'Syne',sans-serif;font-size:13px;font-weight:700;color:#fff;margin-bottom:3px}
        .team-role{font-size:11px;color:var(--muted)}
        .tech-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin:20px 0}
        .tech-item{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:14px;display:flex;align-items:center;gap:10px}
        .tech-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:13px}
        .tech-name{font-size:13px;font-weight:600;color:#fff}
        .tech-desc{font-size:11px;color:var(--muted)}
        .info-box{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:20px 24px;margin:28px 0}
        .info-box h4{font-family:'Syne',sans-serif;font-size:13px;font-weight:700;color:#fff;margin-bottom:12px;display:flex;align-items:center;gap:8px}
        .info-box ul{list-style:none;display:grid;gap:8px}
        .info-box ul li{font-size:13px;color:var(--muted);display:flex;align-items:flex-start;gap:10px}
        .info-box ul li::before{content:'→';color:var(--accent2);flex-shrink:0;margin-top:1px}
        .cta-box{background:linear-gradient(135deg,rgba(0,229,160,.06),rgba(0,184,255,.04));border:1px solid rgba(0,229,160,.2);border-radius:14px;padding:28px;text-align:center;margin:36px 0}
        .btn-cta{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#0a0f1a;font-family:'Syne',sans-serif;font-weight:700;font-size:13px;padding:11px 22px;border-radius:9px;text-decoration:none;transition:opacity .2s}
        .btn-cta:hover{opacity:.9}
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
    <div class="article-cat"><i class="fas fa-lightbulb"></i> Inovasi</div>
    <h1 class="article-title">SmartHealth: Solusi Prediksi Risiko Diabetes Berbasis AI untuk Masyarakat Indonesia</h1>
    <div class="article-meta">
        <span><i class="fas fa-calendar" style="margin-right:5px;color:var(--accent2)"></i>15 Februari 2026</span>
        <span><i class="fas fa-clock" style="margin-right:5px;color:var(--accent2)"></i>7 menit baca</span>
        <span><i class="fas fa-user" style="margin-right:5px;color:var(--accent2)"></i>Tim SmartHealth</span>
    </div>

    <div class="article-hero">
        <img src="/assets/img/news3.png" alt="SmartHealth Platform" onerror="this.style.display='none'">
        <div class="hero-fallback">
            <i class="fas fa-rocket"></i>
            <p>Platform Inovasi Kesehatan Digital</p>
        </div>
    </div>

    <div class="article-body">
        <p>Di tengah krisis diabetes yang melanda Indonesia, sebuah tim mahasiswa dari <strong>Universitas Dipa Makassar</strong> menghadirkan solusi inovatif yang bisa mengubah cara kita mendeteksi dan mencegah penyakit ini. SmartHealth — platform prediksi risiko diabetes berbasis Artificial Intelligence — lahir dari keprihatinan mendalam terhadap tingginya angka penderita diabetes yang tidak terdeteksi di Indonesia.</p>

        <div class="pullquote">"Kami percaya teknologi harus berpihak pada masyarakat. SmartHealth adalah wujud nyata dari keyakinan itu — AI yang bekerja untuk kesehatan semua orang, bukan hanya mereka yang mampu." — Tim SmartHealth</div>

        <h2>Mengenal Tim di Balik SmartHealth</h2>
        <p>SmartHealth dikembangkan oleh tiga mahasiswa Informatika yang bersatu dengan satu visi: membuat teknologi AI yang kompleks menjadi mudah diakses oleh semua orang.</p>

        <div class="team-grid">
            <div class="team-card">
                <div class="team-avatar">R</div>
                <div class="team-name">Reinhart Jens Robert</div>
                <div class="team-role">Fullstack Developer</div>
            </div>
            <div class="team-card">
                <div class="team-avatar">V</div>
                <div class="team-name">Valendino</div>
                <div class="team-role">UI/UX Designer</div>
            </div>
            <div class="team-card">
                <div class="team-avatar">D</div>
                <div class="team-name">Djefri</div>
                <div class="team-role">Data Analyst</div>
            </div>
        </div>

        <h2>Teknologi di Balik SmartHealth</h2>
        <p>SmartHealth dibangun dengan arsitektur hybrid yang menggabungkan kekuatan PHP Native sebagai frontend yang familiar dengan Flask Python sebagai ML backend. Pendekatan ini memungkinkan integrasi model machine learning yang powerful dengan antarmuka web yang responsif dan mudah digunakan.</p>

        <div class="tech-grid">
            <div class="tech-item">
                <div class="tech-icon" style="background:rgba(0,229,160,.1)"><i class="fas fa-brain" style="color:var(--accent)"></i></div>
                <div><div class="tech-name">Random Forest</div><div class="tech-desc">Algoritma ML utama, 97.1% akurasi</div></div>
            </div>
            <div class="tech-item">
                <div class="tech-icon" style="background:rgba(0,184,255,.1)"><i class="fab fa-python" style="color:var(--accent2)"></i></div>
                <div><div class="tech-name">Flask Python</div><div class="tech-desc">ML API backend real-time</div></div>
            </div>
            <div class="tech-item">
                <div class="tech-icon" style="background:rgba(255,182,39,.1)"><i class="fas fa-database" style="color:var(--warning)"></i></div>
                <div><div class="tech-name">MySQL</div><div class="tech-desc">Penyimpanan data prediksi</div></div>
            </div>
            <div class="tech-item">
                <div class="tech-icon" style="background:rgba(0,229,160,.1)"><i class="fas fa-code" style="color:var(--accent)"></i></div>
                <div><div class="tech-name">PHP Native</div><div class="tech-desc">Frontend yang ringan & cepat</div></div>
            </div>
        </div>

        <h2>Fitur Unggulan yang Membedakan</h2>

        <h3>1. Prediksi Real-time dengan Akurasi Tinggi</h3>
        <p>Inti dari SmartHealth adalah model Random Forest yang dilatih dengan <strong>100.000+ data klinis</strong>. Model ini mampu menganalisis 8 faktor risiko utama secara simultan dan memberikan prediksi dalam hitungan detik dengan akurasi 97.1%.</p>

        <h3>2. Rekomendasi Gaya Hidup yang Dipersonalisasi</h3>
        <p>SmartHealth tidak hanya memberikan angka — ia memberikan <em>panduan aksi nyata</em>. Setiap hasil prediksi dilengkapi dengan rekomendasi gaya hidup yang disesuaikan dengan tingkat risiko masing-masing pengguna, mulai dari diet, olahraga, hingga kapan harus segera ke dokter.</p>

        <h3>3. Dashboard Analitik Komprehensif</h3>
        <p>Tenaga medis dapat memantau tren prediksi, distribusi risiko pasien, dan statistik kesehatan melalui dashboard interaktif yang dilengkapi berbagai visualisasi data. Ini memungkinkan pengambilan keputusan klinis yang lebih cepat dan berbasis data.</p>

        <h3>4. Export Laporan PDF Profesional</h3>
        <p>Setiap hasil prediksi dapat diekspor menjadi laporan PDF profesional yang dapat dibawa ke dokter atau disimpan sebagai rekam medis pribadi — memudahkan komunikasi antara pasien dan tenaga kesehatan.</p>

        <div class="info-box">
            <h4><i class="fas fa-trophy" style="color:var(--warning)"></i> Pencapaian SmartHealth</h4>
            <ul>
                <li>Akurasi model 97.1% — diuji pada 20.000+ data test</li>
                <li>AUC-ROC Score: 0.99 — performa hampir sempurna</li>
                <li>Response time prediksi: &lt;200ms per request</li>
                <li>Dibangun dalam rangka INFEST Hackathon 2026 — AI & Data Track</li>
                <li>Dataset: 100.000+ sampel dari Kaggle Diabetes Prediction Dataset</li>
            </ul>
        </div>

        <h2>Dampak dan Visi ke Depan</h2>
        <p>Tim SmartHealth berharap platform ini bisa berkembang menjadi solusi kesehatan digital yang digunakan secara luas di Indonesia — mulai dari puskesmas hingga klinik swasta, dari dokter umum hingga masyarakat awam yang ingin memantau kesehatannya sendiri.</p>

        <p>Ke depannya, tim berencana untuk mengembangkan fitur <strong>pemantauan berkala</strong>, integrasi dengan perangkat wearable, dan ekspansi model untuk mendeteksi risiko penyakit kronis lainnya seperti hipertensi dan penyakit jantung koroner.</p>

        <div class="cta-box">
            <div style="font-family:'Syne',sans-serif;font-size:18px;font-weight:800;color:#fff;margin-bottom:8px">Coba SmartHealth Sekarang</div>
            <div style="font-size:13px;color:var(--muted);margin-bottom:20px">Gratis, cepat, dan akurat. Ketahui risiko diabetes Anda hari ini.</div>
            <a href="/auth/register.php" class="btn-cta"><i class="fas fa-rocket"></i> Daftar Gratis</a>
        </div>
    </div>

    <div class="tag-list">
        <span class="tag">SmartHealth</span>
        <span class="tag">Inovasi</span>
        <span class="tag">Machine Learning</span>
        <span class="tag">Hackathon</span>
        <span class="tag">INFEST 2026</span>
        <span class="tag">Universitas Dipa Makassar</span>
    </div>

    <div class="related">
        <div class="related-title">Artikel Terkait</div>
        <div class="related-grid">
            <a href="/pages/news/artikel1.php" class="related-card">
                <div class="related-cat">Teknologi</div>
                <div class="related-ttl">AI Mampu Deteksi Diabetes Lebih Awal dari Pemeriksaan Konvensional</div>
            </a>
            <a href="/pages/news/artikel2.php" class="related-card">
                <div class="related-cat">Kesehatan</div>
                <div class="related-ttl">Indonesia Darurat Diabetes: 34.7 Juta Penderita dan Terus Meningkat</div>
            </a>
        </div>
    </div>
</div>
<footer>© <?=date('Y')?> SmartHealth — INFEST Hackathon 2026 | AI & Data Track</footer>
</body>
</html>