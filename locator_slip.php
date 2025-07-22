<?php
include './configuration/config.php';
include './configuration/routes.php';
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
    <link rel="shortcut icon" type="image/x-icon" href="./assets/images/favicon.ico">

    <!-- CSS
	============================================ -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./assets/css/vendor/bootstrap.min.css">

    <!-- Icon Font CSS -->
    <link rel="stylesheet" href="./assets/css/vendor/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="./assets/css/vendor/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/vendor/themify-icons.css">
    <link rel="stylesheet" href="./assets/css/vendor/cryptocurrency-icons.css">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="./assets/css/plugins/plugins.css">

    <!-- Helper CSS -->
    <link rel="stylesheet" href="./assets/css/helper.css">

    <!-- Main Style CSS -->
    <link rel="stylesheet" href="./assets/css/style.css">

    <!-- Custom Style CSS Only For Demo Purpose -->
    <link id="cus-style" rel="stylesheet" href="./assets/css/style-primary.css">
    <!-- Leaflet CSS for Map Viewer -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

</head>

<body class="skin-dark">

    <div class="main-wrapper">


        <!-- Header Section Start -->
        <div class="header-section">
            <div class="container-fluid">
                <div class="row justify-content-between align-items-center">

        

                </div>
            </div>
        </div><!-- Header Section End -->
        <!-- Side Header Start -->
      
        <!-- Content Body Start -->
        <div class="content-body">
            <div class="container-fluid">
                <h3>Locator Slip Builder</h3>
                
                <!-- Mode Selection -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="btn-group" role="group" aria-label="Drawing Mode">
                            <button type="button" id="checkpoint-mode" class="btn btn-primary active">Checkpoint Mode</button>
                            <button type="button" id="route-mode" class="btn btn-secondary">Route Mode</button>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <button id="delete-last-checkpoint" class="btn btn-warning">Delete Last Checkpoint</button>
                        <button id="delete-last-route" class="btn btn-info">Delete Last Route Point</button>
                        <button id="clear-all" class="btn btn-danger">Clear All</button>
                    </div>
                </div>
                
                <div id="map" style="height: 500px;"></div>
                <div class="row">
                    <div class="mt-3 col">
                        <input type="text" id="slip-name" class="form-control mb-2" placeholder="Enter slip name">
                        <button id="save-slip" class="btn btn-success">Save Locator Slip</button>
                        <span id="save-status" class="ml-2"></span>
                    </div>
                    <div class="mt-3 col">
                        <input type="text" id="purpose" class="form-control mb-2" placeholder="Purpose">
                        <span id="purpose-status" class="ml-2"></span>
                    </div>
                </div>
                <div id="qr-container" class="mt-3" style="display:none;">
                    <h5>Share this Locator Slip:</h5>
                    <div id="qrcode"></div>
                    <a id="download-qr" class="btn btn-outline-primary mt-2" download="locator_slip_qr.png">Download QR Code</a>
                </div>
                <div class="mt-4">
                    <h5>Instructions:</h5>
                    <ul>
                        <li><strong>Checkpoint Mode:</strong> Click on the map to add <b>checkpoints</b> (red markers).</li>
                        <li><strong>Route Mode:</strong> Click on the map to draw a <b>route</b> (blue line with waypoints).</li>
                        <li>You can combine both checkpoints and routes in the same locator slip.</li>
                        <li>Drag markers to adjust their position.</li>
                        <li>Click a marker to remove it.</li>
                        <li>Use <b>Clear All</b> to reset the map.</li>
                        <li>Click <b>Save Locator Slip</b> to store the route and checkpoints.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Footer Section Start -->
        <div class="footer-section">
            <div class="container-fluid">

                <div class="footer-copyright text-center">
                    <p class="text-body-light"><a href="">Joshua Alboleras & Team</a></p>
                </div>

            </div>
        </div><!-- Footer Section End -->

    </div>

    <!-- JS
