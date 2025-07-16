<?php
include '../configuration/config.php';
include '../configuration/routes.php';

// Fetch all provinces
$provinceStmt = $conn->prepare("SELECT * FROM provinces");
$provinceStmt->execute();
$provinces = $provinceStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all municipalities with province names (for map viewer)
$municipalityStmt = $conn->prepare("
    SELECT m.id, m.municipality, m.geojson, p.province_name 
    FROM municipalities m
    JOIN provinces p ON m.province_id = p.id
");
$municipalityStmt->execute();
$municipalities = $municipalityStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all barangays with their province and municipality names
$barangays = $conn->query("
    SELECT b.*, p.province_name, m.municipality
    FROM barangays b
    JOIN provinces p ON b.province_id = p.id
    JOIN municipalities m ON b.municipal_id = m.id
")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all municipalities for filter dropdown (for barangay filter)
$municipalitiesDropdown = $conn->query("SELECT * FROM municipalities")->fetchAll(PDO::FETCH_ASSOC);
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
                    <?php include '../partials/shared/top-nav.php' ?>
                    <!-- Header Right End -->

                </div>
            </div>
        </div>
        <!-- Header Section End -->
        <!-- Side Header Start -->
        <div class="side-header show">
            <button class="side-header-close"><i class="zmdi zmdi-close"></i></button>
            <!-- Side Header Inner Start -->
            <?= include '../partials/superadmin/nav.php' ?>
            <!-- Side Header Inner End -->
        </div>

        <!-- Side Header End -->

        <!-- Content Body Start -->
        <div class="content-body">

            <!-- Page Headings Start -->
            <div class="row justify-content-between align-items-center mb-10">

                <!-- Page Heading Start -->
                <div class="col-12 col-lg-auto mb-20">
                    <div class="page-heading">
                        <h3>Dashboard <span>/ eCommerce</span></h3>
                    </div>
                </div><!-- Page Heading End -->

                <!-- Page Button Group Start -->
                <div class="col-12 col-lg-auto mb-20">
                    <div class="page-date-range">
                        <input type="text" class="form-control input-date-predefined">
                    </div>
                </div><!-- Page Button Group End -->

            </div><!-- Page Headings End -->

            <!-- Top Report Wrap Start -->
            <div class="row">
                <!-- Top Report Start -->
                <div class="col-xlg-3 col-md-6 col-12 mb-30">
                    <div class="top-report">

                        <!-- Head -->
                        <div class="head">
                            <h4>Total Visitor</h4>
                            <a href="#" class="view"><i class="zmdi zmdi-eye"></i></a>
                        </div>

                        <!-- Content -->
                        <div class="content">
                            <h5>Todays</h5>
                            <h2>100,560.00</h2>
                        </div>

                        <!-- Footer -->
                        <div class="footer">
                            <div class="progess">
                                <div class="progess-bar" style="width: 92%;"></div>
                            </div>
                            <p>92% of unique visitor</p>
                        </div>

                    </div>
                </div><!-- Top Report End -->

                <!-- Top Report Start -->
                <div class="col-xlg-3 col-md-6 col-12 mb-30">
                    <div class="top-report">

                        <!-- Head -->
                        <div class="head">
                            <h4>Product Sold</h4>
                            <a href="#" class="view"><i class="zmdi zmdi-eye"></i></a>
                        </div>

                        <!-- Content -->
                        <div class="content">
                            <h5>Todays</h5>
                            <h2>85,000.00</h2>
                        </div>

                        <!-- Footer -->
                        <div class="footer">
                            <div class="progess">
                                <div class="progess-bar" style="width: 98%;"></div>
                            </div>
                            <p>98% of unique visitor</p>
                        </div>

                    </div>
                </div><!-- Top Report End -->

                <!-- Top Report Start -->
                <div class="col-xlg-3 col-md-6 col-12 mb-30">
                    <div class="top-report">

                        <!-- Head -->
                        <div class="head">
                            <h4>Order Received</h4>
                            <a href="#" class="view"><i class="zmdi zmdi-eye"></i></a>
                        </div>

                        <!-- Content -->
                        <div class="content">
                            <h5>Todays</h5>
                            <h2>5,000.00</h2>
                        </div>

                        <!-- Footer -->
                        <div class="footer">
                            <div class="progess">
                                <div class="progess-bar" style="width: 88%;"></div>
                            </div>
                            <p>88% of unique visitor</p>
                        </div>

                    </div>
                </div><!-- Top Report End -->

                <!-- Top Report Start -->
                <div class="col-xlg-3 col-md-6 col-12 mb-30">
                    <div class="top-report">

                        <!-- Head -->
                        <div class="head">
                            <h4>Total Revenue</h4>
                            <a href="#" class="view"><i class="zmdi zmdi-eye"></i></a>
                        </div>

                        <!-- Content -->
                        <div class="content">
                            <h5>Todays</h5>
                            <h2>3,000,000.00</h2>
                        </div>

                        <!-- Footer -->
                        <div class="footer">
                            <div class="progess">
                                <div class="progess-bar" style="width: 76%;"></div>
                            </div>
                            <p>76% of unique visitor</p>
                        </div>

                    </div>
                </div><!-- Top Report End -->
            </div><!-- Top Report Wrap End -->

            <div class="row mbn-30">

                <!-- Revenue Statistics Chart Start -->
                <div class="col-md-8 mb-30">
                    <div class="box">
                        <div class="box-head">
                            <h4 class="title">Revenue Statistics</h4>
                        </div>
                        <div class="box-body">
                            <div class="chart-legends-1 row">
                                <div class="chart-legend-1 col-12 col-sm-4">
                                    <h5 class="title">Total Sale</h5>
                                    <h3 class="value text-secondary">$5000,000</h3>
                                </div>
                                <div class="chart-legend-1 col-12 col-sm-4">
                                    <h5 class="title">Total View</h5>
                                    <h3 class="value text-primary">10000,000</h3>
                                </div>
                                <div class="chart-legend-1 col-12 col-sm-4">
                                    <h5 class="title">Total Support</h5>
                                    <h3 class="value text-warning">100,000</h3>
                                </div>
                            </div>
                            <div class="chartjs-revenue-statistics-chart">
                                <canvas id="chartjs-revenue-statistics-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div><!-- Revenue Statistics Chart End -->

                <!-- Market Trends Chart Start -->
                <div class="col-md-4 mb-30">
                    <div class="box">
                        <div class="box-head">
                            <h4 class="title">Market Trends</h4>
                        </div>
                        <div class="box-body">
                            <div class="chartjs-market-trends-chart">
                                <canvas id="chartjs-market-trends-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div><!-- Market Trends Chart End -->

                <!-- Recent Transaction Start -->
                <div class="col-12 mb-30">
                    <div class="box">
                        <div class="box-head">
                            <h4 class="title">Recent Transaction</h4>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-vertical-middle table-selectable">

                                    <!-- Table Head Start -->
                                    <thead>
                                        <tr>
                                            <th class="selector"><label class="adomx-checkbox"><input type="checkbox"> <i class="icon"></i></label></th>
                                            <!--<th class="selector h5"><button class="button-check"></button></th>-->
                                            <th><span>Image</span></th>
                                            <th><span>Product Name</span></th>
                                            <th><span>ID</span></th>
                                            <th><span>Quantity</span></th>
                                            <th><span>Price</span></th>
                                            <th><span>Status</span></th>
                                            <th></th>
                                        </tr>
                                    </thead><!-- Table Head End -->

                                    <!-- Table Body Start -->
                                    <tbody>
                                        <tr>
                                            <td class="selector"><label class="adomx-checkbox"><input type="checkbox"> <i class="icon"></i></label></td>
                                            <td><img src="assets/images/product/list-product-1.jpg" alt="" class="table-product-image rounded-circle"></td>
                                            <td><a href="#">Microsoft surface pro 4</a></td>
                                            <td>#MSP40022</td>
                                            <td>05 - Products</td>
                                            <td>$60000000.00</td>
                                            <td><span class="badge badge-success">Paid</span></td>
                                            <td><a class="h3" href="#"><i class="zmdi zmdi-more"></i></a></td>
                                        </tr>
                                        <tr class="selected">
                                            <td class="selector"><label class="adomx-checkbox"><input type="checkbox"> <i class="icon"></i></label></td>
                                            <td><img src="assets/images/product/list-product-2.jpg" alt="" class="table-product-image rounded-circle"></td>
                                            <td><a href="#">Microsoft surface pro 4</a></td>
                                            <td>#MSP40022</td>
                                            <td>05 - Products</td>
                                            <td>$60000000.00</td>
                                            <td><span class="badge badge-success">Paid</span></td>
                                            <td><a class="h3" href="#"><i class="zmdi zmdi-more"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td class="selector"><label class="adomx-checkbox"><input type="checkbox"> <i class="icon"></i></label></td>
                                            <td><img src="assets/images/product/list-product-3.jpg" alt="" class="table-product-image rounded-circle"></td>
                                            <td><a href="#">Microsoft surface pro 4</a></td>
                                            <td>#MSP40022</td>
                                            <td>05 - Products</td>
                                            <td>$60000000.00</td>
                                            <td><span class="badge badge-warning">Due</span></td>
                                            <td><a class="h3" href="#"><i class="zmdi zmdi-more"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td class="selector"><label class="adomx-checkbox"><input type="checkbox"> <i class="icon"></i></label></td>
                                            <td><img src="assets/images/product/list-product-4.jpg" alt="" class="table-product-image rounded-circle"></td>
                                            <td><a href="#">Microsoft surface pro 4</a></td>
                                            <td>#MSP40022</td>
                                            <td>05 - Products</td>
                                            <td>$60000000.00</td>
                                            <td><span class="badge badge-danger">Reject</span></td>
                                            <td><a class="h3" href="#"><i class="zmdi zmdi-more"></i></a></td>
                                        </tr>
                                    </tbody><!-- Table Body End -->

                                </table>
                            </div>
                        </div>
                    </div>
                </div><!-- Recent Transaction End -->
            </div>


            <!-- MUNICIPALITIES MAP VIEWER START -->
            <div class="row mbn-30 p-5">
                <div class="col-12">
                    <h1>Welcome Super Admin</h1>
                    <p>Search and explore saved municipalities on the map below.</p>
                    <div class="filters" style="display: flex; gap: 10px; margin-bottom: 10px;">
                        <input type="text" id="search" placeholder="Search municipality name..." class="form-control" />
                        <select id="provinceFilter" class="form-control">
                            <option value="">All Provinces</option>
                            <?php foreach (
                                $provinces as $prov
                            ): ?>
                                <option value="<?= htmlspecialchars($prov['province_name']) ?>">
                                    <?= htmlspecialchars($prov['province_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div id="map" style="height: 500px; width: 100%; border: 1px solid #ccc;"></div>
                </div>
            </div>
            <!-- MUNICIPALITIES MAP VIEWER END -->
            <!-- BARANGAY MAP VIEWER START -->
            <div class="row mbn-30 p-5">
                <div class="col-12">
                    <h2>Barangay Map Viewer</h2>
                    <p>Search and explore saved barangays on the map below.</p>
                    <div class="filters" style="display: flex; gap: 10px; margin-bottom: 10px;">
                        <input type="text" id="barangaySearch" placeholder="Search barangay name..." class="form-control" />
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
                            <?php foreach ($municipalitiesDropdown as $mun): ?>
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
        const map = L.map('map').setView([12.8797, 121.7740], 6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const municipalities = <?= json_encode($municipalities) ?>;
        const layers = [];

        // Render each municipality
        municipalities.forEach(item => {
            if (item.geojson) {
                const geojson = JSON.parse(item.geojson);
                const layer = L.geoJSON(geojson).addTo(map);
                const popupContent = `<strong>${item.municipality}</strong><br>Province: ${item.province_name}`;
                layer.bindPopup(popupContent);

                // Attach searchable/filterable data
                layer.customData = {
                    municipality: item.municipality.toLowerCase(),
                    province: item.province_name
                };

                layers.push(layer);
            }
        });

        const searchInput = document.getElementById('search');
        const provinceSelect = document.getElementById('provinceFilter');

        function filterMap() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedProvince = provinceSelect.value;
            const visible = [];

            layers.forEach(layer => {
                const data = layer.customData;
                const matchesSearch = data.municipality.includes(searchTerm);
                const matchesProvince = !selectedProvince || data.province === selectedProvince;

                if (matchesSearch && matchesProvince) {
                    if (!map.hasLayer(layer)) map.addLayer(layer);
                    visible.push(layer);
                } else {
                    if (map.hasLayer(layer)) map.removeLayer(layer);
                }
            });

            // Zoom to filtered results
            if (visible.length > 0) {
                const group = L.featureGroup(visible);
                map.fitBounds(group.getBounds());
            }
        }

        searchInput.addEventListener('input', filterMap);
        provinceSelect.addEventListener('change', filterMap);

        // Initial fit to all
        setTimeout(() => {
            const group = L.featureGroup(layers);
            if (group.getLayers().length > 0) {
                map.fitBounds(group.getBounds());
            }
        }, 300);
    </script>
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

                try {
                    const geojson = JSON.parse(b.geojson);
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