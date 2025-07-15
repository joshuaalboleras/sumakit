<?php
include '../../configuration/config.php'; 

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $allowed = ['name','email','password','role_id'];
    foreach($_POST as $key => $value){
        $$key = $value;
    }

    if(!hash_equals($csrf_token,$_SESSION['csrf_token'])){
        header('location:../../superadmin/register.php');
        exit;
    };

    $stmt = $conn->prepare("INSERT INTO users(role_id,name,email,password) VALUES(:role_id,:name,:email,:password)");
    $stmt->execute([
        ':role_id' => $role_id,
        ':name' => $name,
        ':email' => $email,
        ':password' => password_hash($password,PASSWORD_DEFAULT)
    ]);

    header('location:../');
}
