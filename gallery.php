<?php

require_once 'functions.php';

$db = get_db();
$products = $db->products->find();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Galeria</title>
    <link rel="stylesheet" href="styles.css"/>
</head>
<body>

<?php if ($db->products->count()): ?>
    <?php foreach ($products as $index=>$product): ?>
    <div class="gallery_image">
        <h1><?= $index+1 . ". " . $product['name'] ?></h1>
        <h3><?= $product['author'] ?></h3>
        <a href="view.php?id=<?= $product['_id'] ?>">
            <img src="<?= "/images/miniature_" . $product['filename'] ?>"</img> </br>
        </a>
    </div>
    <?php endforeach ?>
<?php else: ?>
    Brak produktów
<?php endif ?>

<a href="index.php">&laquo; Wróæ</a>

</body>
</html>
