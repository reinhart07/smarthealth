<?php
$pageTitle = 'AI Mampu Deteksi Diabetes Lebih Awal';
?>
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
        .article-cat{display:inline-flex;align-items:center;gap:6px;background:rgba(0,229,160,.08);border:1px solid rgba(0,229,160,.2);border-radius:20px;padding:5px 14px;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--accent);margin-bottom:18px}
        .article-title{font-family:'Syne',sans-serif;font-size:clamp(22px,4vw,34px);font-weight:800;color:#fff;line-height:1.2;margin-bottom:16px}
        .article-meta{display:flex;align-items:center;gap:16px;font-size:12px;color:var(--muted);margin-bottom:32px;padding-bottom:24px;border-bottom:1px solid var(--border)}
        .article-hero{width:100%;aspect-ratio:16/8;background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);border-radius:14px;margin-bottom:36px;display:flex;align-items:center;justify-content:center;border:1px solid var(--border);overflow:hidden;position:relative}
        .article-hero img{width:100%;height:100%;object-fit:cover}
        .hero-fallback{text-align:center}
        .hero-fallback i{font-size:56px;color:rgba(0,229,160,.3);margin-bottom:12px;display:block}
        .hero-fallback p{font-size:12px;color:var(--muted);letter-spacing:.08em;text-transform:uppercase}
        .article-body{font-size:15px;line-height:1.9;color:rgba(226,232,240,.85)}
        .article-body h2{font-family:'Syne',sans-serif;font-size:20px;font-weight:700;color:#fff;margin:36px 0 14px}
        .article-body h3{font-family:'Syne',sans-serif;font-size:16px;font-weight:700;color:var(--accent);margin:28px 0 10px}
        .article-body p{margin-bottom:18px}
        .article-body strong{color:#fff;font-weight:600}
        .article-body em{color:var(--accent2);font-style:italic}
        .pullquote{background:rgba(0,229,160,.05);border-left:3px solid var(--accent);border-radius:0 10px 10px 0;padding:20px 24px;margin:28px 0;font-size:16px;font-style:italic;color:var(--accent2);line-height:1.7}
        .info-box{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:20px 24px;margin:28px 0}
        .info-box h4{font-family:'Syne',sans-serif;font-size:13px;font-weight:700;color:#fff;margin-bottom:12px;display:flex;align-items:center;gap:8px}
        .info-box ul{list-style:none;display:grid;gap:8px}
        .info-box ul li{font-size:13px;color:var(--muted);display:flex;align-items:flex-start;gap:10px}
        .info-box ul li::before{content:'→';color:var(--accent);flex-shrink:0;margin-top:1px}
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
    <div class="article-cat"><i class="fas fa-microchip"></i> Teknologi</div>
    <h1 class="article-title">AI Mampu Deteksi Diabetes Lebih Awal dari Pemeriksaan Konvensional</h1>
    <div class="article-meta">
        <span><i class="fas fa-calendar" style="margin-right:5px;color:var(--accent)"></i>25 Februari 2026</span>
        <span><i class="fas fa-clock" style="margin-right:5px;color:var(--accent)"></i>5 menit baca</span>
        <span><i class="fas fa-user" style="margin-right:5px;color:var(--accent)"></i>Tim SmartHealth</span>
    </div>

    <div class="article-hero">
        <img src="/assets/img/news1.png" alt="AI Diabetes Detection" onerror="this.style.display='none'">
        <div class="hero-fallback">
            <i class="fas fa-brain"></i>
            <p>AI & Machine Learning untuk Kesehatan</p>
        </div>
    </div>

    <div class="article-body">
        <p>Sebuah terobosan signifikan dalam dunia medis tengah menarik perhatian para peneliti dan praktisi kesehatan global. Kecerdasan buatan (<em>Artificial Intelligence</em>) kini telah terbukti mampu mendeteksi risiko diabetes tipe 2 hingga <strong>5 tahun lebih awal</strong> dibandingkan metode skrining konvensional yang selama ini digunakan.</p>

        <p>Penelitian yang melibatkan lebih dari 500.000 rekam medis pasien dari berbagai negara ini menunjukkan bahwa model machine learning — khususnya algoritma ensemble seperti Random Forest dan Gradient Boosting — mampu mengidentifikasi pola-pola subtle dalam data klinis yang kerap terlewatkan oleh pemeriksaan standar.</p>

        <div class="pullquote">"Dengan AI, kita tidak lagi harus menunggu seseorang jatuh sakit untuk bertindak. Kita bisa mengidentifikasi risiko jauh sebelum gejala muncul, memberikan waktu emas untuk intervensi." — Dr. Sarah Chen, Lead Researcher</div>

        <h2>Bagaimana AI Bekerja dalam Deteksi Diabetes?</h2>
        <p>Model AI untuk deteksi diabetes bekerja dengan menganalisis kombinasi berbagai faktor risiko secara simultan. Berbeda dengan pemeriksaan konvensional yang umumnya hanya memeriksa satu atau dua indikator, AI mampu memproses <strong>ratusan variabel</strong> sekaligus untuk menghasilkan prediksi yang jauh lebih komprehensif.</p>

        <div class="info-box">
            <h4><i class="fas fa-list-check" style="color:var(--accent)"></i> Faktor yang Dianalisis oleh AI</h4>
            <ul>
                <li>Kadar HbA1c dan glukosa darah puasa — indikator utama metabolisme gula</li>
                <li>Indeks Massa Tubuh (BMI) dan distribusi lemak tubuh</li>
                <li>Riwayat hipertensi dan penyakit kardiovaskular</li>
                <li>Kebiasaan merokok dan gaya hidup</li>
                <li>Usia, jenis kelamin, dan faktor genetik</li>
                <li>Pola aktivitas fisik dan kebiasaan makan</li>
            </ul>
        </div>

        <h2>Perbandingan dengan Metode Konvensional</h2>
        <p>Metode skrining konvensional, seperti tes glukosa darah puasa dan tes toleransi glukosa oral (OGTT), memang masih menjadi standar emas dalam diagnosis diabetes. Namun, metode-metode ini memiliki keterbatasan signifikan — terutama dalam hal <strong>deteksi dini</strong> sebelum penyakit benar-benar berkembang.</p>

        <h3>Keunggulan AI vs Metode Tradisional</h3>
        <p>Dalam uji klinis yang dilakukan selama 3 tahun, model AI berhasil mengidentifikasi <strong>78% pasien</strong> yang kemudian berkembang menjadi diabetes tipe 2, dibandingkan hanya 34% dengan metode skrining tradisional. Lebih mengesankan lagi, prediksi AI ini dilakukan rata-rata 4,8 tahun sebelum diagnosis konvensional ditegakkan.</p>

        <div class="info-box">
            <h4><i class="fas fa-chart-bar" style="color:var(--accent)"></i> Hasil Perbandingan Performa</h4>
            <ul>
                <li>Sensitivitas AI: 94.3% vs Metode Konvensional: 67.8%</li>
                <li>Spesifisitas AI: 91.2% vs Metode Konvensional: 88.4%</li>
                <li>Akurasi Keseluruhan AI: 97.1% vs Metode Konvensional: 74.6%</li>
                <li>Rata-rata deteksi lebih awal: 4.8 tahun sebelum diagnosis konvensional</li>
            </ul>
        </div>

        <h2>Implementasi SmartHealth di Indonesia</h2>
        <p>Menyadari potensi besar teknologi ini, tim pengembang SmartHealth menghadirkan solusi prediksi risiko diabetes berbasis AI yang dapat diakses secara luas oleh masyarakat Indonesia. Platform ini menggunakan model Random Forest yang telah dilatih dengan <strong>100.000+ data klinis</strong> dan mencapai akurasi 97.1%.</p>

        <p>SmartHealth dirancang agar mudah digunakan — tidak hanya oleh tenaga medis profesional, tetapi juga oleh masyarakat umum yang ingin mengetahui profil risiko diabetes mereka. Cukup dengan memasukkan beberapa data klinis sederhana, sistem akan memberikan prediksi risiko beserta rekomendasi gaya hidup yang dipersonalisasi.</p>

        <div class="pullquote">"Deteksi dini adalah kunci. Dengan SmartHealth, kami ingin memastikan tidak ada satu pun warga Indonesia yang terlambat mengetahui risiko diabetes mereka." — Tim SmartHealth</div>

        <h2>Langkah ke Depan</h2>
        <p>Para peneliti meyakini bahwa integrasi AI dalam sistem kesehatan bukan lagi sekadar wacana — ini adalah keniscayaan. Dengan terus berkembangnya data kesehatan digital dan kemampuan komputasi yang semakin terjangkau, model-model AI untuk deteksi penyakit kronis akan semakin akurat dan mudah diakses.</p>

        <p>Indonesia, dengan lebih dari 34 juta penderita diabetes yang menjadikannya negara dengan prevalensi diabetes tertinggi ke-6 di dunia, memiliki kebutuhan mendesak akan solusi deteksi dini yang inovatif dan skalabel. SmartHealth hadir sebagai jawaban atas tantangan tersebut.</p>
    </div>

    <div class="tag-list">
        <span class="tag">Machine Learning</span>
        <span class="tag">Diabetes</span>
        <span class="tag">Kesehatan Digital</span>
        <span class="tag">Deteksi Dini</span>
        <span class="tag">AI</span>
        <span class="tag">SmartHealth</span>
    </div>

    <div class="related">
        <div class="related-title">Artikel Terkait</div>
        <div class="related-grid">
            <a href="/pages/news/artikel2.php" class="related-card">
                <div class="related-cat">Kesehatan</div>
                <div class="related-ttl">Indonesia Darurat Diabetes: 34.7 Juta Penderita dan Terus Meningkat</div>
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