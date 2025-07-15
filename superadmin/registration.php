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
                    <div class="header-logo col-auto">
                        <a href="index.php">
                            <img src="../assets/images/logo/logo.png" alt="">
                            <img src="../assets/images/logo/logo-light.png" class="logo-light" alt="">
                        </a>
                    </div><!-- Header Logo (Header Left) End -->
                    <div class="header-right flex-grow-1 col-auto">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto">
                                <div class="row align-items-center">
                                    <div class="col-auto"><button class="side-header-toggle"><i class="zmdi zmdi-menu"></i></button></div>
                                    <div class="col-auto">
                                        <div class="header-search">
                                            <button class="header-search-open d-block d-xl-none"><i class="zmdi zmdi-search"></i></button>
                                            <div class="header-search-form">
                                                <form action="#">
                                                    <input type="text" placeholder="Search Here">
                                                    <button><i class="zmdi zmdi-search"></i></button>
                                                </form>
                                                <button class="header-search-close d-block d-xl-none"><i class="zmdi zmdi-close"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- Side Header Toggle & Search End -->
                            <div class="col-auto">
                                <ul class="header-notification-area">
                                    <li class="adomx-dropdown position-relative col-auto">
                                        <a class="toggle" href="#"><img class="lang-flag" src="../assets/images/flags/flag-1.jpg" alt=""><i class="zmdi zmdi-caret-down drop-arrow"></i></a>
                                        <ul class="adomx-dropdown-menu dropdown-menu-language">
                                            <li><a href="#"><img src="../assets/images/flags/flag-1.jpg" alt=""> English</a></li>
                                            <li><a href="#"><img src="../assets/images/flags/flag-2.jpg" alt=""> Japanese</a></li>
                                            <li><a href="#"><img src="../assets/images/flags/flag-3.jpg" alt=""> Spanish </a></li>
                                            <li><a href="#"><img src="../assets/images/flags/flag-4.jpg" alt=""> Germany</a></li>
                                        </ul>
                                    </li>
                                    <li class="adomx-dropdown col-auto">
                                        <a class="toggle" href="#"><i class="zmdi zmdi-email-open"></i><span class="badge"></span></a>
                                    </li>
                                    <li class="adomx-dropdown col-auto">
                                        <a class="toggle" href="#"><i class="zmdi zmdi-notifications"></i><span class="badge"></span></a>
                                    </li>
                                    <li class="adomx-dropdown col-auto">
                                        <a class="toggle" href="#">
                                            <span class="user">
                                                <span class="avatar">
                                                    <img src="../assets/images/avatar/avatar-1.jpg" alt="">
                                                    <span class="status"></span>
                                                </span>
                                                <span class="name">Superadmin</span>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div><!-- Header Right End -->
                </div>
            </div>
        </div><!-- Header Section End -->
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
        <!-- USER REGISTRATION FORM -->
        <form action="../handler/superadmin/register.php" method="post" class="standard-form">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
          <div class="row g-3">
            <div class="col-md-3">
              <label for="name" class="form-label">Name</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
                <input type="text" id="name" name="name" placeholder="<?= $name ?? 'Enter name'?>" autocomplete="off" class="form-control bg-light text-dark <?php onerror('name','danger','')?>">
              </div>
            </div>
            <div class="col-md-3">
              <label for="email" class="form-label">Email</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                <input type="text" id="email" name="email" placeholder="<?= $email ?? 'Enter Email'?>" autocomplete="off" class="form-control bg-light text-dark <?= onerror('email','danger')?>">
              </div>
            </div>
            <div class="col-md-3">
              <label for="password" class="form-label">Password</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                <input type="text" id="password" name="password" placeholder="<?= $password ?? 'Enter Password '?>" autocomplete="off" class="form-control bg-light text-dark <?= onerror('password','danger')?>">
              </div>
            </div>
            <div class="col-md-3">
              <label for="role" class="form-label">Role</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa fa-user-tag"></i></span>
                <input type="text" id="role" name="role_id" placeholder="<?= isset($role) ? $role : 'Select Role' ?>" autocomplete="off" class="form-control bg-light text-dark <?= onerror('role','danger')?>">
              </div>
            </div>
          </div>
          <div class="mt-4 text-end">
            <button class="btn btn-primary"><i class="fa fa-paper-plane"></i> Submit</button>
          </div>
        </form>
        <hr>
        <!-- PROVINCE REGISTRATION FORM -->
        <form action="../handler/superadmin/register_province.php" method="post" class="standard-form">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
          <div class="row g-3 align-items-end">
            <div class="col-md-6">
              <label for="province" class="form-label">Province</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
                <input type="text" name="province_name" placeholder="<?php echo $province_name ?? 'Enter Province Name'?>" class="form-control bg-light text-dark <?= onerror('province_name','danger')?>">
              </div>
            </div>
            <div class="col-md-6 text-end">
              <button class="btn btn-success"><i class="fa fa-plus"></i> Submit</button>
            </div>
          </div>
        </form>
        <hr>
        <div style="display:flex; flex-wrap:wrap; gap:24px; align-items:stretch;">
          <!-- MUNICIPALITY REGISTRATION FORM -->
          <form action="../handler/superadmin/register_municipality.php" method="post" class="standard-form" onsubmit="return validateGeoJSON();" style="flex:1 1 350px; min-width:320px; max-width:500px;">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="mb-3">
              <label for="province_id" class="form-label">Province</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                <select name="province_id" id="province_id" required class="form-select bg-light text-dark">
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
                <input type="text" name="municipality" placeholder="Enter Municipality" required class="form-control bg-light text-dark">
              </div>
            </div>
            <input type="hidden" name="geojson" id="geojson">
            <div class="mt-4 text-end">
              <button type="submit" class="btn btn-info"><i class="fa fa-location-arrow"></i> Submit</button>
            </div>
          </form>
          <!-- MAP OUTSIDE FORM -->
          <div style="flex:1 1 350px; min-width:320px; max-width:600px; display:flex; flex-direction:column;">
            <label style="display: block; font-weight: bold; margin: 10px 0 5px;">Draw Municipality Boundary:</label>
            <div id="map" style="width:100%; height:400px; border:1px solid #ccc; border-radius:8px;"></div>
            <div class="text-muted" style="font-size: 0.95em;">Use the polygon tool to draw the boundary of the municipality. Only one polygon is allowed.</div>
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
    </script>
</body>
</html>
<?php unset($_SESSION['errors'])?>