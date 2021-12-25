<?php
function endsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    if( !$length ) {
        return true;
    }
    return substr( $haystack, -$length ) === $needle;
}

if (isset($_POST['name'])) {
	$cookieName = $_POST['name'];
	$cookieValue = $_POST['value'];
	if (endsWith($cookieName, "[")) {
		$validCookieName = substr($cookieName, 0, -1);
		if (isset($_COOKIE[$validCookieName])) {
			$newElementIndex = count($_COOKIE[$validCookieName]);
			if (!in_array($cookieValue, $_COOKIE[$validCookieName]))
				setcookie($cookieName . $newElementIndex . "]", $cookieValue, time()+60*60*24*30, '/');
		}
		else {
			setcookie($cookieName . "0]", $cookieValue, time()+60*60*24*30, '/');
		}
	}
	else {
		setcookie($cookieName, $cookieValue, time()+60*60*24*30, '/');
	}
}
?>