<?php
// --- AJAX HANDLER: must be at the very top! ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    include('../configuration/config.php');
    header('Content-Type: application/json');
    $response = ['status' => 'error', 'message' => 'An unknown error occurred.'];
    try {
        switch ($_POST['action']) {
            case 'get_product':
                $stmt = $conn->prepare("SELECT p.*, i.stock FROM products p LEFT JOIN inventory i ON p.id = i.id WHERE p.id = :id");
                $stmt->execute([':id' => $_POST['id']]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($product) {
                    $response = ['status' => 'success', 'product' => $product];
                } else {
                    $response['message'] = 'Product not found.';
                }
                break;
            case 'add_product':
                // Check if product with the same name already exists
                $stmt = $conn->prepare("SELECT id FROM products WHERE name = :name LIMIT 1");
                $stmt->execute([':name' => $_POST['name']]);
                $existingProduct = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($existingProduct) {
                    // Product exists, update its stock in inventory
                    $productId = $existingProduct['id'];
                    $stmt = $conn->prepare("UPDATE inventory SET stock = stock + :stock WHERE id = :id");
                    $stmt->execute([
                        ':stock' => $_POST['stock'],
                        ':id' => $productId
                    ]);
                    $response = ['status' => 'success', 'message' => 'Stock updated for existing product.'];
                } else {
                    // New product, insert into both tables
                    $conn->beginTransaction();
                    $stmt = $conn->prepare("INSERT INTO products (name, category, description, price, store_id) VALUES (:name, :category, :description, :price, 1)");
                    $stmt->execute([
                        ':name' => $_POST['name'],
                        ':category' => $_POST['category'],
                        ':description' => $_POST['description'],
                        ':price' => $_POST['price']
                    ]);
                    $productId = $conn->lastInsertId();
                    $stmt = $conn->prepare("INSERT INTO inventory (id, name, description, price, stock) VALUES (:id, :name, :description, :price, :stock)");
                    $stmt->execute([
                        ':id' => $productId,
                        ':name' => $_POST['name'],
                        ':description' => $_POST['description'],
                        ':price' => $_POST['price'],
                        ':stock' => $_POST['stock']
                    ]);
                    $conn->commit();
                    $response = ['status' => 'success'];
                }
                break;
            case 'edit_product':
                $conn->beginTransaction();
                $stmt = $conn->prepare("UPDATE products SET name = :name, category = :category, description = :description, price = :price WHERE id = :id");
                $stmt->execute([
                    ':id' => $_POST['id'],
                    ':name' => $_POST['name'],
                    ':category' => $_POST['category'],
                    ':description' => $_POST['description'],
                    ':price' => $_POST['price']
                ]);
                $stmt = $conn->prepare("UPDATE inventory SET name = :name, description = :description, price = :price, stock = :stock WHERE id = :id");
                $stmt->execute([
                    ':id' => $_POST['id'],
                    ':name' => $_POST['name'],
                    ':description' => $_POST['description'],
                    ':price' => $_POST['price'],
                    ':stock' => $_POST['stock']
                ]);
                $conn->commit();
                $response = ['status' => 'success'];
                break;
            case 'delete_product':
                $conn->beginTransaction();
                $stmt = $conn->prepare("DELETE FROM inventory WHERE id = :id");
                $stmt->execute([':id' => $_POST['id']]);
                $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
                $stmt->execute([':id' => $_POST['id']]);
                $conn->commit();
                $response = ['status' => 'success'];
                break;
        }
    } catch (PDOException $e) {
        $conn->rollBack();
        $response['message'] = 'Database Error: ' . $e->getMessage();
    }
    echo json_encode($response);
    exit;
}
// --- END AJAX HANDLER ---

include('../configuration/config.php');
include('includes/header.php');


