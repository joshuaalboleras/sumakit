<?php
include '../configuration/config.php';
include '../configuration/routes.php';
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />

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
             <?php include '../partials/barangayofficial/side-bar.php';?>
            <!-- Side Header Inner End -->
        </div><!-- Side Header End -->

        <!-- Content Body Start -->
        <div class="content-body">
            <div class="container mt-5">
                <div class="container min-vh-75">
                    <div class="box-header text-white">
                        <h4 class="mb-0">Store Registration</h4>
                    </div>
                    <div class="box-body">
                        <form action="../handler/barangayofficial/register_store.php" method="POST">
                            <div class="row" style="height: 600px;">
                                <!-- Left column: text fields -->
                                <div class="col-12 col-md-6 box p-4">
                                    <div class="form-group">
                                        <label for="owner_name">Owner Name</label>
                                        <input type="text" class="form-control" id="owner_name" name="owner_name" maxlength="250" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="province_id">Province</label>
                                        <select class="form-control" id="province_id" name="province_id" required>
                                            <option value="">Select Province</option>
                                            <?php
                                            $provinces = [];
                                            $stmt = $conn->query("SELECT id, province_name FROM provinces ORDER BY province_name ASC");
                                            $provinces = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($provinces as $province): ?>
                                                <option value="<?= $province['id'] ?>"><?= htmlspecialchars($province['province_name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="municipal_id">Municipality /City</label>
                                        <select class="form-control" id="municipal_id" name="municipal_id" required disabled>
                                            <option value="">Select Municipality / City</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="barangay_id">Barangay</label>
                                        <select class="form-control" id="barangay_id" name="barangay_id" required disabled>
                                            <option value="">Select Barangay</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="family_name_group" style="display:none;">
                                        <label for="family_name">Family Name</label>
                                        <select class="form-control" id="family_name" name="family_name">
                                            <option value="">Select Family Name</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="owner_candidate_group" style="display:none;">
                                        <label for="owner_candidate">Recommend Owner</label>
                                        <select class="form-control" id="owner_candidate" name="owner_candidate">
                                            <option value="">Select Owner</option>
                                        </select>
                                    </div>
                                    <input type="hidden" id="household_id" name="household_id" />
                                </div>
                                <!-- Right column: map and geojson -->
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="map">Select Location on Map</label>
                                        <div id="map" style="height: 350px; width: 100%; margin-bottom: 10px;"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="geojson">GeoJSON (auto-filled from map)</label>
                                        <textarea class="form-control" id="geojson" name="geojson" rows="3" readonly required></textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Register Store</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Leaflet JS and Leaflet Draw -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
            <script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>
            <script>
                $(document).ready(function() {
                    // When province changes, fetch municipalities
                    $('#province_id').on('change', function() {
                        var provinceId = $(this).val();
                        $('#municipal_id').prop('disabled', true).html('<option value="">Select Municipality</option>');
                        $('#barangay_id').prop('disabled', true).html('<option value="">Select Barangay</option>');
                        $('#family_name_group').hide();
                        $('#owner_candidate_group').hide();
                        $('#household_id').val('');
                        if (provinceId) {
                            $.get('/handler/barangayofficial/get_municipalities.php', {
                                province_id: provinceId
                            }, function(data) {
                                var options = '<option value="">Select Municipality</option>';
                                if (Array.isArray(data) && data.length > 0) {
                                    data.forEach(function(m) {
                                        options += '<option value="' + m.id + '">' + m.municipality + '</option>';
                                    });
                                    $('#municipal_id').html(options).prop('disabled', false);
                                } else {
                                    options += '<option value="" disabled>No municipalities found</option>';
                                    $('#municipal_id').html(options).prop('disabled', false);
                                }
                            }, 'json');
                        }
                    });
                    // When municipality changes, fetch barangays
                    $('#municipal_id').on('change', function() {
                        var municipalId = $(this).val();
                        $('#barangay_id').prop('disabled', true).html('<option value="">Select Barangay</option>');
                        $('#family_name_group').hide();
                        $('#owner_candidate_group').hide();
                        $('#household_id').val('');
                        if (municipalId) {
                            $.get('/handler/barangayofficial/get_barangays.php', {
                                municipal_id: municipalId
                            }, function(data) {
                                var options = '<option value="">Select Barangay</option>';
                                if (Array.isArray(data) && data.length > 0) {
                                    data.forEach(function(b) {
                                        var geojsonData = b.geojson || '';
                                        options += '<option value="' + b.id + '" data-geojson="' + encodeURIComponent(geojsonData) + '">' + b.barangay_name + '</option>';
                                    });
                                    $('#barangay_id').html(options).prop('disabled', false);
                                } else {
                                    options += '<option value="" disabled>No barangays found</option>';
                                    $('#barangay_id').html(options).prop('disabled', false);
                                }
                            }, 'json');
                        }
                    });
                    // When barangay changes, fetch family names
                    $('#barangay_id').on('change', function() {
                        var barangayId = $(this).val();
                        $('#family_name').html('<option value="">Select Family Name</option>');
                        $('#owner_candidate').html('<option value="">Select Owner</option>');
                        $('#family_name_group').hide();
                        $('#owner_candidate_group').hide();
                        $('#household_id').val('');
                        if (barangayId) {
                            // Fetch family names for this barangay
                            $.get('/handler/barangayofficial/get_family_names.php', {
                                barangay_id: barangayId
                            }, function(data) {
                                var options = '<option value="">Select Family Name</option>';
                                if (Array.isArray(data) && data.length > 0) {
                                    data.forEach(function(f) {
                                        options += '<option value="' + f.household_id + '">' + f.family_name + '</option>';
                                    });
                                    $('#family_name').html(options);
                                    $('#family_name_group').show();
                                } else {
                                    options += '<option value="" disabled>No family names found</option>';
                                    $('#family_name').html(options);
                                    $('#family_name_group').show();
                                }
                            }, 'json');
                        }
                    });
                    // When family name changes, fetch owner candidates
                    $('#family_name').on('change', function() {
                        var householdId = $(this).val();
                        $('#owner_candidate').html('<option value="">Select Owner</option>');
                        $('#owner_candidate_group').hide();
                        $('#household_id').val('');
                        if (householdId) {
                            // Fetch household members for this household
                            $.get('/handler/barangayofficial/get_household_members.php', {
                                household_id: householdId
                            }, function(data) {
                                var options = '<option value="">Select Owner</option>';
                                if (Array.isArray(data) && data.length > 0) {
                                    data.forEach(function(m) {
                                        options += '<option value="' + m.id + '" data-name="' + m.name + '">' + m.name + (m.suffix ? (' ' + m.suffix) : '') + '</option>';
                                    });
                                    $('#owner_candidate').html(options);
                                    $('#owner_candidate_group').show();
                                } else {
                                    options += '<option value="" disabled>No members found</option>';
                                    $('#owner_candidate').html(options);
                                    $('#owner_candidate_group').show();
                                }
                            }, 'json');
                        }
                    });
                    // When owner candidate is selected, autofill owner_name and set household_id
                    $('#owner_candidate').on('change', function() {
                        var selected = $(this).find('option:selected');
                        var name = selected.data('name') || '';
                        var householdId = $('#family_name').val();
                        $('#owner_name').val(name);
                        $('#household_id').val(householdId);
                    });
                    // --- MAP BOUNDARY DRAWING ---
                    var map, drawnItems, drawControl, boundaryLayer;
                    function initMap() {
                        map = L.map('map').setView([12.8797, 121.7740], 6); // Default to Philippines
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '© OpenStreetMap'
                        }).addTo(map);
                        drawnItems = new L.FeatureGroup();
                        map.addLayer(drawnItems);
                        drawControl = new L.Control.Draw({
                            draw: {
                                polygon: false,
                                polyline: false,
                                rectangle: false,
                                circle: false,
                                circlemarker: false,
                                marker: true
                            },
                            edit: {
                                featureGroup: drawnItems,
                                remove: true
                            }
                        });
                        map.addControl(drawControl);
                        map.on(L.Draw.Event.CREATED, function(e) {
                            drawnItems.clearLayers(); // Only one marker at a time
                            var layer = e.layer;
                            drawnItems.addLayer(layer);
                            var geojson = layer.toGeoJSON();
                            document.getElementById('geojson').value = JSON.stringify(geojson.geometry);
                        });
                        map.on('draw:deleted', function() {
                            document.getElementById('geojson').value = '';
                        });
                    }
                    // Initialize map after DOM ready
                    initMap();
                    // Draw barangay boundary when barangay is selected
                    $('#barangay_id').on('change', function() {
                        var selected = $(this).find('option:selected');
                        var geojsonStr = selected.data('geojson');
                        if (boundaryLayer) {
                            map.removeLayer(boundaryLayer);
                            boundaryLayer = null;
                        }
                        if (geojsonStr) {
                            try {
                                var geojson = JSON.parse(decodeURIComponent(geojsonStr));
                                boundaryLayer = L.geoJSON(geojson, {
                                    style: {
                                        color: 'red',
                                        weight: 2,
                                        fillOpacity: 0.1
                                    }
                                }).addTo(map);
                                var bounds = boundaryLayer.getBounds();
                                setTimeout(function() {
                                    map.fitBounds(bounds);
                                }, 100);
                            } catch (e) {
                                console.error('Error parsing GeoJSON:', e);
                            }
                        } else {
                            map.setView([12.8797, 121.7740], 6);
                        }
                    });
                });
            </script>
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
    <!-- jQuery (required for AJAX and DOM manipulation) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Leaflet JS and Leaflet Draw (for map and drawing) -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>
    <!-- Global Vendor, plugins & Activation JS -->
    <script src="../assets/js/vendor/modernizr-3.6.0.min.js"></script>
    <script src="../assets/js/vendor/popper.min.js"></script>
    <script src="../assets/js/vendor/bootstrap.min.js"></script>
    <!--Plugins JS-->
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/tippy4.min.js.js"></script>
    <!--Main JS-->
    <script src="../assets/js/main.js"></script>
    <!-- Plugins & Activation JS For Only This Page -->
    <script src="../assets/js/plugins/moment/moment.min.js"></script>
    <script src="../assets/js/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="../assets/js/plugins/daterangepicker/daterangepicker.active.js"></script>
    <script src="../assets/js/plugins/chartjs/Chart.min.js"></script>
    <script src="../assets/js/plugins/chartjs/chartjs.active.js"></script>
    <script src="../assets/js/plugins/vmap/jquery.vmap.min.js"></script>
    <script src="../assets/js/plugins/vmap/maps/jquery.vmap.world.js"></script>
    <script src="../assets/js/plugins/vmap/maps/samples/jquery.vmap.sampledata.js"></script>
    <script src="../assets/js/plugins/vmap/vmap.active.js"></script>
    <!-- Leaflet JS for Map Viewer -->
   

</body>

</html>