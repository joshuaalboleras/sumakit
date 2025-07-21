<?php
require_once 'includes/config.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Get request parameters from DataTables
$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;
$searchValue = isset($_POST['search']['value']) ? trim($_POST['search']['value']) : '';

// Build the query
$query = "SELECT p.*, i.stock, i.price as inventory_price, 
          CASE 
              WHEN i.stock <= 0 THEN 'Out of Stock'
              WHEN i.stock <= 10 THEN 'Low Stock'
              ELSE 'In Stock'
          END as stock_status
          FROM products p 
          INNER JOIN inventory i ON p.id = i.id 
          WHERE p.store_id = 1";

// Add search condition if search value is provided
if (!empty($searchValue)) {
    $searchValue = "%$searchValue%";
    $query .= " AND (p.name LIKE :search OR p.description LIKE :search OR p.category LIKE :search OR p.sku LIKE :search OR p.barcode LIKE :search)";
}

// Add order by
$query .= " ORDER BY p.created_at DESC";

// Prepare and execute the query
$stmt = $conn->prepare($query);

// Bind search parameter if needed
if (!empty($searchValue)) {
    $stmt->bindParam(':search', $searchValue, PDO::PARAM_STR);
}

$stmt->execute();
$totalRecords = $stmt->rowCount();

// Add pagination
$query .= " LIMIT :start, :length";
$stmt = $conn->prepare($query);

// Bind parameters
if (!empty($searchValue)) {
    $stmt->bindParam(':search', $searchValue, PDO::PARAM_STR);
}
$stmt->bindParam(':start', $start, PDO::PARAM_INT);
$stmt->bindParam(':length', $length, PDO::PARAM_INT);

$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare the response
$response = [
    'draw' => $draw,
    'recordsTotal' => $totalRecords,
    'recordsFiltered' => $totalRecords, // For simplicity, we're using the same count
    'data' => $products
];

echo json_encode($response);
?>
