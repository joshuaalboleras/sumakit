<?php 
$page = explode('/',$_SERVER['PHP_SELF']);
$page = $page[count($page) - 2].'/'.$page[count($page)-1];

$role_name = strtolower($_SESSION['user']['role_name'] ?? 'visitor');
$redirect = strtolower($_SESSION['user']['redirect_to'] ?? '../index.php');

$superadmin = [
    '/locator_slip.php',
    'superadmin/index.php',
    'superadmin/registration.php',
    'superadmin/register-household.php',
    'superadmin/main.php',
    'superadmin/management.php',
    'superadmin/manage-houses.php',
    'superadmin/manage-households.php',
    'superadmin/manage-stores.php',
    'superadmin/store-registration.php',

];

$municipal_official = [
    '/locator_slip.php',
    'municipalofficial/index.php',
    'municipalofficial/registration.php',
    'municipalofficial/manage-barangays.php',
];

$barangay_official = [
    '/index',
    '/locator_slip.php',
    'barangayofficial/index.php',
    'barangayofficial/registration.php',
    'barangayofficial/store-registration.php',
    'barangayofficial/manage-houses.php',
    'barangayofficial/manage-households.php',
    'barangayofficial/manage-stores.php',
];

$visitor = [
    '/index.php',
    'logout.php',
    'login.php'
];

if($role_name == 'super admin'){
    if(!in_array($page,$superadmin)){
        header("Location:../{$redirect}");
    }
}

if($role_name == 'visitor'){
    if(!in_array($page,$visitor)){
        header("Location:{$redirect}");
    }
}

if($role_name == 'municipal official'){
     if(!in_array($page,$municipal_official)){
        header("Location:../{$redirect}");
    }
}

if($role_name == 'barangay official'){
    if(!in_array($page,$barangay_official)){
         header("Location:../{$redirect}");
    }
}