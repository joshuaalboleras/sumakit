<?php
include('../configuration/config.php');

// Handle AJAX requests for cart
if (isset($_POST['action'])) {
    header('Content-Type: application/json');

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    switch ($_POST['action']) {
        case 'add_to_cart':
            $product_id = $_POST['product_id'];
            $qty = intval($_POST['qty'] ?? 1);

            $stmt = $conn->prepare("SELECT p.name, p.price, i.stock FROM products p JOIN inventory i ON p.id = i.id WHERE p.id = :id");
            $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                $current_stock = intval($product['stock']);
                $requested_qty = $qty + ($_SESSION['cart'][$product_id]['qty'] ?? 0);

                if ($requested_qty > $current_stock) {
                    echo json_encode(['status' => 'error', 'message' => 'Not enough stock.']);
                    exit;
                }
                
                if (isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id]['qty'] += $qty;
                } else {
                    $_SESSION['cart'][$product_id] = [
                        'id' => $product_id, 'name' => $product['name'], 'price' => $product['price'], 'qty' => $qty
                    ];
                }
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Product not found.']);
            }
            break;

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
             }
             break;
        
        case 'complete_order':
            $customer_name = $_POST['customer_name'] ?? null;
            if (empty($customer_name)) {
                echo json_encode(['status' => 'error', 'message' => 'Customer name is required.']);
                exit;
            }

            if (empty($_SESSION['cart'])) {
                echo json_encode(['status' => 'error', 'message' => 'Cart is empty.']);
                exit;
            }
            try {
                $conn->beginTransaction();
                $total_amount = 0;
                $total_items = 0;
                foreach ($_SESSION['cart'] as $item) { 
                    $total_amount += $item['price'] * $item['qty'];
                    $total_items += $item['qty'];
                }

                $stmt = $conn->prepare("INSERT INTO orders (customer_name, total_items, total_amount, date_created) VALUES (:customer_name, :total_items, :total_amount, NOW())");
                $stmt->bindParam(':customer_name', $customer_name, PDO::PARAM_STR);
                $stmt->bindParam(':total_items', $total_items, PDO::PARAM_INT);
                $stmt->bindParam(':total_amount', $total_amount);
                $stmt->execute();
                $order_id = $conn->lastInsertId();

                $orderItemsStmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)");
                $inventoryStmt = $conn->prepare("UPDATE inventory SET stock = stock - :quantity WHERE id = :product_id");

                foreach ($_SESSION['cart'] as $item) {
                    $orderItemsStmt->execute([':order_id' => $order_id, ':product_id' => $item['id'], ':quantity' => $item['qty'], ':price' => $item['price']]);
                    $inventoryStmt->execute([':quantity' => $item['qty'], ':product_id' => $item['id']]);
                }

                $conn->commit();
                $_SESSION['cart'] = [];
                echo json_encode(['status' => 'success', 'order_id' => $order_id]);
            } catch (PDOException $e) {
                $conn->rollBack();
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
            break;
    }
    exit;
}
if (isset($_GET['action']) && $_GET['action'] == 'get_cart') {
    header('Content-Type: application/json');
    echo json_encode($_SESSION['cart'] ?? []);
    exit;
}

