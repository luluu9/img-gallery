<?php

session_start();
if (isset($_POST['newFavourite'])) {
	$newFavourite = trim($_POST['newFavourite']);
	if (isset($_SESSION['favourities'])) {
        if (!in_array($newFavourite, $_SESSION['favourities'])) {
            $favNumber = count($_SESSION['favourities']);
            array_push($_SESSION['favourities'], $newFavourite);
        }
    }
    else {
        $_SESSION['favourities'] = [$newFavourite];
    }
}
?>