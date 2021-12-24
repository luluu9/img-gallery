<?php

require_once 'functions.php';

$IMAGES_PER_PAGE = 2;
$startImage = 0;
if (!empty($_GET['page'])) {
    $startImage = ((int)$_GET['page']-1) * $IMAGES_PER_PAGE;
}

$db = get_db();
$products = $db->products->find();
$productsArray = iterator_to_array($products);
$imagesAmount = count($productsArray);
$lastIndex = ($imagesAmount < $startImage+$IMAGES_PER_PAGE) ? $imagesAmount : $startImage+$IMAGES_PER_PAGE;
$pages = ($imagesAmount+1)/$IMAGES_PER_PAGE;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Galeria</title>
    <link rel="stylesheet" href="styles.css"/>
</head>
<body>

<?php if ($db->products->count()): ?>
    <?php for ($i=$startImage; $i<$lastIndex; $i++): ?>
    <div class="gallery_image">
        <h1><?= $i+1 . ". " . $productsArray[$i]['name'] ?></h1>
        <h3><?= $productsArray[$i]['author'] ?></h3>
        <a href="view.php?id=<?= $productsArray[$i]['_id'] ?>">
            <img src="<?= "/images/miniature_" . $productsArray[$i]['filename'] ?>"</img> </br>
        </a>
    </div>
    <?php endfor ?>
<?php else: ?>
    Brak produktów
<?php endif ?>

<?php 
for ($i=1; $i<=$pages; $i++) {
    echo "<span><a href='?page=$i'>$i</a> </span>";
}
?>
</br>   
</br>   
<a href="index.php">&laquo; Wróć</a>

</body>
</html>
