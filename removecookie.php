<?php
if (isset($_POST['name'])) {
	$cookieName = $_POST['name'];
	$cookieValue = $_POST['value'];
	$cookie = $_COOKIE[$cookieName];
	if (is_array($cookie)) {
		$index = array_search($cookieValue, $cookie);
		unset($_COOKIE[$cookieName][$index]); 
		setcookie($cookieName . "[" . $index . "]", null, -1, '/');

		// reset cookies indexing
		$newCookies = array_map('array_values', $_COOKIE);
		foreach ($_COOKIE[$cookieName] as $key => $value) {
			setcookie( $cookieName . "[" . $key . "]", FALSE, -1, '/' );
			unset($_COOKIE[$cookieName][$key]);
		}
		
		foreach ($newCookies[$cookieName] as $key=>$value) {
			setcookie($cookieName . "[" . $key . "]", $value, -1, '/');
		}
	}
	else {
		unset($_COOKIE[$cookieName]); 
		setcookie($cookieName, null, -1, '/'); 
	}

}
?>