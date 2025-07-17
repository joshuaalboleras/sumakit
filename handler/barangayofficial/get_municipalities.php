<?php
include '../../configuration/config.php';
header('Content-Type: application/json');

if (!isset($_GET['province_id']) || !is_numeric($_GET['province_id'])) {
    echo json_encode(['error' => 'Invalid province_id']);
    exit;
}
$province_id = (int)$_GET['province_id'];
$stmt = $conn->prepare('SELECT id, municipality,geojson FROM municipalities WHERE province_id = ? ORDER BY municipality ASC');
$stmt->execute([$province_id]);
$municipalities = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($municipalities); 