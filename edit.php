<?php

require_once 'functions.php';
require_once 'img.php';
use MongoDB\BSON\ObjectID;


$db = get_db();

$product = [
    'name' => null,
    'author' => null,
    'filePath' => null,
    'watermark' => null,
    '_id' => null
];

$imagesDir = "images/";
$uploaddir = $_SERVER['DOCUMENT_ROOT'] . "/" . $imagesDir;

$MINIATURE_WIDTH = 200;
$MINIATURE_HEIGHT = 125;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['name']) &&
        !empty($_POST['author']) &&
        !empty($_POST['watermark']) &&
        !empty($_FILES['image'])
    ) {
        $filename = date('Y-m-d_H-i-s') . "-" . basename($_FILES['image']['name']);
        $uploadFile = $uploaddir . $filename;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            throw new Exception("Failed to move uploaded file");
        }

        $miniatureFilepath = $uploaddir . "miniature_" . $filename;
        resizeAndWriteImage($uploadFile, $miniatureFilepath, $MINIATURE_WIDTH, $MINIATURE_HEIGHT);

        $product = [
            'name' => $_POST['name'],
            'author' => $_POST['author'],
            'watermark' => $_POST['watermark'],
            'filename' => $filename
        ];

        if (empty($_POST['id'])) {
            $db->products->insertOne($product);
        } else {
            $id = $_POST['id'];
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
        <span>Tytu�:</span>
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
        <span>Zdj�cie:</span>
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
       alert("Plik jest zbyt du�y!");
       this.value = "";
    };
    if (this.files[0].type != "image/png" && this.files[0].type != "image/jpeg") {
       alert("Plik ma niew�a�ciwy format! (tylko jpg/png)");
       this.value = "";
    };
};
</script>

</body>
</html>
