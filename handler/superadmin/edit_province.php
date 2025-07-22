<?php
include '../../configuration/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        header('Location: ../../superadmin/management.php');
        exit;
    }
    $id = intval($_POST['edit_province_id'] ?? 0);
    $province_name = trim($_POST['province_name'] ?? '');
    if ($id && $province_name) {
        $stmt = $conn->prepare('UPDATE provinces SET province_name = ? WHERE id = ?');
        $stmt->execute([$province_name, $id]);
    }
    header('Location: ../../superadmin/management.php');
    exit;
} 