<?php
include '../configuration/config.php';
include '../configuration/routes.php';
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Superadmin Registration</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/favicon.ico">

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
    <link id="cus-style" rel="stylesheet" href="../assets/css/style-primary.css">
    <!-- Leaflet CSS for Map Viewer -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <!-- No custom CSS -->
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
                        <?php include '../partials/shared/top-nav.php'?>
                    <!-- Header Right End -->

                </div>
            </div>
        </div>
        <!-- Side Header Start -->
        <div class="side-header show">
            <button class="side-header-close"><i class="zmdi zmdi-close"></i></button>
            <!-- Side Header Inner Start -->
                <?php include '../partials/superadmin/nav.php'?>
            <!-- Side Header Inner End -->
        </div><!-- Side Header End -->
        <!-- Content Body Start -->
        <div class="content-body">
        <?php
        if (isset($_SESSION['errors'])) {
          foreach ($_SESSION['errors'] as $key => $value) {
            $$key = $_SESSION['errors'][$key][0];
          }
   
        }
        ?>
        <div class="container mt-5">
          <!-- USER REGISTRATION FORM -->
          <div class="box mb-4">
            <div class="box-head">
              <h4 class="title">User Registration</h4>
            </div>
            <div class="box-body">
              <form action="../handler/superadmin/register.php" method="post" class="standard-form">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="row mbn-20">
                  <div class="col-12 mb-20">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="<?= $name ?? 'Enter name'?>" autocomplete="off" class="form-control <?php onerror('name','danger','') ?>">
                  </div>
                  <div class="col-12 mb-20">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" placeholder="<?= $email ?? 'Enter Email'?>" autocomplete="off" class="form-control <?= onerror('email','danger')?>">
                  </div>
                  <div class="col-12 mb-20">
                    <label for="password">Password</label>
                    <input type="text" id="password" name="password" placeholder="<?= $password ?? 'Enter Password '?>" autocomplete="off" class="form-control <?= onerror('password','danger')?>">
                  </div>
                  <div class="col-12 mb-20">
                    <label for="role">Role</label>
                    <select name="role_id" id="role" class="form-control">
                      <?php 
                        $stmt = $conn->query("SELECT * FROM roles");
                        $stmt->execute();
                        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach($roles as $role){
                          echo <<<HTML
                            <option value='{$role["id"]}'>{$role['role_name']}</option>
                          HTML;
                        }
                      ?>
                    </select>
                  </div>
                  <div class="col-12 mb-20">
                    <input type="submit" value="Submit" class="button button-primary">
                  </div>
                </div>
              </form>
            </div>
          </div>
          <!-- PROVINCE REGISTRATION FORM -->
          <div class="box mb-4">
            <div class="box-head">
              <h4 class="title">Province Registration</h4>
            </div>
            <div class="box-body">
              <form action="../handler/superadmin/register_province.php" method="post" class="standard-form">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="row g-3 align-items-end">
                  <div class="col-md-8">
                    <label for="province" class="form-label">Province</label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
                      <input type="text" name="province_name" placeholder="<?php echo $province_name ?? 'Enter Province Name'?>" class="form-control <?= onerror('province_name','danger')?>">
                    </div>
                  </div>
                  <div class="col-md-4 text-end">
                    <button class="btn btn-success w-100"><i class="fa fa-plus"></i> Submit</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <!-- MUNICIPALITY REGISTRATION -->
          <div class="box mb-4">
            <div class="box-head">
              <h4 class="title">Municipality Registration</h4>
            </div>
            <div class="box-body">
              <div class="row g-4 align-items-stretch">
                <div class="col-12 col-lg-6 d-flex">
                  <form action="../handler/superadmin/register_municipality.php" method="post" class="standard-form flex-fill" onsubmit="return validateGeoJSON();">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div class="mb-3">
                      <label for="province_id" class="form-label">Province</label>
                      <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                        <select name="province_id" id="province_id" required class="form-control">
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
                      <label for="municipality" class="form-label">Municipality</label>
                      <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-landmark"></i></span>
                        <input type="text" name="municipality" placeholder="Enter Municipality" required class="form-control">
                      </div>
                    </div>
                    <input type="hidden" name="geojson" id="geojson">
                    <div class="mt-4 text-end">
                      <button type="submit" class="btn btn-info"><i class="fa fa-location-arrow"></i> Submit</button>
                    </div>
                  </form>
                </div>
                <div class="col-12 col-lg-6 d-flex flex-column">
                  <label class="fw-bold mb-2">Draw Municipality Boundary:</label>
                  <div id="map" style="width:100%; height:400px; border:1px solid #ccc; border-radius:8px;"></div>
                  <div class="text-muted mt-2" style="font-size: 0.95em;">Use the polygon tool to draw the boundary of the municipality. Only one polygon is allowed.</div>
                </div>
              </div>
            </div>
          </div>
          <!-- BARANGAY REGISTRATION -->
          <div class="box mb-4">
            <div class="box-head">
              <h4 class="title">Barangay Registration</h4>
            </div>
            <div class="box-body">
              <div class="row g-4 align-items-stretch">
                <div class="col-12 col-lg-6 d-flex">
                  <form action="../handler/superadmin/register_barangay.php" method="post" class="standard-form flex-fill" onsubmit="return validateGeoJSONBarangay();">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div class="mb-3">
                      <label for="province_id" class="form-label">Province</label>
                      <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                        <select name="province_id" id="province_id_barangay" required class="form-control">
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
                      <label for="municipal_id_barangay" class="form-label">Municipality</label>
                      <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-landmark"></i></span>
                        <select name="municipal_id" id="municipal_id_barangay" class="form-control" required disabled>
                          <option value="">Select Municipality</option>
                        </select>
                      </div>
                    </div>
                    <div class="mb-3">
                      <label for="barangay" class="form-label">Barangay</label>
                      <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-landmark"></i></span>
                        <input type="text" name="barangay" placeholder="Enter barangay" required class="form-control">
                      </div>
                    </div>
                    <input type="hidden" name="geojson" id="geojson_barangay">
                    <div class="mt-4 text-end">
                      <button type="submit" class="btn btn-warning text-dark"><i class="fa fa-location-arrow"></i> Submit</button>
                    </div>
                  </form>
                </div>
                <div class="col-12 col-lg-6 d-flex flex-column">
                  <label class="fw-bold mb-2">Draw Barangay Boundary:</label>
                  <div id="map_barangay" style="width:100%; height:400px; border:1px solid #ccc; border-radius:8px;"></div>
                  <div class="text-muted mt-2" style="font-size: 0.95em;">Use the polygon tool to draw the boundary of the barangay. Only one polygon is allowed.</div>
                </div>
              </div>
            </div>
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
    <!-- Global Vendor, plugins & Activation JS -->
    <script src="../assets/js/vendor/modernizr-3.6.0.min.js"></script>
    <script src="../assets/js/vendor/jquery-3.3.1.min.js"></script>
    <script src="../assets/js/vendor/popper.min.js"></script>
    <script src="../assets/js/vendor/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/tippy4.min.js.js"></script>
    <script src="../assets/js/main.js"></script>
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
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script>
      const map = L.map('map').setView([10.3157, 123.8854], 13);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
      }).addTo(map);
      const drawnItems = new L.FeatureGroup();
      map.addLayer(drawnItems);
      const drawControl = new L.Control.Draw({
        edit: {
          featureGroup: drawnItems
        },
        draw: {
          polygon: true,
          polyline: false,
          rectangle: false,
          circle: false,
          marker: false,
          circlemarker: false
        }
      });
      map.addControl(drawControl);
      map.on(L.Draw.Event.CREATED, function(e) {
        drawnItems.clearLayers(); // only one polygon
        const layer = e.layer;
        drawnItems.addLayer(layer);
        const geojson = JSON.stringify(layer.toGeoJSON());
        document.getElementById('geojson').value = geojson;
      });
      function validateGeoJSON() {
        const geojson = document.getElementById('geojson').value;
        if (!geojson) {
          alert("Please draw a polygon on the map.");
          return false;
        }
        return true;
      }
      setTimeout(() => {
        map.invalidateSize();
      }, 200);

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
    <script>
$(document).ready(function() {
    // Cascading for Barangay Registration
    $('#province_id_barangay').on('change', function() {
        var provinceId = $(this).val();
        $('#municipal_id_barangay').prop('disabled', true).html('<option value="">Select Municipality</option>');
        if (provinceId) {
            $.get('/handler/barangayofficial/get_municipalities.php', { province_id: provinceId }, function(data) {
                var options = '<option value="">Select Municipality</option>';
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(function(m) {
                        options += '<option value="' + m.id + '">' + m.municipality + '</option>';
                    });
                    $('#municipal_id_barangay').html(options).prop('disabled', false);
                } else {
                    options += '<option value="" disabled>No municipalities found</option>';
                    $('#municipal_id_barangay').html(options).prop('disabled', false);
                }
            }, 'json');
        }
    });
});
</script>
</body>
</html>
<?php unset($_SESSION['errors'])?>