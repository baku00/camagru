<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/user.php';

$publicPages = [
	'' => ['get'],
	'favicon.ico' => ['get'],
	'posts' => ['get'],
	'picture' => ['get'],
	'assets' => ['get'],
	'storage' => ['get'],
];

function isPublicPath($url, $method) {
	global $publicPages;

	if ((isset($publicPages[$url]) && in_array($method, $publicPages[$url])) || startsWith($url, 'storage/'))
		return true;

	return false;
}

function isValidationRoute($url) {
	return startsWith($url, 'account/validate');
}

function isDisconnectionRoute($url) {
	return startsWith($url, 'auth/logout');
}

function isAuthRoute($url) {
	return startsWith($url, 'auth/');
}

function issetUser() {
	return isset($_SESSION['user']) && $_SESSION['user'];
}

function checkPath($url, $method) {
	global $pdo;
	global $publicPages;

	$user = isset($_SESSION['user']) && $_SESSION['user'] ? fetchById($_SESSION['user']['id']) : null;
	$_SESSION['user'] = $user;

	if ($user && !isUserValidated($user) && !isValidationRoute($url) && !isDisconnectionRoute($url)) {
		header('Location: /account/validate');
		exit();
	}

	if (isPublicPath($url, $method))
		return;

	if (!issetUser() && !isAuthRoute($url)) {
		header('Location: /auth/login');
		exit();
	}

	if (issetUser() && isAuthRoute($url) && !isDisconnectionRoute($url)) {
		header('Location: /');
		exit();
	}
}
