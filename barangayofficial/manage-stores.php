<?php
include '../configuration/config.php';
include '../configuration/routes.php';

// Handle Delete
if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM stores WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage-stores.php");
    exit;
}

// Fetch stores
$stmt = $conn->query("SELECT * FROM stores");
$stores = $stmt->fetchAll();
// Fetch provinces for dropdown
$provinces = $conn->query("SELECT id, province_name FROM provinces ORDER BY province_name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Manage Stores</title>
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
                    <?php include '../partials/shared/top-nav.php'; ?>
                </div>
            </div>
        </div>
        <div class="side-header show">
            <button class="side-header-close"><i class="zmdi zmdi-close"></i></button>
            <?php include '../partials/barangayofficial/side-bar.php'; ?>
        </div>
        <div class="content-body">
            <div class="box">
                <div class="box-head">
                    <h4 class="title">Store List</h4>
                </div>
                <div class="box-body">
                    <!-- Cascading Dropdowns -->
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
                                    <th>Owner Name</th>
                                    <th>Province ID</th>
                                    <th>Municipal ID</th>
                                    <th>Barangay ID</th>
                                    <th>GeoJSON</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stores as $row): ?>
                                    <tr data-id="<?= $row['id'] ?>" data-owner_name="<?= htmlspecialchars($row['owner_name']) ?>">
                                        <td><?= $row['id'] ?></td>
                                        <td class="owner-name-cell"><?= htmlspecialchars($row['owner_name']) ?></td>
                                        <td><?= $row['province_id'] ?></td>
                                        <td><?= $row['municipal_id'] ?></td>
                                        <td><?= $row['barangay_id'] ?></td>
                                        <td><a href="../view_location.php?id=<?= $row['id'] ?>&type=store" target="_blank" class="btn btn-info btn-sm">View Location</a></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary edit-btn" 
                                                data-id="<?= $row['id'] ?>" 
                                                data-owner_name="<?= htmlspecialchars($row['owner_name']) ?>"
                                                data-province_id="<?= $row['province_id'] ?>"
                                                data-municipal_id="<?= $row['municipal_id'] ?>"
                                                data-barangay_id="<?= $row['barangay_id'] ?>"
                                                data-geojson="<?= htmlspecialchars($row['geojson'] ?? '') ?>"
                                            >Edit</button>
                                            <form method="post" action="" style="display:inline;" onsubmit="return confirm('Delete this store?');">
                                                <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Edit Store Modal -->
                    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form id="editStoreForm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Edit Store</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="edit_id" name="edit_id">
                                        <div class="form-group">
                                            <label for="owner_name">Owner Name</label>
                                            <input type="text" class="form-control" id="owner_name" name="owner_name" required>
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
                                            <label for="edit_map">Select Location on Map</label>
                                            <div id="edit_map" style="height: 350px; width: 100%; margin-bottom: 10px;"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_geojson">GeoJSON</label>
                                            <textarea class="form-control" id="edit_geojson" name="geojson" rows="3" required></textarea>
                                        </div>
                                        <div id="editStoreMsg"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
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
    <script src="../assets/js/vendor/jquery-3.3.1.min.js"></script>
    <script src="../assets/js/vendor/popper.min.js"></script>
    <script src="../assets/js/vendor/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/tippy4.min.js.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Province -> Municipality
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
                filterStoreTable();
            });
            // Municipality -> Barangay
            $('#municipalityFilter').on('change', function() {
                var municipalId = $(this).val();
                $('#barangayFilter').prop('disabled', true).html('<option value="">Select Barangay</option>');
                if (municipalId) {
                    $.get('/handler/barangayofficial/get_barangays.php', {
                        municipal_id: municipalId
                    }, function(data) {
                        var options = '<option value="">Select Barangay</option>';
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
                filterStoreTable();
            });
            // Barangay filter
            $('#barangayFilter').on('change', function() {
                filterStoreTable();
            });

            function filterStoreTable() {
                var provinceId = $('#provinceFilter').val();
                var municipalId = $('#municipalityFilter').val();
                var barangayId = $('#barangayFilter').val();
                $('table.table tbody tr').each(function() {
                    var row = $(this);
                    var rowProvince = row.find('td').eq(2).text().trim(); // province_id column
                    var rowMunicipal = row.find('td').eq(3).text().trim(); // municipal_id column
                    var rowBarangay = row.find('td').eq(4).text().trim(); // barangay_id column
                    var show = true;
                    if (provinceId && rowProvince !== provinceId) show = false;
                    if (municipalId && rowMunicipal !== municipalId) show = false;
                    if (barangayId && rowBarangay !== barangayId) show = false;
                    row.toggle(show);
                });
            }

            // Edit button click handler
            $('.edit-btn').on('click', function() {
                var id = $(this).data('id');
                var ownerName = $(this).data('owner_name');
                var provinceId = $(this).data('province_id');
                var municipalId = $(this).data('municipal_id');
                var barangayId = $(this).data('barangay_id');
                var geojson = $(this).data('geojson');
                $('#edit_id').val(id);
                $('#owner_name').val(ownerName);
                $('#edit_province_id').val(provinceId);
                $('#edit_geojson').val(geojson);
                // Populate municipalities
                if (provinceId) {
                    $.get('/handler/barangayofficial/get_municipalities.php', { province_id: provinceId }, function(data) {
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
                            $.get('/handler/barangayofficial/get_barangays.php', { municipal_id: municipalId }, function(data) {
                                var options = '<option value="">Select Barangay</option>';
                                if (Array.isArray(data) && data.length > 0) {
                                    data.forEach(function(b) {
                                        options += '<option value="' + b.id + '"' + (b.id == barangayId ? ' selected' : '') + '>' + b.barangay_name + '</option>';
                                    });
                                    $('#edit_barangay_id').html(options).prop('disabled', false);
                                } else {
                                    options += '<option value="" disabled>No barangays found</option>';
                                    $('#edit_barangay_id').html(options).prop('disabled', false);
                                }
                            }, 'json');
                        } else {
                            $('#edit_barangay_id').html('<option value="">Select Barangay</option>').prop('disabled', true);
                        }
                    }, 'json');
                } else {
                    $('#edit_municipal_id').html('<option value="">Select Municipality</option>').prop('disabled', true);
                    $('#edit_barangay_id').html('<option value="">Select Barangay</option>').prop('disabled', true);
                }
                $('#editStoreMsg').html('');
                $('#editModal').modal('show');
            });

            // Cascading dropdowns in modal
            $('#edit_province_id').on('change', function() {
                var provinceId = $(this).val();
                $('#edit_municipal_id').prop('disabled', true).html('<option value="">Select Municipality</option>');
                $('#edit_barangay_id').prop('disabled', true).html('<option value="">Select Barangay</option>');
                if (provinceId) {
                    $.get('/handler/barangayofficial/get_municipalities.php', { province_id: provinceId }, function(data) {
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
                if (municipalId) {
                    $.get('/handler/barangayofficial/get_barangays.php', { municipal_id: municipalId }, function(data) {
                        var options = '<option value="">Select Barangay</option>';
                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(function(b) {
                                options += '<option value="' + b.id + '">' + b.barangay_name + '</option>';
                            });
                            $('#edit_barangay_id').html(options).prop('disabled', false);
                        } else {
                            options += '<option value="" disabled>No barangays found</option>';
                            $('#edit_barangay_id').html(options).prop('disabled', false);
                        }
                    }, 'json');
                }
            });

            // AJAX form submit for editing store
            $('#editStoreForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $('#editStoreMsg').html('<span class="text-info">Saving...</span>');
                $.post('../handler/barangayofficial/update_store.php', formData, function(response) {
                    if (response.success) {
                        // Update the table row in-place
                        var id = $('#edit_id').val();
                        var newOwner = $('#owner_name').val();
                        var newProvince = $('#edit_province_id').val();
                        var newMunicipal = $('#edit_municipal_id').val();
                        var newBarangay = $('#edit_barangay_id').val();
                        var newGeojson = $('#edit_geojson').val();
                        var row = $('tr[data-id="' + id + '"]');
                        row.find('.owner-name-cell').text(newOwner);
                        row.find('td').eq(2).text(newProvince);
                        row.find('td').eq(3).text(newMunicipal);
                        row.find('td').eq(4).text(newBarangay);
                        row.find('.edit-btn').data('owner_name', newOwner)
                            .data('province_id', newProvince)
                            .data('municipal_id', newMunicipal)
                            .data('barangay_id', newBarangay)
                            .data('geojson', newGeojson);
                        $('#editStoreMsg').html('<span class="text-success">' + response.message + '</span>');
                        setTimeout(function() {
                            $('#editModal').modal('hide');
                        }, 1000);
                    } else {
                        $('#editStoreMsg').html('<span class="text-danger">' + response.message + '</span>');
                    }
                }, 'json').fail(function(xhr) {
                    $('#editStoreMsg').html('<span class="text-danger">An error occurred. Please try again.</span>');
                });
            });
        });
    </script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>
    <script>
        var editMap, editDrawnItems, editDrawControl, editMarkerLayer;
        function initEditMap(geojsonStr) {
            if (editMap) {
                editMap.remove();
                editMap = null;
            }
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
                    marker: true
                },
                edit: {
                    featureGroup: editDrawnItems,
                    remove: true
                }
            });
            editMap.addControl(editDrawControl);
            // If geojson exists, add marker
            if (geojsonStr) {
                try {
                    var geojson = JSON.parse(geojsonStr);
                    var marker = L.geoJSON(geojson).getLayers()[0];
                    if (marker) {
                        editDrawnItems.addLayer(marker);
                        editMap.setView(marker.getLatLng(), 16);
                    }
                } catch (e) {
                    // ignore
                }
            }
            editMap.on(L.Draw.Event.CREATED, function(e) {
                editDrawnItems.clearLayers();
                var layer = e.layer;
                editDrawnItems.addLayer(layer);
                var geojson = layer.toGeoJSON();
                $('#edit_geojson').val(JSON.stringify(geojson.geometry));
            });
            editMap.on('draw:edited', function(e) {
                var layers = e.layers;
                layers.eachLayer(function(layer) {
                    var geojson = layer.toGeoJSON();
                    $('#edit_geojson').val(JSON.stringify(geojson.geometry));
                });
            });
            editMap.on('draw:deleted', function() {
                $('#edit_geojson').val('');
            });
            // If marker exists, update geojson textarea
            if (editDrawnItems.getLayers().length > 0) {
                var layer = editDrawnItems.getLayers()[0];
                var geojson = layer.toGeoJSON();
                $('#edit_geojson').val(JSON.stringify(geojson.geometry));
            }
        }
        // When modal is shown, initialize map
        $('#editModal').on('shown.bs.modal', function () {
            var geojsonStr = $('#edit_geojson').val();
            initEditMap(geojsonStr);
            setTimeout(function() { editMap.invalidateSize(); }, 200);
        });
        // When modal is hidden, destroy map
        $('#editModal').on('hidden.bs.modal', function () {
            if (editMap) {
                editMap.remove();
                editMap = null;
            }
        });
        // When geojson textarea changes (e.g. from JS), re-render marker
        $('#edit_geojson').on('change', function() {
            if (!editMap) return;
            editDrawnItems.clearLayers();
            var geojsonStr = $(this).val();
            if (geojsonStr) {
                try {
                    var geojson = JSON.parse(geojsonStr);
                    var marker = L.geoJSON(geojson).getLayers()[0];
                    if (marker) {
                        editDrawnItems.addLayer(marker);
                        editMap.setView(marker.getLatLng(), 16);
                    }
                } catch (e) {}
            }
        });
    </script>

</body>
</html>
