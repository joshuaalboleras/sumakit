<?php
require_once '../../configuration/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : null;
    $owner_name = isset($_POST['owner_name']) ? trim($_POST['owner_name']) : null;
    $province_id = isset($_POST['province_id']) ? intval($_POST['province_id']) : null;
    $municipal_id = isset($_POST['municipal_id']) ? intval($_POST['municipal_id']) : null;
    $barangay_id = isset($_POST['barangay_id']) ? intval($_POST['barangay_id']) : null;
    $geojson = isset($_POST['geojson']) ? trim($_POST['geojson']) : null;

    if (!$id || !$owner_name || !$province_id || !$municipal_id || !$barangay_id || !$geojson) {
        echo json_encode(['success' => false, 'message' => "All fields are required."]);
        exit;
    }

    try {
        $stmt = $conn->prepare("UPDATE stores SET owner_name = ?, province_id = ?, municipal_id = ?, barangay_id = ?, geojson = ? WHERE id = ?");
        $stmt->execute([$owner_name, $province_id, $municipal_id, $barangay_id, $geojson, $id]);
        echo json_encode(['success' => true, 'message' => 'Store updated successfully.']);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
} 