<?php
include '../../configuration/config.php';
header('Content-Type: application/json');
if (!isset($_GET['barangay_id']) || !is_numeric($_GET['barangay_id'])) {
    echo json_encode([]);
    exit;
}
$barangay_id = (int)$_GET['barangay_id'];
$stmt = $conn->prepare('SELECT id as household_id, family_name FROM household WHERE house_id IN (SELECT id FROM houses WHERE barangay_id = ?) GROUP BY family_name, id ORDER BY family_name ASC');
$stmt->execute([$barangay_id]);
$families = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($families); 