<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';

// Gemini API key — ganti dengan key Anda dari https://aistudio.google.com/app/apikey
define('GEMINI_API_KEY','AIzaSyBrAu1g4T-QgVDbv7Ujcz5s1Dt5HHSJ88YB (izin tidak memberikan API key asli)');
define('GEMINI_URL','https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key='.GEMINI_API_KEY);

// Handle AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    header('Content-Type: application/json');

    $userMsg = trim($_POST['message'] ?? '');
    if (!$userMsg || strlen($userMsg) > 1000) {
        echo json_encode(['reply' => 'Pesan tidak valid.']);
        exit;
    }

    // System prompt untuk chatbot SmartHealth
    $systemPrompt = "Kamu adalah SmartBot, asisten kesehatan cerdas dari platform SmartHealth. 

Platform SmartHealth adalah aplikasi prediksi risiko diabetes berbasis AI yang dikembangkan oleh mahasiswa Universitas Dipa Makassar untuk INFEST Hackathon 2026.

Tugas kamu:
1. Menjawab pertanyaan seputar diabetes (gejala, pencegahan, faktor risiko, pengobatan)
2. Menjelaskan cara kerja SmartHealth dan fitur-fiturnya
3. Memberikan edukasi kesehatan umum terkait gaya hidup sehat
4. Membantu user memahami hasil prediksi risiko diabetes mereka
5. Konsultasi gejala ringan dengan saran edukatif (BUKAN diagnosis medis)

Tentang SmartHealth:
- Menggunakan algoritma Random Forest dengan akurasi 97.1%
- Dilatih dengan 100.000+ data klinis dari Kaggle
- Fitur: prediksi AI real-time, dashboard analitik, export PDF, riwayat prediksi, global chat
- Tim: Reinhart (developer), Valendino (UI/UX), Djefri (data analyst)

