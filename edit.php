<?php

require_once 'functions.php';
use MongoDB\BSON\ObjectID;


$db = get_db();

$product = [
    'name' => null,
    'author' => null,
    'filePath' => null,
    '_id' => null
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['name']) &&
        !empty($_POST['author']) &&
        !empty($_FILES['image'])
    ) {
        $uploaddir = $_SERVER['DOCUMENT_ROOT'] . "/images/";
        $filename = date('Y-m-d_H-i-s') . "-" . basename($_FILES['image']['name']);
        $uploadfile = $uploaddir . $filename;
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile);

        $product = [
            'name' => $_POST['name'],
            'author' => $_POST['author'],
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
        <span>Tytu³:</span>
        <input type="text" name="name" value="<?= $product['name'] ?>" required />
    </label>

    <label>
        <span>Autor:</span>
        <input type="text" name="author" value="<?= $product['author'] ?>" required />
    </label>

    <label> 
        <span>Zdjêcie:</span>
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
       alert("Plik jest zbyt du¿y!");
       this.value = "";
    };
    if (this.files[0].type != "image/png" && this.files[0].type != "image/jpeg") {
       alert("Plik ma niew³aœciwy format! (tylko jpg/png)");
       this.value = "";
    };
};
</script>

</body>
</html>
