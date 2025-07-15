<?php
include '../../configuration/config.php'; 

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $proceed = true;
    $allowed = ['name','email','password','role_id'];
    foreach($_POST as $key => $value){
        $$key = $value;
    }

    if(!hash_equals($csrf_token,$_SESSION['csrf_token'])){
        header('location:../../superadmin/registration.php');
        exit;
    };

    if($name == '' || $name == false){
        $_SESSION['errors']['name'][] = 'Field Required';
        $proceed = $false;
    }
    if($email == '' || $email == false){
        $_SESSION['errors']['email'][] = 'Field Required';
        $proceed = $false;
    }

    if(!filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL)){
        $_SESSION['errors']['email'][] = 'Not a valid email';
        $proceed = $false;
    }

    if($password == '' || $password == false){
        $_SESSION['errors']['password'][] = 'Field Required';
        $proceed = $false;
    }
    
    if(!filter_input(INPUT_POST,'role_id',FILTER_VALIDATE_INT)){
        $_SESSION['errors']['role'][] = "Not Valid";
        $proceed = $false;
    }

    if(!$proceed){  
        header('location:../../superadmin/registration.php');
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO users(role_id,name,email,password) VALUES(:role_id,:name,:email,:password)");
    $stmt->execute([
        ':role_id' => $role_id,
        ':name' => $name,
        ':email' => $email,
        ':password' => password_hash($password,PASSWORD_DEFAULT)
    ]);

       header('location:../../superadmin/registration.php');
}
