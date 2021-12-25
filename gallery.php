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
<script src="jquery-3.6.0.min.js"></script>
<body>

<?php if ($db->products->count()): ?>
    <?php print_r($_COOKIE); ?>
    <?php for ($i=$startImage; $i<$lastIndex; $i++): ?>
    <div class="gallery_image">
        <h1><?= $i+1 . ". " . $productsArray[$i]['name'] ?></h1>
        <h3><?= $productsArray[$i]['author'] ?></h3>
        <a href="view.php?id=<?= $productsArray[$i]['_id'] ?>">
            <img src="<?= "/images/miniature_" . $productsArray[$i]['filename'] ?>"</img> </br>
        </a>
        <input type="checkbox" class="rememberCheckbox" name="remember" value="<?= $productsArray[$i]['_id'] ?>">
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

<script>

function get_cookies_array() {
    var cookies = { };
    if (document.cookie) {
        var split = document.cookie.split(';');
        for (var i = 0; i < split.length; i++) {
            var name_value = split[i].split("=");
            name_value[0] = $.trim(name_value[0]);
            cookies[decodeURIComponent(name_value[0])] = decodeURIComponent(name_value[1]);
        }   
    }
    return cookies;
}

var cookies = get_cookies_array();
var favourites = Object.values(cookies);
var checkboxes = document.querySelectorAll(".rememberCheckbox");

checkboxes.forEach(function(checkbox) {
    if (favourites.includes(checkbox.value)) {
        checkbox.checked = true;
    }
    checkbox.addEventListener('change', function() {
    if (checkbox.checked) {
        var serializedData = {'name': 'id[', 'value': checkbox.value};
        request = $.ajax({
            url: "/setcookie.php",
            type: "post",
            data: serializedData
        });
        request.done(function (response, textStatus, jqXHR){
            console.log(response);
        });
    }
    else {
        var serializedData = {'name': 'id', 'value': checkbox.value};
        request = $.ajax({
            url: "/removecookie.php",
            type: "post",
            data: serializedData
        });
        request.done(function (response, textStatus, jqXHR){
            console.log(response);
        });
    }
  })
  
});
</script>

</body>
</html>
