<?php

session_start([
	'cookie_secure' => true,
	'cookie_samesite' => 'Strict',
	'cookie_httponly' => true,
]);

if (!isset($_SESSION["csrf"]))
	$_SESSION['csrf'] = bin2hex(random_bytes(32));

$user = NULL;

if (isset($_SESSION['user'])) {
	$user = $_SESSION['user'];
}
