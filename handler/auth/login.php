<?php 
include '../../configuration/config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $proceed = true;

    $allowed = ['name','email','password','role_id'];
    foreach($_POST as $key => $value){
        $$key = $value;
    }

    if(strlen($email) == 0 || $email == ''){
        session_start();
        $_SESSION['errors']['email'][] = 'Field Required'; 
        $proceed = false;
    }

    if(!filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL)){
        session_start();
        $_SESSION['errors']['email'][] = 'Not a valid email'; 
        $proceed = false;
    }

    if($password == '' || $password == false){
        session_start();
        $_SESSION['errors']['password'][] = 'Required Field';
        $proceed = false;
    }


    if(!$proceed){
        header("Location:../../index.php");
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM users  INNER JOIN roles ON users.role_id = roles.id WHERE email = :email");
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute([
        ':email' => $email,
    ]);
    $user = $stmt->fetch();

    if(password_verify($password,$user['password'])){
        session_start();
        $_SESSION['user'] = $user;
        $_SESSION['message'] = 'Successfuly authenticated';
        header("Location:../../{$user['redirect_to']}");
    }else{
        session_start();
        $_SESSION['message'] = 'We dont have any matches of these credentials';
        header("Location:../../index.php");
    }
    
}