<?php

require_once 'functions.php';

$IMAGES_PER_PAGE = 2;
$startImage = 0;
if (!empty($_GET['page'])) {
    $startImage = ((int)$_GET['page']-1) * $IMAGES_PER_PAGE;
}

$db = get_db();
$products = $db->products->find();
$products_array = iterator_to_array($products);
$images_amount = count($products_array);
$last_index = ($images_amount < $startImage+$IMAGES_PER_PAGE) ? $images_amount : $startImage+$IMAGES_PER_PAGE;
$pages = ($images_amount+1)/$IMAGES_PER_PAGE;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Galeria</title>
    <link rel="stylesheet" href="styles.css"/>
</head>
<body>

<?php if ($db->products->count()): ?>
    <?php for ($i=$startImage; $i<$last_index; $i++): ?>
    <div class="gallery_image">
        <h1><?= $i+1 . ". " . $products_array[$i]['name'] ?></h1>
        <h3><?= $products_array[$i]['author'] ?></h3>
        <a href="view.php?id=<?= $products_array[$i]['_id'] ?>">
            <img src="<?= "/images/miniature_" . $products_array[$i]['filename'] ?>"</img> </br>
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
<a href="index.php">&laquo; Wróæ</a>

</body>
</html>
