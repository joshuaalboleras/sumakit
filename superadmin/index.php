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
                    <div class="header-right flex-grow-1 col-auto">
                        <div class="row justify-content-between align-items-center">

                            <!-- Side Header Toggle & Search Start -->
                            <div class="col-auto">
                                <div class="row align-items-center">

                                    <!--Side Header Toggle-->
                                    <div class="col-auto"><button class="side-header-toggle"><i class="zmdi zmdi-menu"></i></button></div>

                                    <!--Header Search-->
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

                            <!-- Header Notifications Area Start -->
                            <div class="col-auto">

                                <ul class="header-notification-area">

                                    <!--Language-->
                                    <li class="adomx-dropdown position-relative col-auto">
                                        <a class="toggle" href="#"><img class="lang-flag" src="../assets/images/flags/flag-1.jpg" alt=""><i class="zmdi zmdi-caret-down drop-arrow"></i></a>

                                        <!-- Dropdown -->
                                        <ul class="adomx-dropdown-menu dropdown-menu-language">
                                            <li><a href="#"><img src="../assets/images/flags/flag-1.jpg" alt=""> English</a></li>
                                            <li><a href="#"><img src="../assets/images/flags/flag-2.jpg" alt=""> Japanese</a></li>
                                            <li><a href="#"><img src="../assets/images/flags/flag-3.jpg" alt=""> Spanish </a></li>
                                            <li><a href="#"><img src="../assets/images/flags/flag-4.jpg" alt=""> Germany</a></li>
                                        </ul>

                                    </li>

                                    <!--Mail-->
                                    <li class="adomx-dropdown col-auto">
                                        <a class="toggle" href="#"><i class="zmdi zmdi-email-open"></i><span class="badge"></span></a>

                                        <!-- Dropdown -->
                                        <div class="adomx-dropdown-menu dropdown-menu-mail">
                                            <div class="head">
                                                <h4 class="title">You have 3 new mail.</h4>
                                            </div>
                                            <div class="body custom-scroll">
                                                <ul>
                                                    <li>
                                                        <a href="#">
                                                            <div class="image"><img src="../assets/images/avatar/avatar-2.jpg" alt=""></div>
                                                            <div class="content">
                                                                <h6>Sub: New Account</h6>
                                                                <p>There are many variations of passages of Lorem Ipsum available. </p>
                                                            </div>
                                                            <span class="reply"><i class="zmdi zmdi-mail-reply"></i></span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#">
                                                            <div class="image"><img src="../assets/images/avatar/avatar-1.jpg" alt=""></div>
                                                            <div class="content">
                                                                <h6>Sub: Mail Support</h6>
                                                                <p>There are many variations of passages of Lorem Ipsum available. </p>
                                                            </div>
                                                            <span class="reply"><i class="zmdi zmdi-mail-reply"></i></span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#">
                                                            <div class="image"><img src="../assets/images/avatar/avatar-2.jpg" alt=""></div>
                                                            <div class="content">
                                                                <h6>Sub: Product inquiry</h6>
                                                                <p>There are many variations of passages of Lorem Ipsum available. </p>
                                                            </div>
                                                            <span class="reply"><i class="zmdi zmdi-mail-reply"></i></span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#">
                                                            <div class="image"><img src="../assets/images/avatar/avatar-1.jpg" alt=""></div>
                                                            <div class="content">
                                                                <h6>Sub: Mail Support</h6>
                                                                <p>There are many variations of passages of Lorem Ipsum available. </p>
                                                            </div>
                                                            <span class="reply"><i class="zmdi zmdi-mail-reply"></i></span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                    </li>

                                    <!--Notification-->
                                    <li class="adomx-dropdown col-auto">
                                        <a class="toggle" href="#"><i class="zmdi zmdi-notifications"></i><span class="badge"></span></a>

                                        <!-- Dropdown -->
                                        <div class="adomx-dropdown-menu dropdown-menu-notifications">
                                            <div class="head">
                                                <h5 class="title">You have 4 new notification.</h5>
                                            </div>
                                            <div class="body custom-scroll">
                                                <ul>
                                                    <li>
                                                        <a href="#">
                                                            <i class="zmdi zmdi-notifications-none"></i>
                                                            <p>There are many variations of pages available.</p>
                                                            <span>11.00 am Today</span>
                                                        </a>
                                                        <button class="delete"><i class="zmdi zmdi-close-circle-o"></i></button>
                                                    </li>
                                                    <li>
                                                        <a href="#">
                                                            <i class="zmdi zmdi-block"></i>
                                                            <p>There are many variations of pages available.</p>
                                                            <span>11.00 am Today</span>
                                                        </a>
                                                        <button class="delete"><i class="zmdi zmdi-close-circle-o"></i></button>
                                                    </li>
                                                    <li>
                                                        <a href="#">
                                                            <i class="zmdi zmdi-info-outline"></i>
                                                            <p>There are many variations of pages available.</p>
                                                            <span>11.00 am Today</span>
                                                        </a>
                                                        <button class="delete"><i class="zmdi zmdi-close-circle-o"></i></button>
                                                    </li>
                                                    <li>
                                                        <a href="#">
                                                            <i class="zmdi zmdi-shield-security"></i>
                                                            <p>There are many variations of pages available.</p>
                                                            <span>11.00 am Today</span>
                                                        </a>
                                                        <button class="delete"><i class="zmdi zmdi-close-circle-o"></i></button>
                                                    </li>
                                                    <li>
                                                        <a href="#">
                                                            <i class="zmdi zmdi-notifications-none"></i>
                                                            <p>There are many variations of pages available.</p>
                                                            <span>11.00 am Today</span>
                                                        </a>
                                                        <button class="delete"><i class="zmdi zmdi-close-circle-o"></i></button>
                                                    </li>
                                                    <li>
                                                        <a href="#">
                                                            <i class="zmdi zmdi-block"></i>
                                                            <p>There are many variations of pages available.</p>
                                                            <span>11.00 am Today</span>
                                                        </a>
                                                        <button class="delete"><i class="zmdi zmdi-close-circle-o"></i></button>
                                                    </li>
                                                    <li>
                                                        <a href="#">
                                                            <i class="zmdi zmdi-info-outline"></i>
                                                            <p>There are many variations of pages available.</p>
                                                            <span>11.00 am Today</span>
                                                        </a>
                                                        <button class="delete"><i class="zmdi zmdi-close-circle-o"></i></button>
                                                    </li>
                                                    <li>
                                                        <a href="#">
                                                            <i class="zmdi zmdi-shield-security"></i>
                                                            <p>There are many variations of pages available.</p>
                                                            <span>11.00 am Today</span>
                                                        </a>
                                                        <button class="delete"><i class="zmdi zmdi-close-circle-o"></i></button>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="footer">
                                                <a href="#" class="view-all">view all</a>
                                            </div>
                                        </div>

                                    </li>

                                    <!--User-->
                                    <li class="adomx-dropdown col-auto">
                                        <a class="toggle" href="#">
                                            <span class="user">
                                                <span class="avatar">
                                                    <img src="../assets/images/avatar/avatar-1.jpg" alt="">
                                                    <span class="status"></span>
                                                </span>
                                                <span class="name">Madison Howard</span>
                                            </span>
                                        </a>

                                        <!-- Dropdown -->
                                        <div class="adomx-dropdown-menu dropdown-menu-user">
                                            <div class="head">
                                                <h5 class="name"><a href="#">Madison Howard</a></h5>
                                                <a class="mail" href="#">mailnam@mail.com</a>
                                            </div>
                                            <div class="body">
                                                <ul>
                                                    <li><a href="#"><i class="zmdi zmdi-account"></i>Profile</a></li>
                                                    <li><a href="#"><i class="zmdi zmdi-email-open"></i>Inbox</a></li>
                                                    <li><a href="#"><i class="zmdi zmdi-wallpaper"></i>Activity</a></li>
                                                </ul>
                                                <ul>
                                                    <li><a href="#"><i class="zmdi zmdi-settings"></i>Setting</a></li>
                                                    <li><a href="#"><i class="zmdi zmdi-lock-open"></i>Sing out</a></li>
                                                </ul>
                                                <ul>
                                                    <li><a href="#"><i class="zmdi zmdi-paypal"></i>Payment</a></li>
                                                    <li><a href="#"><i class="zmdi zmdi-google-pages"></i>Invoice</a></li>
                                                </ul>
                                            </div>
                                        </div>

                                    </li>

                                </ul>

                            </div><!-- Header Notifications Area End -->

                        </div>
                    </div><!-- Header Right End -->

                </div>
            </div>
        </div><!-- Header Section End -->
        <!-- Side Header Start -->
        <div class="side-header show">
            <button class="side-header-close"><i class="zmdi zmdi-close"></i></button>
            <!-- Side Header Inner Start -->
                <?= include '../partials/superadmin/nav.php'?>
            <!-- Side Header Inner End -->
        </div>
        
        <!-- Side Header End -->

        <!-- Content Body Start -->
        <div class="content-body">

            <!-- Page Headings Start -->
            <div class="row justify-content-between align-items-center mb-10">

                <!-- Page Heading Start -->
                <div class="col-12 col-lg-auto mb-20">
                    <div class="page-heading">
                        <h3>Dashboard <span>/ eCommerce</span></h3>
                    </div>
                </div><!-- Page Heading End -->

                <!-- Page Button Group Start -->
                <div class="col-12 col-lg-auto mb-20">
                    <div class="page-date-range">
                        <input type="text" class="form-control input-date-predefined">
                    </div>
                </div><!-- Page Button Group End -->

            </div><!-- Page Headings End -->

            <!-- Top Report Wrap Start -->
            <div class="row">
                <!-- Top Report Start -->
                <div class="col-xlg-3 col-md-6 col-12 mb-30">
                    <div class="top-report">

                        <!-- Head -->
                        <div class="head">
                            <h4>Total Visitor</h4>
                            <a href="#" class="view"><i class="zmdi zmdi-eye"></i></a>
                        </div>

                        <!-- Content -->
                        <div class="content">
                            <h5>Todays</h5>
                            <h2>100,560.00</h2>
                        </div>

                        <!-- Footer -->
                        <div class="footer">
                            <div class="progess">
                                <div class="progess-bar" style="width: 92%;"></div>
                            </div>
                            <p>92% of unique visitor</p>
                        </div>

                    </div>
                </div><!-- Top Report End -->

                <!-- Top Report Start -->
                <div class="col-xlg-3 col-md-6 col-12 mb-30">
                    <div class="top-report">

                        <!-- Head -->
                        <div class="head">
                            <h4>Product Sold</h4>
                            <a href="#" class="view"><i class="zmdi zmdi-eye"></i></a>
                        </div>

                        <!-- Content -->
                        <div class="content">
                            <h5>Todays</h5>
                            <h2>85,000.00</h2>
                        </div>

                        <!-- Footer -->
                        <div class="footer">
                            <div class="progess">
                                <div class="progess-bar" style="width: 98%;"></div>
                            </div>
                            <p>98% of unique visitor</p>
                        </div>

                    </div>
                </div><!-- Top Report End -->

                <!-- Top Report Start -->
                <div class="col-xlg-3 col-md-6 col-12 mb-30">
                    <div class="top-report">

                        <!-- Head -->
                        <div class="head">
                            <h4>Order Received</h4>
                            <a href="#" class="view"><i class="zmdi zmdi-eye"></i></a>
                        </div>

                        <!-- Content -->
                        <div class="content">
                            <h5>Todays</h5>
                            <h2>5,000.00</h2>
                        </div>

                        <!-- Footer -->
                        <div class="footer">
                            <div class="progess">
                                <div class="progess-bar" style="width: 88%;"></div>
                            </div>
                            <p>88% of unique visitor</p>
                        </div>

                    </div>
                </div><!-- Top Report End -->

                <!-- Top Report Start -->
                <div class="col-xlg-3 col-md-6 col-12 mb-30">
                    <div class="top-report">

                        <!-- Head -->
                        <div class="head">
                            <h4>Total Revenue</h4>
                            <a href="#" class="view"><i class="zmdi zmdi-eye"></i></a>
                        </div>

                        <!-- Content -->
                        <div class="content">
                            <h5>Todays</h5>
                            <h2>3,000,000.00</h2>
                        </div>

                        <!-- Footer -->
                        <div class="footer">
                            <div class="progess">
                                <div class="progess-bar" style="width: 76%;"></div>
                            </div>
                            <p>76% of unique visitor</p>
                        </div>

                    </div>
                </div><!-- Top Report End -->
            </div><!-- Top Report Wrap End -->

            <div class="row mbn-30">

                <!-- Revenue Statistics Chart Start -->
                <div class="col-md-8 mb-30">
                    <div class="box">
                        <div class="box-head">
                            <h4 class="title">Revenue Statistics</h4>
                        </div>
                        <div class="box-body">
                            <div class="chart-legends-1 row">
                                <div class="chart-legend-1 col-12 col-sm-4">
                                    <h5 class="title">Total Sale</h5>
                                    <h3 class="value text-secondary">$5000,000</h3>
                                </div>
                                <div class="chart-legend-1 col-12 col-sm-4">
                                    <h5 class="title">Total View</h5>
                                    <h3 class="value text-primary">10000,000</h3>
                                </div>
                                <div class="chart-legend-1 col-12 col-sm-4">
                                    <h5 class="title">Total Support</h5>
                                    <h3 class="value text-warning">100,000</h3>
                                </div>
                            </div>
                            <div class="chartjs-revenue-statistics-chart">
                                <canvas id="chartjs-revenue-statistics-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div><!-- Revenue Statistics Chart End -->

                <!-- Market Trends Chart Start -->
                <div class="col-md-4 mb-30">
                    <div class="box">
                        <div class="box-head">
                            <h4 class="title">Market Trends</h4>
                        </div>
                        <div class="box-body">
                            <div class="chartjs-market-trends-chart">
                                <canvas id="chartjs-market-trends-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div><!-- Market Trends Chart End -->

                <!-- Recent Transaction Start -->
                <div class="col-12 mb-30">
                    <div class="box">
                        <div class="box-head">
                            <h4 class="title">Recent Transaction</h4>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-vertical-middle table-selectable">

                                    <!-- Table Head Start -->
                                    <thead>
                                        <tr>
                                            <th class="selector"><label class="adomx-checkbox"><input type="checkbox"> <i class="icon"></i></label></th>
                                            <!--<th class="selector h5"><button class="button-check"></button></th>-->
                                            <th><span>Image</span></th>
                                            <th><span>Product Name</span></th>
                                            <th><span>ID</span></th>
                                            <th><span>Quantity</span></th>
                                            <th><span>Price</span></th>
                                            <th><span>Status</span></th>
                                            <th></th>
                                        </tr>
                                    </thead><!-- Table Head End -->

                                    <!-- Table Body Start -->
                                    <tbody>
                                        <tr>
                                            <td class="selector"><label class="adomx-checkbox"><input type="checkbox"> <i class="icon"></i></label></td>
                                            <td><img src="assets/images/product/list-product-1.jpg" alt="" class="table-product-image rounded-circle"></td>
                                            <td><a href="#">Microsoft surface pro 4</a></td>
                                            <td>#MSP40022</td>
                                            <td>05 - Products</td>
                                            <td>$60000000.00</td>
                                            <td><span class="badge badge-success">Paid</span></td>
                                            <td><a class="h3" href="#"><i class="zmdi zmdi-more"></i></a></td>
                                        </tr>
                                        <tr class="selected">
                                            <td class="selector"><label class="adomx-checkbox"><input type="checkbox"> <i class="icon"></i></label></td>
                                            <td><img src="assets/images/product/list-product-2.jpg" alt="" class="table-product-image rounded-circle"></td>
                                            <td><a href="#">Microsoft surface pro 4</a></td>
                                            <td>#MSP40022</td>
                                            <td>05 - Products</td>
                                            <td>$60000000.00</td>
                                            <td><span class="badge badge-success">Paid</span></td>
                                            <td><a class="h3" href="#"><i class="zmdi zmdi-more"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td class="selector"><label class="adomx-checkbox"><input type="checkbox"> <i class="icon"></i></label></td>
                                            <td><img src="assets/images/product/list-product-3.jpg" alt="" class="table-product-image rounded-circle"></td>
                                            <td><a href="#">Microsoft surface pro 4</a></td>
                                            <td>#MSP40022</td>
                                            <td>05 - Products</td>
                                            <td>$60000000.00</td>
                                            <td><span class="badge badge-warning">Due</span></td>
                                            <td><a class="h3" href="#"><i class="zmdi zmdi-more"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td class="selector"><label class="adomx-checkbox"><input type="checkbox"> <i class="icon"></i></label></td>
                                            <td><img src="assets/images/product/list-product-4.jpg" alt="" class="table-product-image rounded-circle"></td>
                                            <td><a href="#">Microsoft surface pro 4</a></td>
                                            <td>#MSP40022</td>
                                            <td>05 - Products</td>
                                            <td>$60000000.00</td>
                                            <td><span class="badge badge-danger">Reject</span></td>
                                            <td><a class="h3" href="#"><i class="zmdi zmdi-more"></i></a></td>
                                        </tr>
                                    </tbody><!-- Table Body End -->

                                </table>
                            </div>
                        </div>
                    </div>
                </div><!-- Recent Transaction End -->
            </div>


            <!-- MUNICIPALITIES MAP VIEWER START -->
            <div class="row mbn-30 p-5">
                <div class="col-12">
                    <h1>Welcome Super Admin</h1>
                    <p>Search and explore saved municipalities on the map below.</p>
                    <div class="filters" style="display: flex; gap: 10px; margin-bottom: 10px;">
                        <input type="text" id="search" placeholder="Search municipality name..." style="padding: 5px; font-size: 14px;" />
                        <select id="provinceFilter" style="padding: 5px; font-size: 14px;">
                            <option value="">All Provinces</option>
                            <?php foreach (
                                $provinces as $prov
                            ): ?>
                                <option value="<?= htmlspecialchars($prov['province_name']) ?>">
                                    <?= htmlspecialchars($prov['province_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div id="map" style="height: 500px; width: 100%; border: 1px solid #ccc;"></div>
                </div>
            </div>
            <!-- MUNICIPALITIES MAP VIEWER END -->
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