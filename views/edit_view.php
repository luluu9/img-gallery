<!DOCTYPE html>
<html>
<head>
    <title>Edycja</title>
    <?php include "includes/head.inc.php"; ?>
</head>

<body>

<form method="post" enctype="multipart/form-data">
    <label>
        <span>Tytuł:</span>
        <input type="text" name="name" value="<?= $model['name'] ?>" required />
    </label>

    <label>
        <span>Autor:</span>
        <input type="text" name="author" value="<?= $model['author'] ?>" required />
    </label>

    <label>
        <span>Znak wodny:</span>
        <input type="text" name="watermark" value="<?= $model['watermark'] ?>" required />
    </label>

    <label> 
        <span>Zdjęcie:</span>
        <input type="file" id="image" name="image" accept="image/png, image/jpeg" required />
    </label>

    <?php if (is_logged()): ?>
        <label>
            <?php if (isset($model["user"])): ?>
                <input type="radio" id="private" name="access" value="private" checked/>
                <input type="radio" id="public" name="access" value="public" />
            <?php else: ?>
                <input type="radio" id="private" name="access" value="private" />
                <input type="radio" id="public" name="access" value="public" checked/>
            <?php endif ?>
        </label>
        <label for="private">Prywatne</label>
        <label for="public">Publiczne</label>
    <?php endif ?>

    <input type="hidden" name="id" value="<?= $model['_id'] ?>">

    <div>
        <a href="products" class="cancel">Anuluj</a>
        <input type="submit" value="Zapisz"/>
    </div>
</form>

<script>
var uploadField = document.getElementById("image");

uploadField.onchange = function() {
    if (this.files[0].size > 1*1024*1024) {
       alert("Plik jest zbyt duży!");
       this.value = "";
    };
    if (this.files[0].type != "image/png" && this.files[0].type != "image/jpeg") {
       alert("Plik ma niewłaściwy format! (tylko jpg/png)");
       this.value = "";
    };
};
</script>

</body>
</html>
