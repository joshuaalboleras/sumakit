<?php
/**
 * Print Receipt Handler
 * This file generates and displays a receipt for printing
 */

// Include the main configuration file
require_once __DIR__ . '/../../configuration/config.php';

// Check if receipt data was sent
$receipt_data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if we received JSON data
    $json_data = file_get_contents('php://input');
    if ($json_data) {
        $receipt_data = json_decode($json_data, true);
    } 
    // Check if we received form data
    elseif (isset($_POST['receipt_data'])) {
        $receipt_data = json_decode($_POST['receipt_data'], true);
    }
    
    if (empty($receipt_data)) {
        die('Invalid receipt data');
    }
    
    // Add additional receipt information
    $sale_data['receipt_number'] = 'RCPT-' . strtoupper(uniqid());
    $sale_data['store_name'] = 'Your Store Name'; // Replace with actual store name
    $sale_data['store_address'] = '123 Store St., City, Country'; // Replace with actual store address
    $sale_data['store_contact'] = '0912-345-6789'; // Replace with actual contact number
    $sale_data['store_tin'] = '123-456-789-000'; // Replace with actual TIN
    $sale_data['cashier_name'] = $_SESSION['user']['name'] ?? 'Cashier'; // Replace with actual cashier name from session
    $sale_data['customer_name'] = $sale_data['customer_name'] ?? 'Walk-in Customer';
    
    // Calculate change if not provided
    if (!isset($sale_data['change']) && isset($sale_data['amount_tendered'], $sale_data['total'])) {
        $sale_data['change'] = $sale_data['amount_tendered'] - $sale_data['total'];
    }
    
    // Set discount percent if not provided
    if (!isset($sale_data['discount_percent']) && isset($sale_data['discount'], $sale_data['subtotal']) && $sale_data['subtotal'] > 0) {
        $sale_data['discount_percent'] = ($sale_data['discount'] / $sale_data['subtotal']) * 100;
    }
    
    // Output the receipt
    header('Content-Type: text/html');
    
    // Start output buffering
    ob_start();
    
    // Include the receipt template
    include __DIR__ . '/../includes/receipt_template.php';
    
    // Get the output and clean the buffer
    $output = ob_get_clean();
    
    // Output the receipt
    echo $output;
    exit();
} else {
    // If not a POST request, redirect to the POS page
    header('Location: ../pos.php');
    exit();
}
