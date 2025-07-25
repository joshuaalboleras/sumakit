<?php
require_once '../../configuration/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $owner_name = isset($_POST['owner_name']) ? trim($_POST['owner_name']) : null;
    $username = isset($_POST['username']) ? trim($_POST['username']) : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $barangay_id = isset($_POST['barangay_id']) ? intval($_POST['barangay_id']) : null;
    $municipal_id = isset($_POST['municipal_id']) ? intval($_POST['municipal_id']) : null;
    $province_id = isset($_POST['province_id']) ? intval($_POST['province_id']) : null;
    $geojson = isset($_POST['geojson']) ? trim($_POST['geojson']) : null;
    $household_id = isset($_POST['household_id']) && $_POST['household_id'] !== '' ? intval($_POST['household_id']) : null;

    // Basic validation
    if (!$owner_name || !$username || !$password || !$barangay_id || !$municipal_id || !$province_id || !$geojson) {
        die('All fields are required.');
    }

    // Encrypt password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Prepare and execute insert
        $stmt = $conn->prepare("INSERT INTO stores (owner_name, barangay_id, municipal_id, province_id, geojson, owner_id, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $owner_name,
            $barangay_id,
            $municipal_id,
            $province_id,
            $geojson,
            $household_id,
            $username,
            $password_hash
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