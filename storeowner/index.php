<?php
// require_once 'includes/config.php';
// $page_title = 'Dashboard';
?>

<?php include 'includes/header.php'; ?>

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

<!-- Summary Cards -->
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Today's Sales</h5>
                <h2 class="card-text">₱0.00</h2>
                <p class="card-text"><small>0 orders</small></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Weekly Sales</h5>
                <h2 class="card-text">₱0.00</h2>
                <p class="card-text"><small>0 orders</small></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Low Stock Items</h5>
                <h2 class="card-text">0</h2>
                <p class="card-text"><small>items to restock</small></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Total Products</h5>
                <h2 class="card-text">0</h2>
                <p class="card-text"><small>in inventory</small></p>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
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
                            <tr>
                                <td colspan="2" class="text-center text-muted py-4">No data available</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6>Recent Orders</h6>
        <a href="pos.php" class="btn btn-sm btn-primary">New Order</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="recentOrdersTable" class="table table-sm" style="width:100%">
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
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No orders found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Only initialize DataTable if there are actual data rows (not just the "No orders found" message)
    const tableBody = $('#recentOrdersTable tbody tr');
    const hasData = tableBody.length > 1 || !tableBody.first().find('td[colspan]').length;
    
    if (hasData) {
        // Initialize DataTable for recent orders only if there's actual data
        $('#recentOrdersTable').DataTable({
            responsive: true,
            pageLength: 5,
            lengthMenu: [5, 10, 25],
            order: [],
            language: {
                emptyTable: "No orders found",
                info: "Showing _START_ to _END_ of _TOTAL_ orders",
                infoEmpty: "Showing 0 to 0 of 0 orders",
                search: "Search orders:"
            },
            columnDefs: [
                { targets: [3, 4, 6], className: "text-end" },
                { targets: [5], className: "text-center" }
            ]
        });
    }
    
    // Sample chart data - Replace with actual data from your database
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'Sales',
                data: [0, 0, 0, 0, 0, 0, 0],
                borderColor: '#4361ee',
                backgroundColor: 'rgba(67, 97, 238, 0.1)',
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>
