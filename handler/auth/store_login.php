<?php
include '../../configuration/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $proceed = true;
    $_SESSION['errors'] = [];

    if ($username === '') {
        $_SESSION['errors']['username'][] = 'Username is required';
        $proceed = false;
    }
    if ($password === '') {
        $_SESSION['errors']['password'][] = 'Password is required';
        $proceed = false;
    }
    if (!$proceed) {
        header('Location: ../../index.php');
        exit;
    }

    $stmt = $conn->prepare('SELECT * FROM stores WHERE username = :username');
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute([':username' => $username]);
    $store = $stmt->fetch();
    
    if ($store && password_verify($password, $store['password'])) {
        $_SESSION['user'] = [
            'id' => $store['id'],
            'role_name' => 'store owner',
            'redirect_to' => 'storeowner/index.php',
            'username' => $store['username'],
            'owner_name' => $store['owner_name'],
            'barangay_id' => $store['barangay_id'],
            'municipal_id' => $store['municipal_id'],
            'province_id' => $store['province_id']
        ];
        $_SESSION['message'] = 'Successfully authenticated as store owner.';
        header('Location: ../../storeowner/index.php'); // Change to store dashboard if available
        exit;
        
    } else {
        $_SESSION['message'] = 'Invalid store username or password.';
        header('Location: ../../index.php');
        exit;
    }
} else {
    header('Location: ../../index.php');
    exit;
} 