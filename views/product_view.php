<!DOCTYPE html>
<html>
<head>
    <title>Obrazek</title>
    <?php include "includes/head.inc.php"; ?>
</head>
<body>

<h1><?= $product['name'] ?></h1>

<h3><?= $product['author'] ?></h3>

<img src="<?= $product['rel_filepath'] ?>"</img> </br>

<a href="products">&laquo; Wróć</a>

</body>
</html>