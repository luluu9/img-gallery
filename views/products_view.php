<!DOCTYPE html>
<html>
<head>
    <title>Produkty</title>
    <?php include "includes/head.inc.php"; ?>
</head>
<body>

<table>
    <thead>
    <tr>
        <th>Tytuł</th>
        <th>Autor</th>
        <th>Operacje</th>
    </tr>
    </thead>

    <tbody>
    <?php if (count($products)): ?>
        <?php foreach ($products as $product): ?>
            <tr>
                <td>
                    <a href="view?id=<?= $product['_id'] ?>"><?= $product['name'] ?></a>
                </td>
                <td><?= $product['author'] ?></td>
                <td>
                    <a href="edit?id=<?= $product['_id'] ?>">Edytuj</a> |
                    <a href="delete?id=<?= $product['_id'] ?>">Usuń</a>
                </td>
            </tr>
        <?php endforeach ?>
    <?php else: ?>
        <tr>
            <td colspan="3">Brak produktów</td>
        </tr>
    <?php endif ?>
    </tbody>

    <tfoot>
    <tr>
        <td colspan="2">Łącznie: <?= count($products) ?></td>
        <td>
            <a href="edit">Dodaj obrazek</a>
        </td>
    </tr>
    </tfoot>
</table>

<?php if (isset($_SESSION['username'])): ?>
    <a href="logout">Wyloguj</a>
<?php else: ?>
    <a href="register">Zarejestruj</a>
    <a href="login">Zaloguj</a>
<?php endif ?>

<a href="gallery">Galeria</a>
<a href="favourities">Ulubione</a>
<a href="search">Wyszukiwarka</a></br>

</body>
</html>
