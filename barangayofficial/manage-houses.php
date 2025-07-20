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
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/favicon.ico">
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
                                    <td><a href="../view_location.php?id=<?= $row['id'] ?>" target="_blank" class="btn btn-info btn-sm">View Location</a></td>
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
        var barangaysData = {}; // Object to store barangay GeoJSON data

        function initEditMap(houseGeojsonObj, barangayGeojsonStr = null) { // Changed parameter name to reflect it's an object
            // Remove existing map if it exists to prevent multiple maps
            if (editMap) {
                editMap.remove();
                editMap = null;
            }

            // Initialize map with a default view (e.g., Philippines)
            editMap = L.map('edit_map').setView([12.8797, 121.7740], 6);
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

            // Clear existing drawn items before adding new one
            editDrawnItems.clearLayers();

            // 1. First, try to fit to barangay GeoJSON if provided
            if (barangayGeojsonStr) {
                try {
                    var barangayGeojson = JSON.parse(barangayGeojsonStr);
                    // Check if it's a valid GeoJSON object with coordinates
                    if (barangayGeojson && barangayGeojson.type && barangayGeojson.coordinates) {
                        var geoJsonLayer = L.geoJSON(barangayGeojson);
                        if (geoJsonLayer.getBounds().isValid()) {
                            editMap.fitBounds(geoJsonLayer.getBounds());
                        } else {
                             console.warn('Barangay GeoJSON bounds are invalid or empty:', barangayGeojson);
                        }
                    } else {
                        console.warn('Invalid barangay GeoJSON structure:', barangayGeojson);
                    }
                } catch (e) {
                    console.error('Barangay GeoJSON parse error:', e, barangayGeojsonStr);
                }
            }

            // 2. Then, add the house marker and adjust view if it's there
            // houseGeojsonObj is already an object, no need to parse it
            if (houseGeojsonObj && houseGeojsonObj.type && houseGeojsonObj.coordinates) {
                try {
                    var marker = L.geoJSON(houseGeojsonObj).getLayers()[0];
                    if (marker) {
                        editDrawnItems.addLayer(marker);
                        // If a barangay GeoJSON was provided and successfully used for zooming,
                        // we don't need to re-center on the house marker unless the barangay geojson was empty/invalid.
                        // However, if the barangay geojson was not used to fitbounds (e.g., it was invalid or null),
                        // then we should make sure the house marker is visible.
                        if (!barangayGeojsonStr || !L.geoJSON(JSON.parse(barangayGeojsonStr)).getBounds().isValid()) {
                            if (marker.getLatLng) {
                                editMap.setView(marker.getLatLng(), 16); // Center map on marker with zoom
                            }
                        }
                    }
                } catch (e) {
                    console.error('House GeoJSON processing error:', e, houseGeojsonObj);
                    $('#edit_geojson').val('');
                }
            }


            // Event listener for when a new shape is created
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
            // Province -> Municipality (existing code)
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

            // Municipality -> Barangay (existing code)
            $('#municipalityFilter').on('change', function() {
                var municipalId = $(this).val();
                $('#barangayFilter').prop('disabled', true).html('<option value="">Select Barangay</option>');
                if (municipalId) {
                    $.get('/handler/barangayofficial/get_barangays.php', {
                        municipal_id: municipalId
                    }, function(data) {
                        var options = '<option value="">Select Barangay</option>';
                        barangaysData = {}; // Clear previous barangays data
                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(function(b) {
                                options += '<option value="' + b.id + '">' + b.barangay_name + '</option>';
                                barangaysData[b.id] = b.geojson; // Store geojson
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

            // Barangay filter (existing code)
            $('#barangayFilter').on('change', function() {
                filterHouseTable();
            });

            function filterHouseTable() {
                var provinceId = $('#provinceFilter').val();
                var municipalId = $('#municipalityFilter').val();
                var barangayId = $('#barangayFilter').val();
                $('table.table tbody tr').each(function() {
                    var row = $(this);
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
                $('#edit_id').val(row.data('id'));
                $('#edit_house_number').val(row.data('house_number'));
                $('#edit_building_type').val(row.data('building_type'));
                $('#edit_status').val(row.data('status'));
                $('#edit_no_floors').val(row.data('no_floors'));
                $('#edit_year_built').val(row.data('year_built'));
                $('#edit_street_name').val(row.data('street_name'));

                var provinceId = row.data('province');
                var municipalId = row.data('municipality');
                var barangayId = row.data('barangay');
                var houseGeojson = row.data('geojson'); // This will already be an object
                $('#edit_geojson').val(JSON.stringify(houseGeojson)); // Convert back to string for textarea

                $('#edit_province_id').val(provinceId);

                // Load municipalities for the selected province
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
                        // Load barangays for the selected municipality
                        if (municipalId) {
                            $.get('/handler/barangayofficial/get_barangays.php', {
                                municipal_id: municipalId
                            }, function(data) {
                                var options = '<option value="">Select Barangay</option>';
                                barangaysData = {}; // Clear previous barangays data
                                if (Array.isArray(data) && data.length > 0) {
                                    data.forEach(function(b) {
                                        options += '<option value="' + b.id + '"' + (b.id == barangayId ? ' selected' : '') + '>' + b.barangay_name + '</option>';
                                        barangaysData[b.id] = b.geojson; // Store geojson
                                    });
                                    $('#edit_barangay_id').html(options).prop('disabled', false);

                                    // IMPORTANT: Call initEditMap here after barangaysData is populated
                                    // This ensures that when the modal opens, the map immediately zooms to the barangay.
                                    var selectedBarangayGeojson = barangaysData[barangayId] || null;
                                    initEditMap(houseGeojson, selectedBarangayGeojson); // houseGeojson is already an object

                                } else {
                                    options += '<option value="" disabled>No barangays found</option>';
                                    $('#edit_barangay_id').html(options).prop('disabled', false);
                                    initEditMap(houseGeojson, null); // Only house geojson
                                }
                            }, 'json');
                        } else {
                            $('#edit_barangay_id').html('<option value="">Select Barangay</option>').prop('disabled', true);
                            initEditMap(houseGeojson, null); // Only house geojson
                        }
                    }, 'json');
                } else {
                    $('#edit_municipal_id').html('<option value="">Select Municipality</option>').prop('disabled', true);
                    $('#edit_barangay_id').html('<option value="">Select Barangay</option>').prop('disabled', true);
                    initEditMap(houseGeojson, null); // Only house geojson
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
                // Pass the house geojson object directly
                var houseGeojson = $('#edit_geojson').val() ? JSON.parse($('#edit_geojson').val()) : null;
                initEditMap(houseGeojson, null); // Reset map when province changes
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
                // Pass the house geojson object directly
                var houseGeojson = $('#edit_geojson').val() ? JSON.parse($('#edit_geojson').val()) : null;
                initEditMap(houseGeojson, null); // Reset map when municipality changes
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

            // Handle change for Barangay dropdown in modal to pan/zoom map
            $('#edit_barangay_id').on('change', function() {
                var barangayId = $(this).val();
                // Ensure houseGeojson is an object if it exists in the textarea
                var houseGeojson = $('#edit_geojson').val() ? JSON.parse($('#edit_geojson').val()) : null;
                var selectedBarangayGeojson = barangaysData[barangayId] || null; // Get geojson from stored data
                initEditMap(houseGeojson, selectedBarangayGeojson);
            });


            // AJAX form submit for editing house (existing code)
            $('#editHouseForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $('#editHouseMsg').html('<span class="text-info">Saving...</span>');
                $.post('../handler/barangayofficial/update_house.php', formData, function(response) {
                    if (response.success) {
                        // Update the table row in-place
                        var id = $('#edit_id').val();
                        var row = $('tr[data-id="' + id + '"]');
                        row.find('td').eq(1).text($('#edit_house_number').val());
                        row.find('td').eq(2).text($('#edit_province_id option:selected').text());
                        row.find('td').eq(3).text($('#edit_municipal_id option:selected').text());
                        row.find('td').eq(4).text($('#edit_barangay_id option:selected').text());
                        row.find('td').eq(5).text($('#edit_street_name').val());
                        row.find('td').eq(6).text($('#edit_building_type').val());
                        row.find('td').eq(7).text($('#edit_status').val());
                        row.find('td').eq(8).text($('#edit_no_floors').val());
                        row.find('td').eq(9).text($('#edit_year_built').val());
                        // Update data attributes (important for filtering and re-editing)
                        row.data('house_number', $('#edit_house_number').val());
                        row.data('building_type', $('#edit_building_type').val());
                        row.data('status', $('#edit_status').val());
                        row.data('no_floors', $('#edit_no_floors').val());
                        row.data('year_built', $('#edit_year_built').val());
                        row.data('street_name', $('#edit_street_name').val());
                        row.data('province', $('#edit_province_id').val());
                        row.data('municipality', $('#edit_municipal_id').val());
                        row.data('barangay', $('#edit_barangay_id').val());
                        // When updating data attribute, ensure it's a string, especially if it was an object
                        row.data('geojson', $('#edit_geojson').val());
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

            // Initialize map when modal is shown (updated to include barangay GeoJSON logic)
            // This handler is still crucial for map rendering once the modal animation finishes.
            $('#editHouseModal').on('shown.bs.modal', function() {
                // The initEditMap is already called by the .edit-house-btn click handler
                // once the barangay dropdown is populated.
                // We just need to invalidate size here to ensure map renders correctly.
                setTimeout(function() {
                    if (editMap) editMap.invalidateSize();
                }, 100);
                setTimeout(function() {
                    if (editMap) editMap.invalidateSize();
                }, 500);
            });

            // Destroy map when modal is hidden (existing code)
            $('#editHouseModal').on('hidden.bs.modal', function() {
                if (editMap) {
                    editMap.remove();
                    editMap = null;
                }
            });

            // Handle manual GeoJSON input change (e.g., if user pastes JSON) (existing code, still useful)
            $('#edit_geojson').on('change', function() {
                if (!editMap) return; // Map might not be initialized yet if modal is not shown

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
                                if (marker.getLatLng) {
                                    editMap.setView(marker.getLatLng(), 16);
                                }
                            }
                        }
                    } catch (e) {
                        console.error('GeoJSON parse error in geojson change handler:', e, geojsonStr);
                        $(this).val('');
                    }
                }
            });
        });
    </script>

    <div class="modal fade" id="editHouseModal" tabindex="-1" role="dialog" aria-labelledby="editHouseModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="editHouseForm" method="post" action="">
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
                            <label>House Number</label>
                            <input type="number" name="house_number" id="edit_house_number" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_province_id">Province</label>
                            <select class="form-control" id="edit_province_id" name="province_id" required>
                                <option value="">Select Province</option>
                                <?php foreach (
                                    $provinces as $province): ?>
                                    <option value="<?= $province['id'] ?>"><?= htmlspecialchars($province['province_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_municipal_id">Municipality / City</label>
                            <select class="form-control" id="edit_municipal_id" name="municipal_id" required>
                                <option value="">Select Municipality / City</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_barangay_id">Barangay</label>
                            <select class="form-control" id="edit_barangay_id" name="barangay_id" required>
                                <option value="">Select Barangay</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Building Type</label>
                            <input type="text" name="building_type" id="edit_building_type" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <input type="text" name="status" id="edit_status" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>No. Floors</label>
                            <input type="number" name="no_floors" id="edit_no_floors" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Year Built</label>
                            <input type="date" name="year_built" id="edit_year_built" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Street Name</label>
                            <input type="text" name="street_name" id="edit_street_name" class="form-control">
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>