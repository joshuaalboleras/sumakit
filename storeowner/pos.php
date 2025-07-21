<?php

include('../configuration/config.php');
include('includes/header.php');

try {
    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Initialize held orders if not exists
    if (!isset($_SESSION['held_orders'])) {
        $_SESSION['held_orders'] = [];
    }

    // Handle AJAX requests
    if (isset($_POST['action'])) {
        header('Content-Type: application/json');
        
        switch ($_POST['action']) {
            case 'search_products':
                $search = $_POST['search'] ?? '';
                $searchTerm = "%$search%";
                $stmt = $conn->prepare("SELECT * FROM product_list WHERE name LIKE :search OR description LIKE :search2");
                $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
                $stmt->bindParam(':search2', $searchTerm, PDO::PARAM_STR);
                $stmt->execute();
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($products);
                exit;
            
            case 'add_to_cart':
                $product_id = $_POST['product_id'];
                $qty = $_POST['qty'] ?? 1;
                
                // Get product details
                $stmt = $conn->prepare("SELECT * FROM product_list WHERE id = :id");
                $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
                $stmt->execute();
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($product) {
                    if (isset($_SESSION['cart'][$product_id])) {
                        $_SESSION['cart'][$product_id]['qty'] += $qty;
                    } else {
                        $_SESSION['cart'][$product_id] = [
                            'id' => $product_id,
                            'name' => $product['name'],
                            'price' => $product['price'],
                            'qty' => $qty
                        ];
                    }
                    echo json_encode(['status' => 'success']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Product not found']);
                }
                exit;
            
        case 'update_cart':
            $product_id = $_POST['product_id'];
            $qty = (int)$_POST['qty'];
            
            if (isset($_SESSION['cart'][$product_id])) {
                if ($qty <= 0) {
                    unset($_SESSION['cart'][$product_id]);
                } else {
                    $_SESSION['cart'][$product_id]['qty'] = $qty;
                }
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Product not in cart']);
            }
            exit;
            
        case 'hold_order':
            if (!empty($_SESSION['cart'])) {
                $order_id = 'HOLD-' . time();
                $_SESSION['held_orders'][$order_id] = [
                    'items' => $_SESSION['cart'],
                    'date' => date('Y-m-d H:i:s')
                ];
                $_SESSION['cart'] = [];
                echo json_encode(['status' => 'success', 'order_id' => $order_id]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Cart is empty']);
            }
            exit;
            
        case 'retrieve_order':
            $order_id = $_POST['order_id'];
            if (isset($_SESSION['held_orders'][$order_id])) {
                $_SESSION['cart'] = $_SESSION['held_orders'][$order_id]['items'];
                unset($_SESSION['held_orders'][$order_id]);
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Order not found']);
            }
            exit;
            
            case 'clear_cart':
                error_log('Clearing cart');
                $_SESSION['cart'] = [];
                echo json_encode(['status' => 'success']);
                exit;
                
            case 'complete_order':
                error_log('Complete order request received');
                if (empty($_SESSION['cart'])) {
                    error_log('Cart is empty');
                    echo json_encode(['status' => 'error', 'message' => 'Cart is empty']);
                    exit;
                }
                
                try {
                    error_log('Starting transaction');
                    $conn->beginTransaction();
                    
                    // Calculate total
                    $total = 0;
                    foreach ($_SESSION['cart'] as $item) {
                        $total += $item['price'] * $item['qty'];
                    }
                    error_log('Calculated total: ' . $total);
                    
                    // Create order
                    $sql = "SHOW TABLES LIKE 'orders'";
                    $tableExists = $conn->query($sql)->rowCount() > 0;
                    error_log('Orders table exists: ' . ($tableExists ? 'Yes' : 'No'));
                    
                    $stmt = $conn->prepare("INSERT INTO orders (total_amount, date_created) VALUES (:total, NOW())");
                    $stmt->bindParam(':total', $total, PDO::PARAM_STR);
                    $stmt->execute();
                    $order_id = $conn->lastInsertId();
                    error_log('Order created with ID: ' . $order_id);
                    
                    // Check if order_items table exists
                    $sql = "SHOW TABLES LIKE 'order_items'";
                    $tableExists = $conn->query($sql)->rowCount() > 0;
                    error_log('Order_items table exists: ' . ($tableExists ? 'Yes' : 'No'));
                    
                    // Add order items
                    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)");
                    
                    foreach ($_SESSION['cart'] as $item) {
                        error_log('Adding item to order: ' . print_r($item, true));
                        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                        $stmt->bindParam(':product_id', $item['id'], PDO::PARAM_INT);
                        $stmt->bindParam(':quantity', $item['qty'], PDO::PARAM_INT);
                        $stmt->bindParam(':price', $item['price'], PDO::PARAM_STR);
                        $stmt->execute();
                    }
                    
                    $conn->commit();
                    error_log('Order completed successfully');
                    $_SESSION['cart'] = [];
                    echo json_encode(['status' => 'success', 'order_id' => $order_id]);
                } catch (PDOException $e) {
                    $conn->rollBack();
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                }
                exit;
        }
    }
} catch (PDOException $e) {
    if (isset($_POST['action'])) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        exit;
    } else {
        // For non-AJAX requests, you might want to handle the error differently
        die('Database error: ' . $e->getMessage());
    }
}
?>
<!-- Sidebar -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="pos.php">
                            <i class="bi bi-cash-stack me-2"></i> POS
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orders.php">
                            <i class="bi bi-list-check me-2"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">
                            <i class="bi bi-box-seam me-2"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reports.php">
                            <i class="bi bi-graph-up me-2"></i> Reports
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Point of Sale</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="newOrderBtn">
                            <i class="bi bi-plus-circle me-1"></i> New Order
                        </button>
                    </div>
                </div>
            </div>
    <div class="container-fluid pos-container">
        <div class="row">
            <!-- Product Search and Selection -->
            <div class="col-md-8">
                <div class="product-search">
                    <h2>Point of Sale</h2>
                    <div class="input-group mb-3">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search products...">
                        <button class="btn btn-primary" type="button" id="searchBtn">Search</button>
                    </div>
                    <div id="searchResults" class="row row-cols-1 row-cols-md-3 g-4">
                        <!-- Search results will be populated here -->
                    </div>
                </div>
            </div>
            
            <!-- Cart and Order Summary -->
            <div class="col-md-4">
                <div class="cart-container">
                    <h4>Order Summary</h4>
                    <div class="cart-items" id="cartItems">
                        <!-- Cart items will be populated here -->
                        <p class="text-muted">Your cart is empty</p>
                    </div>
                    
                    <div class="cart-summary">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotal">₱0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong id="total">₱0.00</strong>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" id="holdOrder">Hold Order</button>
                            <button class="btn btn-success" id="completeOrder">Complete Order</button>
                        </div>
                    </div>
                </div>
                
                <!-- Held Orders -->
                <div class="held-orders mt-4">
                    <h5>Held Orders</h5>
                    <div id="heldOrdersList">
                        <?php if (!empty($_SESSION['held_orders'])): ?>
                            <?php foreach ($_SESSION['held_orders'] as $order_id => $order): ?>
                                <div class="card mb-2">
                                    <div class="card-body p-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0"><?= $order_id ?></h6>
                                                <small class="text-muted"><?= count($order['items']) ?> items</small>
                                            </div>
                                            <button class="btn btn-sm btn-outline-primary retrieve-order" data-order-id="<?= $order_id ?>">Retrieve</button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">No held orders</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- Initialize tooltips -->
    <script>
        $(document).ready(function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Toggle sidebar if element exists
            const sidebarToggle = document.getElementById('sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    document.body.classList.toggle('sb-sidenav-toggled');
                    localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
                });
            }
            
            // Initialize modals and toasts if elements exist
            const newOrderModalEl = document.getElementById('newOrderModal');
            const successToastEl = document.getElementById('successToast');
            
            if (newOrderModalEl) {
                const newOrderModal = new bootstrap.Modal(newOrderModalEl);
                
                // New order button - show modal
                const newOrderBtn = document.getElementById('newOrderBtn');
                if (newOrderBtn) {
                    newOrderBtn.addEventListener('click', function() {
                        newOrderModal.show();
                    });
                }

                // Confirm new order
                const confirmNewOrderBtn = document.getElementById('confirmNewOrder');
                if (confirmNewOrderBtn) {
                    confirmNewOrderBtn.addEventListener('click', function() {
                        const printReceipt = document.getElementById('printReceipt')?.checked || false;
                        
                        // Show loading state
                        const confirmBtn = this;
                        const originalText = confirmBtn.innerHTML;
                        confirmBtn.disabled = true;
                        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

                        // Clear the cart
                        $.post('pos.php', { 
                            action: 'clear_cart',
                            print_receipt: printReceipt ? 1 : 0
                        }, function(response) {
                            if (response.status === 'success') {
                                // Clear the cart display
                                $('#cartItems').html('<p class="text-muted">Your cart is empty</p>');
                                $('#subtotal').text('₱0.00');
                                $('#total').text('₱0.00');
                                
                                // Hide modal and show success message
                                newOrderModal.hide();
                                
                                // Show success toast if available
                                if (successToastEl) {
                                    const successToast = new bootstrap.Toast(successToastEl);
                                    successToast.show();
                                }
                                
                                // Print receipt if needed
                                if (printReceipt && response.receipt_url) {
                                    window.open(response.receipt_url, '_blank');
                                }
                            }
                            
                            // Always reset button state
                            confirmBtn.disabled = false;
                            confirmBtn.innerHTML = originalText;
                            
                        }, 'json').fail(function() {
                            // Handle AJAX failure
                            if (confirmBtn) {
                                confirmBtn.disabled = false;
                                confirmBtn.innerHTML = originalText;
                            }
                            alert('Failed to start new order. Please try again.');
                        });
                    });
                    
                    // Reset modal when hidden
                    newOrderModalEl.addEventListener('hidden.bs.modal', function () {
                        const printReceipt = document.getElementById('printReceipt');
                        if (printReceipt) printReceipt.checked = true;
                        
                        if (confirmNewOrderBtn) {
                            confirmNewOrderBtn.disabled = false;
                            confirmNewOrderBtn.innerHTML = 'Start New Order';
                        }
                    });
                }
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            // Search products
            $('#searchBtn').click(searchProducts);
            $('#searchInput').keypress(function(e) {
                if (e.which === 13) searchProducts();
            });
            
            // Handle add to cart
            $(document).on('click', '.add-to-cart', function() {
                const productId = $(this).data('id');
                const qty = $(this).siblings('.qty-input').val() || 1;
                
                $.post('pos.php', {
                    action: 'add_to_cart',
                    product_id: productId,
                    qty: qty
                }, function(response) {
                    if (response.status === 'success') {
                        updateCart();
                    }
                }, 'json');
            });
            
            // Handle quantity update
            $(document).on('change', '.item-qty', function() {
                const productId = $(this).data('id');
                const qty = $(this).val();
                
                $.post('pos.php', {
                    action: 'update_cart',
                    product_id: productId,
                    qty: qty
                }, function(response) {
                    if (response.status === 'success') {
                        updateCart();
                    }
                }, 'json');
            });
            
            // Handle hold order
            $('#holdOrder').click(function() {
                $.post('pos.php', { action: 'hold_order' }, function(response) {
                    if (response.status === 'success') {
                        updateCart();
                        location.reload(); // Refresh to show the held order
                    } else {
                        alert(response.message || 'Failed to hold order');
                    }
                }, 'json');
            });
            
            // Handle retrieve order
            $(document).on('click', '.retrieve-order', function() {
                const orderId = $(this).data('order-id');
                
                if (confirm('Retrieve this order? This will replace your current cart.')) {
                    $.post('pos.php', {
                        action: 'retrieve_order',
                        order_id: orderId
                    }, function(response) {
                        if (response.status === 'success') {
                            updateCart();
                            location.reload();
                        } else {
                            alert(response.message || 'Failed to retrieve order');
                        }
                    }, 'json');
                }
            });
            
            // Handle complete order
            $('#completeOrder').click(function() {
                if (Object.keys(<?= json_encode($_SESSION['cart'] ?? []) ?>).length === 0) {
                    alert('Your cart is empty');
                    return;
                }
                
                if (confirm('Complete this order?')) {
                    $.post('pos.php', { action: 'complete_order' }, function(response) {
                        if (response.status === 'success') {
                            alert('Order #' + response.order_id + ' completed successfully!');
                            updateCart();
                        } else {
                            alert(response.message || 'Failed to complete order');
                        }
                    }, 'json');
                }
            });
            
            // Function to search products
            function searchProducts() {
                const searchTerm = $('#searchInput').val();
                
                if (searchTerm.length < 2) {
                    alert('Please enter at least 2 characters');
                    return;
                }
                
                $.post('pos.php', {
                    action: 'search_products',
                    search: searchTerm
                }, function(products) {
                    let html = '';
                    
                    if (products.length > 0) {
                        products.forEach(product => {
                            html += `
                                <div class="col">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">${product.name}</h5>
                                            <p class="card-text text-muted">${product.description || 'No description'}</p>
                                            <p class="card-text"><strong>₱${parseFloat(product.price).toFixed(2)}</strong></p>
                                            <div class="input-group mb-2">
                                                <input type="number" class="form-control qty-input" value="1" min="1">
                                                <button class="btn btn-primary add-to-cart" data-id="${product.id}">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        html = '<p>No products found</p>';
                    }
                    
                    $('#searchResults').html(html);
                }, 'json');
            }
            
            // Function to update cart display
            function updateCart() {
                $.get('pos.php', { action: 'get_cart' }, function(cart) {
                    let html = '';
                    let subtotal = 0;
                    
                    if (Object.keys(cart).length > 0) {
                        Object.values(cart).forEach(item => {
                            const itemTotal = item.price * item.qty;
                            subtotal += itemTotal;
                            
                            html += `
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <h6 class="mb-0">${item.name}</h6>
                                        <small class="text-muted">₱${parseFloat(item.price).toFixed(2)} × ${item.qty}</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <input type="number" class="form-control form-control-sm item-qty" 
                                               data-id="${item.id}" value="${item.qty}" min="1" style="width: 70px;">
                                        <span class="ms-2">₱${itemTotal.toFixed(2)}</span>
                                    </div>
                                </div>
                                <hr class="my-2">
                            `;
                        });
                        
                        $('#cartItems').html(html);
                    } else {
                        $('#cartItems').html('<p class="text-muted">Your cart is empty</p>');
                    }
                    
                    $('#subtotal').text('₱' + subtotal.toFixed(2));
                    $('#total').text('₱' + subtotal.toFixed(2));
                }, 'json');
            }
            
            // Initialize cart on page load
            updateCart();
        });
    </script>
            <!-- New Order Modal -->
            <div class="modal fade" id="newOrderModal" tabindex="-1" aria-labelledby="newOrderModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="newOrderModalLabel">Start New Order</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to start a new order? This will clear the current cart.</p>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="printReceipt" checked>
                                <label class="form-check-label" for="printReceipt">
                                    Print receipt for previous order
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="confirmNewOrder">Start New Order</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Toast -->
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="bi bi-check-circle me-2"></i> New order started successfully!
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Include footer -->
<?php include('includes/footer.php'); ?>

<style>
    /* Modal styling */
    #newOrderModal .modal-content {
        border: none;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    }
    #newOrderModal .modal-header {
        border-radius: 10px 10px 0 0;
    }
    #newOrderModal .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
    /* Toast styling */
    #successToast {
        background-color: #198754 !important;
    }
</style>
