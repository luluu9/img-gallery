<!DOCTYPE html>
<html>
<head>
    <title>Galeria</title>
    <?php include "includes/head.inc.php"; ?>
</head>

<body>

<?php if (count($products)>0): ?>
    <?php foreach ($products as $key=>$product): ?>
        <div class="gallery_image">
            <h1><?= $firstIndex+$key . ". " . $product['name'] ?></h1>
            <h3><?= $product['author'] ?></h3>
            <a href="view?id=<?= $product['_id'] ?>">
                <img src="<?= $product['rel_miniature_filepath'] ?>"</img> </br>
            </a>
            <?php if (isset($product['user'])): ?>
                <p>Plik prywatny</p>
            <?php endif ?>
            <input type="checkbox" class="rememberCheckbox" name="remember" autocomplete="off" value="<?= $product['_id']  ?>" 
            <?php if (isset($_SESSION["favourities"]) && in_array($product['_id'], $_SESSION["favourities"])): ?>
                checked
            <?php endif ?>/>
            <label for="remember">Zapamiętaj</label>
        </div>
    <?php endforeach ?>
<?php else: ?>
    Brak produktów
<?php endif ?>

<?php for ($i=1; $i<=$pagesNumber; $i++): ?>
    <span><a href='?page=<?=$i?>'><?=$i?></a></span>
<?php endfor ?>
</br>   
</br>   
<a href="products">&laquo; Wróć</a>

<script src="static/js/favourities.js"></script>

</body>
</html>
