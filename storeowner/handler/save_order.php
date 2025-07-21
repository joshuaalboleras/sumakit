<?php
require_once '../includes/config.php';
header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['items']) || !is_array($data['items'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid order data']);
    exit;
}

try {
    $conn->beginTransaction();
    // Insert into sales
    $stmt = $conn->prepare("INSERT INTO sales (store_id, invoice_number, subtotal, tax_amount, discount_amount, total_amount, payment_method, payment_status, status, created_at, updated_at) VALUES (1, :invoice, :subtotal, :tax, :discount, :total, :method, 'paid', 'completed', NOW(), NOW())");
    $stmt->execute([
        ':invoice' => $data['invoice_number'] ?? uniqid('INV'),
        ':subtotal' => $data['subtotal'],
        ':tax' => $data['tax'],
        ':discount' => $data['discount'],
        ':total' => $data['total'],
        ':method' => $data['payment_method'] ?? 'cash',
    ]);
    $sale_id = $conn->lastInsertId();

    // Insert sale items and update inventory
    $itemStmt = $conn->prepare("INSERT INTO sale_items (sale_id, product_id, quantity, unit_price, discount, tax, total, created_at) VALUES (:sale_id, :product_id, :qty, :unit_price, :discount, :tax, :total, NOW())");
    $invStmt = $conn->prepare("UPDATE inventory SET stock = stock - :qty WHERE id = :product_id");
    foreach ($data['items'] as $item) {
        $itemStmt->execute([
            ':sale_id' => $sale_id,
            ':product_id' => $item['id'],
            ':qty' => $item['quantity'],
            ':unit_price' => $item['price'],
            ':discount' => $item['discount'] ?? 0,
            ':tax' => $item['tax'] ?? 0,
            ':total' => $item['total'],
        ]);
        $invStmt->execute([
            ':qty' => $item['quantity'],
            ':product_id' => $item['id'],
        ]);
    }
    $conn->commit();
    echo json_encode(['success' => true, 'sale_id' => $sale_id]);
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 