include('includes/header.php');

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Fetch all products to display
try {
    $stmt = $conn->prepare("
        SELECT p.id, p.name, p.description, p.price, i.stock 
        FROM products p
        LEFT JOIN inventory i ON p.id = i.id
        ORDER BY p.name ASC
    ");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Products</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Products</li>
    </ol>

    <div class="row">
        <!-- Products Grid -->
        <div class="col-lg-8">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <?php
                            $stock = (int)($product['stock'] ?? 0);
                            $isOutOfStock = $stock <= 0;
                            $stockStatus = $isOutOfStock ? 'Out of Stock' : "In Stock: {$stock}";
                            $stockClass = $isOutOfStock ? 'text-danger' : 'text-success';
                        ?>
                        <div class="col">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                    <p class="card-text small text-muted flex-grow-1"><?= htmlspecialchars($product['description'] ?? 'No description available.') ?></p>
                                    <p class="card-text mb-1"><strong>₱<?= number_format($product['price'], 2) ?></strong></p>
                                    <p class="card-text mb-2"><small class="<?= $stockClass ?> fw-bold"><?= $stockStatus ?></small></p>
                                    <div class="input-group mt-auto">
                                        <input type="number" class="form-control form-control-sm qty-input" value="1" min="1" max="<?= $stock ?>" <?= $isOutOfStock ? 'disabled' : '' ?>>
                                        <button class="btn btn-sm <?= $isOutOfStock ? 'btn-secondary' : 'btn-primary' ?> add-to-cart" 
                                                data-id="<?= $product['id'] ?>"
                                                <?= $isOutOfStock ? 'disabled' : '' ?>>
                                            <i class="bi bi-cart-plus"></i> Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No products found in the inventory.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Cart Summary -->
        <div class="col-lg-4">
            <div class="card position-sticky" style="top: 20px;">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-cart me-2"></i>Shopping Cart</h5>
                </div>
                <div class="card-body">
                    <div id="cartItems">
                        <!-- Cart items will be populated by JavaScript -->
                    </div>
                    <hr>
                    <div class="cart-summary">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotal">₱0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong id="total">₱0.00</strong>
                        </div>
                        <div class="mb-3">
                            <label for="customerName" class="form-label fw-bold">Customer Name</label>
                            <input type="text" class="form-control" id="customerName" placeholder="Enter customer name" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-success" id="completeOrder">Complete Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<style>
    .card-title {
        font-size: 1rem;
        font-weight: bold;
    }
    .add-to-cart {
        white-space: nowrap;
    }
</style>

<script>
$(document).ready(function() {
    // Add to cart
    $('.add-to-cart').click(function() {
        const productId = $(this).data('id');
        const qty = $(this).siblings('.qty-input').val();
        
        $.post('products.php', { action: 'add_to_cart', product_id: productId, qty: qty }, function(response) {
            if (response.status === 'success') {
                updateCartDisplay();
            } else {
                alert('Error: ' + response.message);
            }
        }, 'json');
    });

    // Update quantity in cart
    $(document).on('change', '.cart-item-qty', function() {
        const productId = $(this).data('id');
        const qty = $(this).val();
        
        $.post('products.php', { action: 'update_cart', product_id: productId, qty: qty }, function(response) {
            if (response.status === 'success') {
                updateCartDisplay();
            }
        }, 'json');
    });

    // Complete order
    $('#completeOrder').click(function() {
        const customerName = $('#customerName').val().trim();
        if (!customerName) {
            alert('Customer name is required.');
            return;
        }
        if (confirm('Are you sure you want to complete this order for ' + customerName + '?')) {
            $.post('products.php', { action: 'complete_order', customer_name: customerName }, function(response) {
                if (response.status === 'success') {
                    alert('Order #' + response.order_id + ' completed successfully!');
                    $('#customerName').val(''); // Clear name field
                    updateCartDisplay();
                } else {
                    alert('Error: ' + response.message);
                }
            }, 'json');
        }
    });

    // Function to update cart display
    function updateCartDisplay() {
        $.get('products.php?action=get_cart', function(cart) {
            let html = '';
            let subtotal = 0;
            
            if (Object.keys(cart).length > 0) {
                Object.values(cart).forEach(item => {
                    const itemTotal = item.price * item.qty;
                    subtotal += itemTotal;
                    html += `
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h6 class="mb-0 small">${item.name}</h6>
                                <small class="text-muted">₱${parseFloat(item.price).toFixed(2)}</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <input type="number" class="form-control form-control-sm cart-item-qty" 
                                       data-id="${item.id}" value="${item.qty}" min="0" style="width: 60px;">
                                <span class="ms-2 fw-bold" style="width: 70px; text-align: right;">₱${itemTotal.toFixed(2)}</span>
                            </div>
                        </div>`;
                });
            } else {
                html = '<p class="text-muted text-center">Your cart is empty.</p>';
            }
            
            $('#cartItems').html(html);
            $('#subtotal').text('₱' + subtotal.toFixed(2));
            $('#total').text('₱' + subtotal.toFixed(2));
        }, 'json');
    }
    
    // Initial cart load
    updateCartDisplay();
});
</script> 