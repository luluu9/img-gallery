<!DOCTYPE html>
<html>
<head>
    <title>Rejestracja</title>
    <?php include "includes/head.inc.php"; ?>
</head>
<body>
    <form method="post">
        <h1>Rejestracja</h1>
        <input type="text" name="username" placeholder="Username" required />
        <input type="text" name="email" placeholder="Adres email" required>
        <input type="password" name="password" placeholder="Hasło" required>
        <input type="password" name="passwordAgain" placeholder="Powtórz hasło">
        <input type="submit" name="submit" value="Zarejestruj">
        <div id="respone"><?= $response ?></div>
    </form></br>
    <a href="login">Zaloguj się</a>
</body>
</html>
