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
         <?= include '../partials/municipalofficial/side-bar.php'?>
       <!-- Side Header End -->

        <!-- Content Body Start -->
        <div class="content-body">

        


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
   

</body>

</html>