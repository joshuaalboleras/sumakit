<?php
include '../../configuration/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        header('Location: ../../superadmin/management.php');
        exit;
    }
    $id = intval($_POST['edit_municipality_id'] ?? 0);
    $municipality = trim($_POST['municipality'] ?? '');
    if ($id && $municipality) {
        $stmt = $conn->prepare('UPDATE municipalities SET municipality = ? WHERE id = ?');
        $stmt->execute([$municipality, $id]);
    }
    header('Location: ../../superadmin/management.php');
    exit;
} 