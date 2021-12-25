<?php
if (isset($_POST['name'])) {
	$cookieName = $_POST['name'];
	$cookieValue = $_POST['value'];
	$cookie = $_COOKIE[$cookieName];
	if (is_array($cookie)) {
		$index = array_search($cookieValue, $cookie);
		unset($_COOKIE[$cookieName][$index]); 
		setcookie($cookieName . "[" . $index . "]", null, -1, '/'); 
	}
	else {
		unset($_COOKIE[$cookieName]); 
		setcookie($cookieName, null, -1, '/'); 
	}

}
?>