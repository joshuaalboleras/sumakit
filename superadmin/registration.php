<?php 
include '../configuration/config.php';
include '../configuration/routes.php';
if(isset($_SESSION['errors'])){
  foreach($_SESSION['errors'] as $key => $value){
    $$key = $_SESSION['errors'][$key][0];
  }
  unset($_SESSION['errors']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />

  <style>
    .standard-form {
      border: 1px solid black;
      padding: 10px;
      margin-bottom: 20px;
    }

    .standard-form div {
      border: 1px solid black;
      padding: 10px;
      display: inline-block;
      vertical-align: top;
    }

    .standard-form div label {
      display: block;
    }

    #map {
      width: 100%;
      height: 400px;
      border: 1px solid #ccc;
      margin-top: 10px;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

  <!-- USER REGISTRATION FORM -->
  <form action="../handler/superadmin/register.php" method="post" class="standard-form">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <div>
      <label for="name">Name</label>
      <input type="text" id="name" name="name" placeholder="Enter Name" autocomplete="off">
    </div>
    <div>
      <label for="email">Email</label>
      <input type="text" id="email" name="email" placeholder="Enter Email" autocomplete="off">
    </div>
    <div>
      <label for="password">Password</label>
      <input type="text" id="password" name="password" placeholder="Enter Password" autocomplete="off">
    </div>
    <div>
      <label for="role">Role</label>
      <input type="text" id="role" name="role_id" placeholder="<?= $role ?? 'Select Role'?>" autocomplete="off">
    </div>
    <button>Submit</button>
  </form>

  <hr>

  <!-- PROVINCE REGISTRATION FORM -->
  <form action="../handler/superadmin/register_province.php" class="standard-form" method="post">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <div>
      <label for="province">Province</label>
      <input type="text" name="province_name" placeholder="Enter Province Name">
    </div>
    <button>Submit</button>
  </form>

  <hr>

  <!-- MUNICIPALITY REGISTRATION FORM -->
  <form action="../handler/superadmin/register_municipality.php" class="standard-form" method="post" onsubmit="return validateGeoJSON();">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <div>
      <label for="province_id">Province</label>
      <select name="province_id" id="province_id" required>
        <?php 
          $stmt = $conn->query("SELECT * FROM provinces");
          $stmt->setFetchMode(PDO::FETCH_ASSOC);
          $stmt->execute();
          $res = $stmt->fetchAll();
          foreach($res as $province){
              echo <<<HTML
                  <option value="{$province['id']}">{$province['province_name']}</option>
              HTML;
          }
        ?>
      </select>
    </div>

    <div>
      <label for="municipality">Municipality</label>
      <input type="text" name="municipality" placeholder="Enter Municipality" required>
    </div>

    <!-- Hidden input to store GeoJSON -->
    <input type="hidden" name="geojson" id="geojson">

    <button type="submit">Submit</button>
  </form>

  <!-- MAP OUTSIDE FORM -->
  <label style="display: block; font-weight: bold; margin: 10px 0 5px;">Draw Municipality Boundary:</label>
  <div id="map"></div>

  <!-- Leaflet JS -->
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

    map.on(L.Draw.Event.CREATED, function (e) {
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

    // Ensure map renders correctly
    setTimeout(() => {
      map.invalidateSize();
    }, 200);
  </script>

</body>
</html>
