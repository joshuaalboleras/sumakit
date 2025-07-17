<?php
include '../configuration/config.php';
include '../configuration/routes.php';
// Fetch all barangays with their province and municipality names
$barangays = $conn->query("
    SELECT b.*, p.province_name, m.municipality
    FROM barangays b
    JOIN provinces p ON b.province_id = p.id
    JOIN municipalities m ON b.municipal_id = m.id
")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all provinces for filter dropdown
$provinces = $conn->query("SELECT * FROM provinces")->fetchAll(PDO::FETCH_ASSOC);
// Fetch all municipalities for filter dropdown
$municipalities = $conn->query("SELECT * FROM municipalities")->fetchAll(PDO::FETCH_ASSOC);
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
        <?= include '../partials/municipalofficial/side-bar.php'?>
        <!-- Side Header End -->

        <!-- Content Body Start -->
        <div class="content-body">

         <!-- BARANGAY MAP VIEWER START -->
            <div class="row mbn-30 p-5">
                <div class="col-12">
                    <h2>Barangay Map Viewer</h2>
                    <p>Search and explore saved barangays on the map below.</p>
                    <div class="filters" style="display: flex; gap: 10px; margin-bottom: 10px;">
                        <input type="text" id="barangaySearch" placeholder="Search barangay name..." class="form-control"/>
                        <select id="barangayProvinceFilter" class="form-control">
                            <option value="">All Provinces</option>
                            <?php foreach ($provinces as $prov): ?>
                                <option value="<?= htmlspecialchars($prov['province_name']) ?>">
                                    <?= htmlspecialchars($prov['province_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select id="municipalityFilter" class="form-control">
                            <option value="">All Municipalities</option>
                            <?php foreach ($municipalities as $mun): ?>
                                <option value="<?= htmlspecialchars($mun['municipality']) ?>">
                                    <?= htmlspecialchars($mun['municipality']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div id="barangayMap" style="height: 500px; width: 100%; border: 1px solid #ccc;"></div>
                </div>
            </div>
            <!-- BARANGAY MAP VIEWER END -->


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
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        const barangays = <?= json_encode($barangays) ?>;

        const barangayMap = L.map('barangayMap').setView([12.8797, 121.7740], 6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(barangayMap);

        let barangayLayers = [];
        let barangayLayerMap = {}; // Map barangay_name (lowercase) to {layer, bounds}

        function renderBarangays(filter = {}) {
            // Remove old layers
            barangayLayers.forEach(layer => barangayMap.removeLayer(layer));
            barangayLayers = [];
            barangayLayerMap = {};

            barangays.forEach(b => {
                // Filtering
                if (filter.province && b.province_name !== filter.province) return;
                if (filter.municipality && b.municipality !== filter.municipality) return;
                if (filter.search && !b.barangay_name.toLowerCase().includes(filter.search.toLowerCase())) return;

                // Skip if geojson is empty or invalid
                if (!b.geojson || b.geojson.trim() === "") return;
                let geojson;
                try {
                    geojson = JSON.parse(b.geojson);
                } catch (e) {
                    return; // Invalid geojson, skip
                }
                try {
                    const layer = L.geoJSON(geojson).bindPopup(
                        `<strong>${b.barangay_name}</strong><br>
                 Municipality: ${b.municipality}<br>
                 Province: ${b.province_name}`
                    );
                    layer.addTo(barangayMap);
                    barangayLayers.push(layer);

                    // Store for search/pan
                    const bounds = layer.getBounds ? layer.getBounds() : null;
                    barangayLayerMap[b.barangay_name.toLowerCase()] = {
                        layer,
                        bounds
                    };
                } catch (e) {
                    // Invalid geojson, skip
                }
            });
        }

        // Initial render
        renderBarangays();

        // Filtering logic

        document.getElementById('barangayProvinceFilter').addEventListener('change', function() {
            const province = this.value;
            // Update municipality filter options
            const municipalitySelect = document.getElementById('municipalityFilter');
            municipalitySelect.innerHTML = '<option value="">All Municipalities</option>';
            const filteredMunicipalities = barangays
                .filter(b => !province || b.province_name === province)
                .map(b => b.municipality)
                .filter((v, i, a) => a.indexOf(v) === i); // unique
            filteredMunicipalities.forEach(mun => {
                const opt = document.createElement('option');
                opt.value = mun;
                opt.textContent = mun;
                municipalitySelect.appendChild(opt);
            });
            renderBarangays({
                province,
                municipality: municipalitySelect.value,
                search: document.getElementById('barangaySearch').value
            });
        });

        document.getElementById('municipalityFilter').addEventListener('change', function() {
            renderBarangays({
                province: document.getElementById('barangayProvinceFilter').value,
                municipality: this.value,
                search: document.getElementById('barangaySearch').value
            });

            // Pan/zoom to municipality bounds
            const selectedMunicipality = this.value;
            if (selectedMunicipality) {
                // Collect bounds of all barangays in this municipality
                let bounds = null;
                barangays.forEach(b => {
                    if (b.municipality === selectedMunicipality && b.geojson && b.geojson.trim() !== "") {
                        try {
                            const geojson = JSON.parse(b.geojson);
                            const layer = L.geoJSON(geojson);
                            const layerBounds = layer.getBounds();
                            if (layerBounds.isValid()) {
                                if (!bounds) {
                                    bounds = layerBounds;
                                } else {
                                    bounds.extend(layerBounds);
                                }
                            }
                        } catch (e) {}
                    }
                });
                if (bounds && bounds.isValid()) {
                    barangayMap.fitBounds(bounds, { maxZoom: 14 });
                }
            }
        });

        document.getElementById('barangaySearch').addEventListener('input', function() {
            const searchValue = this.value.trim().toLowerCase();
            renderBarangays({
                province: document.getElementById('barangayProvinceFilter').value,
                municipality: document.getElementById('municipalityFilter').value,
                search: searchValue
            });

            // Pan to exact match if found
            if (searchValue && barangayLayerMap[searchValue]) {
                const {
                    layer,
                    bounds
                } = barangayLayerMap[searchValue];
                if (bounds && bounds.isValid()) {
                    barangayMap.fitBounds(bounds, {
                        maxZoom: 16
                    });
                    // Open popup (if available)
                    if (layer.getLayers && layer.getLayers().length > 0) {
                        layer.getLayers()[0].openPopup();
                    }
                }
            }
        });
    </script>

</body>

</html>