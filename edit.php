<?php

require_once 'functions.php';
require_once 'img.php';
use MongoDB\BSON\ObjectID;

session_start();
$db = get_db();

$author = (isset($_SESSION['username'])) ? $_SESSION['username'] : null;
$product = [
    'name' => null,
    'author' => $author,
    'filePath' => null,
    'watermark' => null,
    '_id' => null
];

$imagesDir = "images/";
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/" . $imagesDir;

$MINIATURE_WIDTH = 200;
$MINIATURE_HEIGHT = 125;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['name']) &&
        !empty($_POST['author']) &&
        !empty($_POST['watermark']) &&
        !empty($_FILES['image'])
    ) {
        $filename = date('Y-m-d_H-i-s') . "-" . basename($_FILES['image']['name']);
        $uploadFile = $uploadDir . $filename;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            throw new Exception("Failed to move uploaded file");
        }

        $miniatureFilepath = $uploadDir . "miniature_" . $filename;
        $watermarkFilepath = $uploadDir . "watermark_" . $filename;
        resizeAndWriteImage($uploadFile, $miniatureFilepath, $MINIATURE_WIDTH, $MINIATURE_HEIGHT);
        watermarkAndWriteImage($uploadFile, $watermarkFilepath, $_POST['watermark']);
        $product = [
            'name' => $_POST['name'],
            'author' => $_POST['author'],
            'watermark' => $_POST['watermark'],
            'filename' => $filename,
            'filepath' => $uploadFile,
            'miniature_filepath' => $miniatureFilepath,
            'watermark_filepath' => $watermarkFilepath
        ];

        if (empty($_POST['id'])) { // new image
            $db->products->insertOne($product);
        } else { // edit image
            $id = $_POST['id']; 
            
            $oldProduct = $db->products->findOne(['_id' => new ObjectID($id)]);
            unlink($oldProduct['filepath']);
            unlink($oldProduct['watermark_filepath']);
            unlink($oldProduct['miniature_filepath']);
            $db->products->replaceOne(['_id' => new ObjectID($id)], $product);
        }

        header('Location: index.php');
        exit;
    }

} else {
    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
        $product = $db->products->findOne(['_id' => new ObjectID($id)]);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edycja</title>
    <link rel="stylesheet" href="styles.css"/>
</head>

<body>

<form method="post" enctype="multipart/form-data">
    <label>
        <span>Tytuł:</span>
        <input type="text" name="name" value="<?= $product['name'] ?>" required />
    </label>

    <label>
        <span>Autor:</span>
        <input type="text" name="author" value="<?= $product['author'] ?>" required />
    </label>

     <label>
        <span>Znak wodny:</span>
        <input type="text" name="watermark" value="<?= $product['watermark'] ?>" required />
    </label>

    <label> 
        <span>Zdjęcie:</span>
        <input type="file" id="image" name="image" accept="image/png, image/jpeg" required />
    </label>

    <input type="hidden" name="id" value="<?= $product['_id'] ?>">

    <div>
        <a href="index.php" class="cancel">Anuluj</a>
        <input type="submit" value="Zapisz"/>
    </div>
</form>

<script>
var uploadField = document.getElementById("image");

uploadField.onchange = function() {
    if (this.files[0].size > 1*1024*1024) {
       alert("Plik jest zbyt duży!");
       this.value = "";
    };
    if (this.files[0].type != "image/png" && this.files[0].type != "image/jpeg") {
       alert("Plik ma niewłaściwy format! (tylko jpg/png)");
       this.value = "";
    };
};
</script>

</body>
</html>
