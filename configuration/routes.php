<?php 
$page = explode('/',$_SERVER['PHP_SELF']);
$page = $page[count($page) - 2].'/'.$page[count($page)-1];

$role_name = strtolower($_SESSION['user']['role_name'] ?? 'visitor');
$redirect = strtolower($_SESSION['user']['redirect_to'] ?? '../index.php');

$superadmin = [
    'superadmin/index.php',
    'superadmin/registration.php'
];

$municipal_official = [
    'municipalofficial/index.php',
];

$barangay_official = [
    '/index',
    'barangayofficial/index.php'
];

$visitor = [
    '/index.php',
    'logout.php'
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