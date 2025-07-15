<?php

$host = 'localhost';
$username ='root';
$pass = '';
$db = 'unknown';


try{
    $conn = new PDO("mysql:host=$host;dbname=$db",$username,$pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    
}catch(PDOException $e){
    echo "ERROR".$e->getMessage();
}

