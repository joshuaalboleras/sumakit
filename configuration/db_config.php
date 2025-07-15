<?php

$host = 'localhost';
$username ='root';
$pass = '';
$db = 'unknown';


try{
    $conn = new PDO("mysql:host=$host;dbname=$db",$username,$pass);
}catch(PDOException $e){
    echo "ERROR".$e->getMessage();
}

