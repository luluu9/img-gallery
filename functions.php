<?php
require '../../vendor/autoload.php';

if(!isset($_SESSION)) { 
    session_start(); 
}

function get_db() {
    $mongo = new MongoDB\Client(
        "mongodb://localhost:27017/wai",
        [
            'username' => 'wai_web',
            'password' => 'w@i_w3b',
        ]);

    $db = $mongo->wai;

    return $db;
}


function startsWith($string, $startString) {
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}
 

function getCurrentUserProducts($products) {
    $userProducts = [];
    foreach ($products as $key=>$product) {
        if (isset($product['user'])) {
            if (!isset($_SESSION['username']) || $_SESSION['username'] !== $product['user']) {
                continue;
            }
        }
        array_push($userProducts, $product);
    }
    return $userProducts;
}
