<?php
/**
 * Save Product Handler
 * This file handles saving new products to the database
 */

// Disable error display in production
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Function to send JSON response
function sendJsonResponse($success, $message = '', $data = []) {
    http_response_code($success ? 200 : 400);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit();
}

// Set content type to JSON
header('Content-Type: application/json');

// Check if this is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Invalid request method');
}

// Get the raw POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Check if JSON is valid
if (json_last_error() !== JSON_ERROR_NONE) {
    sendJsonResponse(false, 'Invalid JSON data');
}

// Debug: Log received data and server info
error_log('=== SAVE_PRODUCT.PHP DEBUG ===');
error_log('Request Method: ' . $_SERVER['REQUEST_METHOD']);
error_log('Content Type: ' . ($_SERVER['CONTENT_TYPE'] ?? 'not set'));
error_log('Raw Input: ' . file_get_contents('php://input'));
error_log('Decoded Data: ' . print_r($data, true));
error_log('Session Data: ' . print_r($_SESSION ?? [], true));

// Validate required fields
$required_fields = [
    'name' => 'Product Name',
    'price' => 'Price',
    'stock' => 'Stock Level'
];

$missing_fields = [];
$validation_errors = [];

foreach ($required_fields as $field => $label) {
    if (!isset($data[$field]) || (is_string($data[$field]) && trim($data[$field]) === '')) {
        $missing_fields[] = $label;
    }
}

// Additional validation for numeric fields
if (isset($data['price']) && !is_numeric($data['price'])) {
    $validation_errors[] = 'Price must be a number';
}

if (isset($data['stock']) && !is_numeric($data['stock'])) {
    $validation_errors[] = 'Stock must be a number';
}

// Combine all errors
$errors = [];
if (!empty($missing_fields)) {
    $errors[] = 'Missing required fields: ' . implode(', ', $missing_fields);
}
if (!empty($validation_errors)) {
    $errors = array_merge($errors, $validation_errors);
}

if (!empty($errors)) {
    sendJsonResponse(false, implode('; ', $errors));
}

// Set default values for optional fields
$data['barcode'] = $data['barcode'] ?? '';
$data['category_id'] = !empty($data['category_id']) ? (int)$data['category_id'] : null;
$data['description'] = $data['description'] ?? '';
$data['cost'] = isset($data['cost']) ? (float)$data['cost'] : 0;
$data['reorder_level'] = isset($data['reorder_level']) ? (int)$data['reorder_level'] : 5;
$data['status'] = 'active';

try {
    // Check database connection
    if (!isset($conn) || !$conn) {
        error_log('Database connection is not set');
        throw new Exception('Database connection failed');
    }

    try {
        // Test the connection
        $conn->query('SELECT 1');
    } catch (PDOException $e) {
        error_log('Database connection test failed: ' . $e->getMessage());
        throw new Exception('Database connection test failed: ' . $e->getMessage());
    }

    // Start transaction
    $conn->beginTransaction();

    // Prepare the SQL statement
    $sql = "INSERT INTO inventory (
        name, 
        barcode, 
        description, 
        category_id, 
        price, 
        cost, 
        stock, 
        reorder_level, 
        status,
        created_at,
        updated_at
    ) VALUES (
        :name, 
        :barcode, 
        :description, 
        :category_id, 
        :price, 
        :cost, 
        :stock, 
        :reorder_level, 
        'active',
        NOW(),
        NOW()
    ) ON DUPLICATE KEY UPDATE
        name = VALUES(name),
        description = VALUES(description),
        category_id = VALUES(category_id),
        price = VALUES(price),
        cost = VALUES(cost),
        stock = stock + VALUES(stock),
        reorder_level = VALUES(reorder_level),
        status = VALUES(status),
        updated_at = NOW()";

    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':barcode', $data['barcode']);
    $stmt->bindParam(':description', $data['description'] ?? '');
    $stmt->bindParam(':category_id', $data['category_id'], PDO::PARAM_INT);
    $stmt->bindParam(':price', $data['price']);
    $stmt->bindParam(':cost', $data['cost']);
    $stmt->bindParam(':stock', $data['stock'], PDO::PARAM_INT);
    $stmt->bindParam(':reorder_level', $data['reorder_level'], PDO::PARAM_INT);

    // Execute the statement
    $stmt->execute();
    $product_id = $conn->lastInsertId();

    // Commit the transaction
    $conn->commit();

    // Return success response
    sendJsonResponse(true, 'Product saved successfully', ['product_id' => $product_id]);

} catch (Exception $e) {
    // Rollback the transaction on error
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    
    // Log the error
    error_log('Error in save_product.php: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
    
    // Return error response
    sendJsonResponse(false, 'Error: ' . $e->getMessage());
}
