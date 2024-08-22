<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/user.php';

$publicPages = [
	'' => ['get'],
	'favicon.ico' => ['get'],
	'posts' => ['get'],
	'picture' => ['get'],
	'assets' => ['get'],
];

function checkPath($url, $method) {
	global $pdo;
	global $publicPages;

	$user = isset($_SESSION['user']) && $_SESSION['user'] ? fetchById($_SESSION['user']['id']) : null;
	$_SESSION['user'] = $user;
	if (isset($_SESSION['user']) && $_SESSION['user']['validated_at'] === null && !startsWith($url, 'account/validate') && !startsWith($url, 'auth/logout')) {
		header('Location: /account/validate');
		exit();
	}

	if (isset($publicPages[$url]) && in_array($method, $publicPages[$url]))
		return;

	if (!isset($_SESSION['user']) && !startsWith($url, 'auth/')) {
		header('Location: /auth/login');
		exit();
	}

	if (isset($_SESSION['user']) && startsWith($url, 'auth/') && !startsWith($url, 'auth/logout')) {
		header('Location: /');
		exit();
	}
}

