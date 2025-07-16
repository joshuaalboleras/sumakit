<?php
include '../../configuration/config.php';
header('Content-Type: application/json');

if (!isset($_GET['municipal_id']) || !is_numeric($_GET['municipal_id'])) {
    echo json_encode(['error' => 'Invalid municipal_id']);
    exit;
}
$municipal_id = (int)$_GET['municipal_id'];
$stmt = $conn->prepare('SELECT id, barangay_name FROM barangays WHERE municipal_id = ? ORDER BY barangay_name ASC');
$stmt->execute([$municipal_id]);
$barangays = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($barangays); 