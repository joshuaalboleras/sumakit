<?php
include '../configuration/config.php';
include '../configuration/routes.php';

// Fetch barangays
$barangays = [];
$stmt = $conn->query("SELECT id, barangay_name FROM barangays ORDER BY barangay_name ASC");
$barangays = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Fetch municipalities
$municipalities = [];
$stmt = $conn->query("SELECT id, municipality FROM municipalities ORDER BY municipality ASC");
$municipalities = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Fetch provinces
$provinces = [];
$stmt = $conn->query("SELECT id, province_name FROM provinces ORDER BY province_name ASC");
$provinces = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

<body class="skin-dark d-flex flex-column min-vh-100">

    <div class="main-wrapper flex-grow-1 d-flex flex-column">


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
                    <?php include '../partials/shared/top-nav.php'; ?>
                    <!-- Header Right End -->

                </div>
            </div>
        </div><!-- Header Section End -->
        <!-- Side Header Start -->
        <div class="side-header show">
            <button class="side-header-close"><i class="zmdi zmdi-close"></i></button>
            <!-- Side Header Inner Start -->
            <?php include '../partials/barangayofficial/side-bar.php'; ?>
            <!-- Side Header Inner End -->
        </div><!-- Side Header End -->

        <!-- Content Body Start -->
        <div class="content-body">

            <div class="container mt-5">
                <div class="container min-vh-75">
                    <div class="box-header text-white">
                        <h4 class="mb-0">House Registration</h4>
                    </div>
                    <div class="box-body">
                        <form action="../handler/barangayofficial/register_house.php" method="POST">
                            <div class="row" style="height: 900px;">
                                <!-- Left column: text fields -->
                                <div class="col-12 col-md-6 box p-4">
                                    <div class="form-group">
                                        <label for="house_number">House Number</label>
                                        <input type="number" class="form-control" id="house_number" name="house_number" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="family_name">Family Name</label>
                                        <input type="text" class="form-control" id="family_name" name="family_name" maxlength="250" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="street_name">Street Name</label>
                                        <input type="text" class="form-control" id="street_name" name="street_name" maxlength="250" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="building_type">Building Type</label>
                                        <input type="text" class="form-control" id="building_type" name="building_type" maxlength="250">
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="">Select Status</option>
                                            <option value="occupied">Occupied</option>
                                            <option value="vacant">Vacant</option>
                                            <option value="under construction">Under Construction</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="no_floors">Number of Floors</label>
                                        <input type="number" class="form-control" id="no_floors" name="no_floors" min="1">
                                    </div>
                                    <div class="form-group">
                                        <label for="year_built">Year Built</label>
                                        <input type="date" class="form-control" id="year_built" name="year_built">
                                    </div>
                                    <div class="form-group">
                                        <label for="province_id">Province</label>
                                        <select class="form-control" id="province_id" name="province_id" required>
                                            <option value="">Select Province</option>
                                            <?php foreach (
                                                $provinces as $province
                                            ): ?>
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
                                    <div class="form-group">
                                        <label for="barangay_id">Number of member</label>
                                        <input type="number" id="no_members" class="form-control" value="3" onchange="renderHouseMembers()">
                                    </div>

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
                                <div class="row mt-4" id="house_members_container">
                                    <!-- Dynamic house member cards will be injected here -->
                                </div>
                                <button type="submit" class="btn btn-primary">Register House</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <!-- Leaflet JS and Leaflet Draw -->
            <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
            <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
            <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
            <script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>


        </div><!-- Content Body End -->

        <!-- Footer Section Start -->
        <!-- <div class="footer-section bg-dark text-body mt-auto">
            <div class="container-fluid">
                <div class="footer-copyright text-center">
                    <p class="text-body-light">2022 &copy; <a href="https://themeforest.net/user/codecarnival" class="text-body">Codecarnival</a></p>
                </div>
            </div>
        </div> -->
        <!-- Footer Section End -->

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

    <!-- Move the custom script here, after jQuery is loaded -->
    <script>
        function renderHouseMembers() {
            const container = document.getElementById('house_members_container');
            const numMembers = parseInt(document.getElementById('no_members').value) || 0;
            container.innerHTML = '';
            for (let i = 0; i < numMembers; i++) {
                const memberBox = document.createElement('div');
                memberBox.className = 'col-12 col-lg-6 mb-30';
                memberBox.innerHTML = `
                    <div class="box">
                        <div class="box-head">
                            <h6 class="title">Household Member #${i + 1}</h6>
                        </div>
                        <div class="box-body">
                            <div class="row mbn-20">
                                <div class="col-8 mb-20">
                                    <label for="house_holdmember_${i}">Full Name</label>
                                    <input type="text" id="house_holdmember_${i}" name="house_holdmember[]" class="form-control" placeholder="Enter Full Name" required />
                                </div>
                                <div class="col-4 mb-20">
                                    <label for="suffixes_${i}">Suffix</label>
                                    <input type="text" id="suffixes_${i}" name="suffixes[]" class="form-control" placeholder="e.g. Jr., Sr., III" />
                                </div>
                                <div class="col-12 mb-20">
                                    <label for="ages_${i}">Birth Date</label>
                                    <input type="date" id="ages_${i}" name="ages[]" class="form-control" required />
                                </div>
                                <div class="col-12 mb-20">
                                    <label for="relationships_${i}">Relationship</label>
                                    <input type="text" id="relationships_${i}" name="relationships[]" class="form-control" placeholder="Relationship" required />
                                </div>
                                <div class="col-12 mb-20">
                                    <label for="occupations_${i}">Occupation</label>
                                    <input type="text" id="occupations_${i}" name="occupations[]" class="form-control" placeholder="Occupation" />
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(memberBox);
            }
        }

        // Listen for changes to the number of members
        document.addEventListener('DOMContentLoaded', function() {
            renderHouseMembers();
            document.getElementById('no_members').addEventListener('input', renderHouseMembers);
        });
    </script>


    <script>
        $(document).ready(function() {
            // When province changes, fetch municipalities
            $('#province_id').on('change', function() {
                var provinceId = $(this).val();
                $('#municipal_id').prop('disabled', true).html('<option value="">Select Municipality</option>');
                $('#barangay_id').prop('disabled', true).html('<option value="">Select Barangay</option>');
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
                if (municipalId) {
                    $.get('/handler/barangayofficial/get_barangays.php', {
                        municipal_id: municipalId
                    }, function(data) {
                        var options = '<option value="">Select Barangay</option>';
                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(function(b) {
                                console.log('Barangay data:', b);
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
                console.log('Selected barangay:', selected.text());
                console.log('GeoJSON string:', geojsonStr);

                if (boundaryLayer) {
                    map.removeLayer(boundaryLayer);
                    boundaryLayer = null;
                }

                if (geojsonStr) {
                    try {
                        var geojson = JSON.parse(decodeURIComponent(geojsonStr));
                        console.log('Parsed GeoJSON:', geojson);

                        boundaryLayer = L.geoJSON(geojson, {
                            style: {
                                color: 'red',
                                weight: 2,
                                fillOpacity: 0.1
                            }
                        }).addTo(map);

                        // Fit map to boundary
                        var bounds = boundaryLayer.getBounds();
                        console.log('Boundary bounds:', bounds);

                        // Add a small delay to ensure the layer is properly added
                        setTimeout(function() {
                            map.fitBounds(bounds);
                        }, 100);

                    } catch (e) {
                        console.error('Error parsing GeoJSON:', e);
                        console.error('GeoJSON string was:', geojsonStr);
                    }
                } else {
                    console.log('No GeoJSON data found for selected barangay');
                    // Reset map to default view if no boundary data
                    map.setView([12.8797, 121.7740], 6);
                }
            });
        });
    </script>
</body>

</html>