<?php 
include '../configuration/config.php';
include '../configuration/routes.php';

// Fetch all provinces
$provinceStmt = $conn->prepare("SELECT * FROM provinces");
$provinceStmt->execute();
$provinces = $provinceStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all municipalities with province names
$municipalityStmt = $conn->prepare("
    SELECT m.id, m.municipality, m.geojson, p.province_name 
    FROM municipalities m
    JOIN provinces p ON m.province_id = p.id
");
$municipalityStmt->execute();
$municipalities = $municipalityStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Municipalities Map Viewer</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <style>
    body {
      font-family: sans-serif;
      padding: 20px;
    }
    h1 {
      margin-bottom: 10px;
    }
    .filters {
      display: flex;
      gap: 10px;
      margin-bottom: 10px;
    }
    input[type="text"], select {
      padding: 5px;
      font-size: 14px;
    }
    #map {
      height: 500px;
      width: 100%;
      border: 1px solid #ccc;
    }
  </style>
</head>
<body>

  <h1>Welcome Super Admin</h1>
  <p>Search and explore saved municipalities on the map below.</p>

  <!-- 🔍 Search and Filter UI -->
  <div class="filters">
    <input type="text" id="search" placeholder="Search municipality name..." />
    <select id="provinceFilter">
      <option value="">All Provinces</option>
      <?php foreach ($provinces as $prov): ?>
        <option value="<?= htmlspecialchars($prov['province_name']) ?>">
          <?= htmlspecialchars($prov['province_name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- 🗺️ Map Container -->
  <div id="map"></div>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script>
    const map = L.map('map').setView([10.3157, 123.8854], 8);
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
</body>
</html>
