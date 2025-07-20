<?php
include '../configuration/config.php';
include '../configuration/routes.php';

// Handle Delete
if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM houses WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage-houses.php");
    exit;
}

// Fetch provinces for dropdown
$provinces = $conn->query("SELECT id, province_name FROM provinces ORDER BY province_name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all houses with location info
// It's generally better to select specific columns instead of h.* for clarity and performance
$stmt = $conn->query("SELECT h.id, h.house_number, h.street_name, h.building_type, h.status, h.no_floors, h.year_built, h.geojson, h.province_id, h.municipal_id, h.barangay_id, b.barangay_name, m.municipality, p.province_name FROM houses h JOIN barangays b ON h.barangay_id = b.id JOIN municipalities m ON h.municipal_id = m.id JOIN provinces p ON h.province_id = p.id");
$houses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Manage Houses</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/x-xicon" href="../assets/images/favicon.ico">
    <link rel="stylesheet" href="../assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/vendor/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="../assets/css/vendor/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/vendor/themify-icons.css">
    <link rel="stylesheet" href="../assets/css/vendor/cryptocurrency-icons.css">
    <link rel="stylesheet" href="../assets/css/plugins/plugins.css">
    <link rel="stylesheet" href="../assets/css/helper.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link id="cus-style" rel="stylesheet" href="../assets/css/style-primary.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
    <style>
        /* This style is fine for the map container */
        #edit_map {
            background: #222 !important;
            min-height: 350px; /* Increased height for better visibility */
            width: 100%; /* Ensure it takes full width of parent */
        }
        /* This changes the map background, which might be intended, or just a default */
        .leaflet-container {
            background: #ddd !important;
        }
    </style>
