<!DOCTYPE html>
<html>
<head>
    <title>Ulubione</title>
    <?php include "includes/head.inc.php"; ?>
</head>

<body>

<?php if (count($favourities)): ?>
    <?php foreach ($favourities as $key=>$favourite): ?>
        <div class="gallery_image">
            <h1><?= $key+1 . ". " . $favourite['name'] ?></h1>
            <h3><?= $favourite['author'] ?></h3>
            <a href="view?id=<?= $favourite['_id'] ?>">
                <img src="<?= $favourite['rel_miniature_filepath'] ?>"</img> </br>
            </a>
            <input type="checkbox" class="rememberCheckbox" name="remember" checked=true value="<?= $favourite['_id'] ?>">
            <label for="remember">Zapamiętaj</label>
        </div>
    <?php endforeach ?>
<?php else: ?>
    Brak ulubionych
<?php endif ?>  

</br>
</br>   
<a href="products">&laquo; Wróć</a>

<script src="static/js/favourities.js"></script>

</body>
</html>
