<?php
require_once '../../configuration/config.php'; // Adjust path as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $house_number = isset($_POST['house_number']) ? intval($_POST['house_number']) : null;
    $street_name = isset($_POST['street_name']) ? trim($_POST['street_name']) : null;
    $barangay_id = isset($_POST['barangay_id']) ? intval($_POST['barangay_id']) : null;
    $municipal_id = isset($_POST['municipal_id']) ? intval($_POST['municipal_id']) : null;
    $province_id = isset($_POST['province_id']) ? intval($_POST['province_id']) : null;
    $geojson = isset($_POST['geojson']) ? trim($_POST['geojson']) : null;
    $building_type = isset($_POST['building_type']) ? trim($_POST['building_type']) : null;
    $status = isset($_POST['status']) ? trim($_POST['status']) : null;
    $no_floors = isset($_POST['no_floors']) ? intval($_POST['no_floors']) : null;
    $year_built = isset($_POST['year_built']) ? $_POST['year_built'] : null;

    // Basic validation
    if (
        !$house_number || !$street_name || !$barangay_id ||
        !$municipal_id || !$province_id || !$geojson ||
        !$building_type || !$status || !$no_floors || !$year_built
    ) {
        die('All fields are required.');
    }

    try {
        // Prepare and execute insert
        $stmt = $conn->prepare("INSERT INTO houses (house_number, street_name, barangay_id, municipal_id, province_id, geojson, building_type, status, no_floors, year_built) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $house_number,
            $street_name,
            $barangay_id,
            $municipal_id,
            $province_id,
            $geojson,
            $building_type,
            $status,
            $no_floors,
            $year_built
        ]);
        // Redirect or show success
        header('Location: ../../barangayofficial/index.php?success=1');
        exit;
    } catch (PDOException $e) {
        // Handle error
        die("Database error: " . $e->getMessage());
    }
} else {
    die('Invalid request.');
} 