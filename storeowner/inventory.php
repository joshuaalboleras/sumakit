<?php
include '../configuration/config.php';
include '../configuration/routes.php';

// Handle AJAX requests for CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    switch ($_POST['action']) {
        case 'get_product':
            try {
                $stmt = $conn->prepare("SELECT p.*, i.stock, 10 as low_stock_threshold 
                                      FROM products p 
                                      LEFT JOIN inventory i ON p.id = i.id 
                                      WHERE p.id = :id");
                $stmt->execute([':id' => $_POST['id']]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($product) {
                    $product['stock_status'] = $product['stock'] <= 0 ? 'Out of Stock' : 
                                            ($product['stock'] <= 10 ? 'Low Stock' : 'In Stock');
                    echo json_encode(['success' => true, 'product' => $product]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Product not found']);
                }
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit();
            
        case 'update_product':
            try {
                $conn->beginTransaction();
                // Update products table
                $stmt = $conn->prepare("UPDATE products SET 
                                      name = :name, 
                                      description = :description, 
                                      price = :price, 
                                      category = :category, 
                                      updated_at = NOW() 
                                      WHERE id = :id");
                $stmt->execute([
                    ':id' => $_POST['id'],
                    ':name' => trim($_POST['name']),
                    ':description' => trim($_POST['description']),
                    ':price' => floatval($_POST['price']),
                    ':category' => trim($_POST['category'])
                ]);
                // Update inventory table
                $inventoryStmt = $conn->prepare("UPDATE inventory SET 
                                               name = :name,
                                               description = :description,
                                               price = :price,
                                               stock = :stock,
                                               updated_at = NOW() 
                                               WHERE id = :id");
                $inventoryStmt->execute([
                    ':id' => $_POST['id'],
                    ':name' => trim($_POST['name']),
                    ':description' => trim($_POST['description']),
                    ':price' => floatval($_POST['price']),
                    ':stock' => intval($_POST['stock'])
                ]);
                $conn->commit();
                echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
            } catch (PDOException $e) {
                $conn->rollBack();
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit();
            
        case 'delete_product':
            try {
                $conn->beginTransaction();
                $stmt = $conn->prepare("DELETE FROM inventory_transactions WHERE product_id = :id");
                $stmt->execute([':id' => $_POST['id']]);
                $stmt = $conn->prepare("DELETE FROM inventory WHERE id = :id");
                $stmt->execute([':id' => $_POST['id']]);
                $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
                $stmt->execute([':id' => $_POST['id']]);
                $conn->commit();
                echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
            } catch (PDOException $e) {
                $conn->rollBack();
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit();
    }
}

// Handle form submission for adding new products
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $name = trim($_POST['name'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    if (!empty($name) && $price > 0) {
        try {
            $conn->beginTransaction();
            // Insert into inventory first
            $inventoryStmt = $conn->prepare("INSERT INTO inventory (name, description, price, stock, created_at, updated_at) 
                                           VALUES (:name, :description, :price, :stock, NOW(), NOW())");
            $inventoryStmt->execute([
                ':name' => $name,
                ':description' => $description,
                ':price' => $price,
                ':stock' => $stock
            ]);
            $inventory_id = $conn->lastInsertId();
            // Insert into products with the same ID
            $stmt = $conn->prepare("INSERT INTO products (id, store_id, name, description, price, category, unit, created_at, updated_at) 
                                  VALUES (:id, 1, :name, :description, :price, :category, 'pcs', NOW(), NOW())");
            $stmt->execute([
                ':id' => $inventory_id,
                ':name' => $name,
                ':description' => $description,
                ':price' => $price,
                ':category' => $category
            ]);
            $conn->commit();
            $_SESSION['success_message'] = 'Product "' . htmlspecialchars($name) . '" has been saved successfully!';
            header('Location: inventory.php');
            exit();
        } catch (PDOException $e) {
            if (isset($conn)) {
                $conn->rollBack();
            }
            $error_message = "Error saving product: " . $e->getMessage();
        }
    } else {
        $error_message = "Product name and valid price are required";
    }
}

// Fetch products for display
try {
    $stmt = $conn->prepare("SELECT p.*, i.stock, 10 as low_stock_threshold,
                           CASE 
                               WHEN i.stock <= 0 THEN 'Out of Stock'
                               WHEN i.stock <= 10 THEN 'Low Stock'
                               ELSE 'In Stock'
                           END as stock_status
                           FROM products p 
                           INNER JOIN inventory i ON p.id = i.id 
                           WHERE p.store_id = 1
                           ORDER BY p.created_at DESC");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get inventory summary
    $summaryStmt = $conn->prepare("SELECT 
                                  COUNT(*) as total_products,
                                  SUM(CASE WHEN i.stock > 10 THEN 1 ELSE 0 END) as in_stock,
                                  SUM(CASE WHEN i.stock <= 10 AND i.stock > 0 THEN 1 ELSE 0 END) as low_stock,
                                  SUM(CASE WHEN i.stock <= 0 OR i.stock IS NULL THEN 1 ELSE 0 END) as out_of_stock
                                  FROM products p 
                                  INNER JOIN inventory i ON p.id = i.id 
                                  WHERE p.store_id = 1");
    $summaryStmt->execute();
    $summary = $summaryStmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $products = [];
    $summary = ['total_products' => 0, 'in_stock' => 0, 'low_stock' => 0, 'out_of_stock' => 0];
    $error_message = "Error loading products: " . $e->getMessage();
}
?>

<?php include 'includes/header.php'; ?>

<!-- Display success message if set in session -->
<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php 
        echo $_SESSION['success_message'];
        unset($_SESSION['success_message']); // Clear the message after displaying
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Inventory Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="bi bi-plus-circle"></i> Add Product
        </button>
    </div>
</div>

<!-- Inventory Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="text-muted mb-1">Total Products</h5>
                        <h3 class="mb-0"><?php echo $summary['total_products']; ?></h3>
                    </div>
                    <i class="bi bi-box-seam" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="text-muted mb-1">In Stock</h5>
                        <h3 class="mb-0"><?php echo $summary['in_stock']; ?></h3>
                    </div>
                    <i class="bi bi-check-circle" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-dark">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="text-muted mb-1">Low Stock</h5>
                        <h3 class="mb-0"><?php echo $summary['low_stock']; ?></h3>
                    </div>
                    <i class="bi bi-exclamation-triangle" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="text-muted mb-1">Out of Stock</h5>
                        <h3 class="mb-0"><?php echo $summary['out_of_stock']; ?></h3>
                    </div>
                    <i class="bi bi-x-circle" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inventory Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Product Inventory</h6>
        <div class="input-group input-group-sm" style="width: 250px;">
            <input type="text" id="customSearch" class="form-control" placeholder="Search products...">
            <button class="btn btn-outline-secondary" type="button" id="searchButton">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="productsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th class="text-end">Price</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-box-seam" style="font-size: 2rem;"></i>
                                <p class="mt-2">No products found</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $index => $product): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td>
                                    <div>
                                        <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                        <?php if (!empty($product['description'])): ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($product['description']); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($product['category'] ?? 'Uncategorized'); ?></td>
                                <td class="text-end">₱<?php echo number_format($product['price'], 2); ?></td>
                                <td class="text-center"><?php echo $product['stock'] ?? 0; ?></td>
                                <td class="text-center">
                                    <?php 
                                    $status = $product['stock_status'] ?? 'Unknown';
                                    $badgeClass = 'secondary';
                                    switch ($status) {
                                        case 'In Stock':
                                            $badgeClass = 'success';
                                            break;
                                        case 'Low Stock':
                                            $badgeClass = 'warning';
                                            break;
                                        case 'Out of Stock':
                                            $badgeClass = 'danger';
                                            break;
                                    }
                                    ?>
                                    <span class="badge bg-<?php echo $badgeClass; ?>"><?php echo $status; ?></span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary" 
                                                onclick="editProduct(<?php echo $product['id']; ?>)" 
                                                title="Edit Product">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="deleteProduct(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?>')" 
                                                title="Delete Product">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addProductForm" method="POST" class="needs-validation" novalidate>
                <div class="modal-body p-4">
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-check-circle me-2"></i>Product added successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error_message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="row g-3">
                        <!-- Product Name -->
                        <div class="col-12">
                            <label for="productName" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                <input type="text" class="form-control form-control-lg" id="productName" name="name" placeholder="Enter product name" required>
                            </div>
                            <div class="invalid-feedback">Please provide a product name.</div>
                        </div>
                        
                        <!-- Category -->
                        <div class="col-md-6">
                            <label for="productCategory" class="form-label">Category</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-grid"></i></span>
                                <select class="form-select form-select-lg" id="productCategory" name="category">
                                    <option value="">Select Category</option>
                                    <option value="Electronics">Electronics</option>
                                    <option value="Clothing">Clothing</option>
                                    <option value="Food & Beverage">Food & Beverage</option>
                                    <option value="Health & Beauty">Health & Beauty</option>
                                    <option value="Home & Garden">Home & Garden</option>
                                    <option value="Sports & Outdoors">Sports & Outdoors</option>
                                    <option value="Books & Media">Books & Media</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Price -->
                        <div class="col-md-6">
                            <label for="productPrice" class="form-label">Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-currency-peso"></i></span>
                                <input type="number" class="form-control form-control-lg" id="productPrice" name="price" 
                                       step="0.01" min="0" placeholder="0.00" required>
                                <div class="invalid-feedback">Please provide a valid price.</div>
                            </div>
                        </div>
                        
                        <!-- Stock -->
                        <div class="col-md-6">
                            <label for="productStock" class="form-label">Initial Stock</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-box-seam"></i></span>
                                <input type="number" class="form-control form-control-lg" id="productStock" 
                                       name="stock" min="0" value="0" placeholder="0">
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="col-12">
                            <label for="productDescription" class="form-label">Description</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                <textarea class="form-control" id="productDescription" name="description" 
                                          rows="3" placeholder="Enter product description (optional)"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-lg btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-lg btn-primary">
                        <i class="bi bi-save me-1"></i> Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProductForm">
                <input type="hidden" id="editProductId" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editProductName" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editProductName" name="name" required>
                            <div class="invalid-feedback">Please provide a product name.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editCategory" class="form-label">Category</label>
                            <select class="form-select" id="editCategory" name="category">
                                <option value="">Select Category</option>
                                <option value="Electronics">Electronics</option>
                                <option value="Clothing">Clothing</option>
                                <option value="Food & Beverage">Food & Beverage</option>
                                <option value="Health & Beauty">Health & Beauty</option>
                                <option value="Home & Garden">Home & Garden</option>
                                <option value="Sports & Outdoors">Sports & Outdoors</option>
                                <option value="Books & Media">Books & Media</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editPrice" class="form-label">Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control" id="editPrice" name="price" step="0.01" min="0" required>
                                <div class="invalid-feedback">Please provide a valid price.</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editStock" class="form-label">Initial Stock</label>
                            <input type="number" class="form-control" id="editStock" name="stock" min="0" value="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editLowStockThreshold" class="form-label">Low Stock Threshold</label>
                        <input type="number" class="form-control" id="editLowStockThreshold" name="low_stock_threshold" min="1" value="10">
                        <div class="form-text">Alert when stock falls below this number</div>
                    </div>
                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="me-auto">
                        <button type="button" class="btn btn-outline-danger" id="deleteProductBtn">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables CSS and JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable with AJAX data source
    var table = $('#productsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        order: [],
        ajax: {
            url: 'get_products.php', // We'll create this file next
            type: 'POST',
            data: function(d) {
                // Add any additional parameters here if needed
            },
            error: function(xhr, error, thrown) {
                console.error('Error loading products:', error);
                $('#productsTable tbody').html(
                    '<tr><td colspan="7" class="text-center text-danger">Error loading products. Please try again.</td></tr>'
                );
            }
        },
        columns: [
            { 
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                orderable: false,
                className: 'text-center'
            },
            { 
                data: 'name',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return '<strong>' + data + '</strong>' + 
                               (row.description ? '<br><small class="text-muted">' + row.description + '</small>' : '');
                    }
                    return data;
                }
            },
            { 
                data: 'category',
                defaultContent: 'Uncategorized',
                render: function(data) {
                    return data || 'Uncategorized';
                }
            },
            { 
                data: 'price',
                render: function(data) {
                    return '₱' + parseFloat(data || 0).toFixed(2);
                },
                className: 'text-end'
            },
            { 
                data: 'stock',
                render: function(data) {
                    return parseInt(data) || 0;
                },
                className: 'text-center'
            },
            {
                data: 'stock_status',
                render: function(data, type, row) {
                    var status = data || 'Unknown';
                    if (type === 'display') {
                        var badgeClass = 'secondary';
                        if (row.stock <= 0) {
                            status = 'Out of Stock';
                            badgeClass = 'danger';
                        } else if (row.stock <= 10) {
                            status = 'Low Stock';
                            badgeClass = 'warning';
                        } else {
                            status = 'In Stock';
                            badgeClass = 'success';
                        }
                        return '<span class="badge bg-' + badgeClass + '">' + status + '</span>';
                    }
                    return status;
                },
                className: 'text-center'
            },
            {
                data: 'id',
                render: function(data) {
                    return '<div class="btn-group btn-group-sm">' +
                           '<button type="button" class="btn btn-outline-primary" onclick="editProduct(' + data + ')" title="Edit">' +
                           '<i class="bi bi-pencil"></i></button>' +
                           '<button type="button" class="btn btn-outline-danger" onclick="deleteProduct(' + data + ')" title="Delete">' +
                           '<i class="bi bi-trash"></i></button></div>';
                },
                orderable: false,
                className: 'text-end'
            }
        ],
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div> Loading products...',
            search: "",
            searchPlaceholder: "Search products...",
            emptyTable: "No products found",
            zeroRecords: "No matching products found"
        },
        initComplete: function() {
            // Add search input to the search box
            var searchInput = $('.dataTables_filter input');
            searchInput.addClass('form-control form-control-sm');
            searchInput.attr('placeholder', 'Search products...');
            
            // Handle search from our custom search box
            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });
        }
    });

    // Form validation and submission
    const form = document.getElementById('addProductForm');
    
    // Only add event listener if form exists on the page
    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Check form validity
            if (!this.checkValidity()) {
                event.stopPropagation();
                this.classList.add('was-validated');
                return;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
                
                // Store original button state for reset
                submitBtn.dataset.originalHtml = originalBtnText;
            }
            
            // Submit the form via AJAX
            const formData = new FormData(this);
            
            fetch('', {  // Submit to the same URL
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    return response.text().then(html => {
                        // This handles non-redirect responses (shouldn't normally happen)
                        document.documentElement.innerHTML = html;
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = submitBtn.dataset.originalHtml || 'Save Product';
                    
                    // Show error message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                    alertDiv.role = 'alert';
                    alertDiv.innerHTML = `
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Error saving product. Please try again.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    this.insertAdjacentElement('beforebegin', alertDiv);
                }
            });
        });
    }
    });
    
    // Close success/error alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
    
    // Reset form when modal is hidden
    $('#addProductModal').on('hidden.bs.modal', function () {
        form.reset();
        form.classList.remove('was-validated');
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Save Product';
    });
    
    // Edit Product Form Handler
    const editForm = document.getElementById('editProductForm');
    editForm.addEventListener('submit', function(event) {
        event.preventDefault();
        
        if (!editForm.checkValidity()) {
            event.stopPropagation();
            editForm.classList.add('was-validated');
            return;
        }
        
        const formData = new FormData(editForm);
        formData.append('action', 'update_product');
        
        const submitBtn = editForm.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
        
        fetch('inventory.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                $('#editProductModal').modal('hide');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('danger', data.message);
            }
        })
        .catch(error => {
            showAlert('danger', 'An error occurred while updating the product.');
            console.error('Error:', error);
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        });
    });
    
    // Delete Product Handler
    $('#deleteProductBtn').on('click', function() {
        const productId = $('#editProductId').val();
        const productName = $('#editProductName').val();
        
        if (confirm(`Are you sure you want to delete "${productName}"? This action cannot be undone.`)) {
            deleteProductById(productId);
        }
    });
});

// Edit Product Function
function editProduct(productId) {
    // Show loading state
    const modal = new bootstrap.Modal(document.getElementById('editProductModal'));
    modal.show();
    
    // Fetch product data
    const formData = new FormData();
    formData.append('action', 'get_product');
    formData.append('id', productId);
    
    fetch('inventory.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const product = data.product;
            document.getElementById('editProductId').value = product.id;
            document.getElementById('editProductName').value = product.name;
            document.getElementById('editCategory').value = product.category || '';
            document.getElementById('editPrice').value = product.price;
            document.getElementById('editStock').value = product.quantity || 0;
            document.getElementById('editLowStockThreshold').value = product.low_stock_threshold || 10;
            document.getElementById('editDescription').value = product.description || '';
        } else {
            showAlert('danger', data.message);
            modal.hide();
        }
    })
    .catch(error => {
        showAlert('danger', 'An error occurred while loading product data.');
        console.error('Error:', error);
        modal.hide();
    });
}

// Delete Product Function
function deleteProduct(productId, productName) {
    if (confirm(`Are you sure you want to delete "${productName}"? This action cannot be undone.`)) {
        deleteProductById(productId);
    }
}

function deleteProductById(productId) {
    const formData = new FormData();
    formData.append('action', 'delete_product');
    formData.append('id', productId);
    
    fetch('inventory.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            $('#editProductModal').modal('hide');
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        showAlert('danger', 'An error occurred while deleting the product.');
        console.error('Error:', error);
    });
}

// Show Alert Function
function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Insert alert at the top of the page
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}
</script>

</body>
</html>
