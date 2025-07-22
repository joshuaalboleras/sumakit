<?php
/**
 * dashboard.php
 *
 * This file displays the main dashboard for the POS Management System.
 * It includes summary statistics, sales charts, top-selling products,
 * and a recent orders table powered by DataTables with server-side processing.
 */

// Include your database configuration file.
// Adjust the path if necessary. This path assumes config.php is in 'macaldos-kyuts/configuration/'
include('../configuration/config.php');

// Include your header file which should contain DOCTYPE, html, head, and opening body tags,
// along with links to Bootstrap CSS, custom CSS, and potentially jQuery.
// Ensure header.php does NOT include JavaScript libraries to avoid duplicates.
include('includes/header.php');

// --- PHP Logic for Dashboard Statistics ---

// Today's Sales
$today = date('Y-m-d');
try {
    $stmt = $conn->prepare("SELECT COUNT(*) as order_count, COALESCE(SUM(total_amount),0) as total_sales FROM orders WHERE DATE(date_created) = :today");
    $stmt->execute([':today' => $today]);
    $todayStats = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching today's sales: " . $e->getMessage());
    $todayStats = ['order_count' => 0, 'total_sales' => 0]; // Fallback
}


// Weekly Sales (Monday to Sunday of the current week)
$monday = date('Y-m-d', strtotime('monday this week'));
$sunday = date('Y-m-d', strtotime('sunday this week'));
try {
    $stmt = $conn->prepare("SELECT COUNT(*) as order_count, COALESCE(SUM(total_amount),0) as total_sales FROM orders WHERE DATE(date_created) BETWEEN :monday AND :sunday");
    $stmt->execute([':monday' => $monday, ':sunday' => $sunday]);
    $weekStats = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching weekly sales: " . $e->getMessage());
    $weekStats = ['order_count' => 0, 'total_sales' => 0]; // Fallback
}


// Low Stock Items (stock less than or equal to threshold, but greater than 0)
$low_stock_threshold = 10;
try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM inventory WHERE stock <= :threshold AND stock > 0");
    $stmt->execute([':threshold' => $low_stock_threshold]);
    $lowStockCount = $stmt->fetchColumn();
} catch (PDOException $e) {
    error_log("Error fetching low stock count: " . $e->getMessage());
    $lowStockCount = 0; // Fallback
}


// Total Products
try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM products");
    $stmt->execute();
    $totalProducts = $stmt->fetchColumn();
} catch (PDOException $e) {
    error_log("Error fetching total products: " . $e->getMessage());
    $totalProducts = 0; // Fallback
}


// Top Selling Products (limited to 5)
try {
    $stmt = $conn->prepare("SELECT p.name, SUM(oi.quantity) as qty_sold FROM order_items oi JOIN products p ON oi.product_id = p.id GROUP BY oi.product_id ORDER BY qty_sold DESC LIMIT 5");
    $stmt->execute();
    $topProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching top selling products: " . $e->getMessage());
    $topProducts = []; // Fallback
}

?>

