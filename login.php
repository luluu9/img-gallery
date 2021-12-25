<?php
    require_once 'functions.php';
    session_start();
    $db = get_db();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Logowanie</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
<?php
    if (isset($_POST['username']) &&
        isset($_POST['password'])) {
        
        $users = $db->users->find();
        $username = stripslashes($_REQUEST['username']);
        $password = stripslashes($_REQUEST['password']);
        $logged = false;
        foreach ($users as $user) {
            if ($username == $user['username'] && $password == $user['password']) {
                 $logged = true;
            }
        }
        if ($logged) {
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            echo "Zalogowano!</br>";
            echo "<a href='index.php'>&laquo; Strone główna</a>";   
        }
        else {
            echo "Nie znaleziono takiego użytkownika!";
        }
    }
?>
    <form method="post">
        <h1>Logowanie</h1>
        <input type="text" name="username" placeholder="Username" required />
        <input type="password" name="password" placeholder="Hasło" required>
        <input type="submit" name="submit" value="Zaloguj">
    </form></br>
    <a href="register.php">Zarejestruj się</a>

</body>
</html>