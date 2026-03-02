<?php ob_start(); $pageTitle = 'Tim Kami'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tim Kami — SmartHealth</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        :root{--bg:#0a0f1a;--surface:#111827;--surface2:#1a2235;--border:#1f2d45;--accent:#00e5a0;--accent2:#00b8ff;--danger:#ff4d6d;--warning:#ffb627;--text:#e2e8f0;--muted:#64748b}
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh}
        nav{position:sticky;top:0;z-index:100;padding:0 40px;height:64px;display:flex;align-items:center;justify-content:space-between;background:rgba(10,15,26,.9);backdrop-filter:blur(12px);border-bottom:1px solid var(--border)}
        .logo{display:flex;align-items:center;gap:10px;text-decoration:none}
        .logo-icon{width:36px;height:36px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:9px;display:flex;align-items:center;justify-content:center;color:#0a0f1a;font-size:15px}
        .logo-name{font-family:'Syne',sans-serif;font-weight:800;font-size:16px;color:#fff}
        .nav-links{display:flex;align-items:center;gap:6px}
        .nav-links a{text-decoration:none;font-size:13px;padding:7px 16px;border-radius:8px;color:var(--muted);transition:all .2s}
        .nav-links a:hover{color:#fff;background:var(--surface2)}
        .nav-links .link{color:var(--muted)}.nav-links .link:hover{color:#fff;background:var(--surface2)}
        .nav-links .btn{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#0a0f1a!important;font-weight:700}
        .nav-links .btn:hover{opacity:.9}
        .nav-links .btn-outline{border:1px solid var(--border);color:var(--text)}.nav-links .btn-outline:hover{border-color:var(--accent);color:var(--accent)}
        .hero{padding:100px 40px 60px;text-align:center;position:relative;overflow:hidden}
        .hero::before{content:'';position:absolute;top:0;left:50%;transform:translateX(-50%);width:600px;height:400px;background:radial-gradient(ellipse,rgba(0,229,160,.07),transparent 70%);pointer-events:none}
        .grid-bg{position:absolute;inset:0;background-image:linear-gradient(rgba(31,45,69,.2) 1px,transparent 1px),linear-gradient(90deg,rgba(31,45,69,.2) 1px,transparent 1px);background-size:40px 40px;mask-image:radial-gradient(ellipse at center,black 20%,transparent 70%);pointer-events:none}
        .hero-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(0,229,160,.08);border:1px solid rgba(0,229,160,.2);border-radius:20px;padding:6px 14px;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--accent);margin-bottom:16px}
        .hero-title{font-family:'Syne',sans-serif;font-size:clamp(28px,5vw,48px);font-weight:800;color:#fff;line-height:1.1;margin-bottom:14px}
        .hero-title span{background:linear-gradient(135deg,var(--accent),var(--accent2));-webkit-background-clip:text;-webkit-text-fill-color:transparent}
        .hero-desc{font-size:15px;color:var(--muted);line-height:1.8;max-width:520px;margin:0 auto}
        .team-section{max-width:1100px;margin:0 auto;padding:60px 40px}
        .team-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
        .member-card{position:relative;background:var(--surface);border:1px solid var(--border);border-radius:20px;overflow:hidden;transition:border-color .3s,transform .3s}
        .member-card:hover{border-color:var(--accent);transform:translateY(-6px)}
        .member-card:hover .card-glow{opacity:1}
        .card-glow{position:absolute;inset:0;background:radial-gradient(ellipse at 50% 0%,rgba(0,229,160,.08),transparent 60%);opacity:0;transition:opacity .3s;pointer-events:none}
        .photo-wrap{position:relative;padding:28px 32px 0;display:flex;justify-content:center}
        .photo-container{position:relative;width:144px;height:144px}
        .photo-bg{position:absolute;inset:0;border-radius:50%;background:linear-gradient(135deg,rgba(0,229,160,.15),rgba(0,184,255,.1));filter:blur(16px)}
        .photo-ring{position:absolute;inset:0;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));padding:2.5px}
        .photo-ring-inner{width:100%;height:100%;border-radius:50%;background:var(--surface);overflow:hidden}
        /* FOTO NORMAL - tidak zoom, cover tapi centered */
        .member-photo{width:100%;height:100%;object-fit:cover;object-position:center top;display:block}
        .role-badge{position:absolute;top:14px;right:14px;background:rgba(0,229,160,.12);border:1px solid rgba(0,229,160,.25);border-radius:20px;padding:4px 12px;font-size:10px;font-weight:700;letter-spacing:.06em;color:var(--accent)}
        .card-body{padding:16px 24px 24px;text-align:center}
        .member-name{font-family:'Syne',sans-serif;font-size:16px;font-weight:800;color:#fff;margin-bottom:4px}
        .member-role{font-size:12px;color:var(--muted);margin-bottom:14px;display:flex;align-items:center;justify-content:center;gap:6px}
        .member-role i{font-size:10px}
        .quote-bubble{background:var(--surface2);border:1px solid var(--border);border-radius:12px;padding:12px 14px;margin-bottom:14px;position:relative;text-align:left}
        .quote-bubble::before{content:'"';font-family:'Syne',sans-serif;font-size:32px;color:var(--accent);opacity:.3;position:absolute;top:-4px;left:10px;line-height:1}
        .quote-text{font-size:11px;color:rgba(226,232,240,.7);line-height:1.7;padding-top:10px;font-style:italic}
        .skills{display:flex;flex-wrap:wrap;gap:6px;justify-content:center}
        .skill-tag{background:rgba(0,184,255,.08);border:1px solid rgba(0,184,255,.2);border-radius:6px;padding:3px 10px;font-size:10px;color:var(--accent2);font-weight:600}
        .divider{height:1px;background:linear-gradient(90deg,transparent,var(--border),transparent);max-width:1100px;margin:0 auto}
        .sponsor-section{max-width:1100px;margin:0 auto;padding:60px 40px}
        /* 3 KOLOM SPONSOR */
        .logo-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
        .logo-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:24px 20px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;transition:border-color .2s,transform .2s;min-height:120px}
        .logo-card:hover{border-color:rgba(0,229,160,.4);transform:translateY(-3px)}
        .logo-card img{max-width:100%;max-height:52px;object-fit:contain;filter:grayscale(30%);opacity:.85;transition:all .2s}
        .logo-card:hover img{filter:grayscale(0%);opacity:1}
        .logo-placeholder{width:52px;height:52px;background:var(--surface2);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:var(--muted);text-align:center;line-height:1.3}
        .logo-name-label{font-size:11px;color:var(--muted);text-align:center;font-weight:600;line-height:1.4}
        footer{text-align:center;padding:28px;font-size:11px;color:var(--muted);border-top:1px solid var(--border);margin-top:20px}
        @keyframes fadeUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
        .fade-up{animation:fadeUp .5s ease both}
        .fade-up-2{animation:fadeUp .5s .1s ease both}
        .fade-up-3{animation:fadeUp .5s .2s ease both}
        .fade-up-4{animation:fadeUp .5s .3s ease both}
    </style>
</head>
<body>

<nav>
    <a href="/" class="logo">
        <div class="logo-icon"><i class="fas fa-heartbeat"></i></div>
        <span class="logo-name">SmartHealth</span>
    </a>
    <div class="nav-links">
        <a href="/#fitur" class="link">Fitur</a>
        <a href="/#about" class="link">Tentang</a>
        <a href="/#news" class="link">Berita</a>
        <a href="/pages/tim.php" class="link">Tim</a>
        <a href="/auth/login.php" class="link btn-outline" style="border:1px solid var(--border);border-radius:8px">Masuk</a>
        <a href="/auth/register.php" class="btn" style="padding:8px 18px;border-radius:8px;font-size:13px;font-weight:700">Daftar Gratis</a>
    </div>
</nav>

<div class="hero">
    <div class="grid-bg"></div>
    <div class="hero-badge fade-up"><i class="fas fa-users"></i> Tim Pengembang</div>
    <h1 class="hero-title fade-up">Orang-Orang di Balik<br><span>SmartHealth</span></h1>
    <p class="hero-desc fade-up-2">Tiga mahasiswa dengan satu visi — menghadirkan teknologi AI yang membantu masyarakat Indonesia hidup lebih sehat.</p>
</div>

<div class="team-section">
    <div class="team-grid">

        <!-- Reinhart -->
        <div class="member-card fade-up">
            <div class="card-glow"></div>
            <div class="role-badge"><i class="fas fa-code"></i> Developer</div>
            <div class="photo-wrap">
                <div class="photo-container">
                    <div class="photo-bg"></div>
                    <div class="photo-ring">
                        <div class="photo-ring-inner">
                            <img class="member-photo" src="/assets/img/rein.png" alt="Reinhart"
                                 onerror="this.parentElement.innerHTML='<div style=width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#0a2a1a,#0d3520);><span style=font-family:Syne,sans-serif;font-size:42px;font-weight:800;background:linear-gradient(135deg,#00e5a0,#00b8ff);-webkit-background-clip:text;-webkit-text-fill-color:transparent;>R</span></div>'">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="member-name">Reinhart Jens Robert</div>
                <div class="member-role" style="color:var(--accent)"><i class="fas fa-laptop-code" style="color:var(--accent)"></i> Fullstack Developer</div>
                <div class="quote-bubble">
                    <div class="quote-text">Kode yang baik bukan hanya yang berjalan — tapi yang bisa dipahami, dipelihara, dan memberikan dampak nyata bagi orang lain.</div>
                </div>
                <div class="skills">
                    <span class="skill-tag">PHP</span>
                    <span class="skill-tag">Python</span>
                    <span class="skill-tag">Flask</span>
                    <span class="skill-tag">MySQL</span>
                    <span class="skill-tag">REST API</span>
                </div>
            </div>
        </div>

        <!-- Valendino -->
        <div class="member-card fade-up-2">
            <div class="card-glow" style="background:radial-gradient(ellipse at 50% 0%,rgba(0,184,255,.08),transparent 60%)"></div>
            <div class="role-badge" style="background:rgba(0,184,255,.12);border-color:rgba(0,184,255,.25);color:var(--accent2)"><i class="fas fa-palette"></i> UI/UX</div>
            <div class="photo-wrap">
                <div class="photo-container">
                    <div class="photo-bg" style="background:linear-gradient(135deg,rgba(0,184,255,.2),rgba(99,102,241,.15));filter:blur(16px)"></div>
                    <div class="photo-ring" style="background:linear-gradient(135deg,var(--accent2),#6366f1)">
                        <div class="photo-ring-inner">
                            <img class="member-photo" src="/assets/img/valendino.png" alt="Valendino"
                                 onerror="this.parentElement.innerHTML='<div style=width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#0a1525,#0d2035);><span style=font-family:Syne,sans-serif;font-size:42px;font-weight:800;background:linear-gradient(135deg,#00b8ff,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;>V</span></div>'">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="member-name">Happy Valendino Hendrik Budi</div>
                <div class="member-role" style="color:var(--accent2)"><i class="fas fa-pen-ruler" style="color:var(--accent2)"></i> UI/UX Designer</div>
                <div class="quote-bubble">
                    <div class="quote-text">Desain yang baik tidak terlihat — ia hanya terasa benar. Saya percaya antarmuka yang indah bisa membuat teknologi terasa manusiawi.</div>
                </div>
                <div class="skills">
                    <span class="skill-tag">Figma</span>
                    <span class="skill-tag">Tailwind CSS</span>
                    <span class="skill-tag">Prototyping</span>
                    <span class="skill-tag">User Research</span>
                </div>
            </div>
        </div>

        <!-- Djefri -->
        <div class="member-card fade-up-3">
            <div class="card-glow" style="background:radial-gradient(ellipse at 50% 0%,rgba(255,182,39,.06),transparent 60%)"></div>
            <div class="role-badge" style="background:rgba(255,182,39,.1);border-color:rgba(255,182,39,.25);color:var(--warning)"><i class="fas fa-chart-bar"></i> Data</div>
            <div class="photo-wrap">
                <div class="photo-container">
                    <div class="photo-bg" style="background:linear-gradient(135deg,rgba(255,182,39,.2),rgba(255,120,0,.15));filter:blur(16px)"></div>
                    <div class="photo-ring" style="background:linear-gradient(135deg,var(--warning),#ff7800)">
                        <div class="photo-ring-inner">
                            <img class="member-photo" src="/assets/img/djefri.png" alt="Djefri"
                                 onerror="this.parentElement.innerHTML='<div style=width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#1a1205,#2a1e0a);><span style=font-family:Syne,sans-serif;font-size:42px;font-weight:800;background:linear-gradient(135deg,#ffb627,#ff7800);-webkit-background-clip:text;-webkit-text-fill-color:transparent;>D</span></div>'">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="member-name">Djefri Wotyla Nugroho</div>
                <div class="member-role" style="color:var(--warning)"><i class="fas fa-database" style="color:var(--warning)"></i> Data Analyst & Dokumentasi</div>
                <div class="quote-bubble">
                    <div class="quote-text">Di balik setiap keputusan hebat, ada data yang berbicara. Tugas saya memastikan data itu dipahami — oleh mesin maupun manusia.</div>
                </div>
                <div class="skills">
                    <span class="skill-tag">Python</span>
                    <span class="skill-tag">Scikit-learn</span>
                    <span class="skill-tag">Pandas</span>
                    <span class="skill-tag">Jupyter</span>
                    <span class="skill-tag">Dokumentasi</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Tim Quote -->
    <div style="margin-top:48px;background:linear-gradient(135deg,rgba(0,229,160,.05),rgba(0,184,255,.04));border:1px solid rgba(0,229,160,.15);border-radius:18px;padding:36px;text-align:center" class="fade-up-4">
        <div style="font-size:40px;color:var(--accent);opacity:.25;font-family:'Syne',sans-serif;line-height:1;margin-bottom:4px">"</div>
        <p style="font-size:17px;color:rgba(226,232,240,.8);line-height:1.8;font-style:italic;max-width:600px;margin:0 auto 16px">Kami bukan hanya membangun aplikasi — kami membangun harapan bahwa teknologi bisa menjangkau semua orang, terutama mereka yang paling membutuhkan.</p>
        <div style="font-family:'Syne',sans-serif;font-size:13px;font-weight:700;background:linear-gradient(135deg,var(--accent),var(--accent2));-webkit-background-clip:text;-webkit-text-fill-color:transparent">— Tim SmartHealth, INFEST Hackathon 2026</div>
    </div>
</div>

<div class="divider"></div>

<!-- SPONSOR — 3 kolom × 2 baris -->
<div class="sponsor-section">
    <div class="section-label" style="font-size:10px;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:var(--muted);text-align:center;margin-bottom:8px">Didukung Oleh</div>
    <h2 style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:#fff;text-align:center;margin-bottom:6px">Partner & Institusi</h2>
    <p style="font-size:13px;color:var(--muted);text-align:center;margin-bottom:36px">SmartHealth dikembangkan atas dukungan berbagai institusi dan teknologi terkemuka</p>

    <div class="logo-grid">
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
        <div class="logo-card">
            <img src="/assets/img/<?=$lg[0]?>" alt="<?=htmlspecialchars($lg[1])?>"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
            <div class="logo-placeholder" style="display:none">LOGO <?=$i+1?></div>
            <div class="logo-name-label"><?=htmlspecialchars($lg[1])?></div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<footer>
    © <?=date('Y')?> SmartHealth — Dikembangkan untuk <strong>INFEST Hackathon 2026</strong> | AI & Data Track | Universitas Dipa Makassar
</footer>

<?php include __DIR__ . '/../pages/chatbot.php'; ?>
</body>
</html>