<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/auth_check.php';
if (isLoggedIn()) { header('Location: /pages/dashboard.php'); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartHealth — AI Diabetes Risk Predictor</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        :root{--bg:#0a0f1a;--surface:#111827;--surface2:#1a2235;--border:#1f2d45;--accent:#00e5a0;--accent2:#00b8ff;--danger:#ff4d6d;--warning:#ffb627;--text:#e2e8f0;--muted:#64748b}
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh}
        h1,h2,h3,h4,nav .logo{font-family:'Syne',sans-serif}
        nav{position:fixed;top:0;left:0;right:0;z-index:100;padding:0 40px;height:64px;display:flex;align-items:center;justify-content:space-between;background:rgba(10,15,26,0.85);backdrop-filter:blur(12px);border-bottom:1px solid var(--border)}
        .logo{display:flex;align-items:center;gap:10px;text-decoration:none}
        .logo-icon{width:36px;height:36px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:9px;display:flex;align-items:center;justify-content:center}
        .logo-icon i{color:#0a0f1a;font-size:15px}
        .logo-name{font-weight:800;font-size:16px;color:#fff}
        .nav-links{display:flex;align-items:center;gap:6px}
        .nav-links a{text-decoration:none;font-size:13px;padding:7px 16px;border-radius:8px;transition:all .2s}
        .nav-links .link{color:var(--muted)}.nav-links .link:hover{color:#fff;background:var(--surface2)}
        .nav-links .btn{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#0a0f1a;font-weight:700}
        .nav-links .btn:hover{opacity:.9}
        .nav-links .btn-outline{border:1px solid var(--border);color:var(--text)}.nav-links .btn-outline:hover{border-color:var(--accent);color:var(--accent)}
        .hero{min-height:100vh;padding-top:64px;display:flex;align-items:center;position:relative;overflow:hidden}
        .hero-bg{position:absolute;inset:0;background:radial-gradient(ellipse 80% 60% at 60% 40%,rgba(0,229,160,0.06) 0%,transparent 70%),radial-gradient(ellipse 50% 40% at 80% 70%,rgba(0,184,255,0.05) 0%,transparent 60%)}
        .grid-bg{position:absolute;inset:0;background-image:linear-gradient(rgba(31,45,69,0.3) 1px,transparent 1px),linear-gradient(90deg,rgba(31,45,69,0.3) 1px,transparent 1px);background-size:40px 40px;mask-image:radial-gradient(ellipse at center,black 20%,transparent 70%)}
        .hero-inner{max-width:1200px;margin:0 auto;padding:60px 40px;display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;width:100%;position:relative;z-index:1}
        .hero-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(0,229,160,0.08);border:1px solid rgba(0,229,160,0.2);border-radius:20px;padding:6px 14px;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--accent);margin-bottom:20px}
        .hero-title{font-size:clamp(32px,4vw,52px);font-weight:800;line-height:1.1;color:#fff;margin-bottom:18px}
        .hero-title span{background:linear-gradient(135deg,var(--accent),var(--accent2));-webkit-background-clip:text;-webkit-text-fill-color:transparent}
        .hero-desc{font-size:15px;color:var(--muted);line-height:1.8;margin-bottom:30px;max-width:480px}
        .hero-actions{display:flex;gap:12px;flex-wrap:wrap}
        .btn-primary{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#0a0f1a;font-family:'Syne',sans-serif;font-weight:700;border:none;border-radius:10px;padding:13px 26px;cursor:pointer;font-size:14px;text-decoration:none;display:inline-flex;align-items:center;gap:8px;transition:opacity .2s,transform .1s}
        .btn-primary:hover{opacity:.9}.btn-primary:active{transform:scale(.98)}
        .btn-ghost{border:1px solid var(--border);color:var(--text);border-radius:10px;padding:12px 22px;font-size:13px;text-decoration:none;display:inline-flex;align-items:center;gap:8px;transition:all .2s;background:transparent}
        .btn-ghost:hover{border-color:var(--accent);color:var(--accent)}
        .hero-stats{display:flex;gap:28px;margin-top:36px;padding-top:28px;border-top:1px solid var(--border)}
        .stat-item .val{font-family:'Syne',sans-serif;font-size:24px;font-weight:800;background:linear-gradient(135deg,var(--accent),var(--accent2));-webkit-background-clip:text;-webkit-text-fill-color:transparent}
        .stat-item .lbl{font-size:11px;color:var(--muted);margin-top:2px}
        .carousel{position:relative;border-radius:16px;overflow:hidden;border:1px solid var(--border);background:var(--surface);aspect-ratio:16/10}
        .carousel-track{display:flex;transition:transform .6s cubic-bezier(.4,0,.2,1);height:100%}
        .carousel-slide{min-width:100%;height:100%;position:relative;overflow:hidden}
        .carousel-slide img{width:100%;height:100%;object-fit:cover;display:block}
        .slide-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(10,15,26,.7) 0%,transparent 50%)}
        .slide-caption{position:absolute;bottom:16px;left:20px;right:20px}
        .slide-caption h4{font-family:'Syne',sans-serif;font-size:14px;font-weight:700;color:#fff;margin-bottom:3px}
        .slide-caption p{font-size:11px;color:rgba(255,255,255,.6)}
        .carousel-dots{position:absolute;bottom:14px;right:16px;display:flex;gap:5px}
        .dot{width:6px;height:6px;border-radius:3px;background:rgba(255,255,255,.3);transition:all .3s;cursor:pointer}
        .dot.active{width:18px;background:var(--accent)}
        .carousel-nav{position:absolute;top:50%;transform:translateY(-50%);width:32px;height:32px;background:rgba(10,15,26,.6);border:1px solid var(--border);border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#fff;font-size:11px;transition:all .2s;z-index:2}
        .carousel-nav:hover{background:var(--accent);color:#0a0f1a;border-color:var(--accent)}
        .carousel-prev{left:10px}.carousel-next{right:10px}
        .slide-placeholder{width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px}
        .slide-p1{background:linear-gradient(135deg,#0f2027,#203a43,#2c5364)}
        .slide-p2{background:linear-gradient(135deg,#0a0f1a,#1a2a1a,#0d2b1a)}
        .slide-p3{background:linear-gradient(135deg,#0d1b2a,#1b263b,#415a77)}
        .slide-label{font-family:'Syne',sans-serif;font-size:13px;color:rgba(255,255,255,.5);letter-spacing:.08em;text-transform:uppercase}
        section{max-width:1200px;margin:0 auto;padding:80px 40px}
        .section-label{font-size:10px;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:var(--accent);margin-bottom:10px}
        .section-title{font-size:clamp(24px,3vw,36px);font-weight:800;color:#fff;line-height:1.2;margin-bottom:14px}
        .section-desc{font-size:14px;color:var(--muted);line-height:1.8;max-width:560px}
        .divider{height:1px;background:linear-gradient(90deg,transparent,var(--border),transparent);max-width:1200px;margin:0 auto}
        .features-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:40px}
        .feature-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:24px;transition:border-color .2s,transform .2s}
        .feature-card:hover{border-color:var(--accent);transform:translateY(-4px)}
        .feature-icon{width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;font-size:16px}
        .feature-title{font-family:'Syne',sans-serif;font-weight:700;font-size:14px;color:#fff;margin-bottom:8px}
        .feature-desc{font-size:12px;color:var(--muted);line-height:1.7}
        .about-grid{display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;margin-top:40px}
        .about-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:28px}
        .about-item{display:flex;gap:14px;align-items:flex-start;padding:14px 0;border-bottom:1px solid var(--border)}
        .about-item:last-child{border-bottom:none;padding-bottom:0}
        .about-num{font-family:'Syne',sans-serif;font-size:28px;font-weight:800;background:linear-gradient(135deg,var(--accent),var(--accent2));-webkit-background-clip:text;-webkit-text-fill-color:transparent;flex-shrink:0;width:50px}

        /* ===== NEWS — FIXED ===== */
        .news-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:40px}
        .news-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;overflow:hidden;transition:border-color .2s,transform .2s;text-decoration:none;display:block}
        .news-card:hover{border-color:var(--accent);transform:translateY(-4px)}
        .news-img{width:100%;aspect-ratio:16/9;position:relative;overflow:hidden;background:var(--surface2)}
        .news-img img{width:100%;height:100%;object-fit:cover;display:block;position:absolute;inset:0}
        .news-img .news-fallback{width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;position:absolute;inset:0}
        .news-body{padding:16px}
        .news-cat{font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--accent);margin-bottom:8px}
        .news-title{font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:#fff;line-height:1.5;margin-bottom:8px}
        .news-excerpt{font-size:11px;color:var(--muted);line-height:1.6}
        .news-footer{padding:10px 16px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
        .news-date{font-size:10px;color:var(--muted)}

        .cta-section{text-align:center;padding:80px 40px}
        .cta-box{background:linear-gradient(135deg,rgba(0,229,160,.08),rgba(0,184,255,.06));border:1px solid rgba(0,229,160,.2);border-radius:24px;padding:60px 40px;max-width:700px;margin:0 auto;position:relative;overflow:hidden}
        .cta-box::before{content:'';position:absolute;top:-40%;left:-20%;width:300px;height:300px;background:radial-gradient(circle,rgba(0,229,160,.1),transparent 70%);pointer-events:none}
        footer{border-top:1px solid var(--border);padding:24px 40px;text-align:center;font-size:11px;color:var(--muted)}
        @keyframes fadeUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
        .fade-up{animation:fadeUp .5s ease both}
        .fade-up-2{animation:fadeUp .5s .1s ease both}
        .fade-up-3{animation:fadeUp .5s .2s ease both}
        @keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}
    </style>
</head>
<body>

<nav>
    <a href="/" class="logo">
        <div class="logo-icon"><i class="fas fa-heartbeat"></i></div>
        <span class="logo-name">SmartHealth</span>
    </a>
    <div class="nav-links">
        <a href="#fitur" class="link">Fitur</a>
        <a href="#about" class="link">Tentang</a>
        <a href="#news" class="link">Berita</a>
        <a href="/pages/tim.php" class="link">Tim</a>
        <a href="/auth/login.php" class="link btn-outline" style="border:1px solid var(--border);border-radius:8px">Masuk</a>
        <a href="/auth/register.php" class="btn" style="padding:8px 18px;border-radius:8px;font-size:13px;font-weight:700">Daftar Gratis</a>
    </div>
</nav>

<section class="hero">
    <div class="hero-bg"></div>
    <div class="grid-bg"></div>
    <div class="hero-inner">
        <div class="fade-up">
            <div class="hero-badge"><span style="width:6px;height:6px;background:var(--accent);border-radius:50%;display:inline-block;animation:pulse 2s infinite"></span> INFEST Hackathon 2026 — AI & Data</div>
            <h1 class="hero-title">Deteksi Risiko Diabetes<br><span>Lebih Cepat dengan AI</span></h1>
            <p class="hero-desc">SmartHealth menggunakan Machine Learning untuk memprediksi risiko diabetes berdasarkan data klinis. Hasil instan, rekomendasi personal, dan laporan PDF profesional.</p>
            <div class="hero-actions">
                <a href="/auth/register.php" class="btn-primary"><i class="fas fa-rocket"></i> Mulai Gratis</a>
                <a href="#fitur" class="btn-ghost"><i class="fas fa-play-circle"></i> Pelajari Lebih</a>
            </div>
            <div class="hero-stats">
                <div class="stat-item"><div class="val">97.1%</div><div class="lbl">Akurasi Model</div></div>
                <div class="stat-item"><div class="val">100K+</div><div class="lbl">Data Training</div></div>
                <div class="stat-item"><div class="val">4</div><div class="lbl">Algoritma ML</div></div>
            </div>
        </div>

        <div class="carousel fade-up-2" id="carousel">
            <div class="carousel-track" id="track">
                <div class="carousel-slide">
                    <img src="/assets/img/hero1.jpg" alt="Dashboard" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <div class="slide-placeholder slide-p1" style="display:none"><i class="fas fa-chart-pie" style="font-size:56px;color:rgba(0,229,160,.4)"></i><span class="slide-label">Dashboard Analytics</span></div>
                    <div class="slide-overlay"></div>
                    <div class="slide-caption"><h4>Dashboard Analitik Real-time</h4><p>Visualisasi data prediksi dengan grafik interaktif</p></div>
                </div>
                <div class="carousel-slide">
                    <img src="/assets/img/hero2.jpg" alt="AI Prediction" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <div class="slide-placeholder slide-p2" style="display:none"><i class="fas fa-brain" style="font-size:56px;color:rgba(0,229,160,.4)"></i><span class="slide-label">AI Prediction Engine</span></div>
                    <div class="slide-overlay"></div>
                    <div class="slide-caption"><h4>Prediksi Berbasis Machine Learning</h4><p>Random Forest dengan akurasi 97.1% pada 100K+ data</p></div>
                </div>
                <div class="carousel-slide">
                    <img src="/assets/img/hero3.jpg" alt="Health Report" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <div class="slide-placeholder slide-p3" style="display:none"><i class="fas fa-file-medical" style="font-size:56px;color:rgba(0,184,255,.4)"></i><span class="slide-label">Health Report PDF</span></div>
                    <div class="slide-overlay"></div>
                    <div class="slide-caption"><h4>Laporan PDF Profesional</h4><p>Export hasil prediksi dan rekomendasi lengkap</p></div>
                </div>
            </div>
            <div class="carousel-dots" id="dots">
                <div class="dot active" onclick="goTo(0)"></div>
                <div class="dot" onclick="goTo(1)"></div>
                <div class="dot" onclick="goTo(2)"></div>
            </div>
            <div class="carousel-nav carousel-prev" onclick="prev()"><i class="fas fa-chevron-left"></i></div>
            <div class="carousel-nav carousel-next" onclick="next()"><i class="fas fa-chevron-right"></i></div>
        </div>
    </div>
</section>

<div class="divider"></div>

<section id="fitur">
    <div style="text-align:center;margin-bottom:10px">
        <div class="section-label">Fitur Unggulan</div>
        <h2 class="section-title" style="margin:0 auto">Teknologi AI untuk Kesehatan Anda</h2>
        <p class="section-desc" style="margin:12px auto 0">Dibangun untuk kemudahan tenaga medis dan masyarakat umum</p>
    </div>
    <div class="features-grid">
        <?php
        $features=[
            ['fas fa-brain','rgba(0,229,160,.1)','var(--accent)','Prediksi AI Real-time','Model Random Forest terlatih dengan 100K+ data memberikan prediksi akurat dalam detik.'],
            ['fas fa-chart-pie','rgba(0,184,255,.1)','var(--accent2)','Dashboard Interaktif','Visualisasi data dengan grafik dinamis — distribusi hasil, tren risiko, dan statistik.'],
            ['fas fa-file-pdf','rgba(255,182,39,.1)','var(--warning)','Export Laporan PDF','Download laporan prediksi profesional dengan satu klik untuk dokumentasi medis.'],
            ['fas fa-lightbulb','rgba(0,229,160,.1)','var(--accent)','Rekomendasi Personal','Saran gaya hidup yang dipersonalisasi berdasarkan tingkat risiko Anda.'],
            ['fas fa-history','rgba(255,77,109,.1)','var(--danger)','Riwayat Lengkap','Simpan dan akses seluruh riwayat prediksi dengan filter dan pencarian canggih.'],
            ['fas fa-shield-alt','rgba(0,184,255,.1)','var(--accent2)','Aman & Terenkripsi','Data dienkripsi bcrypt. Sistem autentikasi berbasis sesi yang aman.'],
        ];
        foreach($features as $f): ?>
        <div class="feature-card">
            <div class="feature-icon" style="background:<?=$f[1]?>"><i class="<?=$f[0]?>" style="color:<?=$f[2]?>"></i></div>
            <div class="feature-title"><?=$f[3]?></div>
            <div class="feature-desc"><?=$f[4]?></div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<div class="divider"></div>

<section id="about">
    <div class="section-label">Tentang Kami</div>
    <h2 class="section-title">Dibangun untuk Dampak Nyata</h2>
    <div class="about-grid">
        <div>
            <p style="font-size:14px;color:var(--muted);line-height:1.9;margin-bottom:20px">SmartHealth adalah platform prediksi risiko diabetes berbasis Artificial Intelligence yang dikembangkan sebagai solusi inovatif dalam bidang <em>AI & Data</em> untuk INFEST Hackathon 2026.</p>
            <p style="font-size:14px;color:var(--muted);line-height:1.9;margin-bottom:20px">Sistem ini menggunakan algoritma <strong style="color:var(--accent)">Random Forest</strong> yang dilatih dengan lebih dari 100.000 data klinis untuk mendeteksi risiko diabetes secara dini — membantu tenaga medis dan masyarakat umum dalam pengambilan keputusan berbasis data.</p>
            <p style="font-size:14px;color:var(--muted);line-height:1.9">Dengan antarmuka yang intuitif, hasil prediksi instan, dan rekomendasi gaya hidup yang dipersonalisasi, SmartHealth hadir sebagai jembatan antara teknologi mutakhir dan layanan kesehatan masyarakat.</p>
            <div style="display:flex;gap:12px;margin-top:24px">
                <a href="/auth/register.php" class="btn-primary"><i class="fas fa-arrow-right"></i> Coba Sekarang</a>
            </div>
        </div>
        <div class="about-card">
            <div class="about-item"><div class="about-num">01</div><div><div style="font-family:'Syne',sans-serif;font-weight:700;font-size:14px;color:#fff;margin-bottom:4px">Dataset Berkualitas Tinggi</div><div style="font-size:12px;color:var(--muted);line-height:1.7">Menggunakan Diabetes Prediction Dataset dengan 100.000+ sampel dari Kaggle untuk memastikan model yang robust dan akurat.</div></div></div>
            <div class="about-item"><div class="about-num">02</div><div><div style="font-family:'Syne',sans-serif;font-weight:700;font-size:14px;color:#fff;margin-bottom:4px">Multi-Algoritma ML</div><div style="font-size:12px;color:var(--muted);line-height:1.7">Membandingkan 4 algoritma (Random Forest, Gradient Boosting, Decision Tree, Logistic Regression) untuk memilih model terbaik.</div></div></div>
            <div class="about-item"><div class="about-num">03</div><div><div style="font-family:'Syne',sans-serif;font-weight:700;font-size:14px;color:#fff;margin-bottom:4px">Arsitektur Hybrid</div><div style="font-size:12px;color:var(--muted);line-height:1.7">PHP Native sebagai frontend yang familiar, Flask Python sebagai ML backend — integrasi seamless via REST API.</div></div></div>
            <div class="about-item"><div class="about-num">04</div><div><div style="font-family:'Syne',sans-serif;font-weight:700;font-size:14px;color:#fff;margin-bottom:4px">Open Source & Extensible</div><div style="font-size:12px;color:var(--muted);line-height:1.7">Dibangun dengan teknologi open source, mudah dikembangkan dan diintegrasikan ke sistem kesehatan yang sudah ada.</div></div></div>
        </div>
    </div>
</section>

<div class="divider"></div>

<!-- ===== NEWS — FIXED: $n[4]=gambar, $n[5]=url ===== -->
<section id="news">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:16px">
        <div>
            <div class="section-label">Berita & Artikel</div>
            <h2 class="section-title" style="margin:0">Update Terkini</h2>
        </div>
        <a href="#news" style="font-size:12px;color:var(--accent);text-decoration:none">Lihat semua →</a>
    </div>
    <div class="news-grid">
        <?php
        // [0]=kategori [1]=judul [2]=excerpt [3]=tanggal [4]=gambar [5]=url
        $news=[
            ['Teknologi','AI Mampu Deteksi Diabetes Lebih Awal dari Pemeriksaan Konvensional','Penelitian terbaru menunjukkan bahwa model machine learning mampu mendeteksi risiko diabetes tipe 2 hingga 5 tahun lebih awal dibandingkan metode skrining tradisional...','25 Feb 2026','news1.png','/pages/news/artikel1.php'],
            ['Kesehatan','Indonesia Darurat Diabetes: 34.7 Juta Penderita dan Terus Meningkat','Data Riskesdas terbaru mengungkap lonjakan kasus diabetes di Indonesia. Faktor gaya hidup dan kurangnya deteksi dini menjadi penyebab utama peningkatan yang signifikan ini...','20 Feb 2026','news2.png','/pages/news/artikel2.php'],
            ['Inovasi','SmartHealth: Solusi Prediksi Risiko Diabetes Berbasis AI untuk Masyarakat Indonesia','Tim pengembang SmartHealth memperkenalkan platform prediksi risiko diabetes yang dapat diakses oleh tenaga medis maupun masyarakat umum secara mudah dan cepat...','15 Feb 2026','news3.png','/pages/news/artikel3.php'],
        ];
        $catColors=['Teknologi'=>'var(--accent)','Kesehatan'=>'#00b8ff','Inovasi'=>'var(--warning)'];
        $fallbackIcons=['fas fa-microchip','fas fa-heartbeat','fas fa-lightbulb'];
        foreach($news as $i=>$n): ?>
        <a href="<?=$n[5]?>" class="news-card">
            <!-- FIXED: $n[4] = nama file gambar -->
            <div class="news-img">
                <img src="/assets/img/<?=$n[4]?>" alt="<?=htmlspecialchars($n[1])?>">
                <div class="news-fallback" id="nf<?=$i?>" style="display:none">
                    <i class="<?=$fallbackIcons[$i]?>" style="font-size:32px;color:var(--muted);opacity:.4"></i>
                    <span style="font-size:10px;color:var(--muted);letter-spacing:.06em;text-transform:uppercase"><?=$n[4]?></span>
                </div>
            </div>
            <div class="news-body">
                <div class="news-cat" style="color:<?=$catColors[$n[0]]??'var(--accent)'?>"><?=$n[0]?></div>
                <div class="news-title"><?=$n[1]?></div>
                <div class="news-excerpt"><?=substr($n[2],0,100)?>...</div>
            </div>
            <div class="news-footer">
                <span class="news-date"><i class="fas fa-calendar" style="margin-right:4px"></i><?=$n[3]?></span>
                <span style="font-size:11px;color:var(--accent)">Baca →</span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- ===== SPONSOR — 3 kolom, 2 baris ===== -->
<div style="max-width:1200px;margin:0 auto;padding:60px 40px 0">
    <div style="text-align:center;margin-bottom:32px">
        <div style="font-size:10px;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:var(--muted);margin-bottom:8px">Didukung Oleh</div>
        <h2 style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:#fff">Partner &amp; Institusi</h2>
    </div>
    <!-- 3 kolom × 2 baris = 6 logo -->
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:40px">
        <?php
        $logos=[
            ['Diktisaitek Berdampak.png','Diktisaintek Berdampak'],
            ['polman.png','Politeknik Manufaktur Negeri Bangka Belitung'],
            ['aptikom.png','Aptikom Bangka Belitung'],
            ['himpunan.png','Himpunan Mahasiswa Informatika'],
            ['Infest.png','INFEST 2026'],
            ['Undipa.png','Universitas Dipa Makassar'],
        ];
        foreach($logos as $i=>$lg): ?>
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:24px 20px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;min-height:120px;transition:border-color .2s,transform .2s" onmouseover="this.style.borderColor='rgba(0,229,160,.4)';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--border)';this.style.transform='none'">
            <img src="/assets/img/<?=$lg[0]?>" alt="<?=htmlspecialchars($lg[1])?>"
                 style="max-width:100%;max-height:56px;object-fit:contain;opacity:.85;filter:brightness(1.1)"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
            <div style="display:none;width:52px;height:52px;background:var(--surface2);border-radius:10px;align-items:center;justify-content:center;font-size:11px;color:var(--muted);font-weight:700">LOGO <?=$i+1?></div>
            <div style="font-size:11px;color:var(--muted);text-align:center;font-weight:600;line-height:1.4"><?=htmlspecialchars($lg[1])?></div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div style="height:1px;background:linear-gradient(90deg,transparent,var(--border),transparent);max-width:1200px;margin:0 auto"></div>

<div class="cta-section">
    <div class="cta-box">
        <div class="hero-badge" style="margin:0 auto 16px;width:fit-content"><i class="fas fa-rocket"></i> Mulai Sekarang</div>
        <h2 style="font-size:clamp(22px,3vw,34px);font-weight:800;color:#fff;margin-bottom:12px;line-height:1.2">Siap Prediksi Risiko<br>Diabetes Anda?</h2>
        <p style="font-size:14px;color:var(--muted);margin-bottom:28px">Gratis, cepat, dan akurat. Tidak perlu kartu kredit.</p>
        <a href="/auth/register.php" class="btn-primary" style="font-size:15px;padding:14px 32px"><i class="fas fa-user-plus"></i> Daftar Gratis Sekarang</a>
    </div>
</div>

<footer>
    © <?=date('Y')?> SmartHealth — Dikembangkan untuk <strong>INFEST Hackathon 2026</strong> | AI & Data Track | Universitas Dipa Makassar
</footer>

<script>
let current=0;
const track=document.getElementById('track');
const dots=document.querySelectorAll('.dot');
const total=document.querySelectorAll('.carousel-slide').length;

// FIXED: hide image, show fallback div on error
document.querySelectorAll('.news-img img').forEach(function(img, i) {
    img.onerror = function() {
        this.style.display = 'none';
        var fb = document.getElementById('nf' + i);
        if (fb) fb.style.display = 'flex';
    };
});

function goTo(n){current=(n+total)%total;track.style.transform='translateX(-'+current*100+'%)';dots.forEach(function(d,i){d.classList.toggle('active',i===current)});}
function next(){goTo(current+1);}
function prev(){goTo(current-1);}
setInterval(next,2000);
document.querySelectorAll('a[href^="#"]').forEach(function(a){a.addEventListener('click',function(e){var t=document.querySelector(a.getAttribute('href'));if(t){e.preventDefault();t.scrollIntoView({behavior:'smooth'});}});});
</script>
<?php include __DIR__ . '/pages/chatbot.php'; ?>
</body>
</html>