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
    <!-- Leaflet CSS for Map Viewer -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />

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

                      <div style="display:flex; flex-wrap:wrap; gap:24px; align-items:stretch;">
            <form action="../handler/superadmin/register_barangay.php" method="post" class="standard-form" onsubmit="return validateGeoJSONBarangay();" style="flex:1 1 350px; min-width:320px; max-width:500px;">
              <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
              <div class="mb-3">
                <label for="province_id" class="form-label">Province</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                  <select name="province_id" id="province_id" required class="form-select bg-light text-dark">
                    <option value="">Select Province</option>
                    <?php
                    $stmt = $conn->query("SELECT * FROM provinces");
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $stmt->execute();
                    $res = $stmt->fetchAll();
                    foreach ($res as $province) {
                      echo <<<HTML
                              <option value="{$province['id']}">{$province['province_name']}</option>
                          HTML;
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="mb-3">
                <label for="municipal_id" class="form-label">Municipality</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa fa-landmark"></i></span>
                  <select name="municipal_id" id="municipal_id" class="form-control bg-light text-dark" required disabled>
                    <option value="">Select Municipality</option>
                  </select>
                </div>
              </div>
              <div class="mb-3">
                <label for="barangay" class="form-label">Barangay</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa fa-landmark"></i></span>
                  <input type="text" name="barangay" placeholder="Enter barangay" required class="form-control bg-light text-dark">
                </div>
              </div>
              <input type="hidden" name="geojson" id="geojson_barangay">
              <div class="mt-4 text-end">
                <button type="submit" class="btn btn-info"><i class="fa fa-location-arrow"></i> Submit</button>
              </div>
            </form>
            <!-- MAP OUTSIDE FORM -->
            <div style="flex:1 1 350px; min-width:320px; max-width:600px; display:flex; flex-direction:column;">
              <label style="display: block; font-weight: bold; margin: 10px 0 5px;">Draw Barangay Boundary:</label>
              <div id="map_barangay" style="width:100%; height:400px; border:1px solid #ccc; border-radius:8px;"></div>
              <div class="text-muted" style="font-size: 0.95em;">Use the polygon tool to draw the boundary of the barangay. Only one polygon is allowed.</div>
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
      <!-- Leaflet JS for Map Viewer -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

     <script>
      // Barangay Map
      const mapBarangay = L.map('map_barangay').setView([10.3157, 123.8854], 13);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
      }).addTo(mapBarangay);
      const drawnItemsBarangay = new L.FeatureGroup();
      mapBarangay.addLayer(drawnItemsBarangay);
      const drawControlBarangay = new L.Control.Draw({
        edit: { featureGroup: drawnItemsBarangay },
        draw: {
          polygon: true,
          polyline: false,
          rectangle: false,
          circle: false,
          marker: false,
          circlemarker: false
        }
      });
      mapBarangay.addControl(drawControlBarangay);
      mapBarangay.on(L.Draw.Event.CREATED, function(e) {
        drawnItemsBarangay.clearLayers();
        const layer = e.layer;
        drawnItemsBarangay.addLayer(layer);
        const geojson = JSON.stringify(layer.toGeoJSON());
        document.getElementById('geojson_barangay').value = geojson;
      });
      function validateGeoJSONBarangay() {
        const geojson = document.getElementById('geojson_barangay').value;
        if (!geojson) {
          alert("Please draw a polygon on the map.");
          return false;
        }
        return true;
      }
      setTimeout(() => {
        mapBarangay.invalidateSize();
      }, 200);
    </script>

    <!-- Move the custom script here, after jQuery is loaded -->
    <script>
    $(document).ready(function() {
        // When province changes, fetch municipalities
        $('#province_id').on('change', function() {
            var provinceId = $(this).val();
            $('#municipal_id').prop('disabled', true).html('<option value="">Select Municipality</option>');
            if (provinceId) {
                $.get('/handler/barangayofficial/get_municipalities.php', { province_id: provinceId }, function(data) {
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
    });
    </script>

</body>

</html>