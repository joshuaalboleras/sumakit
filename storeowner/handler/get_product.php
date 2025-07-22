<?php
// This script fetches product data for the DataTables on the inventory page.
// It uses an output buffer to prevent stray characters from breaking the JSON response.
ob_start();

$response = [
    'draw' => isset($_POST['draw']) ? intval($_POST['draw']) : 1,
    'recordsTotal' => 0,
    'recordsFiltered' => 0,
    'data' => [],
    'error' => null
];

try {
    // Locate the configuration file using an absolute path to prevent errors.
    require_once __DIR__ . '/../configuration/config.php';

    // This query safely joins products with inventory.
    // Using a LEFT JOIN ensures that products will appear even if they are missing an inventory entry.
    $query = "
        SELECT 
            p.id, 
            p.name, 
            p.description, 
            p.category, 
            p.price, 
            IFNULL(i.stock, 0) as stock, -- Show 0 if no inventory record exists
            CASE 
                WHEN IFNULL(i.stock, 0) <= 0 THEN 'Out of Stock' 
                WHEN i.stock > 0 AND i.stock <= 10 THEN 'Low Stock' 
                ELSE 'In Stock' 
            END as stock_status
        FROM products p
        LEFT JOIN inventory i ON p.id = i.id
        WHERE p.store_id = 1
        ORDER BY p.id DESC
    ";
    
    $stmt = $conn->query($query);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Populate the response for DataTables.
    $response['recordsTotal'] = count($products);
    $response['recordsFiltered'] = count($products);
    $response['data'] = $products;

} catch (Exception $e) {
    // If any error occurs, capture the message for easier debugging.
    $response['error'] = $e->getMessage();
}

// Clean the buffer and send the final, pure JSON response.
ob_end_clean();
header('Content-Type: application/json');
echo json_encode($response);
