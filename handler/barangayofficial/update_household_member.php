<?php
include '../../configuration/config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : 0;
$family_name = isset($_POST['family_name']) ? trim($_POST['family_name']) : '';
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$birthdate = isset($_POST['birthdate']) ? trim($_POST['birthdate']) : '';
$occupation = isset($_POST['occupation']) ? trim($_POST['occupation']) : '';
$relationship = isset($_POST['relationship']) ? trim($_POST['relationship']) : '';
$suffix = isset($_POST['suffix']) ? trim($_POST['suffix']) : '';

if (!$id || $family_name === '' || $name === '' || $birthdate === '') {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE household SET family_name=?, name=?, birthdate=?, occupation=?, relationship=?, suffix=? WHERE id=?");
    $stmt->execute([$family_name, $name, $birthdate, $occupation, $relationship, $suffix, $id]);
    echo json_encode(['success' => true, 'message' => 'Household member updated successfully.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} 