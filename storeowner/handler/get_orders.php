<?php
// Include your database configuration
// Ensure this path is correct relative to get_orders.php (e.g., storeowner/handler/get_orders.php)
include('../../configuration/config.php');

// Set content type to JSON
header('Content-Type: application/json');

$draw = $_POST['draw'] ?? 1;
$start = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10; // Number of records to show per page
$searchValue = $_POST['search']['value'] ?? ''; // Global search value

// Order by column
$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
$orderDir = $_POST['order'][0]['dir'] ?? 'asc';

// Define columns for ordering and searching
// IMPORTANT: These must match the 'data' property in your DataTables column definitions in index.php
$columns = ['id', 'date_created', 'customer_name', 'total_items', 'total_amount', 'status', 'actions']; // Changed 'status_display_text_placeholder_for_now' to 'status'

// Determine the column to order by
// Use a default if the index is out of bounds or column name is not valid for ordering
$orderColumnName = $columns[$orderColumnIndex] ?? 'date_created';
// Basic sanitization for order direction
$orderDir = (strtolower($orderDir) == 'desc') ? 'DESC' : 'ASC';

$totalRecords = 0;
$recordsFiltered = 0;
$data = [];
$error = null;

try {
    // Check if database connection is available
    if (!isset($conn) || !$conn instanceof PDO) {
        throw new Exception("Database connection not established. Check configuration/config.php.");
    }

    // Prepare base query for total records
    $totalRecordsQuery = "SELECT COUNT(*) FROM orders";
    $stmt = $conn->prepare($totalRecordsQuery);
    $stmt->execute();
    $totalRecords = $stmt->fetchColumn();

    // Prepare query for filtered records
    $searchCondition = '';
    $params = [];

    if (!empty($searchValue)) {
        // Add more columns to search if needed, e.g., total_amount, date_created (if stored as string)
        $searchCondition = " WHERE o.id LIKE :search_id OR o.customer_name LIKE :search_customer ";
        $params[':search_id'] = '%' . $searchValue . '%';
        $params[':search_customer'] = '%' . $searchValue . '%';
    }

    $filteredRecordsQuery = "SELECT COUNT(*) FROM orders o" . $searchCondition;
    $stmt = $conn->prepare($filteredRecordsQuery);
    $stmt->execute($params);
    $recordsFiltered = $stmt->fetchColumn();

    // Prepare data query
    $dataQuery = "
        SELECT
            o.id,
            o.customer_name,
            o.total_items,
            o.total_amount,
            o.date_created,
            o.status -- Fetch the actual status column
        FROM
            orders o
        " . $searchCondition . "
        ORDER BY
            " . $orderColumnName . " " . $orderDir . "
        LIMIT :start, :length
    ";

    $stmt = $conn->prepare($dataQuery);
    // Bind parameters for search
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    // Bind parameters for pagination
    $stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
    $stmt->bindValue(':length', (int)$length, PDO::PARAM_INT);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($orders as $order) {
        // Determine status badge class based on the actual status value
        $status_class = '';
        $display_status = htmlspecialchars($order['status'] ?: 'N/A');
        if ($order['status'] == 'completed') {
            $status_class = 'bg-success';
        } else if ($order['status'] == 'pending') {
            $status_class = 'bg-warning text-dark';
        } else {
            $status_class = 'bg-secondary'; // For NULL or other unexpected values
        }

        $data[] = [
            'id' => $order['id'],
            'date_created' => date('Y-m-d H:i', strtotime($order['date_created'])),
            'customer_name' => htmlspecialchars($order['customer_name']),
            'total_items' => $order['total_items'],
            'total_amount' => '₱' . number_format($order['total_amount'], 2),
            'status' => '<span class="badge ' . $status_class . '">' . $display_status . '</span>', // Use the actual status
            'actions' => '
                <div class="dropdown">
                    <button class="btn btn-sm btn-info dropdown-toggle" type="button" id="dropdownMenuButton' . htmlspecialchars($order['id']) . '" data-bs-toggle="dropdown" aria-expanded="false">
                        Change Status
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . htmlspecialchars($order['id']) . '">
                        <li><a class="dropdown-item update-status-btn" href="#" data-order-id="' . htmlspecialchars($order['id']) . '" data-new-status="completed">Completed</a></li>
                        <li><a class="dropdown-item update-status-btn" href="#" data-order-id="' . htmlspecialchars($order['id']) . '" data-new-status="pending">Pending</a></li>
                    </ul>
                </div>
            '
        ];
    }

} catch (PDOException $e) {
    // Catch any database connection or query errors
    $error = "Database error: " . $e->getMessage();
    error_log("PDO Error in get_orders.php: " . $e->getMessage()); // Log error for server-side debugging
    // Clear data to ensure consistent response structure on error
    $data = [];
    $totalRecords = 0;
    $recordsFiltered = 0;
} catch (Exception $e) {
    // Catch any other unexpected errors
    $error = "An unexpected error occurred: " . $e->getMessage();
    error_log("General Error in get_orders.php: " . $e->getMessage()); // Log error
    $data = [];
    $totalRecords = 0;
    $recordsFiltered = 0;
}

// Prepare the JSON response
$response = [
    'draw' => (int)$draw,
    'recordsTotal' => (int)$totalRecords,
    'recordsFiltered' => (int)$recordsFiltered,
    'data' => $data,
    'error' => $error // Include error message in the response if one occurred
];

echo json_encode($response);

// It's a good practice to omit the closing PHP tag in files that output only data
// to prevent accidental whitespace or newlines from corrupting JSON output.