============================================ -->

    <!-- Global Vendor, plugins & Activation JS -->
    <script src="./assets/js/vendor/modernizr-3.6.0.min.js"></script>
    <script src="./assets/js/vendor/jquery-3.3.1.min.js"></script>
    <script src="./assets/js/vendor/popper.min.js"></script>
    <script src="./assets/js/vendor/bootstrap.min.js"></script>
    <!--Plugins JS-->
    <script src="./assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="./assets/js/plugins/tippy4.min.js.js"></script>
    <!--Main JS-->
    <script src="./assets/js/main.js"></script>

    <!-- Plugins & Activation JS For Only This Page -->

    <!--Moment-->
    <script src="./assets/js/plugins/moment/moment.min.js"></script>

    <!--Daterange Picker-->
    <script src="./assets/js/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="./assets/js/plugins/daterangepicker/daterangepicker.active.js"></script>

    <!--Echarts-->
    <script src="./assets/js/plugins/chartjs/Chart.min.js"></script>
    <script src="./assets/js/plugins/chartjs/chartjs.active.js"></script>

    <!--VMap-->
    <script src="./assets/js/plugins/vmap/jquery.vmap.min.js"></script>
    <script src="./assets/js/plugins/vmap/maps/jquery.vmap.world.js"></script>
    <script src="./assets/js/plugins/vmap/maps/samples/jquery.vmap.sampledata.js"></script>
    <script src="./assets/js/plugins/vmap/vmap.active.js"></script>
    <!-- Leaflet JS for Map Viewer -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <!-- QRCode.js for QR generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
    let map = L.map('map').setView([10.3157, 123.8854], 13); // Cebu City default
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    let currentMode = 'checkpoint'; // 'checkpoint' or 'route'
    let checkpoints = [];
    let checkpointMarkers = [];
    let routePoints = [];
    let routeMarkers = [];
    let routeLine = null;

    // Mode switching
    $('#checkpoint-mode').on('click', function() {
        currentMode = 'checkpoint';
        $(this).removeClass('btn-secondary').addClass('btn-primary');
        $('#route-mode').removeClass('btn-primary').addClass('btn-secondary');
    });

    $('#route-mode').on('click', function() {
        currentMode = 'route';
        $(this).removeClass('btn-secondary').addClass('btn-primary');
        $('#checkpoint-mode').removeClass('btn-primary').addClass('btn-secondary');
    });

    // Clear all function
    $('#clear-all').on('click', function() {
        checkpointMarkers.forEach(m => map.removeLayer(m));
        routeMarkers.forEach(m => map.removeLayer(m));
        if (routeLine) map.removeLayer(routeLine);
        
        checkpointMarkers = [];
        checkpoints = [];
        routeMarkers = [];
        routePoints = [];
        routeLine = null;
    });

    function redrawRoute() {
        if (routeLine) map.removeLayer(routeLine);
        if (routePoints.length > 1) {
            routeLine = L.polyline(routePoints, {color: 'blue', weight: 4}).addTo(map);
        }
    }

    function updateGeoJson() {
        let features = [];
        
        // Add checkpoints
        checkpoints.forEach((latlng, i) => {
            features.push({
                type: 'Feature',
                geometry: {type: 'Point', coordinates: [latlng.lng, latlng.lat]},
                properties: {type: 'checkpoint', order: i+1}
            });
        });
        
        // Add route points
        routePoints.forEach((latlng, i) => {
            features.push({
                type: 'Feature',
                geometry: {type: 'Point', coordinates: [latlng.lng, latlng.lat]},
                properties: {type: 'route_point', order: i+1}
            });
        });
        
        // Add route line
        if (routePoints.length > 1) {
            features.push({
                type: 'Feature',
                geometry: {type: 'LineString', coordinates: routePoints.map(ll => [ll.lng, ll.lat])},
                properties: {type: 'route_line'}
            });
        }
        
        return JSON.stringify({type: 'FeatureCollection', features: features});
    }

    function addCheckpoint(latlng) {
        let marker = L.circleMarker(latlng, {
            color: 'red',
            fillColor: 'red',
            fillOpacity: 1,
            radius: 10,
            weight: 2
        });
        marker.addTo(map);
        marker.on('click', function() {
            let idx = checkpointMarkers.indexOf(marker);
            if (idx !== -1) {
                map.removeLayer(marker);
                checkpointMarkers.splice(idx, 1);
                checkpoints.splice(idx, 1);
            }
        });
        // Make circle marker draggable
        marker.dragging = false;
        marker.on('mousedown', function(e) {
            marker.dragging = true;
            map.on('mousemove', onDrag);
            map.on('mouseup', onDrop);
            function onDrag(ev) {
                if (marker.dragging) {
                    marker.setLatLng(ev.latlng);
                    let idx = checkpointMarkers.indexOf(marker);
                    if (idx !== -1) {
                        checkpoints[idx] = ev.latlng;
                    }
                }
            }
            function onDrop(ev) {
                marker.dragging = false;
                map.off('mousemove', onDrag);
                map.off('mouseup', onDrop);
            }
        });
        checkpointMarkers.push(marker);
        checkpoints.push(latlng);
    }

    function addRoutePoint(latlng) {
        let marker = L.circleMarker(latlng, {
            color: 'blue',
            fillColor: 'blue',
            fillOpacity: 1,
            radius: 8,
            weight: 2
        });
        marker.addTo(map);
        // Add popup for conversion
        marker.bindPopup('<button class="btn btn-sm btn-warning convert-to-checkpoint">Convert to Checkpoint</button>');
        marker.on('popupopen', function(e) {
            setTimeout(function() {
                const popupNode = marker.getPopup().getElement();
                if (popupNode) {
                    const btn = popupNode.querySelector('.convert-to-checkpoint');
                    if (btn) {
                        btn.onclick = function(ev) {
                            ev.preventDefault();
                            let idx = routeMarkers.indexOf(marker);
                            if (idx !== -1) {
                                // Visually change marker to red and update event handlers
                                marker.setStyle({color: 'red', fillColor: 'red', radius: 10});
                                marker.closePopup();
                                marker.unbindPopup();
                                // Remove from routeMarkers, but keep in routePoints for polyline
                                routeMarkers.splice(idx, 1);
                                // Add to checkpointMarkers/checkpoints if not already present
                                if (checkpointMarkers.indexOf(marker) === -1) {
                                    checkpointMarkers.push(marker);
                                    checkpoints.push(marker.getLatLng());
                                }
                                // Remove old route marker events
                                marker.off('click');
                                marker.off('dblclick');
                                // Add checkpoint events
                                marker.on('click', function() {
                                    let cidx = checkpointMarkers.indexOf(marker);
                                    if (cidx !== -1) {
                                        map.removeLayer(marker);
                                        checkpointMarkers.splice(cidx, 1);
                                        checkpoints.splice(cidx, 1);
                                    }
                                });
                                // Drag logic for checkpoint
                                marker.dragging = false;
                                marker.on('mousedown', function(e) {
                                    marker.dragging = true;
                                    map.on('mousemove', onDrag);
                                    map.on('mouseup', onDrop);
                                    function onDrag(ev) {
                                        if (marker.dragging) {
                                            marker.setLatLng(ev.latlng);
                                            let cidx = checkpointMarkers.indexOf(marker);
                                            if (cidx !== -1) {
                                                checkpoints[cidx] = ev.latlng;
                                            }
                                        }
                                    }
                                    function onDrop(ev) {
                                        marker.dragging = false;
                                        map.off('mousemove', onDrag);
                                        map.off('mouseup', onDrop);
                                    }
                                });
                            }
                        };
                    }
                }
            }, 0);
        });
        marker.on('click', function(e) {
            marker.openPopup();
        });
        // Remove marker on double click (only for route points)
        marker.on('dblclick', function() {
            let idx = routeMarkers.indexOf(marker);
            if (idx !== -1) {
                map.removeLayer(marker);
                routeMarkers.splice(idx, 1);
                routePoints.splice(idx, 1);
                redrawRoute();
            }
        });
        // Make circle marker draggable
        marker.dragging = false;
        marker.on('mousedown', function(e) {
            marker.dragging = true;
            map.on('mousemove', onDrag);
            map.on('mouseup', onDrop);
            function onDrag(ev) {
                if (marker.dragging) {
                    marker.setLatLng(ev.latlng);
                    let idx = routeMarkers.indexOf(marker);
                    if (idx !== -1) {
                        routePoints[idx] = ev.latlng;
                        redrawRoute();
                    } else {
                        // If this marker was converted to checkpoint, update its latlng in checkpoints
                        let cidx = checkpointMarkers.indexOf(marker);
                        if (cidx !== -1) {
                            checkpoints[cidx] = ev.latlng;
                        }
                    }
                }
            }
            function onDrop(ev) {
                marker.dragging = false;
                map.off('mousemove', onDrag);
                map.off('mouseup', onDrop);
            }
        });
        routeMarkers.push(marker);
        routePoints.push(latlng);
        redrawRoute();
    }

    // Delete last checkpoint
    $('#delete-last-checkpoint').on('click', function() {
        if (checkpointMarkers.length > 0) {
            let marker = checkpointMarkers.pop();
            map.removeLayer(marker);
            checkpoints.pop();
        }
    });
    // Delete last route point
    $('#delete-last-route').on('click', function() {
        if (routeMarkers.length > 0) {
            let marker = routeMarkers.pop();
            map.removeLayer(marker);
            routePoints.pop();
            redrawRoute();
        }
    });

    map.on('click', function(e) {
        if (currentMode === 'checkpoint') {
            addCheckpoint(e.latlng);
        } else if (currentMode === 'route') {
            addRoutePoint(e.latlng);
        }
    });

    $('#save-slip').on('click', function() {
        let name = $('#slip-name').val().trim();
        let purpose = $('#purpose').val().trim();
        if (!name) {
            $('#save-status').text('Please enter a slip name.').css('color', 'red');
            return;
        }
        if (!purpose) {
            $('#purpose-status').text('Please enter Purpose.').css('color', 'red');
            return;
        }
        if (checkpoints.length === 0 && routePoints.length === 0) {
            $('#save-status').text('Add at least one checkpoint or route point.').css('color', 'red');
            return;
        }
        let geojson = updateGeoJson();
        $.post('handler/locator_slip/save.php', {name: name, geojson: geojson, purpose: purpose}, function(resp) {
            if (resp.success && resp.id) {
                $('#save-status').text('Saved!').css('color', 'green');
                // Optionally clear map
                $('#clear-all').click();
                $('#slip-name').val('');
                // Generate QR code
                let url = 'http://192.168.1.3/macaldos-kyuts/view_location.php?type=locator_slip&id=' + resp.id;
                $('#qr-container').show();
                $('#qrcode').empty();
                let qr = new QRCode(document.getElementById("qrcode"), {
                    text: url,
                    width: 200,
                    height: 200
                });
                // Download QR code as image
                setTimeout(function() {
                    let img = $('#qrcode img')[0];
                    if (img) {
                        $('#download-qr').attr('href', img.src);
                    }
                }, 500);
            } else {
                $('#save-status').text(resp.message || 'Error saving.').css('color', 'red');
                $('#qr-container').hide();
            }
        }, 'json').fail(function() {
            $('#save-status').text('Error saving.').css('color', 'red');
            $('#qr-container').hide();
        });
    });
    </script>

</body>

</html>