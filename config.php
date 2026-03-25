<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'smarthealth');

define('APP_NAME', 'SmartHealth');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('APP_URL', $protocol . '://' . $host);

define('FLASK_API_URL', 'http://localhost:5000');

// ===================================
// AUTO-START FLASK
// ===================================
function startFlaskIfNeeded() {
    $url = FLASK_API_URL . '/health';

    // Cek apakah Flask sudah berjalan
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) return; // Sudah jalan, skip

    $pythonPath = 'C:\\Users\\ASUS\\AppData\\Local\\Programs\\Python\\Python311\\python.exe';
    $apiPath    = realpath(__DIR__ . '/api');
    $logPath    = $apiPath . '\\flask.log';
    $vbsPath    = $apiPath . '\\start_flask.vbs';

    // Tulis VBS
    $vbsContent  = 'Set WshShell = CreateObject("WScript.Shell")' . "\r\n";
    $vbsContent .= 'WshShell.Run Chr(34) & "' . $pythonPath . '" & Chr(34) & " ' . $apiPath . '\\predict.py >> ' . $logPath . ' 2>&1", 0, False' . "\r\n";
    file_put_contents($vbsPath, $vbsContent);

    // Jalankan VBS
    shell_exec("wscript.exe \"{$vbsPath}\"");

    // Tunggu Flask siap, maksimal 10 detik
    for ($i = 0; $i < 10; $i++) {
        sleep(1);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($code === 200) break;
    }
}

startFlaskIfNeeded();

session_start();