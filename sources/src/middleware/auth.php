<?php	
$publicPages = [
	'' => ['get'],
	'favicon.ico' => ['get'],
	'posts' => ['get'],
];

function checkPath($url, $method) {
	global $publicPages;

	if (isset($publicPages[$url]) && in_array($method, $publicPages[$url]))
		return;

	if (!isset($_SESSION['user']) && !startsWith($url, 'auth/')) {
		header('Location: /auth/login');
		exit();
	}

	if (isset($_SESSION['user']) && $_SESSION['user']['validated_at'] === null && !startsWith($url, 'account/validate') && !startsWith($url, 'auth/logout') && !startsWith($url, 'auth/validate')) {
		header('Location: /account/validate');
		exit();
	}

	if (isset($_SESSION['user']) && startsWith($url, 'auth/') && !startsWith($url, 'auth/logout')) {
		header('Location: /');
		exit();
	}
}

