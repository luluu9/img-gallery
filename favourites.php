<?php

require_once 'functions.php';

$db = get_db();
$products = $db->products->find();
$productsArray = iterator_to_array($products);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Ulubione</title>
    <link rel="stylesheet" href="styles.css"/>
</head>
<body>

<?php if (!empty($_COOKIE["id"])): ?>
    <?php if ($db->products->count()): ?>
        <?php for ($i=0; $i<$db->products->count(); $i++): ?>
            <?php if (in_array($productsArray[$i]['_id'], $_COOKIE["id"])): ?>
                <div class="gallery_image">
                    <h1><?= $i+1 . ". " . $productsArray[$i]['name'] ?></h1>
                    <h3><?= $productsArray[$i]['author'] ?></h3>
                    <a href="view.php?id=<?= $productsArray[$i]['_id'] ?>">
                        <img src="<?= "/images/miniature_" . $productsArray[$i]['filename'] ?>"</img> </br>
                    </a>
                </div>
            <?php endif ?>
        <?php endfor ?>
    <?php else: ?>
        Brak ulubionych
    <?php endif ?>  
<?php else: ?>
    Brak ulubionych
<?php endif ?>

</br>
</br>   
<a href="index.php">&laquo; Wróć</a>

</body>
</html>
