<!DOCTYPE html>
<html>
<head>
    <title>Logowanie</title>
    <?php include "includes/head.inc.php"; ?>
</head>
<body>
    <form method="post">
        <h1>Logowanie</h1>
        <input type="text" name="username" placeholder="Username" required />
        <input type="password" name="password" placeholder="Hasło" required>
        <div id="respone"><?= $response ?></div>
        <input type="submit" name="submit" value="Zaloguj">
    </form></br>
    <a href="products">&laquo; Wróć</a>
    <a href="register">Zarejestruj się</a>
</body>
</html>
