<?php
include './configuration/config.php';

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
    <link rel="shortcut icon" type="image/x-icon" href="./assets/images/favicon.ico">

    <!-- CSS
	============================================ -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./assets/css/vendor/bootstrap.min.css">

    <!-- Icon Font CSS -->
    <link rel="stylesheet" href="./assets/css/vendor/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="./assets/css/vendor/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/vendor/themify-icons.css">
    <link rel="stylesheet" href="./assets/css/vendor/cryptocurrency-icons.css">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="./assets/css/plugins/plugins.css">

    <!-- Helper CSS -->
    <link rel="stylesheet" href="./assets/css/helper.css">

    <!-- Main Style CSS -->
    <link rel="stylesheet" href="./assets/css/style.css">

    <!-- Custom Style CSS Only For Demo Purpose -->
    <link id="cus-style" rel="stylesheet" href="./assets/css/style-primary.css">
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
                            <img src="./assets/images/logo/logo.png" alt="">
                            <img src="./assets/images/logo/logo-light.png" class="logo-light" alt="">
                        </a>
                    </div><!-- Header Logo (Header Left) End -->

                    <!-- Header Right Start -->
                        <?php include './partials/shared/top-nav.php';?>
                    <!-- Header Right End -->

                </div>
            </div>
        </div><!-- Header Section End -->
        <!-- Side Header Start -->
        <div class="side-header show">
            <button class="side-header-close"><i class="zmdi zmdi-close"></i></button>
            <!-- Side Header Inner Start -->
           
            <!-- Side Header Inner End -->
        </div><!-- Side Header End -->

        <!-- Content Body Start -->
        <div class="content-body">
            <div class="container mt-4">
                <?php
                $item = null;
                $geojson = null;
                $type = isset($_GET['type']) && in_array($_GET['type'], ['store', 'municipality']) ? $_GET['type'] : 'house';
                if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                    $id = intval($_GET['id']);
                    if ($type === 'store') {
                        $stmt = $conn->prepare("SELECT * FROM stores WHERE id = ?");
                        $stmt->execute([$id]);
                        $item = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($item && !empty($item['geojson'])) {
                            $geojson = $item['geojson'];
                        }
                    } else if ($type === 'municipality') {
                        $stmt = $conn->prepare("SELECT m.*, p.province_name FROM municipalities m JOIN provinces p ON m.province_id = p.id WHERE m.id = ?");
                        $stmt->execute([$id]);
                        $item = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($item && !empty($item['geojson'])) {
                            $geojson = $item['geojson'];
                        }
                    } else {
                        $stmt = $conn->prepare("SELECT * FROM houses WHERE id = ?");
                        $stmt->execute([$id]);
                        $item = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($item && !empty($item['geojson'])) {
                            $geojson = $item['geojson'];
                        }
                    }
                }
                ?>
                <h3>
                    <?php
                    if ($type === 'store') echo 'Store Location Viewer';
                    else if ($type === 'municipality') echo 'Municipality Location Viewer';
                    else echo 'House Location Viewer';
                    ?>
                </h3>
                <?php if ($item): ?>
                    <div class="mb-2">
                        <?php if ($type === 'store'): ?>
                            <strong>Owner Name:</strong> <?= htmlspecialchars($item['owner_name'] ?? '') ?><br>
                            <strong>Barangay:</strong> <?= htmlspecialchars($item['barangay_id'] ?? '') ?><br>
                            <strong>Municipality:</strong> <?= htmlspecialchars($item['municipal_id'] ?? '') ?><br>
                            <strong>Province:</strong> <?= htmlspecialchars($item['province_id'] ?? '') ?><br>
                        <?php elseif ($type === 'municipality'): ?>
                            <strong>Municipality:</strong> <?= htmlspecialchars($item['municipality'] ?? '') ?><br>
                            <strong>Province:</strong> <?= htmlspecialchars($item['province_name'] ?? '') ?><br>
                        <?php else: ?>
                            <strong>House Number:</strong> <?= htmlspecialchars($item['house_number'] ?? '') ?><br>
                            <strong>Street Name:</strong> <?= htmlspecialchars($item['street_name'] ?? '') ?><br>
                            <strong>Barangay:</strong> <?= htmlspecialchars($item['barangay_id'] ?? '') ?><br>
                            <strong>Municipality:</strong> <?= htmlspecialchars($item['municipal_id'] ?? '') ?><br>
                            <strong>Province:</strong> <?= htmlspecialchars($item['province_id'] ?? '') ?><br>
                        <?php endif; ?>
                    </div>
                    <div id="map" style="height: 400px; width: 100%;"></div>
                    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var map = L.map('map').setView([12.8797, 121.7740], 6);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                maxZoom: 19,
                                attribution: '© OpenStreetMap'
                            }).addTo(map);
                            var geojsonStr = <?= json_encode($geojson) ?>;
                            if (geojsonStr) {
                                try {
                                    var geometry = JSON.parse(geojsonStr);
                                    var layer = null;
                                    if ("<?= $type ?>" === 'municipality') {
                                        // Municipality: render as boundary (Polygon/Multipolygon)
                                        layer = L.geoJSON(geometry).addTo(map);
                                        map.fitBounds(layer.getBounds());
                                    } else if (geometry.type === 'Point') {
                                        var coords = geometry.coordinates;
                                        layer = L.marker([coords[1], coords[0]]).addTo(map);
                                        map.setView([coords[1], coords[0]], 18);
                                    } else {
                                        layer = L.geoJSON({type: 'Feature', geometry: geometry}).addTo(map);
                                        map.fitBounds(layer.getBounds());
                                    }
                                } catch (e) {
                                    alert('Invalid GeoJSON data.');
                                }
                            } else {
                                alert('No location data available for this ' + <?= json_encode($type) ?> + '.');
                            }
                        });
                    </script>
                <?php else: ?>
                    <div class="alert alert-danger"><?= $type === 'store' ? 'Store' : ($type === 'municipality' ? 'Municipality' : 'House') ?> not found or no location data available.</div>
                <?php endif; ?>
            </div>
        </div>

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
    <script src="./assets/js/vendor/modernizr-3.6.0.min.js"></script>
    <script src="./assets/js/vendor/jquery-3.3.1.min.js"></script>
    <script src="./assets/js/vendor/popper.min.js"></script>
    <script src="./assets/js/vendor/bootstrap.min.js"></script>
    <!--Plugins JS-->
    <script src="./assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="./assets/js/plugins/tippy4.min.js.js"></script>
    <!--Main JS-->
    <script src="./assets/js/main.js"></script>

    <!-- Plugins & Activation JS For Only This Page -->

    <!--Moment-->
    <script src="./assets/js/plugins/moment/moment.min.js"></script>

    <!--Daterange Picker-->
    <script src="./assets/js/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="./assets/js/plugins/daterangepicker/daterangepicker.active.js"></script>

    <!--Echarts-->
    <script src="./assets/js/plugins/chartjs/Chart.min.js"></script>
    <script src="./assets/js/plugins/chartjs/chartjs.active.js"></script>

    <!--VMap-->
    <script src="./assets/js/plugins/vmap/jquery.vmap.min.js"></script>
    <script src="./assets/js/plugins/vmap/maps/jquery.vmap.world.js"></script>
    <script src="./assets/js/plugins/vmap/maps/samples/jquery.vmap.sampledata.js"></script>
    <script src="./assets/js/plugins/vmap/vmap.active.js"></script>
    <!-- Leaflet JS for Map Viewer -->
   

</body>

</html>