</head>
<body class="skin-dark">
    <div class="main-wrapper">
        <div class="header-section">
            <div class="container-fluid">
                <div class="row justify-content-between align-items-center">
                    <div class="header-logo col-auto">
                        <a href="index.html">
                            <img src="../assets/images/logo/logo.png" alt="">
                            <img src="../assets/images/logo/logo-light.png" class="logo-light" alt="">
                        </a>
                    </div>
                    <?php include '../partials/shared/top-nav.php';?>
                </div>
            </div>
        </div>
        <div class="side-header show">
            <button class="side-header-close"><i class="zmdi zmdi-close"></i></button>
            <?php include '../partials/barangayofficial/side-bar.php';?>
        </div>
        <div class="content-body">
            <div class="box">
                <div class="box-head">
                    <h4 class="title">Houses</h4>
                </div>
                <div class="box-body">
                    <div class="row mb-3">
                        <div class="col-md-4 mb-2">
                            <select id="provinceFilter" class="form-control">
                                <option value="">Select Province</option>
                                <?php foreach ($provinces as $prov): ?>
                                    <option value="<?= $prov['id'] ?>"><?= htmlspecialchars($prov['province_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <select id="municipalityFilter" class="form-control" disabled>
                                <option value="">Select Municipality / City</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <select id="barangayFilter" class="form-control" disabled>
                                <option value="">Select Barangay</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vertical-middle table-selectable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>House Number</th>
                                    <th>Province</th>
                                    <th>Municipality</th>
                                    <th>Barangay</th>
                                    <th>Street Name</th>
                                    <th>Building Type</th>
                                    <th>Status</th>
                                    <th>No. Floors</th>
                                    <th>Year Built</th>
                                    <th>GeoJSON</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($houses as $row): ?>
                                <tr
                                    data-id="<?= $row['id'] ?>"
                                    data-house_number="<?= htmlspecialchars($row['house_number'] ?? '') ?>"
                                    data-building_type="<?= htmlspecialchars($row['building_type'] ?? '') ?>"
                                    data-status="<?= htmlspecialchars($row['status'] ?? '') ?>"
                                    data-no_floors="<?= htmlspecialchars($row['no_floors'] ?? '') ?>"
                                    data-year_built="<?= htmlspecialchars($row['year_built'] ?? '') ?>"
                                    data-street_name="<?= htmlspecialchars($row['street_name'] ?? '') ?>"
                                    data-province="<?= $row['province_id'] ?>"
                                    data-municipality="<?= $row['municipal_id'] ?>"
                                    data-barangay="<?= $row['barangay_id'] ?>"
                                    data-geojson="<?= htmlspecialchars($row['geojson'] ?? '') ?>"
                                >
                                    <td><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['house_number'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['province_name'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['municipality'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['barangay_name'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['street_name'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['building_type'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['status'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['no_floors'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['year_built'] ?? '') ?></td>
                                    <td><a href="../view_location.php?id=<?= $row['id'] ?>&type=house" target="_blank" class="btn btn-info btn-sm">View Location</a></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary edit-house-btn" type="button">Edit</button>
                                        <form method="post" action="" style="display:inline;" onsubmit="return confirm('Delete this house?');">
                                            <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-section">
            <div class="container-fluid">
                <div class="footer-copyright text-center">
                    <p class="text-body-light">2022 &copy; <a href="https://themeforest.net/user/codecarnival">Codecarnival</a></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editHouseModal" tabindex="-1" role="dialog" aria-labelledby="editHouseModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="editHouseForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editHouseModalLabel">Edit House</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="edit_id">
                        <div class="form-group">
                            <label for="edit_house_number">House Number</label>
                            <input type="text" class="form-control" id="edit_house_number" name="house_number" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_province_id">Province</label>
                            <select class="form-control" id="edit_province_id" name="province_id" required>
                                <option value="">Select Province</option>
                                <?php foreach ($provinces as $prov): ?>
                                    <option value="<?= $prov['id'] ?>"><?= htmlspecialchars($prov['province_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_municipal_id">Municipality</label>
                            <select class="form-control" id="edit_municipal_id" name="municipal_id" required>
                                <option value="">Select Municipality</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_barangay_id">Barangay</label>
                            <select class="form-control" id="edit_barangay_id" name="barangay_id" required>
                                <option value="">Select Barangay</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_street_name">Street Name</label>
                            <input type="text" class="form-control" id="edit_street_name" name="street_name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_building_type">Building Type</label>
                            <input type="text" class="form-control" id="edit_building_type" name="building_type">
                        </div>
                        <div class="form-group">
                            <label for="edit_status">Status</label>
                            <input type="text" class="form-control" id="edit_status" name="status">
                        </div>
                        <div class="form-group">
                            <label for="edit_no_floors">Number of Floors</label>
                            <input type="number" class="form-control" id="edit_no_floors" name="no_floors" min="1">
                        </div>
                        <div class="form-group">
                            <label for="edit_year_built">Year Built</label>
                            <input type="date" class="form-control" id="edit_year_built" name="year_built" min="1900" max="<?= date('Y') ?>">
                        </div>
                        <div class="form-group">
                            <label for="edit_map">Select Location on Map</label>
                            <div id="edit_map" style="height: 350px; width: 100%; margin-bottom: 10px;"></div>
                        </div>
                        <div class="form-group">
                            <label for="edit_geojson">GeoJSON</label>
                            <textarea class="form-control" id="edit_geojson" name="geojson" rows="3" required></textarea>
                        </div>
                        <div id="editHouseMsg"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script src="../assets/js/vendor/modernizr-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/vendor/popper.min.js"></script>
    <script src="../assets/js/vendor/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/tippy4.min.js.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>

    <script>
        var editMap, editDrawnItems, editDrawControl;
        var barangaysData = {}; // Object to store barangay GeoJSON data {id: geojson_string}
        var barangayBoundaryLayer; // To hold the current barangay boundary layer

        /**
         * Initializes or re-initializes the Leaflet map in the edit modal.
         * @param {object | null} houseGeojsonObj - The GeoJSON object for the house marker.
         * @param {string | null} barangayGeojsonStr - The GeoJSON string for the barangay boundary.
         */
        function initEditMap(houseGeojsonObj, barangayGeojsonStr = null) {
            // Remove existing map if it exists
            if (editMap) {
                editMap.remove();
                editMap = null;
            }

            // Initialize map with a default view (e.g., Philippines)
            editMap = L.map('edit_map').setView([12.8797, 121.7740], 6); // Centered on the Philippines
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(editMap);

            editDrawnItems = new L.FeatureGroup();
            editMap.addLayer(editDrawnItems);

            editDrawControl = new L.Control.Draw({
                draw: {
                    polygon: false,
                    polyline: false,
                    rectangle: false,
                    circle: false,
                    circlemarker: false,
                    marker: true // Only allow drawing markers
                },
                edit: {
                    featureGroup: editDrawnItems,
                    remove: true
                }
            });
            editMap.addControl(editDrawControl);

            // Clear existing drawn items (like previous house marker)
            editDrawnItems.clearLayers();

            // Remove previous barangay boundary if it exists
            if (barangayBoundaryLayer) {
                editMap.removeLayer(barangayBoundaryLayer);
                barangayBoundaryLayer = null;
            }

            let mapZoomedToBoundary = false;

            // 1. Draw and fit to barangay GeoJSON if provided
            if (barangayGeojsonStr) {
                try {
                    var barangayGeojson = JSON.parse(barangayGeojsonStr);
                    // Corrected check: Ensure it's a Feature and has a geometry with coordinates
                    if (barangayGeojson && barangayGeojson.type === "Feature" &&
                        barangayGeojson.geometry && barangayGeojson.geometry.coordinates) {

                        barangayBoundaryLayer = L.geoJSON(barangayGeojson, {
                            style: function(feature) {
                                return {
                                    color: '#ff7800', // Orange color for boundary
                                    weight: 3,
                                    opacity: 0.65,
                                    fillOpacity: 0.1
                                };
                            }
                        }).addTo(editMap);

                        if (barangayBoundaryLayer.getBounds().isValid()) {
                            editMap.fitBounds(barangayBoundaryLayer.getBounds(), { padding: [50, 50] });
                            mapZoomedToBoundary = true;
                        } else {
                            console.warn('Barangay GeoJSON bounds are invalid or empty for feature:', barangayGeojson);
                        }
                    } else if (barangayGeojson && barangayGeojson.type && barangayGeojson.coordinates) {
                        // This else if handles direct Geometry Objects (e.g., {"type":"Polygon","coordinates":...})
                        // This might be the case if 'geojson' field in the 'barangays' table directly stores geometry, not a full Feature.
                        barangayBoundaryLayer = L.geoJSON(barangayGeojson, {
                            style: function(feature) {
                                return {
                                    color: '#ff7800',
                                    weight: 3,
                                    opacity: 0.65,
                                    fillOpacity: 0.1
                                };
                            }
                        }).addTo(editMap);

                        if (barangayBoundaryLayer.getBounds().isValid()) {
                            editMap.fitBounds(barangayBoundaryLayer.getBounds(), { padding: [50, 50] });
                            mapZoomedToBoundary = true;
                        } else {
                            console.warn('Barangay GeoJSON bounds are invalid or empty for geometry:', barangayGeojson);
                        }
                    } else {
                        console.warn('Invalid barangay GeoJSON structure (neither Feature nor valid Geometry):', barangayGeojson);
                    }
                } catch (e) {
                    console.error('Barangay GeoJSON parse error:', e, barangayGeojsonStr);
                }
            }

            // 2. Add the house marker (if it exists)
            if (houseGeojsonObj && houseGeojsonObj.type && houseGeojsonObj.coordinates) {
                try {
                    var marker = L.geoJSON(houseGeojsonObj).getLayers()[0];
                    if (marker) {
                        editDrawnItems.addLayer(marker);
                        // If map wasn't already zoomed to a boundary, zoom to marker
                        if (!mapZoomedToBoundary && marker.getLatLng) {
                            editMap.setView(marker.getLatLng(), 16); // Center map on marker with zoom
                        }
                    }
                } catch (e) {
                    console.error('House GeoJSON processing error:', e, houseGeojsonObj);
                    $('#edit_geojson').val(''); // Clear invalid GeoJSON from textarea
                }
            }

            // Event listener for when a new shape is created (marker in this case)
            editMap.on(L.Draw.Event.CREATED, function(e) {
                editDrawnItems.clearLayers(); // Clear previous layers to ensure only one marker
                var layer = e.layer;
                editDrawnItems.addLayer(layer);
                var geojson = layer.toGeoJSON();
                // Store only the geometry part of the GeoJSON
                $('#edit_geojson').val(JSON.stringify(geojson.geometry));
            });

            // Event listener for when a shape is edited
            editMap.on('draw:edited', function(e) {
                var layers = e.layers;
                layers.eachLayer(function(layer) {
                    var geojson = layer.toGeoJSON();
                    $('#edit_geojson').val(JSON.stringify(geojson.geometry));
                });
            });

            // Event listener for when a shape is deleted
            editMap.on('draw:deleted', function() {
                $('#edit_geojson').val(''); // Clear geojson input if marker is deleted
            });
        }

        $(document).ready(function() {
            // Province -> Municipality for filters
            $('#provinceFilter').on('change', function() {
                var provinceId = $(this).val();
                $('#municipalityFilter').prop('disabled', true).html('<option value="">Select Municipality</option>');
                $('#barangayFilter').prop('disabled', true).html('<option value="">Select Barangay</option>');
                if (provinceId) {
                    $.get('/handler/barangayofficial/get_municipalities.php', {
                        province_id: provinceId
                    }, function(data) {
                        var options = '<option value="">Select Municipality</option>';
                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(function(m) {
                                options += '<option value="' + m.id + '">' + m.municipality + '</option>';
                            });
                            $('#municipalityFilter').html(options).prop('disabled', false);
                        } else {
                            options += '<option value="" disabled>No municipalities found</option>';
                            $('#municipalityFilter').html(options).prop('disabled', false);
                        }
                    }, 'json');
                }
                filterHouseTable();
            });

            // Municipality -> Barangay for filters
            $('#municipalityFilter').on('change', function() {
                var municipalId = $(this).val();
                $('#barangayFilter').prop('disabled', true).html('<option value="">Select Barangay</option>');
                if (municipalId) {
                    $.get('/handler/barangayofficial/get_barangays.php', {
                        municipal_id: municipalId
                    }, function(data) {
                        var options = '<option value="">Select Barangay</option>';
                        // Do not store barangaysData for filter dropdowns here, only for modal
                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(function(b) {
                                options += '<option value="' + b.id + '">' + b.barangay_name + '</option>';
                            });
                            $('#barangayFilter').html(options).prop('disabled', false);
                        } else {
                            options += '<option value="" disabled>No barangays found</option>';
                            $('#barangayFilter').html(options).prop('disabled', false);
                        }
                    }, 'json');
                }
                filterHouseTable();
            });

            // Barangay filter
            $('#barangayFilter').on('change', function() {
                filterHouseTable();
            });

            function filterHouseTable() {
                var provinceId = $('#provinceFilter').val();
                var municipalId = $('#municipalityFilter').val();
                var barangayId = $('#barangayFilter').val();

                $('table.table tbody tr').each(function() {
                    var row = $(this);
                    // Get data directly from data attributes set in PHP
                    var rowProvince = row.data('province').toString();
                    var rowMunicipal = row.data('municipality').toString();
                    var rowBarangay = row.data('barangay').toString();

                    var show = true;
                    if (provinceId && rowProvince !== provinceId) show = false;
                    if (municipalId && rowMunicipal !== municipalId) show = false;
                    if (barangayId && rowBarangay !== barangayId) show = false;
                    row.toggle(show);
                });
            }

            // Edit button click handler
            $('.edit-house-btn').on('click', function() {
                var row = $(this).closest('tr');
                var id = row.data('id');
                var houseNumber = row.data('house_number');
                var buildingType = row.data('building_type');
                var status = row.data('status');
                var noFloors = row.data('no_floors');
                var yearBuilt = row.data('year_built');
                var streetName = row.data('street_name');
                var provinceId = row.data('province');
                var municipalId = row.data('municipality');
                var barangayId = row.data('barangay');
                var houseGeojson = row.data('geojson'); // This should already be an object if jQuery parses it

                $('#edit_id').val(id);
                $('#edit_house_number').val(houseNumber);
                $('#edit_building_type').val(buildingType);
                $('#edit_status').val(status);
                $('#edit_no_floors').val(noFloors);
                $('#edit_year_built').val(yearBuilt);
                $('#edit_street_name').val(streetName);
                // Set the geojson textarea with a stringified version (important for the change handler)
                $('#edit_geojson').val(houseGeojson ? JSON.stringify(houseGeojson) : '');

                $('#edit_province_id').val(provinceId);

                // Populate municipalities for the selected province
                if (provinceId) {
                    $.get('/handler/barangayofficial/get_municipalities.php', {
                        province_id: provinceId
                    }, function(data) {
                        var options = '<option value="">Select Municipality</option>';
                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(function(m) {
                                options += '<option value="' + m.id + '"' + (m.id == municipalId ? ' selected' : '') + '>' + m.municipality + '</option>';
                            });
                            $('#edit_municipal_id').html(options).prop('disabled', false);
                        } else {
                            options += '<option value="" disabled>No municipalities found</option>';
                            $('#edit_municipal_id').html(options).prop('disabled', false);
                        }

                        // Populate barangays after municipalities
                        if (municipalId) {
                            $.get('/handler/barangayofficial/get_barangays.php', {
                                municipal_id: municipalId
                            }, function(data) {
                                var options = '<option value="">Select Barangay</option>';
                                barangaysData = {}; // Clear previous barangays data
                                if (Array.isArray(data) && data.length > 0) {
                                    data.forEach(function(b) {
                                        options += '<option value="' + b.id + '"' + (b.id == barangayId ? ' selected' : '') + '>' + b.barangay_name + '</option>';
                                        barangaysData[b.id] = b.geojson; // Store barangay geojson string
                                    });
                                    $('#edit_barangay_id').html(options).prop('disabled', false);

                                    // Initialize map here, after barangaysData is populated
                                    var selectedBarangayGeojson = barangaysData[barangayId] || null;
                                    // Pass houseGeojson as an object (jQuery data() might have already parsed it,
                                    // but ensure it's an object before passing to initEditMap)
                                    let houseGeojsonParsed = typeof houseGeojson === 'string' ? JSON.parse(houseGeojson) : houseGeojson;
                                    initEditMap(houseGeojsonParsed, selectedBarangayGeojson);

                                } else {
                                    options += '<option value="" disabled>No barangays found</option>';
                                    $('#edit_barangay_id').html(options).prop('disabled', false);
                                    let houseGeojsonParsed = typeof houseGeojson === 'string' ? JSON.parse(houseGeojson) : houseGeojson;
                                    initEditMap(houseGeojsonParsed, null); // Only house geojson
                                }
                            }, 'json');
                        } else {
                            $('#edit_barangay_id').html('<option value="">Select Barangay</option>').prop('disabled', true);
                            let houseGeojsonParsed = typeof houseGeojson === 'string' ? JSON.parse(houseGeojson) : houseGeojson;
                            initEditMap(houseGeojsonParsed, null); // Only house geojson
                        }
                    }, 'json');
                } else {
                    $('#edit_municipal_id').html('<option value="">Select Municipality</option>').prop('disabled', true);
                    $('#edit_barangay_id').html('<option value="">Select Barangay</option>').prop('disabled', true);
                    let houseGeojsonParsed = typeof houseGeojson === 'string' ? JSON.parse(houseGeojson) : houseGeojson;
                    initEditMap(houseGeojsonParsed, null); // Only house geojson
                }

                $('#editHouseMsg').html('');
                $('#editHouseModal').modal('show');
            });

            // Cascading dropdowns in modal (updated to trigger map view)
            $('#edit_province_id').on('change', function() {
                var provinceId = $(this).val();
                $('#edit_municipal_id').prop('disabled', true).html('<option value="">Select Municipality</option>');
                $('#edit_barangay_id').prop('disabled', true).html('<option value="">Select Barangay</option>');
                barangaysData = {}; // Clear barangays data when province changes
                // Get house geojson from the textarea, parse it if it exists
                var houseGeojson = $('#edit_geojson').val() ? JSON.parse($('#edit_geojson').val()) : null;
                initEditMap(houseGeojson, null); // Reset map with only house geojson
                if (provinceId) {
                    $.get('/handler/barangayofficial/get_municipalities.php', {
                        province_id: provinceId
                    }, function(data) {
                        var options = '<option value="">Select Municipality</option>';
                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(function(m) {
                                options += '<option value="' + m.id + '">' + m.municipality + '</option>';
                            });
                            $('#edit_municipal_id').html(options).prop('disabled', false);
                        } else {
                            options += '<option value="" disabled>No municipalities found</option>';
                            $('#edit_municipal_id').html(options).prop('disabled', false);
                        }
                    }, 'json');
                }
            });

            $('#edit_municipal_id').on('change', function() {
                var municipalId = $(this).val();
                $('#edit_barangay_id').prop('disabled', true).html('<option value="">Select Barangay</option>');
                barangaysData = {}; // Clear barangays data when municipality changes
                // Get house geojson from the textarea, parse it if it exists
                var houseGeojson = $('#edit_geojson').val() ? JSON.parse($('#edit_geojson').val()) : null;
                initEditMap(houseGeojson, null); // Reset map with only house geojson
                if (municipalId) {
                    $.get('/handler/barangayofficial/get_barangays.php', {
                        municipal_id: municipalId
                    }, function(data) {
                        var options = '<option value="">Select Barangay</option>';
                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(function(b) {
                                options += '<option value="' + b.id + '">' + b.barangay_name + '</option>';
                                barangaysData[b.id] = b.geojson; // Store geojson
                            });
                            $('#edit_barangay_id').html(options).prop('disabled', false);
                        } else {
                            options += '<option value="" disabled>No barangays found</option>';
                            $('#edit_barangay_id').html(options).prop('disabled', false);
                        }
                    }, 'json');
                }
            });

            // Handle change for Barangay dropdown in modal to pan/zoom map and draw boundary
            $('#edit_barangay_id').on('change', function() {
                var barangayId = $(this).val();
                // Ensure houseGeojson is an object if it exists in the textarea
                var houseGeojson = $('#edit_geojson').val() ? JSON.parse($('#edit_geojson').val()) : null;
                var selectedBarangayGeojson = barangaysData[barangayId] || null; // Get geojson string from stored data
                initEditMap(houseGeojson, selectedBarangayGeojson);
            });


            // AJAX form submit for editing house
            $('#editHouseForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $('#editHouseMsg').html('<span class="text-info">Saving...</span>');
                $.post('../handler/barangayofficial/update_house.php', formData, function(response) {
                    if (response.success) {
                        // Update the table row in-place
                        var id = $('#edit_id').val();
                        var newHouseNumber = $('#edit_house_number').val();
                        var newBuildingType = $('#edit_building_type').val();
                        var newStatus = $('#edit_status').val();
                        var newNoFloors = $('#edit_no_floors').val();
                        var newYearBuilt = $('#edit_year_built').val();
                        var newStreetName = $('#edit_street_name').val();
                        var newProvinceId = $('#edit_province_id').val();
                        var newMunicipalId = $('#edit_municipal_id').val();
                        var newBarangayId = $('#edit_barangay_id').val();
                        var newGeojson = $('#edit_geojson').val(); // This is the stringified version from textarea

                        var row = $('tr[data-id="' + id + '"]');
                        row.find('td').eq(1).text(newHouseNumber);
                        // Update the displayed names based on selected dropdown text
                        row.find('td').eq(2).text($('#edit_province_id option:selected').text());
                        row.find('td').eq(3).text($('#edit_municipal_id option:selected').text());
                        row.find('td').eq(4).text($('#edit_barangay_id option:selected').text());
                        row.find('td').eq(5).text(newStreetName);
                        row.find('td').eq(6).text(newBuildingType);
                        row.find('td').eq(7).text(newStatus);
                        row.find('td').eq(8).text(newNoFloors);
                        row.find('td').eq(9).text(newYearBuilt);

                        // Update data attributes (important for filtering and re-editing)
                        row.data('house_number', newHouseNumber);
                        row.data('building_type', newBuildingType);
                        row.data('status', newStatus);
                        row.data('no_floors', newNoFloors);
                        row.data('year_built', newYearBuilt);
                        row.data('street_name', newStreetName);
                        row.data('province', newProvinceId);
                        row.data('municipality', newMunicipalId);
                        row.data('barangay', newBarangayId);
                        // When updating data attribute, parse the string back to an object if it was stored as string
                        row.data('geojson', newGeojson ? JSON.parse(newGeojson) : null);

                        $('#editHouseMsg').html('<span class="text-success">' + response.message + '</span>');
                        setTimeout(function() {
                            $('#editHouseModal').modal('hide');
                        }, 1000);
                    } else {
                        $('#editHouseMsg').html('<span class="text-danger">' + response.message + '</span>');
                    }
                }, 'json').fail(function(xhr) {
                    $('#editHouseMsg').html('<span class="text-danger">An error occurred. Please try again.</span>');
                });
            });

            // When modal is shown, ensure map invalidates size to render correctly
            $('#editHouseModal').on('shown.bs.modal', function () {
                // The initEditMap is already called by the .edit-house-btn click handler
                // once the barangay dropdown is populated.
                // We just need to invalidate size here to ensure map renders correctly.
                setTimeout(function() {
                    if (editMap) editMap.invalidateSize();
                }, 100);
                setTimeout(function() {
                    if (editMap) editMap.invalidateSize();
                }, 500); // Small delay to ensure modal is fully rendered
            });

            // When modal is hidden, destroy map
            $('#editHouseModal').on('hidden.bs.modal', function () {
                if (editMap) {
                    editMap.remove();
                    editMap = null;
                }
                // Also clear the barangay boundary layer reference
                barangayBoundaryLayer = null;
            });

            // When geojson textarea changes (e.g. from JS or manual edit), re-render marker
            $('#edit_geojson').on('change', function() {
                if (!editMap) return; // Map might not be initialized yet if modal is not shown

                // Get current barangay GeoJSON from the stored data
                var currentBarangayId = $('#edit_barangay_id').val();
                var currentBarangayGeojson = barangaysData[currentBarangayId] || null;

                editDrawnItems.clearLayers();
                var geojsonStr = $(this).val();
                if (geojsonStr) {
                    try {
                        var geojson = JSON.parse(geojsonStr);
                        // Ensure it's a valid GeoJSON object and has geometry
                        if (geojson && geojson.type && geojson.coordinates) {
                            var marker = L.geoJSON(geojson).getLayers()[0];
                            if (marker) {
                                editDrawnItems.addLayer(marker);
                                // Don't recenter if a barangay boundary is already displayed and the map is zoomed to it
                                // Only recenter on marker if no valid boundary is drawn, or if marker is outside current view
                                if ((!barangayBoundaryLayer || !barangayBoundaryLayer.getBounds().isValid()) && marker.getLatLng) {
                                    editMap.setView(marker.getLatLng(), 16);
                                } else if (marker.getLatLng && !editMap.getBounds().contains(marker.getLatLng())) {
                                    editMap.setView(marker.getLatLng(), editMap.getZoom()); // Just center, keep current zoom
                                }
                            }
                        }
                    } catch (e) {
                        console.error('GeoJSON parse error in geojson change handler:', e, geojsonStr);
                        $(this).val(''); // Clear invalid JSON
                    }
                }
                // Always re-initialize map with current house and barangay GeoJSON
                // This ensures the barangay boundary is still there even if marker is cleared/edited
                initEditMap(geojsonStr ? JSON.parse(geojsonStr) : null, currentBarangayGeojson);
            });
        });
    </script>
</body>
</html>