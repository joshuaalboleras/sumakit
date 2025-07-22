<?php
// This is your database configuration file. Adjust the path if necessary.
include('../configuration/config.php');

// Start a session if not already started (needed for potential future features like admin authentication)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include your header file which should contain DOCTYPE, html, head, and opening body tags,
// along with links to Bootstrap CSS, custom CSS, and potentially jQuery.
include('includes/header.php');

// Fetch all orders from the database, ordered by id in descending order
try {
    $stmt = $conn->prepare("SELECT id, customer_name, total_items, total_amount, date_created, status FROM orders ORDER BY id DESC");
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // In a real application, log this error and show a user-friendly message.
    die("Error fetching orders: " . $e->getMessage());
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Manage Orders</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Manage Orders</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Orders List
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="ordersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Total Items</th>
                            <th>Total Amount</th>
                            <th>Date Created</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Total Items</th>
                            <th>Total Amount</th>
                            <th>Date Created</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php if (!empty($orders)): ?>
                            <?php foreach ($orders as $order): ?>
                                <tr id="order-row-<?= htmlspecialchars($order['id']) ?>">
                                    <td><?= htmlspecialchars($order['id']) ?></td>
                                    <td><?= htmlspecialchars($order['customer_name'] ?: 'N/A') ?></td>
                                    <td><?= htmlspecialchars($order['total_items']) ?></td>
                                    <td>₱<?= number_format($order['total_amount'], 2) ?></td>
                                    <td><?= htmlspecialchars($order['date_created']) ?></td>
                                    <td class="order-status-cell">
                                        <?php
                                        $status_class = '';
                                        $display_status = htmlspecialchars($order['status'] ?: 'N/A');
                                        if ($order['status'] == 'completed') {
                                            $status_class = 'bg-success';
                                        } else if ($order['status'] == 'pending') {
                                            $status_class = 'bg-warning text-dark';
                                        } else {
                                            $status_class = 'bg-secondary'; // For NULL or other unexpected values
                                        }
                                        ?>
                                        <span class="badge <?= $status_class ?>"><?= $display_status ?></span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-info dropdown-toggle" type="button" id="dropdownMenuButton<?= htmlspecialchars($order['id']) ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                Change Status
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton<?= htmlspecialchars($order['id']) ?>">
                                                <li><a class="dropdown-item update-status-btn" href="#" data-order-id="<?= htmlspecialchars($order['id']) ?>" data-new-status="completed">Completed</a></li>
                                                <li><a class="dropdown-item update-status-btn" href="#" data-order-id="<?= htmlspecialchars($order['id']) ?>" data-new-status="pending">Pending</a></li>
                                                <?php /* Add more statuses here if your ENUM expands, e.g.,
                                                <li><a class="dropdown-item update-status-btn" href="#" data-order-id="<?= htmlspecialchars($order['id']) ?>" data-new-status="cancelled">Cancelled</a></li>
                                                */ ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No orders found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
// Include your footer file which should contain closing body and html tags,
// and links to Bootstrap JS, jQuery, and DataTables JS (if used).
include('includes/footer.php');
?>


<script>
    $(document).ready(function() {
        // Initialize DataTables for the ordersTable
        $('#ordersTable').DataTable({
            // Order by the 'Order ID' column (index 0) in descending order by default
            "order": [[ 0, "desc" ]],
            "responsive": true, // Enable responsive features
            "pageLength": 10,
            "lengthMenu": [10, 25, 50, 100],
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search...",
            }
        });

        // Custom AJAX script for handling status updates
        // This uses event delegation for robustness, ensuring it works even if table rows are dynamically added/removed
        $(document).on('click', '.update-status-btn', function(e) {
            e.preventDefault(); // Prevent the default action of the <a> tag (navigating to #)

            // Get the order ID and the new status from the data attributes of the clicked link
            const orderId = $(this).data('order-id');
            const newStatus = $(this).data('new-status');

            // Confirm with the user before proceeding
            // Using custom modals for confirmation/alerts is recommended instead of native alert/confirm
            // For now, retaining alert/confirm as per your existing code for consistency.
            if (confirm(`Are you sure you want to change order ${orderId} to '${newStatus}'?`)) {
                // Initiate the AJAX request
                $.ajax({
                    url: './handler/update_order_status.php', // The URL of your PHP backend script
                    type: 'POST', // Use POST method for sending data
                    dataType: 'json', // Expect a JSON response from the server
                    data: { // Data to be sent to the server
                        action: 'update_order_status', // A parameter to tell the PHP script what action to perform
                        order_id: orderId, // The ID of the order to update
                        status: newStatus // The new status for the order
                    },
                    success: function(response) {
                        // This function runs if the AJAX request is successful and the server responds
                        if (response.status === 'success') {
                            // Display success message to the user
                            alert(response.message);

                            // Update the UI immediately without a full page reload
                            // Find the specific table row for the updated order
                            const orderRow = $(`#order-row-${orderId}`);
                            // Find the badge element within that row's status cell
                            const statusBadge = orderRow.find('.order-status-cell .badge');

                            // Update the text of the badge
                            statusBadge.text(newStatus);

                            // Update the visual styling (background color) of the badge
                            statusBadge.removeClass('bg-success bg-warning text-dark bg-secondary'); // Remove all current status classes
                            if (newStatus === 'completed') {
                                statusBadge.addClass('bg-success'); // Add success class for 'completed'
                            } else if (newStatus === 'pending') {
                                statusBadge.addClass('bg-warning text-dark'); // Add warning class for 'pending'
                            }
                            // Add more conditions here if you introduce other statuses (e.g., 'cancelled', 'shipped')

                        } else {
                            // Display error message from the server
                            alert('Error updating status: ' + response.message);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // This function runs if the AJAX request itself fails (e.g., network error, server not found)
                        alert('AJAX request failed: ' + textStatus + ' - ' + errorThrown + '\nServer Response: ' + jqXHR.responseText);
                        console.error("AJAX Error Details:", textStatus, errorThrown, jqXHR.responseText); // Log full error for debugging
                    }
                });
            }
        });
    });
</script>
