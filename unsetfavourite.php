<?php
session_start();
if (isset($_POST['favourite'])) {
	$favourite = trim($_POST['favourite']);
	if (in_array($favourite, $_SESSION['favourities'])) {
		$favouriteId = array_search($favourite, $_SESSION['favourities']);
        unset($_SESSION['favourities'][$favouriteId]);
    }
}
?>