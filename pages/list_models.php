<?php
// Buka: http://smarthealth_app.test/pages/list_models.php
// Untuk lihat model yang tersedia di API key kamu

$apiKey = 'AIzaSyAMytnCJxiPk8RhTFPAfJRCiRUGJOdX_bc';

$ch = curl_init('https://generativelanguage.googleapis.com/v1beta/models?key=' . $apiKey);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_TIMEOUT        => 15,
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$data = json_decode($response, true);

echo "<h3>HTTP: $httpCode</h3><ul>";
if (isset($data['models'])) {
    foreach ($data['models'] as $m) {
        if (in_array('generateContent', $m['supportedGenerationMethods'] ?? [])) {
            echo "<li><strong>" . $m['name'] . "</strong> — " . ($m['displayName'] ?? '') . "</li>";
        }
    }
} else {
    echo "<pre>" . $response . "</pre>";
}
echo "</ul>";
?>