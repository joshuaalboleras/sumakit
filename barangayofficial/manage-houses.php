


<?php
include '../configuration/config.php';
include '../configuration/routes.php';

// Handle Delete
if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM houses WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage-houses.php");
    exit;
}

// Handle Update
if (isset($_POST['edit_id'])) {
    $id = intval($_POST['edit_id']);
    $house_number = $_POST['house_number'];
    $building_type = $_POST['building_type'];
    $status = $_POST['status'];
    $no_floors = $_POST['no_floors'];
    $year_built = $_POST['year_built'];
    $street_name = $_POST['street_name'];
    $stmt = $conn->prepare("UPDATE houses SET house_number=?, building_type=?, status=?, no_floors=?, year_built=?, street_name=? WHERE id=?");
    $stmt->execute([$house_number, $building_type, $status, $no_floors, $year_built, $street_name, $id]);
    header("Location: manage-houses.php");
    exit;
}

// Fetch provinces for dropdown
$provinces = $conn->query("SELECT id, province_name FROM provinces ORDER BY province_name ASC")->fetchAll(PDO::FETCH_ASSOC);
// Fetch all houses with location info
$stmt = $conn->query("SELECT h.*, b.barangay_name, m.municipality, p.province_name FROM houses h JOIN barangays b ON h.barangay_id = b.id JOIN municipalities m ON h.municipal_id = m.id JOIN provinces p ON h.province_id = p.id");
$houses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Manage Houses</title>
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
             <?php include '../partials/barangayofficial/side-bar.php';?>
    </div>
        <div class="content-body">
        <div class="box">
            <div class="box-head">
                <h4 class="title">Houses</h4>
            </div>
            <div class="box-body">
                <!-- Cascading Dropdowns -->
                <div class="row mb-3">
                    <div class="col-md-4 mb-2">
                        <select id="provinceFilter" class="form-control">
                            <option value="">Select Province</option>
                            <?php foreach ($provinces as $prov): ?>
                                <option value="<?= $prov['id'] ?>"><?= htmlspecialchars($prov['province_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <select id="municipalityFilter" class="form-control" disabled>
                            <option value="">Select Municipality / City</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <select id="barangayFilter" class="form-control" disabled>
                            <option value="">Select Barangay</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-vertical-middle table-selectable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>House Number</th>
                                <th>Province</th>
                                <th>Municipality</th>
                                <th>Barangay</th>
                                <th>Street Name</th>
                                <th>Building Type</th>
                                <th>Status</th>
                                <th>No. Floors</th>
                                <th>Year Built</th>
                                <th>GeoJSON</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($houses as $row): ?>
                            <tr
                                data-province="<?= $row['province_id'] ?>"
                                data-municipality="<?= $row['municipal_id'] ?>"
                                data-barangay="<?= $row['barangay_id'] ?>"
                            >
                                <td><?= $row['id'] ?></td>
                                <td><?= htmlspecialchars($row['house_number'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['province_name'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['municipality'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['barangay_name'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['street_name'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['building_type'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['status'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['no_floors'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['year_built'] ?? '') ?></td>
                                <td><a href="../view_location.php?id=<?= $row['id'] ?>" target="_blank" class="btn btn-info btn-sm">View Location</a></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal<?= $row['id'] ?>">Edit</button>
                                    <form method="post" action="" style="display:inline;" onsubmit="return confirm('Delete this house?');">
                                        <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?= $row['id'] ?>" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form method="post" action="">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel<?= $row['id'] ?>">Edit House</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="edit_id" value="<?= $row['id'] ?>">
                                                <div class="form-group">
                                                    <label>House Number</label>
                                                    <input type="number" name="house_number" class="form-control" value="<?= htmlspecialchars($row['house_number'] ?? '') ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Building Type</label>
                                                    <input type="text" name="building_type" class="form-control" value="<?= htmlspecialchars($row['building_type'] ?? '') ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <input type="text" name="status" class="form-control" value="<?= htmlspecialchars($row['status'] ?? '') ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>No. Floors</label>
                                                    <input type="number" name="no_floors" class="form-control" value="<?= htmlspecialchars($row['no_floors'] ?? '') ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Year Built</label>
                                                    <input type="date" name="year_built" class="form-control" value="<?= htmlspecialchars($row['year_built'] ?? '') ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Street Name</label>
                                                    <input type="text" name="street_name" class="form-control" value="<?= htmlspecialchars($row['street_name'] ?? '') ?>">
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Province -> Municipality
    $('#provinceFilter').on('change', function() {
        var provinceId = $(this).val();
        $('#municipalityFilter').prop('disabled', true).html('<option value="">Select Municipality</option>');
        $('#barangayFilter').prop('disabled', true).html('<option value="">Select Barangay</option>');
        if (provinceId) {
            $.get('/handler/barangayofficial/get_municipalities.php', { province_id: provinceId }, function(data) {
                var options = '<option value="">Select Municipality</option>';
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(function(m) {
                        options += '<option value="' + m.id + '">' + m.municipality + '</option>';
                    });
                    $('#municipalityFilter').html(options).prop('disabled', false);
                } else {
                    options += '<option value="" disabled>No municipalities found</option>';
                    $('#municipalityFilter').html(options).prop('disabled', false);
                }
            }, 'json');
        }
        filterHouseTable();
    });
    // Municipality -> Barangay
    $('#municipalityFilter').on('change', function() {
        var municipalId = $(this).val();
        $('#barangayFilter').prop('disabled', true).html('<option value="">Select Barangay</option>');
        if (municipalId) {
            $.get('/handler/barangayofficial/get_barangays.php', { municipal_id: municipalId }, function(data) {
                var options = '<option value="">Select Barangay</option>';
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(function(b) {
                        options += '<option value="' + b.id + '">' + b.barangay_name + '</option>';
                    });
                    $('#barangayFilter').html(options).prop('disabled', false);
                } else {
                    options += '<option value="" disabled>No barangays found</option>';
                    $('#barangayFilter').html(options).prop('disabled', false);
                }
            }, 'json');
        }
        filterHouseTable();
    });
    // Barangay filter
    $('#barangayFilter').on('change', function() {
        filterHouseTable();
    });
    function filterHouseTable() {
        var provinceId = $('#provinceFilter').val();
        var municipalId = $('#municipalityFilter').val();
        var barangayId = $('#barangayFilter').val();
        $('table.table tbody tr').each(function() {
            var row = $(this);
            var rowProvince = row.data('province').toString();
            var rowMunicipal = row.data('municipality').toString();
            var rowBarangay = row.data('barangay').toString();
            var show = true;
            if (provinceId && rowProvince !== provinceId) show = false;
            if (municipalId && rowMunicipal !== municipalId) show = false;
            if (barangayId && rowBarangay !== barangayId) show = false;
            row.toggle(show);
        });
    }
});
</script>
</body>
</html>