<?php 
include '../../configuration/config.php'; 

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $proceed = true;
    $allowed = ['province_name'];
    foreach($_POST as $key => $value){
        $$key = $value;
    }

    if(!hash_equals($csrf_token,$_SESSION['csrf_token'])){
        header('location:../../superadmin/registration.php');
        exit;
    };
    
    if($province_name == '' || $province_name == null || $province_name == false){
        $proceed = false;
        $_SESSION['errors']['province_name'][] = 'Required Field';
    }

    if(!$proceed){
        header('location:../../superadmin/registration.php');
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO provinces(province_name) VALUES(:province_name)");
    $stmt->execute([
        ':province_name' => $province_name
    ]);
    header('location:../../superadmin/registration.php');
}
