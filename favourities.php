<?php

require_once 'functions.php';

$db = get_db();
$products = $db->products->find();
$productsArray = getCurrentUserProducts($products);
print_r($productsArray);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Ulubione</title>
    <link rel="stylesheet" href="styles.css"/>
    <script src="jquery-3.6.0.min.js"></script>
</head>
<body>

<?php if (!empty($_COOKIE["id"])): ?>
    <?php if (count($productsArray)): ?>
        <?php for ($i=0; $i<count($productsArray); $i++): ?>
            <?php if (in_array($productsArray[$i]['_id'], $_COOKIE["id"])): ?>
                <div class="gallery_image">
                    <h1><?= $i+1 . ". " . $productsArray[$i]['name'] ?></h1>
                    <h3><?= $productsArray[$i]['author'] ?></h3>
                    <a href="view.php?id=<?= $productsArray[$i]['_id'] ?>">
                        <img src="<?= "/images/miniature_" . $productsArray[$i]['filename'] ?>"</img> </br>
                    </a>
                    <input type="checkbox" class="rememberCheckbox" name="remember" value="<?= $productsArray[$i]['_id'] ?>">
                    <label for="remember">Zapamiętaj</label>
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

<script src="favourities.js"></script>

</body>
</html>
