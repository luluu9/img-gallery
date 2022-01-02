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

function get_products_by_category($cat)
{
    $db = get_db();
    $products = $db->products->find(['cat' => $cat]);
    return $products;
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

function user_exists($username, $password='', $only_username=false) {
    $users = get_users();
    if (count($users) > 0) {
        foreach ($users as $user) {
            if ($only_username) {
                if ($username == $user['username']) {
                    return true;
                }
            }
            else {
                if ($username == $user['username'] && $password == $user['password']) {
                    return true;
                }
            }
            
        }
    }
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
    $db->users->insertOne($user);
}

function is_logged() {
    if (isset($_SESSION['username'])) {
        return true;
    }
    return false;
}
?>