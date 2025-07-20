<?php
include '../../configuration/config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : 0;
$house_number = isset($_POST['house_number']) ? trim($_POST['house_number']) : '';
$building_type = isset($_POST['building_type']) ? trim($_POST['building_type']) : '';
$status = isset($_POST['status']) ? trim($_POST['status']) : '';
$no_floors = isset($_POST['no_floors']) ? trim($_POST['no_floors']) : '';
$year_built = isset($_POST['year_built']) ? trim($_POST['year_built']) : '';
$street_name = isset($_POST['street_name']) ? trim($_POST['street_name']) : '';
$province_id = isset($_POST['province_id']) ? intval($_POST['province_id']) : 0;
$municipal_id = isset($_POST['municipal_id']) ? intval($_POST['municipal_id']) : 0;
$barangay_id = isset($_POST['barangay_id']) ? intval($_POST['barangay_id']) : 0;
$geojson = isset($_POST['geojson']) ? trim($_POST['geojson']) : '';

if (!$id || $house_number === '' || !$province_id || !$municipal_id || !$barangay_id || $geojson === '') {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE houses SET house_number=?, building_type=?, status=?, no_floors=?, year_built=?, street_name=?, province_id=?, municipal_id=?, barangay_id=?, geojson=? WHERE id=?");
    $stmt->execute([$house_number, $building_type, $status, $no_floors, $year_built, $street_name, $province_id, $municipal_id, $barangay_id, $geojson, $id]);
    echo json_encode(['success' => true, 'message' => 'House updated successfully.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} 