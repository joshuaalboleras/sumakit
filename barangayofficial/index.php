<?php
include '../configuration/config.php';
include '../configuration/routes.php';
// Fetch all provinces
$provinces = $conn->query("SELECT * FROM provinces ORDER BY province_name ASC")->fetchAll(PDO::FETCH_ASSOC);
// Fetch all municipalities
$municipalities = $conn->query("SELECT * FROM municipalities ORDER BY municipality ASC")->fetchAll(PDO::FETCH_ASSOC);
// Fetch all barangays
$barangays = $conn->query("SELECT * FROM barangays ORDER BY barangay_name ASC")->fetchAll(PDO::FETCH_ASSOC);
// Fetch all houses
$houses = $conn->query("SELECT * FROM houses")->fetchAll(PDO::FETCH_ASSOC);
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
             <?php include '../partials/barangayofficial/side-bar.php';?>
            <!-- Side Header Inner End -->
        </div><!-- Side Header End -->

        <!-- Content Body Start -->
        <div class="content-body">
            <div class="container mt-5">
                <div class="box">
                    <div class="box-header text-white">
                        <h4 class="mb-0">Barangay Map & Houses</h4>
                    </div>
                    <div class="box-body">
                        <div class="row mb-3">
                            <div class="col-md-4 mb-2">
                                <select id="provinceFilter" class="form-control">
                                    <option value="">All Provinces</option>
                                    <?php foreach ($provinces as $prov): ?>
                                        <option value="<?= $prov['id'] ?>"><?= htmlspecialchars($prov['province_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <select id="municipalityFilter" class="form-control">
                                    <option value="">All Municipalities</option>
                                    <?php foreach ($municipalities as $mun): ?>
                                        <option value="<?= $mun['id'] ?>"><?= htmlspecialchars($mun['municipality']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <select id="barangayFilter" class="form-control">
                                    <option value="">All Barangays</option>
                                    <?php foreach ($barangays as $brgy): ?>
                                        <option value="<?= $brgy['id'] ?>"><?= htmlspecialchars($brgy['barangay_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div id="houseMap" style="height: 500px; width: 100%; border: 1px solid #ccc;"></div>
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
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
            <script>
                const allHouses = <?= json_encode($houses) ?>;
                const allBarangays = <?= json_encode($barangays) ?>;
                const allMunicipalities = <?= json_encode($municipalities) ?>;
                const allProvinces = <?= json_encode($provinces) ?>;
                let mapCenter = [12.8797, 121.7740];
                let mapZoom = 6;
                let map = L.map('houseMap').setView(mapCenter, mapZoom);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
                let boundaryLayer = null;
                let houseMarkers = [];

                function filterData() {
                    const provinceId = document.getElementById('provinceFilter').value;
                    const municipalityId = document.getElementById('municipalityFilter').value;
                    const barangayId = document.getElementById('barangayFilter').value;

                    // Filter barangays
                    let filteredBarangays = allBarangays;
                    if (provinceId) filteredBarangays = filteredBarangays.filter(b => b.province_id == provinceId);
                    if (municipalityId) filteredBarangays = filteredBarangays.filter(b => b.municipal_id == municipalityId);
                    if (barangayId) filteredBarangays = filteredBarangays.filter(b => b.id == barangayId);

                    // Filter houses
                    let filteredHouses = allHouses;
                    if (barangayId) {
                        filteredHouses = filteredHouses.filter(h => h.barangay_id == barangayId);
                    } else if (municipalityId) {
                        const brgyIds = filteredBarangays.map(b => b.id);
                        filteredHouses = filteredHouses.filter(h => brgyIds.includes(Number(h.barangay_id)));
                    } else if (provinceId) {
                        const brgyIds = filteredBarangays.map(b => b.id);
                        filteredHouses = filteredHouses.filter(h => brgyIds.includes(Number(h.barangay_id)));
                    }

                    return { filteredBarangays, filteredHouses, barangayId };
                }

                function updateMap() {
                    // Remove old boundary
                    if (boundaryLayer) {
                        map.removeLayer(boundaryLayer);
                        boundaryLayer = null;
                    }
                    // Remove old markers
                    houseMarkers.forEach(m => map.removeLayer(m));
                    houseMarkers = [];

                    const { filteredBarangays, filteredHouses, barangayId } = filterData();

                    // Draw barangay boundary if only one barangay selected
                    let fitBounds = false;
                    if (barangayId && filteredBarangays.length === 1 && filteredBarangays[0].geojson) {
                        try {
                            const geojson = JSON.parse(filteredBarangays[0].geojson);
                            boundaryLayer = L.geoJSON(geojson, {
                                style: { color: '#007bff', weight: 2, fillOpacity: 0.1 }
                            }).addTo(map);
                            fitBounds = true;
                        } catch (e) {}
                    }

                    // Draw house markers
                    let markerGroup = [];
                    filteredHouses.forEach(house => {
                        if (!house.geojson) return;
                        let coords = null;
                        try {
                            const geometry = JSON.parse(house.geojson);
                            if (geometry.type === 'Point') {
                                coords = [geometry.coordinates[1], geometry.coordinates[0]];
                            }
                        } catch (e) {}
                        if (coords) {
                            const marker = L.marker(coords).addTo(map)
                                .bindPopup(`<strong>House #${house.house_number}</strong><br>Street: ${house.street_name}`);
                            houseMarkers.push(marker);
                            markerGroup.push(marker);
                        }
                    });

                    // Fit map
                    if (fitBounds && boundaryLayer) {
                        setTimeout(() => {
                            map.fitBounds(boundaryLayer.getBounds());
                        }, 200);
                    } else if (markerGroup.length > 0) {
                        const group = L.featureGroup(markerGroup);
                        setTimeout(() => {
                            map.fitBounds(group.getBounds(), { maxZoom: 16 });
                        }, 200);
                    } else {
                        map.setView(mapCenter, mapZoom);
                    }
                }

                // Filter dropdown logic
                document.getElementById('provinceFilter').addEventListener('change', function() {
                    const provinceId = this.value;
                    // Update municipalities
                    const munSel = document.getElementById('municipalityFilter');
                    munSel.innerHTML = '<option value="">All Municipalities</option>';
                    allMunicipalities.filter(m => !provinceId || m.province_id == provinceId).forEach(m => {
                        const opt = document.createElement('option');
                        opt.value = m.id;
                        opt.textContent = m.municipality;
                        munSel.appendChild(opt);
                    });
                    // Update barangays
                    const brgySel = document.getElementById('barangayFilter');
                    brgySel.innerHTML = '<option value="">All Barangays</option>';
                    allBarangays.filter(b => !provinceId || b.province_id == provinceId).forEach(b => {
                        const opt = document.createElement('option');
                        opt.value = b.id;
                        opt.textContent = b.barangay_name;
                        brgySel.appendChild(opt);
                    });
                    updateMap();
                });
                document.getElementById('municipalityFilter').addEventListener('change', function() {
                    const municipalityId = this.value;
                    const provinceId = document.getElementById('provinceFilter').value;
                    // Update barangays
                    const brgySel = document.getElementById('barangayFilter');
                    brgySel.innerHTML = '<option value="">All Barangays</option>';
                    allBarangays.filter(b => (!provinceId || b.province_id == provinceId) && (!municipalityId || b.municipal_id == municipalityId)).forEach(b => {
                        const opt = document.createElement('option');
                        opt.value = b.id;
                        opt.textContent = b.barangay_name;
                        brgySel.appendChild(opt);
                    });
                    updateMap();
                });
                document.getElementById('barangayFilter').addEventListener('change', function() {
                    updateMap();
                });
                // Initial map render
                updateMap();
            </script>

</body>

</html>