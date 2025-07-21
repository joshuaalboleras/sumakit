<?php
// Use absolute path to the main config file
require_once 'C:/laragon/www/sumakit/configuration/config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['store'])) {
    header('Location: ../../store_login.php');
    exit();
}

// Get store ID from session
$store_id = $_SESSION['store']['id'];

// Function to get database connection
function getDBConnection() {
    global $conn;
    return $conn;
}
?>