// Fetch products from inventory to display
try {
    $stmt = $conn->prepare("
        SELECT 
            p.id, 
            p.name, 
            p.description,
            p.category, 
            p.price, 
            i.stock 
        FROM products p
        LEFT JOIN inventory i ON p.id = i.id
        ORDER BY p.id ASC
    ");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate inventory stats
    $total_products = count($products);
    $total_stock_quantity = 0;
    $low_stock_items = 0;
    $out_of_stock_items = 0;
    $low_stock_threshold = 10; // Define what is considered "low stock"

    foreach ($products as $product) {
        $stock = (int)$product['stock'];
        $total_stock_quantity += $stock;
        if ($stock <= $low_stock_threshold && $stock > 0) {
            $low_stock_items++;
        }
        if ($stock == 0) {
            $out_of_stock_items++;
        }
    }

} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Inventory Management</h1>
</div>

<!-- Stat Cards -->
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Total Products</h5>
                <h2 class="card-text"><?= $total_products ?></h2>
                <p class="card-text"><small>unique products</small></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Total Items in Stock</h5>
                <h2 class="card-text"><?= $total_stock_quantity ?></h2>
                <p class="card-text"><small>items on hand</small></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Low Stock Items</h5>
                <h2 class="card-text"><?= $low_stock_items ?></h2>
                <p class="card-text"><small>items to restock</small></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title">Out of Stock</h5>
                <h2 class="card-text"><?= $out_of_stock_items ?></h2>
                <p class="card-text"><small>items unavailable</small></p>
            </div>
        </div>
    </div>
</div>


<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table me-1"></i>
            Product Inventory
            <button class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="fas fa-plus me-1"></i> Add New Product
            </button>
    </div>
    <div class="card-body">
            <table id="productsTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php $rownum = 1; foreach ($products as $product): ?>
                            <tr>
                                <td><?= $rownum++ ?></td>
                                <td><?= htmlspecialchars($product['name']) ?></td>
                                <td><?= htmlspecialchars($product['category'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($product['description'] ?? '') ?></td>
                                <td>₱<?= number_format($product['price'], 2) ?></td>
                                <td><?= htmlspecialchars($product['stock']) ?></td>
                                <td>
                                    <button class="btn btn-info btn-sm edit-btn" data-id="<?= $product['id'] ?>">Edit</button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $product['id'] ?>">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No products found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addProductForm">
                    <input type="hidden" name="action" value="add_product">
                    <div class="mb-3">
                        <label for="addName" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="addName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="addCategory" class="form-label">Category</label>
                        <select class="form-select" id="addCategory" name="category">
                            <option value="Uncategorized">Uncategorized</option>
                            <option value="Food & Beverage">Food & Beverage</option>
                            <option value="Electronics">Electronics</option>
                            <option value="Health & Beauty">Health & Beauty</option>
                            <option value="Books & Media">Books & Media</option>
                            <option value="Antibacteria">Antibacteria</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="addDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="addDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="addPrice" class="form-label">Price</label>
                            <input type="number" class="form-control" id="addPrice" name="price" step="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="addStock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="addStock" name="stock" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Product</button>
                </form>
                </div>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="editProductForm">
                    <input type="hidden" name="action" value="edit_product">
                <input type="hidden" id="editProductId" name="id">
                    <div class="mb-3">
                        <label for="editName" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCategory" class="form-label">Category</label>
                        <select class="form-select" id="editCategory" name="category">
                            <option value="Uncategorized">Uncategorized</option>
                            <option value="Food & Beverage">Food & Beverage</option>
                            <option value="Electronics">Electronics</option>
                            <option value="Health & Beauty">Health & Beauty</option>
                            <option value="Books & Media">Books & Media</option>
                            <option value="Antibacteria">Antibacteria</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editPrice" class="form-label">Price</label>
                            <input type="number" class="form-control" id="editPrice" name="price" step="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editStock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="editStock" name="stock" required>
                </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Product</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this product? This action cannot be undone.
            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
        </div>
    </div>
</div>


<?php include('includes/footer.php'); ?>

<script>
$(document).ready(function() {
    var table = $('#productsTable').DataTable();
    var addProductModal = new bootstrap.Modal(document.getElementById('addProductModal'));
    var editProductModal = new bootstrap.Modal(document.getElementById('editProductModal'));
    var deleteConfirmModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    var productToDeleteId = null;

    // Handle Add Product form submission
    $('#addProductForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.post('inventory.php', formData, function(response) {
            if (response.status === 'success') {
                addProductModal.hide();
                location.reload(); 
            } else {
                alert('Error: ' + response.message);
            }
        }, 'json').fail(function(jqXHR, textStatus, errorThrown) {
            console.error("AJAX Error:", textStatus, errorThrown, jqXHR.responseText);
            alert('A critical error occurred. Please check the browser console (F12) for details.');
        });
    });

    // Handle Edit button click
    $('#productsTable').on('click', '.edit-btn', function() {
        var productId = $(this).data('id');
        $.post('inventory.php', { action: 'get_product', id: productId }, function(response) {
            if (response.status === 'success') {
                var product = response.product;
                $('#editProductId').val(product.id);
                $('#editName').val(product.name);
                $('#editCategory').val(product.category);
                $('#editDescription').val(product.description);
                $('#editPrice').val(product.price);
                $('#editStock').val(product.stock);
                editProductModal.show();
            } else {
                alert('Error: ' + response.message);
            }
        }, 'json');
    });

    // Handle Edit Product form submission
    $('#editProductForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.post('inventory.php', formData, function(response) {
            if (response.status === 'success') {
                editProductModal.hide();
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        }, 'json');
    });

    // Handle Delete button click
    $('#productsTable').on('click', '.delete-btn', function() {
        productToDeleteId = $(this).data('id');
        deleteConfirmModal.show();
    });

    // Handle final Delete confirmation
    $('#confirmDeleteBtn').click(function() {
        if (productToDeleteId) {
            $.post('inventory.php', { action: 'delete_product', id: productToDeleteId }, function(response) {
                if (response.status === 'success') {
                    deleteConfirmModal.hide();
                    location.reload();
        } else {
                    alert('Error: ' + response.message);
                }
            }, 'json');
        }
    });

});
</script>
