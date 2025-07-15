<?php
session_start();
if(!isset($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
include 'functions.php';
include 'db_config.php';

