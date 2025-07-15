<?php 
include '../../configuration/config.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $allowed = ['province_id', 'municipality', 'geojson'];
    foreach ($_POST as $key => $value) {
        if (in_array($key, $allowed)) {
            $$key = trim($value);
        }
    }

    // Proper CSRF validation
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        // Redirect or show error
        header('Location: ../../superadmin/registration.php');
        exit;
    }

    // Insert with GeoJSON support
    $stmt = $conn->prepare("
        INSERT INTO municipalities (province_id, municipality, geojson)
        VALUES (:province_id, :municipality, :geojson)
    ");
    
    $stmt->execute([
        ':province_id' => $province_id,
        ':municipality' => $municipality,
        ':geojson' => $geojson
    ]);

    header('Location: ../../superadmin/registration.php');
    exit;
}
