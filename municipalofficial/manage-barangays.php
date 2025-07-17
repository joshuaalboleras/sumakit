<?php
include '../configuration/config.php';
include '../configuration/routes.php';

// Handle Delete
if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM barangays WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage-barangay.php");
    exit;
}

// Handle Update
if (isset($_POST['edit_id'])) {
    $id = intval($_POST['edit_id']);
    $barangay_name = $_POST['barangay_name'];
    $stmt = $conn->prepare("UPDATE barangays SET barangay_name = ? WHERE id = ?");
    $stmt->execute([$barangay_name, $id]);
    header("Location: manage-barangay.php");
    exit;
}

// Fetch barangays
$stmt = $conn->query("SELECT * FROM barangays");
$barangays = $stmt->fetchAll();

// Fetch provinces for dropdown
$provinces = $conn->query("SELECT id, province_name FROM provinces ORDER BY province_name ASC")->fetchAll(PDO::FETCH_ASSOC);
// Optionally, fetch all municipalities for initial load (not required for cascading)
// $municipalities = $conn->query("SELECT id, municipality, province_id FROM municipalities ORDER BY municipality ASC")->fetchAll(PDO::FETCH_ASSOC);
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
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/favicon.ico">

    <!-- CSS
	============================================ -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/css/vendor/bootstrap.min.css">

    <!-- Icon Font CSS -->
    <link rel="stylesheet" href="../assets/css/vendor/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="../assets/css/vendor/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/vendor/themify-icons.css">
    <link rel="stylesheet" href="../assets/css/vendor/cryptocurrency-icons.css">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="../assets/css/plugins/plugins.css">

    <!-- Helper CSS -->
    <link rel="stylesheet" href="../assets/css/helper.css">

    <!-- Main Style CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Custom Style CSS Only For Demo Purpose -->
    <link id="cus-style" rel="stylesheet" href="../assets/css/style-primary.css">
    <!-- Leaflet CSS for Map Viewer -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

</head>

<body class="skin-dark">

    <div class="main-wrapper">


        <!-- Header Section Start -->
        <div class="header-section">
            <div class="container-fluid">
                <div class="row justify-content-between align-items-center">

                    <!-- Header Logo (Header Left) Start -->
                    <div class="header-logo col-auto">
                        <a href="index.html">
                            <img src="../assets/images/logo/logo.png" alt="">
                            <img src="../assets/images/logo/logo-light.png" class="logo-light" alt="">
                        </a>
                    </div><!-- Header Logo (Header Left) End -->

                    <!-- Header Right Start -->
                        <?php include '../partials/shared/top-nav.php';?>
                    <!-- Header Right End -->

                </div>
            </div>
        </div><!-- Header Section End -->
        <!-- Side Header Start -->
        <div class="side-header show">
            <button class="side-header-close"><i class="zmdi zmdi-close"></i></button>
            <!-- Side Header Inner Start -->
             <?php include '../partials/municipalofficial/side-bar.php';?>
            <!-- Side Header Inner End -->
        </div><!-- Side Header End -->

        <!-- Content Body Start -->
        <div class="content-body">

            <div class="box">
                <div class="box-head">
                    <h4 class="title">Barangay List</h4>
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
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vertical-middle table-selectable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Barangay Name</th>
                                    <th>Municipal ID</th>
                                    <th>Province ID</th>
                                    <th>Date Added</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($barangays as $row): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['barangay_name']) ?></td>
                                    <td><?= $row['municipal_id'] ?></td>
                                    <td><?= $row['province_id'] ?></td>
                                    <td><?= $row['date_added'] ?></td>
                                    <td>
                                        <!-- Edit Button (opens modal) -->
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal<?= $row['id'] ?>">Edit</button>
                                        <!-- Delete Button (form submit) -->
                                        <form method="post" action="" style="display:inline;" onsubmit="return confirm('Delete this barangay?');">
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
                                          <h5 class="modal-title" id="editModalLabel<?= $row['id'] ?>">Edit Barangay</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                          <input type="hidden" name="edit_id" value="<?= $row['id'] ?>">
                                          <div class="form-group">
                                            <label>Barangay Name</label>
                                            <input type="text" name="barangay_name" class="form-control" value="<?= htmlspecialchars($row['barangay_name']) ?>" required>
                                          </div>
                                          <!-- Add more fields if needed -->
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

        </div><!-- Content Body End -->

        <!-- Footer Section Start -->
        <div class="footer-section">
            <div class="container-fluid">

                <div class="footer-copyright text-center">
                    <p class="text-body-light">2022 &copy; <a href="https://themeforest.net/user/codecarnival">Codecarnival</a></p>
                </div>

            </div>
        </div><!-- Footer Section End -->

    </div>

    <!-- JS
============================================ -->

    <!-- Global Vendor, plugins & Activation JS -->
    <script src="../assets/js/vendor/modernizr-3.6.0.min.js"></script>
    <script src="../assets/js/vendor/jquery-3.3.1.min.js"></script>
    <script src="../assets/js/vendor/popper.min.js"></script>
    <script src="../assets/js/vendor/bootstrap.min.js"></script>
    <!--Plugins JS-->
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/tippy4.min.js.js"></script>
    <!--Main JS-->
    <script src="../assets/js/main.js"></script>

    <!-- Plugins & Activation JS For Only This Page -->

    <!--Moment-->
    <script src="../assets/js/plugins/moment/moment.min.js"></script>

    <!--Daterange Picker-->
    <script src="../assets/js/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="../assets/js/plugins/daterangepicker/daterangepicker.active.js"></script>

    <!--Echarts-->
    <script src="../assets/js/plugins/chartjs/Chart.min.js"></script>
    <script src="../assets/js/plugins/chartjs/chartjs.active.js"></script>

    <!--VMap-->
    <script src="../assets/js/plugins/vmap/jquery.vmap.min.js"></script>
    <script src="../assets/js/plugins/vmap/maps/jquery.vmap.world.js"></script>
    <script src="../assets/js/plugins/vmap/maps/samples/jquery.vmap.sampledata.js"></script>
    <script src="../assets/js/plugins/vmap/vmap.active.js"></script>
    <!-- Leaflet JS for Map Viewer -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
$(document).ready(function() {
    // When province changes, fetch municipalities
    $('#provinceFilter').on('change', function() {
        var provinceId = $(this).val();
        $('#municipalityFilter').prop('disabled', true).html('<option value="">Select Municipality</option>');
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
        filterBarangayTable();
    });
    // When municipality changes, filter table
    $('#municipalityFilter').on('change', function() {
        filterBarangayTable();
    });
    function filterBarangayTable() {
        var provinceId = $('#provinceFilter').val();
        var municipalId = $('#municipalityFilter').val();
        // Hide all rows, then show only those matching
        $('table.table tbody tr').each(function() {
            var row = $(this);
            var rowProvince = row.find('td').eq(3).text().trim(); // province_id column
            var rowMunicipal = row.find('td').eq(2).text().trim(); // municipal_id column
            var show = true;
            if (provinceId && rowProvince !== provinceId) show = false;
            if (municipalId && rowMunicipal !== municipalId) show = false;
            row.toggle(show);
        });
    }
});
</script>

</body>

</html>