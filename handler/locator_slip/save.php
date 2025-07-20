<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

function log_error($msg) {
    $logfile = __DIR__ . '/save_error.log';
    file_put_contents($logfile, date('Y-m-d H:i:s') . ' ' . $msg . "\n", FILE_APPEND);
}

try {
    include '../../configuration/config.php';
} catch (Exception $e) {
    log_error('Config include failed: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Config include failed: ' . $e->getMessage()]);
    exit;
}

// Check POST parameters
if (!isset($_POST['name']) || !isset($_POST['geojson']) || !isset($_POST['purpose'])) {
    log_error('Missing parameters: ' . json_encode($_POST));
    echo json_encode(['success' => false, 'message' => 'Missing parameters.']);
    exit;
}

$name = trim($_POST['name']);
$purpose = trim($_POST['purpose']);
$geojson = $_POST['geojson'];

if ($name === '' || $geojson === '') {
    log_error('Empty name or geojson.');
    echo json_encode(['success' => false, 'message' => 'Name and geojson are required.']);
    exit;
}

if (!isset($conn) || !$conn) {
    log_error('Database connection not established.');
    echo json_encode(['success' => false, 'message' => 'Database connection not established.']);
    exit;
}

try {
    $stmt = $conn->prepare('INSERT INTO locator_slips (name, geojson,purpose) VALUES (?, ?,?)');
    $stmt->execute([$name, $geojson,$purpose]);
    $id = $conn->lastInsertId();
    echo json_encode(['success' => true, 'id' => $id]);
} catch (PDOException $e) {
    log_error('PDO error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'PDO error: ' . $e->getMessage()]);
} 