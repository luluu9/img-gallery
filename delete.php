<?php

require_once 'functions.php';
use MongoDB\BSON\ObjectID;


$db = get_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $db->products->deleteOne(['_id' => new ObjectID($id)]);

    header('Location: index.php');
    exit;
}

if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $product = $db->products->findOne(['_id' => new ObjectID($id)]);
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Usuwanie produktu</title>
    <link rel="stylesheet" href="styles.css"/>
</head>
<body>

<form method="post">
    Czy usunąć produkt: <?= $product['name'] ?>?

    <input type="hidden" name="id" value="<?= $product['_id'] ?>">

    <div>
        <a href="index.php" class="cancel">Anuluj</a>
        <input type="submit" value="Potwierdź"/>
    </div>
</form>

</body>
</html>
