<?php
include './configuration/config.php';
include './configuration/routes.php';
if (isset($_SESSION['message'])) {
    unset($_SESSION['message']);
}

if (isset($_SESSION['errors'])) {
    foreach ($_SESSION['errors'] as $key => $value) {
        $$key = $_SESSION['errors'][$key][0];
    }
}
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
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico">

    <!-- CSS
	============================================ -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">

    <!-- Icon Font CSS -->
    <link rel="stylesheet" href="assets/css/vendor/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="assets/css/vendor/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/vendor/themify-icons.css">
    <link rel="stylesheet" href="assets/css/vendor/cryptocurrency-icons.css">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="assets/css/plugins/plugins.css">

    <!-- Helper CSS -->
    <link rel="stylesheet" href="assets/css/helper.css">

    <!-- Main Style CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Custom Style CSS Only For Demo Purpose -->
    <link id="cus-style" rel="stylesheet" href="assets/css/style-primary.css">

</head>

<body class="skin-dark">

    <div class="main-wrapper">

        <!-- Content Body Start -->
        <div class="content-body m-0 p-0">

            <div class="login-register-wrap">
                <div class="row">

                    <div class="d-flex align-self-center justify-content-center order-2 order-lg-1 col-lg-5 col-12">
                        <div class="login-register-form-wrap">
                            <div class="content">
                                <h1>Sign in</h1>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                            </div>

                            <div class="login-type-toggle mb-3 text-center">
                                <button type="button" class="btn btn-outline-primary btn-sm mr-2" id="adminLoginBtn">Admin/Official Login</button>
                                <button type="button" class="btn btn-outline-success btn-sm" id="storeLoginBtn">Store Owner Login</button>
                            </div>
                            <div class="login-register-form" id="adminLoginForm">
                                <form action="./handler/auth/login.php" method="post">
                                    <div class="row">
                                        <div class="col-12 mb-20"><input class="form-control <?php onerror('email','danger','')?>" type="text" name="email" placeholder="<?= $email ?? 'Enter email' ?>"></div>
                                        <div class="col-12 mb-20"><input class="form-control <?php onerror('password','danger','') ?>" type="password" name="password" placeholder="<?= $password ?? 'Enter Password' ?> "></div>
                                        <div class="col-12 mb-20"><label for="remember" class="adomx-checkbox-2"><input id="remember" type="checkbox"><i class="icon"></i>Remember.</label></div>
                                        <div class="col-12">
                                            <div class="row justify-content-between">
                                                <div class="col-auto mb-15"><a href="#">Forgot Password?</a></div>
                                                <div class="col-auto mb-15">Dont have account? <a href="register.html">Create Now.</a></div>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-10"><button class="button button-primary button-outline">sign in</button></div>
                                    </div>
                                </form>
                            </div>
                            <div class="login-register-form" id="storeLoginForm" style="display:none;">
                                <form action="./handler/auth/store_login.php" method="post">
                                    <div class="row">
                                        <div class="col-12 mb-20"><input class="form-control" type="text" name="username" placeholder="Enter store username"></div>
                                        <div class="col-12 mb-20"><input class="form-control" type="password" name="password" placeholder="Enter store password"></div>
                                        <div class="col-12 mt-10"><button class="button button-success button-outline">sign in as store owner</button></div>
                                    </div>
                                </form>
                            </div>
                            <script>
                                document.getElementById('adminLoginBtn').onclick = function() {
                                    document.getElementById('adminLoginForm').style.display = '';
                                    document.getElementById('storeLoginForm').style.display = 'none';
                                };
                                document.getElementById('storeLoginBtn').onclick = function() {
                                    document.getElementById('adminLoginForm').style.display = 'none';
                                    document.getElementById('storeLoginForm').style.display = '';
                                };
                            </script>
                        </div>
                    </div>

                    <div class="login-register-bg order-1 order-lg-2 col-lg-7 col-12">
                        <div class="content">
                            <h1>Sign in</h1>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                        </div>
                    </div>

                </div>
            </div>

        </div><!-- Content Body End -->

    </div>

    <!-- JS
============================================ -->

    <!-- Global Vendor, plugins & Activation JS -->
    <script src="assets/js/vendor/modernizr-3.6.0.min.js"></script>
    <script src="assets/js/vendor/jquery-3.3.1.min.js"></script>
    <script src="assets/js/vendor/popper.min.js"></script>
    <script src="assets/js/vendor/bootstrap.min.js"></script>
    <!--Plugins JS-->
    <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="assets/js/plugins/tippy4.min.js.js"></script>
    <!--Main JS-->
    <script src="assets/js/main.js"></script>

</body>

</html>
<?php 
unset($_SESSION['errors']);
?>