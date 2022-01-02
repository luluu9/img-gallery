<?php
use MongoDB\BSON\ObjectID;

function get_db()
{
    $mongo = new MongoDB\Client(
        "mongodb://localhost:27017/wai",
        [
            'username' => 'wai_web',
            'password' => 'w@i_w3b',
        ]);

    $db = $mongo->wai;

    return $db;
}

function get_products()
{
    $db = get_db();
    return $db->products->find()->toArray();
}

function get_product($id)
{
    $db = get_db();
    return $db->products->findOne(['_id' => new ObjectID($id)])->getArrayCopy();
}

function getCurrentUserProducts() {
    $products = get_products();
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

function save_product($id, $product) {
    $db = get_db();

    if ($id == null) {
        $db->products->insertOne($product);
    } else {
        $db->products->replaceOne(['_id' => new ObjectID($id)], $product);
    }

    return true;
}

function delete_product($id) {
    $db = get_db();
    delete_product_files(get_product($id));
    $db->products->deleteOne(['_id' => new ObjectID($id)]);
}

function delete_product_files($product) {
    unlink($product['filepath']);
    unlink($product['watermark_filepath']);
    unlink($product['miniature_filepath']);
}

function get_users() {
    $db = get_db();
    return $db->users->find();
}

function get_user($username, $password, $only_username=false) {
    $db = get_db();
    $user = ["username" => $username];
    if ($only_username == false) {
        $user["password"] = $password;
    }
    return $db->users->findOne($user);
}

function user_exists($username, $password='', $only_username=false) {
    $users = get_users();
    $user = get_user($username, $password, $only_username);
    if ($user) { return true; }
    return false;
}

function try_login($username, $password) {
    if (user_exists($username, $password)) {
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        return true; 
    }
    return false;
}

function register_user($user) {
    $db = get_db();
    if ($db->products->findOne(['username' => $user['username']]) == null) {
        $db->users->insertOne($user);
        return true;
    }
    return false;
}

function is_logged() {
    if (isset($_SESSION['username'])) {
        return true;
    }
    return false;
}
?>