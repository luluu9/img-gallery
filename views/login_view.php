<!DOCTYPE html>
<html>
<head>
    <title>Logowanie</title>
    <?php include "includes/head.inc.php"; ?>
</head>
<body>
    <form method="post" id="hashForm">
        <h1>Logowanie</h1>
        <input type="text" name="username" placeholder="Username" required />
        <input type="password" name="password" id="password" placeholder="Hasło" required>
        <div id="response"><?= $response ?></div>
        <input type="submit" name="submit" value="Zaloguj">
    </form></br>
    <a href="products">&laquo; Wróć</a>
    <a href="register">Zarejestruj się</a>
</body>
<script src="static/js/hash.js"></script>
</html>
