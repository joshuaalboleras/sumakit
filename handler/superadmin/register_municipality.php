<?php 
include '../../configuration/config.php'; 

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $allowed = ['province_id','municipality'];
    foreach($_POST as $key => $value){
        $$key = $value;
    }
    
    if(!hash_equals($csrf_token,$_SESSION['csrf_token'])){
        header('location:../../superadmin/register.php');
        exit;
    };

    $stmt = $conn->prepare("INSERT INTO municipalities(province_id,municipality) VALUES(:province_id,:municipality)");
    $stmt->execute([
        ':province_id' => $province_id,
        ':municipality' => $municipality
    ]);
    header('location:../../superadmin/register.php');
}
