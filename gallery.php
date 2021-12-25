<?php

session_start();
require_once 'functions.php';

$IMAGES_PER_PAGE = 2;
$startImage = 0;
if (!empty($_GET['page'])) {
    $startImage = ((int)$_GET['page']-1) * $IMAGES_PER_PAGE;
}

$db = get_db();
$products = $db->products->find();

$userProducts = getCurrentUserProducts($products);

$imagesAmount = count($userProducts);
$lastIndex = ($imagesAmount < $startImage+$IMAGES_PER_PAGE) ? $imagesAmount : $startImage+$IMAGES_PER_PAGE;
$pages = ($imagesAmount+1)/$IMAGES_PER_PAGE;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Galeria</title>
    <link rel="stylesheet" href="styles.css"/>
    <script src="jquery-3.6.0.min.js"></script>
</head>

<body>

<?php if ($lastIndex): ?>
    <?php for ($i=$startImage; $i<$lastIndex; $i++): ?>
        <div class="gallery_image">
            <h1><?= $i+1 . ". " . $userProducts[$i]['name'] ?></h1>
            <h3><?= $userProducts[$i]['author'] ?></h3>
            <a href="view.php?id=<?= $userProducts[$i]['_id'] ?>">
                <img src="<?= "/images/miniature_" . $userProducts[$i]['filename'] ?>"</img> </br>
            </a>
            <?php if (isset($userProducts[$i]['user'])) { echo "<p>Plik prywatny</p>"; }?>
            <input type="checkbox" class="rememberCheckbox" name="remember" value="<?= $userProducts[$i]['_id'] ?>">
            <label for="remember">Zapamiętaj</label>
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

<script src="favourities.js"></script>

</body>
</html>