Aturan penting:
- Selalu ingatkan bahwa kamu BUKAN dokter dan saran kamu bukan pengganti konsultasi medis
- Jawab dalam Bahasa Indonesia yang ramah dan mudah dipahami
- Jawaban singkat dan padat (maksimal 3-4 paragraf pendek)
- Jika ditanya hal yang tidak relevan dengan kesehatan atau SmartHealth, arahkan kembali ke topik tersebut dengan sopan
- Gunakan emoji secukupnya agar terasa lebih friendly";

    // Kirim ke Gemini API
    $payload = json_encode([
        'contents' => [
            [
                'role' => 'user',
                'parts' => [['text' => $systemPrompt . "\n\nUser: " . $userMsg]]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.7,
            'maxOutputTokens' => 512,
        ]
    ]);

    $ch = curl_init(GEMINI_URL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        $data  = json_decode($response, true);
        $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak bisa menjawab saat ini.';
    } else {
        $reply = 'Maaf, layanan AI sedang tidak tersedia. Silakan coba lagi nanti.';
    }

    echo json_encode(['reply' => $reply]);
    exit;
}
?>
<!-- CHATBOT WIDGET — include di footer atau halaman manapun -->
<style>
#smartbot-btn{position:fixed;bottom:28px;right:28px;width:56px;height:56px;background:linear-gradient(135deg,#00e5a0,#00b8ff);border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 4px 20px rgba(0,229,160,.4);z-index:999;transition:transform .2s,box-shadow .2s;border:none}
#smartbot-btn:hover{transform:scale(1.08);box-shadow:0 6px 28px rgba(0,229,160,.5)}
#smartbot-btn i{font-size:22px;color:#0a0f1a;transition:transform .3s}
#smartbot-btn.open i{transform:rotate(90deg)}
.smartbot-pulse{position:absolute;top:0;right:0;width:14px;height:14px;background:#ff4d6d;border-radius:50%;border:2px solid #0a0f1a;animation:sbPulse 2s infinite}

#smartbot-box{position:fixed;bottom:96px;right:28px;width:360px;background:#111827;border:1px solid #1f2d45;border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,.5);z-index:998;display:none;flex-direction:column;overflow:hidden;animation:sbSlideUp .25s ease}
#smartbot-box.show{display:flex}

.sb-header{padding:14px 18px;background:linear-gradient(135deg,rgba(0,229,160,.1),rgba(0,184,255,.08));border-bottom:1px solid #1f2d45;display:flex;align-items:center;gap:10px}
.sb-avatar{width:36px;height:36px;background:linear-gradient(135deg,#00e5a0,#00b8ff);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0}
.sb-title{font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:#fff}
.sb-sub{font-size:10px;color:#00e5a0;display:flex;align-items:center;gap:4px}
.sb-close{margin-left:auto;background:none;border:none;color:#64748b;cursor:pointer;font-size:16px;padding:4px;transition:color .2s}
.sb-close:hover{color:#fff}

.sb-messages{flex:1;overflow-y:auto;padding:14px;display:flex;flex-direction:column;gap:10px;max-height:340px;min-height:200px}
.sb-msg{display:flex;gap:8px;align-items:flex-end;animation:sbFade .2s ease}
.sb-msg.user{flex-direction:row-reverse}
.sb-bubble{max-width:82%;padding:9px 13px;border-radius:14px;font-size:12px;line-height:1.6;word-break:break-word}
.sb-bubble.bot{background:#1a2235;border:1px solid #1f2d45;color:#e2e8f0;border-bottom-left-radius:4px}
.sb-bubble.user{background:linear-gradient(135deg,rgba(0,229,160,.2),rgba(0,184,255,.15));border:1px solid rgba(0,229,160,.25);color:#fff;border-bottom-right-radius:4px}
.sb-avatar-sm{width:26px;height:26px;border-radius:50%;background:linear-gradient(135deg,#00e5a0,#00b8ff);display:flex;align-items:center;justify-content:center;font-size:10px;color:#0a0f1a;font-weight:800;flex-shrink:0;font-family:'Syne',sans-serif}
.sb-typing{display:flex;gap:4px;padding:10px 13px;background:#1a2235;border:1px solid #1f2d45;border-radius:14px;border-bottom-left-radius:4px;width:fit-content}
.sb-typing span{width:6px;height:6px;background:#64748b;border-radius:50%;animation:sbDot 1.2s infinite}
.sb-typing span:nth-child(2){animation-delay:.2s}
.sb-typing span:nth-child(3){animation-delay:.4s}

.sb-suggestions{padding:0 14px 10px;display:flex;flex-wrap:wrap;gap:6px}
.sb-chip{background:rgba(0,229,160,.06);border:1px solid rgba(0,229,160,.2);border-radius:20px;padding:4px 12px;font-size:10px;color:#00e5a0;cursor:pointer;transition:all .2s;white-space:nowrap}
.sb-chip:hover{background:rgba(0,229,160,.15);border-color:rgba(0,229,160,.4)}

.sb-input-area{padding:10px 12px;border-top:1px solid #1f2d45;display:flex;gap:8px;align-items:center}
#sb-input{flex:1;background:#1a2235;border:1px solid #1f2d45;border-radius:10px;padding:9px 12px;color:#e2e8f0;font-size:12px;font-family:'DM Sans',sans-serif;outline:none;transition:border-color .2s}
#sb-input:focus{border-color:#00e5a0}
#sb-input::placeholder{color:#64748b}
#sb-send{width:34px;height:34px;background:linear-gradient(135deg,#00e5a0,#00b8ff);border:none;border-radius:9px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:opacity .2s;flex-shrink:0}
#sb-send:hover{opacity:.85}
#sb-send i{font-size:12px;color:#0a0f1a}

@keyframes sbPulse{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(1.3);opacity:.7}}
@keyframes sbSlideUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
@keyframes sbFade{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:translateY(0)}}
@keyframes sbDot{0%,80%,100%{transform:scale(.6);opacity:.4}40%{transform:scale(1);opacity:1}}
.sb-messages::-webkit-scrollbar{width:3px}.sb-messages::-webkit-scrollbar-thumb{background:#1f2d45;border-radius:2px}
</style>

<!-- Tombol floating -->
<button id="smartbot-btn" onclick="toggleBot()" title="SmartBot — Asisten Kesehatan">
    <i class="fas fa-robot"></i>
    <div class="smartbot-pulse"></div>
</button>

<!-- Box chat -->
<div id="smartbot-box">
    <div class="sb-header">
        <div class="sb-avatar">🤖</div>
        <div>
            <div class="sb-title">SmartBot</div>
            <div class="sb-sub"><span style="width:6px;height:6px;background:#00e5a0;border-radius:50%;display:inline-block"></span> Online — Asisten Kesehatan AI</div>
        </div>
        <button class="sb-close" onclick="toggleBot()"><i class="fas fa-times"></i></button>
    </div>

    <div class="sb-messages" id="sb-messages">
        <!-- Pesan pembuka -->
        <div class="sb-msg">
            <div class="sb-avatar-sm">🤖</div>
            <div class="sb-bubble bot">Halo! Saya <strong>SmartBot</strong>, asisten kesehatan AI dari SmartHealth. 👋<br><br>Saya bisa membantu Anda seputar diabetes, hasil prediksi, atau info tentang platform ini. Ada yang bisa saya bantu?</div>
        </div>
    </div>

    <!-- Quick suggestions -->
    <div class="sb-suggestions" id="sb-suggestions">
        <div class="sb-chip" onclick="sendSuggestion(this)">Apa itu diabetes?</div>
        <div class="sb-chip" onclick="sendSuggestion(this)">Faktor risiko diabetes</div>
        <div class="sb-chip" onclick="sendSuggestion(this)">Cara pakai SmartHealth</div>
        <div class="sb-chip" onclick="sendSuggestion(this)">Tips hidup sehat</div>
    </div>

    <div class="sb-input-area">
        <input type="text" id="sb-input" placeholder="Ketik pertanyaan..." maxlength="500"
               onkeypress="if(event.key==='Enter')sendMsg()">
        <button id="sb-send" onclick="sendMsg()"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>

<script>
var sbOpen = false;

function toggleBot() {
    sbOpen = !sbOpen;
    var box = document.getElementById('smartbot-box');
    var btn = document.getElementById('smartbot-btn');
    box.classList.toggle('show', sbOpen);
    btn.classList.toggle('open', sbOpen);
    if (sbOpen) {
        document.querySelector('.smartbot-pulse').style.display = 'none';
        document.getElementById('sb-input').focus();
        scrollSB();
    }
}

function scrollSB() {
    var msgs = document.getElementById('sb-messages');
    msgs.scrollTop = msgs.scrollHeight;
}

function appendMsg(text, role) {
    var msgs = document.getElementById('sb-messages');
    var div = document.createElement('div');
    div.className = 'sb-msg ' + (role === 'user' ? 'user' : '');
    var avatarHtml = role === 'user'
        ? '<div class="sb-avatar-sm" style="background:linear-gradient(135deg,#1a2235,#2a3545);color:#64748b;font-size:9px">You</div>'
        : '<div class="sb-avatar-sm">🤖</div>';
    // Convert **bold** markdown to <strong>
    var formatted = text
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\n/g, '<br>');
    div.innerHTML = avatarHtml + '<div class="sb-bubble ' + (role === 'user' ? 'user' : 'bot') + '">' + formatted + '</div>';
    msgs.appendChild(div);
    scrollSB();
}

function showTyping() {
    var msgs = document.getElementById('sb-messages');
    var div = document.createElement('div');
    div.className = 'sb-msg'; div.id = 'sb-typing';
    div.innerHTML = '<div class="sb-avatar-sm">🤖</div><div class="sb-typing"><span></span><span></span><span></span></div>';
    msgs.appendChild(div);
    scrollSB();
    return div;
}

function sendSuggestion(el) {
    var text = el.textContent;
    document.getElementById('sb-suggestions').style.display = 'none';
    doSend(text);
}

function sendMsg() {
    var input = document.getElementById('sb-input');
    var text = input.value.trim();
    if (!text) return;
    input.value = '';
    document.getElementById('sb-suggestions').style.display = 'none';
    doSend(text);
}

function doSend(text) {
    appendMsg(text, 'user');
    var typing = showTyping();
    document.getElementById('sb-send').disabled = true;

    var formData = new FormData();
    formData.append('message', text);

    fetch('/pages/chatbot.php', {method:'POST', body: formData})
        .then(function(r){ return r.json(); })
        .then(function(data) {
            typing.remove();
            appendMsg(data.reply || 'Maaf, tidak ada respons.', 'bot');
            document.getElementById('sb-send').disabled = false;
        })
        .catch(function() {
            typing.remove();
            appendMsg('Koneksi bermasalah. Coba lagi ya! 🙏', 'bot');
            document.getElementById('sb-send').disabled = false;
        });
}
</script>