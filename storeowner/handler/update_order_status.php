<?php
include('../../configuration/config.php'); // Include your database configuration

header('Content-Type: application/json');

if (isset($_POST['action']) && $_POST['action'] === 'update_order_status') {
    $order_id = $_POST['order_id'] ?? null;
    $status = $_POST['status'] ?? null;

    // Validate inputs
    if ($order_id === null || $status === null) {
        echo json_encode(['status' => 'error', 'message' => 'Missing order ID or status.']);
        exit;
    }

    if (!in_array($status, ['pending', 'completed'])) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid status value.']);
        exit;
    }

    try {
        $stmt = $conn->prepare("UPDATE orders SET status = :status WHERE id = :order_id");
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Order status updated successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Order not found or status already set.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
}
exit;
?>