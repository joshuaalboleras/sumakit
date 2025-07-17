<?php
include '../../configuration/config.php';
header('Content-Type: application/json');
if (!isset($_GET['household_id']) || !is_numeric($_GET['household_id'])) {
    echo json_encode([]);
    exit;
}
$household_id = (int)$_GET['household_id'];
$stmt = $conn->prepare('SELECT id, name, suffix FROM household WHERE id = ? OR house_id = (SELECT house_id FROM household WHERE id = ?)');
$stmt->execute([$household_id, $household_id]);
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($members); 