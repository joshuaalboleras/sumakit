<?php 
include '../../configuration/config.php'; 
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF validation
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        // Redirect or show error
        header('Location: ../../superadmin/registration.php');
        exit;
    }

    // Collect and sanitize input
    $province_id = trim($_POST['province_id'] ?? '');
    $municipal_id = trim($_POST['municipal_id'] ?? '');
    $barangay_name = trim($_POST['barangay'] ?? '');
    $geojson = trim($_POST['geojson'] ?? '');

    // Basic validation (optional: add more)
    if ($province_id && $municipal_id && $barangay_name && $geojson) {
        $stmt = $conn->prepare("
            INSERT INTO barangays (province_id, municipal_id, barangay_name, geojson)
            VALUES (:province_id, :municipal_id, :barangay_name, :geojson)
        ");
        $stmt->execute([
            ':province_id' => $province_id,
            ':municipal_id' => $municipal_id,
            ':barangay_name' => $barangay_name,
            ':geojson' => $geojson
        ]);
    }

    header('Location: ../../superadmin/registration.php');
    exit;
}
