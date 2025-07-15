<?php

function dump($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}


function onerror($key,$truthy,$falsy = ''){
    echo isset($_SESSION['errors'][$key]) ? $truthy:$falsy; 
}