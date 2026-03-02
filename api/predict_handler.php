<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_check.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Data tidak valid']);
    exit;
}

// Hit Flask API
$ch = curl_init('http://127.0.0.1:5000/predict');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS     => json_encode($input),
    CURLOPT_TIMEOUT        => 15,
]);
$response  = curl_exec($ch);
$httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if (!$response || $httpCode !== 200) {
    echo json_encode(['success' => false, 'message' => 'Flask error HTTP:' . $httpCode . ' ' . $curlError]);
    exit;
}

$ml = json_decode($response, true);
if (!$ml || !isset($ml['prediction'])) {
    echo json_encode(['success' => false, 'message' => 'Response invalid: ' . $response]);
    exit;
}

$prob      = $ml['probability_diabetes'] * 100;
$riskLevel = $prob < 20 ? 'Rendah' : ($prob < 50 ? 'Sedang' : ($prob < 75 ? 'Tinggi' : 'Sangat Tinggi'));

$db           = getDB();
$userId       = (int)$_SESSION['user_id'];
$patientName  = $input['patient_name'] ?? 'Anonim';
$gender       = $input['gender'];
$age          = (float)$input['age'];
$hypertension = (int)$input['hypertension'];
$heartDisease = (int)$input['heart_disease'];
$smoking      = $input['smoking_history'];
$bmi          = (float)$input['bmi'];
$hba1c        = (float)$input['hba1c_level'];
$glucose      = (int)$input['blood_glucose_level'];
$result       = (int)$ml['prediction'];
$probVal      = (float)$ml['probability_diabetes'];

$stmt = $db->prepare("INSERT INTO predictions
    (user_id, patient_name, gender, age, hypertension, heart_disease,
     smoking_history, bmi, hba1c_level, blood_glucose_level,
     result, probability_diabetes, risk_level)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param('issdiisddiids',
    $userId, $patientName, $gender, $age,
    $hypertension, $heartDisease, $smoking,
    $bmi, $hba1c, $glucose,
    $result, $probVal, $riskLevel
);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'DB error: ' . $stmt->error]);
    exit;
}

$id = $db->insert_id;
$db->close();

echo json_encode(['success' => true, 'prediction_id' => $id, 'result' => $result, 'probability' => $probVal, 'risk_level' => $riskLevel]);