<!-- Main content area for the dashboard -->
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                <i class="bi bi-calendar"></i> This week
            </button>
        </div>
    </div>

    <!-- Summary Cards Row -->
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Today's Sales</h5>
                    <h2 class="card-text">₱<?= number_format($todayStats['total_sales'], 2) ?></h2>
                    <p class="card-text"><small><?= $todayStats['order_count'] ?> orders</small></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Weekly Sales</h5>
                    <h2 class="card-text">₱<?= number_format($weekStats['total_sales'], 2) ?></h2>
                    <p class="card-text"><small><?= $weekStats['order_count'] ?> orders</small></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Low Stock Items</h5>
                    <h2 class="card-text"><?= $lowStockCount ?></h2>
                    <p class="card-text"><small>items to restock</small></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <h2 class="card-text"><?= $totalProducts ?></h2>
                    <p class="card-text"><small>in inventory</small></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row (Sales Overview and Top Selling Products) -->
    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6>Sales Overview</h6>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6>Top Selling Products</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-end">Qty Sold</th>
                                    </tr>
                            </thead>
                            <tbody>
                                <?php if (count($topProducts) > 0): ?>
                                    <?php foreach ($topProducts as $prod): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($prod['name']) ?></td>
                                            <td class="text-end"><?= $prod['qty_sold'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-4">No data available</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6>Recent Orders</h6>
            <a href="pos.php" class="btn btn-sm btn-primary">New Order</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="recentOrdersTable" class="table table-sm table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th class="text-end">Items</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- DataTables will populate this tbody via AJAX from handler/get_orders.php -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
// Include your footer file which should contain closing body and html tags,
// and links to Bootstrap JS, jQuery, and DataTables JS (if used).
// IMPORTANT: The custom JavaScript for this page will be placed AFTER this include.
include('includes/footer.php');
?>

<!-- Custom Scripts for Dashboard (placed AFTER footer.php to ensure all libraries are loaded) -->
<script>
$(document).ready(function() {
    // Check if DataTables has already been initialized on #recentOrdersTable
    // This is crucial because footer.php might also initialize it.
    if ($.fn.DataTable.isDataTable('#recentOrdersTable')) {
        // Destroy the existing DataTable instance to prevent reinitialization error
        $('#recentOrdersTable').DataTable().destroy();
        // Removed $('#recentOrdersTable').empty(); to preserve the <thead> and <tfoot>
    }

    // Now, initialize DataTables for Recent Orders table with server-side processing
    var recentOrdersDataTable = $('#recentOrdersTable').DataTable({
        "processing": true, // Show processing indicator
        "serverSide": true, // Enable server-side processing
        "ajax": {
            "url": "./handler/get_orders.php", // Your AJAX endpoint for fetching orders
            "type": "POST"
        },
        "columns": [
            { "data": "id" },
            { "data": "date_created" },
            { "data": "customer_name" },
            { "data": "total_items", "className": "text-end" },
            { "data": "total_amount", "className": "text-end" },
            { "data": "status", "className": "text-center", "orderable": false }, // Status column is not sortable
            { "data": "actions", "className": "text-end", "orderable": false } // Actions column is not sortable
        ],
        "order": [[ 1, "desc" ]], // Default sort by Date (column index 1) in descending order
        "responsive": true, // Enable responsive features
        "language": {
            "search": "_INPUT_",
            "searchPlaceholder": "Search orders...",
            "lengthMenu": "Show _MENU_ entries"
        }
    });

    // Custom AJAX script for handling status updates
    // This uses event delegation to ensure it works for dynamically loaded content
    $(document).on('click', '.update-status-btn', function(e) {
        e.preventDefault(); // Prevent the default action of the <a> tag (navigating to #)

        // Get the order ID and the new status from the data attributes of the clicked link
        const orderId = $(this).data('order-id');
        const newStatus = $(this).data('new-status');

        // Use a custom modal for confirmation instead of alert()/confirm()
        showConfirmationModal(`Are you sure you want to change order ${orderId} to '${newStatus}'?`, function() {
            // User confirmed, proceed with AJAX request
            $.ajax({
                url: './handler/update_order_status.php', // Corrected URL path
                type: 'POST',                   // Use POST method for sending data
                dataType: 'json',               // Expect a JSON response from the server
                data: {                         // Data to be sent to the server
                    action: 'update_order_status', // Action parameter for the PHP script
                    order_id: orderId,              // The ID of the order to update
                    status: newStatus               // The new status for the order
                },
                success: function(response) {
                    // This function runs if the AJAX request is successful and the server responds
                    if (response.status === 'success') {
                        // Display success message to the user using a custom modal
                        showMessageModal('Success', response.message);

                        // Update the UI immediately without a full page reload
                        // DataTables will automatically redraw the table to reflect changes from the server
                        recentOrdersDataTable.ajax.reload(null, false); // Reload data, but don't reset pagination
                    } else {
                        // Display error message from the server using a custom modal
                        showMessageModal('Error', 'Error updating status: ' + response.message);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // This function runs if the AJAX request itself fails (e.g., network error, server not found)
                    showMessageModal('AJAX Error', 'AJAX request failed: ' + textStatus + ' - ' + errorThrown + '\nServer Response: ' + jqXHR.responseText);
                    console.error("AJAX Error Details:", textStatus, errorThrown, jqXHR.responseText); // Log full error for debugging
                }
            });
        });
    });

    // --- Custom Modals for Messages and Confirmations (replaces alert/confirm) ---

    // Function to show a generic message modal
    function showMessageModal(title, message) {
        // Remove any existing message modals to prevent duplicates
        $('#messageModal').remove();
        let modalHtml = `
            <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="messageModalLabel">${title}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ${message}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>`;
        $('body').append(modalHtml); // Append modal to body
        let messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
        messageModal.show();
        // Remove modal from DOM after it's hidden to prevent duplicates
        document.getElementById('messageModal').addEventListener('hidden.bs.modal', function (event) {
            this.remove();
        });
    }

    // Function to show a confirmation modal
    function showConfirmationModal(message, callback) {
        // Remove any existing confirmation modals to prevent duplicates
        $('#confirmationModal').remove();
        let modalHtml = `
            <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmationModalLabel">Confirm Action</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ${message}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="confirmActionButton">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>`;
        $('body').append(modalHtml);
        let confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        confirmationModal.show();

        document.getElementById('confirmActionButton').onclick = function() {
            confirmationModal.hide();
            callback(); // Execute the callback function if confirmed
        };

        // Remove modal from DOM after it's hidden
        document.getElementById('confirmationModal').addEventListener('hidden.bs.modal', function (event) {
            this.remove();
        });
    }

    // --- Chart.js Initialization (Example Sales Chart) ---
    // You'll need to fetch actual sales data for this from your backend
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'], // Example labels
            datasets: [{
                label: 'Sales (PHP)',
                data: [12000, 19000, 30000, 50000, 23000, 35000, 40000], // Example data
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
