<?php

require_once 'functions.php';

$db = get_db();
$products = getCurrentUserProducts($db->products->find());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = $_POST['name'];
    $matchingImages = [];
    foreach ($products as $product) {
        if (startsWith($product['name'], $query)) {
            array_push($matchingImages, $product['filename']);
        }
    }
    echo json_encode($matchingImages);
    die();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Wyszukiwarka</title>
    <link rel="stylesheet" href="styles.css"/>
    <script src="jquery-3.6.0.min.js"></script>
</head>
<body>
    <input type="text" name="search" id="search" onkeyup="search(this.value);"> </br>
    <div id="searchResult">
    </div> </br>   
    <a href="index.php">&laquo; Wróć</a>

<script>
var req = null;

function search(value) {
    if (!value) return; 
    if (req != null) req.abort();
    
    req = $.ajax({
        type: "POST",
        url: "search.php",
        data: {'name' : value},
        success: function(msg){
            var resultDiv = document.getElementById("searchResult"); 
            var matchingImages = JSON.parse(msg);
            var newElements = []
            
            matchingImages.forEach(image => { 
                var img = document.createElement('img');
                img.src = "images/miniature_" + image;
                newElements.push(img);
            })
            resultDiv.replaceChildren(...newElements);
        }
    });
}
</script>

</body>
</html>
