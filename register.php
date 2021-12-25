<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Rejestracja</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
<?php
    require_once 'functions.php';
 
    if (isset($_POST['username']) &&
        isset($_POST['email']) && 
        isset($_POST['password']) && 
        isset($_POST['passwordAgain'])) {
        if ($_REQUEST['password'] <> $_REQUEST['passwordAgain']) {
            echo "Hasła muszą być takie same! </br>";
            die();
        }
        $db = get_db();
        $users = $db->users->find();
        $username = stripslashes($_REQUEST['username']);
        foreach ($users as $user) {
            if ($username == $user['username']) {
                 echo "Użytkownik już istnieje!";
                 die();
            }
        }
        $email    = stripslashes($_REQUEST['email']);
        $password = stripslashes($_REQUEST['password']);
        $user = [
                'username' => $username,
                'email' => $email,
                'password' => $password,
            ];

        $db->users->insertOne($user);
        echo "Pomyślnie zarejestrowano! <a href='login.php'>Zaloguj się</a>";
    } else {
?>
    <form method="post">
        <h1>Rejestracja</h1>
        <input type="text" name="username" placeholder="Username" required />
        <input type="text" name="email" placeholder="Adres email" required>
        <input type="password" name="password" placeholder="Hasło" required>
        <input type="password" name="passwordAgain" placeholder="Powtórz hasło">
        <input type="submit" name="submit" value="Zarejestruj">
    </form>
    <a href="login.php">Zaloguj się</a>
<?php
    }
?>
</body>
</html>