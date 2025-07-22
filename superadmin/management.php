<?php
include '../configuration/config.php';
include '../configuration/routes.php';


// Handle Province Delete
if (isset($_POST['delete_province_id'])) {
    $id = intval($_POST['delete_province_id']);
    $stmt = $conn->prepare("DELETE FROM provinces WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: management.php");
    exit;
}
// Handle Province Update
if (isset($_POST['edit_province_id'])) {
    $id = intval($_POST['edit_province_id']);
    $province_name = $_POST['province_name'];
    $stmt = $conn->prepare("UPDATE provinces SET province_name = ? WHERE id = ?");
    $stmt->execute([$province_name, $id]);
    header("Location: management.php");
    exit;
}
// Handle Municipality Delete
if (isset($_POST['delete_municipality_id'])) {
    $id = intval($_POST['delete_municipality_id']);
    $stmt = $conn->prepare("DELETE FROM municipalities WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: management.php");
    exit;
}
// Handle Municipality Update
if (isset($_POST['edit_municipality_id'])) {
    $id = intval($_POST['edit_municipality_id']);
    $municipality = $_POST['municipality'];
    $stmt = $conn->prepare("UPDATE municipalities SET municipality = ? WHERE id = ?");
    $stmt->execute([$municipality, $id]);
    header("Location: management.php");
    exit;
}
// Fetch provinces
$provinces = $conn->query("SELECT * FROM provinces ORDER BY province_name ASC")->fetchAll(PDO::FETCH_ASSOC);
// Fetch municipalities (with province name and geojson)
$stmt = $conn->query("SELECT m.*, p.province_name FROM municipalities m JOIN provinces p ON m.province_id = p.id ORDER BY m.municipality ASC");
$municipalities = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Adomx - Responsive Bootstrap 4 Admin Template</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/favicon.ico">
    <link rel="stylesheet" href="../assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/vendor/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="../assets/css/vendor/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/vendor/themify-icons.css">
    <link rel="stylesheet" href="../assets/css/vendor/cryptocurrency-icons.css">
    <link rel="stylesheet" href="../assets/css/plugins/plugins.css">
    <link rel="stylesheet" href="../assets/css/helper.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link id="cus-style" rel="stylesheet" href="../assets/css/style-primary.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body class="skin-dark">
<div class="main-wrapper">
    <div class="header-section">
        <div class="container-fluid">
            <div class="row justify-content-between align-items-center">
                <div class="header-logo col-auto">
                    <a href="index.html">
                        <img src="../assets/images/logo/logo.png" alt="">
                        <img src="../assets/images/logo/logo-light.png" class="logo-light" alt="">
                    </a>
                </div>
                <?php include '../partials/shared/top-nav.php';?>
            </div>
        </div>
    </div>
    <div class="side-header show">
        <button class="side-header-close"><i class="zmdi zmdi-close"></i></button>
        <?php include '../partials/superadmin/side-bar.php';?>
    </div>
    <div class="content-body">
        <!-- Province Table -->
        <div class="box mb-4">
            <div class="box-head">
                <h4 class="title">Provinces</h4>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-vertical-middle table-selectable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Province Name</th>
                                <th>Date Added</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($provinces as $row): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= htmlspecialchars($row['province_name'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['date_added'] ?? '') ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editProvinceModal<?= $row['id'] ?>">Edit</button>
                                    <form method="post" action="" style="display:inline;" onsubmit="return confirm('Delete this province?');">
                                        <input type="hidden" name="delete_province_id" value="<?= $row['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Province Edit Modals -->
        <?php foreach($provinces as $row): ?>
        <div class="modal fade" id="editProvinceModal<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editProvinceModalLabel<?= $row['id'] ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" action="../handler/superadmin/edit_province.php">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProvinceModalLabel<?= $row['id'] ?>">Edit Province</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="edit_province_id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                            <div class="form-group">
                                <label>Province Name</label>
                                <input type="text" name="province_name" class="form-control" value="<?= htmlspecialchars($row['province_name'] ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
        <!-- Municipality Table -->
        <div class="box mb-4">
            <div class="box-head">
                <h4 class="title">Municipalities</h4>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-vertical-middle table-selectable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Municipality</th>
                                <th>Province</th>
                                <th>Date Added</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($municipalities as $row): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= htmlspecialchars($row['municipality'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['province_name'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['date_added'] ?? '') ?></td>
                                <td>
                                    <a href="../view_location.php?id=<?= $row['id'] ?>&type=municipality" target="_blank" class="btn btn-info btn-sm mb-1">View Location</a>
                                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editMunicipalityModal<?= $row['id'] ?>">Edit</button>
                                    <form method="post" action="" style="display:inline;" onsubmit="return confirm('Delete this municipality?');">
                                        <input type="hidden" name="delete_municipality_id" value="<?= $row['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Municipality Edit Modals -->
        <?php foreach($municipalities as $row): ?>
        <div class="modal fade" id="editMunicipalityModal<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editMunicipalityModalLabel<?= $row['id'] ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" action="../handler/superadmin/edit_municipality.php">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editMunicipalityModalLabel<?= $row['id'] ?>">Edit Municipality</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="edit_municipality_id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                            <div class="form-group">
                                <label>Municipality</label>
                                <input type="text" name="municipality" class="form-control" value="<?= htmlspecialchars($row['municipality'] ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="footer-section">
        <div class="container-fluid">
            <div class="footer-copyright text-center">
                <p class="text-body-light">2022 &copy; <a href="https://themeforest.net/user/codecarnival">Codecarnival</a></p>
            </div>
        </div>
    </div>
</div>
<script src="../assets/js/vendor/modernizr-3.6.0.min.js"></script>
<script src="../assets/js/vendor/jquery-3.3.1.min.js"></script>
<script src="../assets/js/vendor/popper.min.js"></script>
<script src="../assets/js/vendor/bootstrap.min.js"></script>
<script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../assets/js/plugins/tippy4.min.js.js"></script>
<script src="../assets/js/main.js"></script>
<script>
// Fallback: Manually trigger modal on Edit button click
$(document).on('click', 'button[data-toggle="modal"][data-target^="#editProvinceModal"], button[data-toggle="modal"][data-target^="#editMunicipalityModal"]', function(e) {
    e.preventDefault();
    var target = $(this).attr('data-target');
    $(target).modal('show');
});
</script>
</body>
</html>