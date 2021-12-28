<?php
require_once 'business.php';
require_once 'controller_utils.php';
use MongoDB\BSON\ObjectID;

function products(&$model)
{
    $models = getCurrentUserProducts();

    $model['products'] = $models;

    return 'products_view';
}

function product(&$model)
{
    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
        $product = get_product($id);
        if ($product) {
            $model['product'] = $product;

            return 'product_view';
        }
    }

    http_response_code(404);
    exit;
}

function edit(&$model) {
    $db = get_db();
    $imagesDir = "images/";
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/" . $imagesDir;
    $MINIATURE_WIDTH = 200;
    $MINIATURE_HEIGHT = 125;

    $author = (is_logged()) ? $_SESSION['username'] : null;
    $model = [
        'name' => null,
        'author' => $author,
        'filePath' => null,
        'watermark' => null,
        '_id' => null
    ];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!empty($_POST['name']) &&
            !empty($_POST['author']) &&
            !empty($_POST['watermark']) &&
            !empty($_FILES['image'])) {
            $filename = date('Y-m-d_H-i-s') . "-" . basename($_FILES['image']['name']);
            $uploadFile = $uploadDir . $filename;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                throw new Exception("Failed to move uploaded file");
            }

            $miniatureFilepath = $uploadDir . "miniature_" . $filename;
            $watermarkFilepath = $uploadDir . "watermark_" . $filename;
            resizeAndWriteImage($uploadFile, $miniatureFilepath, $MINIATURE_WIDTH, $MINIATURE_HEIGHT);
            watermarkAndWriteImage($uploadFile, $watermarkFilepath, $_POST['watermark']);
            $model = [
                'name' => $_POST['name'],
                'author' => $_POST['author'],
                'watermark' => $_POST['watermark'],
                'filename' => $filename,
                'filepath' => $uploadFile,
                'miniature_filepath' => $miniatureFilepath,
                'watermark_filepath' => $watermarkFilepath,
                'rel_filepath' =>  "/" . $imagesDir . $filename,
                'rel_miniature_filepath' => "/" . $imagesDir . "miniature_" . $filename,
                'rel_watermark_filepath' => "/" . $imagesDir . "watermark_" . $filename
            ];

            if (isset($_POST['access']) && $_POST['access'] == 'private') {
                if (is_logged()) {
                    $model['user'] = $_SESSION['username'];
                }
            }

            if (empty($_POST['id'])) { // new image
                save_product(null, $model);
            } else { // edit image
                $id = $_POST['id']; 
                $oldProduct = get_product($id);
                unlink($oldProduct['filepath']);
                unlink($oldProduct['watermark_filepath']);
                unlink($oldProduct['miniature_filepath']);
                save_product($id, $model);
            }

            return 'redirect:products';
        }

    } else {
        if (!empty($_GET['id'])) {
            $id = $_GET['id'];
            $model = $db->products->findOne(['_id' => new ObjectID($id)])->getArrayCopy();
        }
    }
    return 'edit_view';
}

function delete(&$model)
{
    if (!empty($_REQUEST['id'])) {
        $id = $_REQUEST['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            delete_product($id);
            return 'redirect:products';

        } else {
            if ($model = get_product($id)) {
                $model['product'] = $model;
                return 'delete_view';
            }
        }
    }

    http_response_code(404);
    exit;
}

function login(&$model) {
    $model['response'] = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
        if (isset($_POST['username']) &&
            isset($_POST['password'])) {
            $username = stripslashes($_REQUEST['username']);
            $password = stripslashes($_REQUEST['password']);
          
            if (try_login($username, $password)) {
                return 'redirect:products'; 
            }
            else {
                $model['response'] = "Nie znaleziono takiego u¿ytkownika!";
            }
        }
    }    
    return 'login_view';
}

function logout(&$model) {
    unset($_SESSION["username"]);
    unset($_SESSION["password"]);
    return 'redirect:login';
}

function register(&$model) {
   $model['response'] = '';
   if (isset($_POST['username']) &&
        isset($_POST['email']) && 
        isset($_POST['password']) && 
        isset($_POST['passwordAgain'])) {
        $username = stripslashes($_REQUEST['username']);
        $email    = stripslashes($_REQUEST['email']);
        $password = stripslashes($_REQUEST['password']);
        if ($password <> $_POST['passwordAgain']) {
            $model['response'] =  "Has³a musz¹ byæ takie same! </br>";
        }
        if (user_exists($_POST['username'], $only_username=true)) {
            $model['response'] = "U¿ytkownik ju¿ istnieje!";
        }

        $user = [
                'username' => $username,
                'email' => $email,
                'password' => $password
            ];

        register_user($user);
        $model['response'] = "Pomyœlnie zarejestrowano! <a href='login'>Zaloguj siê</a>";
    }
    return 'register_view';
}

function gallery(&$model) {
    $IMAGES_PER_PAGE = 2;
    $startImage = 0;
    if (!empty($_GET['page'])) {
        $startImage = ((int)$_GET['page']-1) * $IMAGES_PER_PAGE;
    }

    $products = getCurrentUserProducts();
    $imagesAmount = count($products);

    $model['firstIndex'] = $startImage+1;
    $model['products'] = array_slice($products, $startImage, $IMAGES_PER_PAGE);
    $model['pagesNumber'] = ($imagesAmount+1)/$IMAGES_PER_PAGE;

    return 'gallery_view';
}

function set_favourite(&$model) {
    if (isset($_POST['newFavourite'])) {
	    $newFavourite = trim($_POST['newFavourite']);
	    if (isset($_SESSION['favourities'])) {
            if (!in_array($newFavourite, $_SESSION['favourities'])) {
                $favNumber = count($_SESSION['favourities']);
                array_push($_SESSION['favourities'], $newFavourite);
            }
        }
        else {
            $_SESSION['favourities'] = [$newFavourite];
        }
    }
    http_response_code(200);
    exit;
}

function unset_favourite(&$model) {
    if (isset($_POST['favourite'])) {
	    $favourite = trim($_POST['favourite']);
	    if (in_array($favourite, $_SESSION['favourities'])) {
		    $favouriteId = array_search($favourite, $_SESSION['favourities']);
            unset($_SESSION['favourities'][$favouriteId]);
        }
    }
    http_response_code(200);
    exit;
}

function favourities(&$model) {
    $favourities = [];
    if (isset($_SESSION["favourities"])) {
        $productsArray = getCurrentUserProducts();
        foreach($productsArray as $key=>$product) {
            if (in_array($product['_id'], $_SESSION["favourities"])) {
                array_push($favourities, $product);
            }
        }
    }
    
    $model['favourities'] = $favourities;
    return 'favourities';
}

function search(&$model) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $products = getCurrentUserProducts();
        $matchingImages = [];
        $query = $_POST['name'];
        foreach ($products as $product) {
            if (startsWith($product['name'], $query)) {
                array_push($matchingImages, $product['filename']);
            }
        }
        echo json_encode($matchingImages);
        die();
    }
    return 'search';
}