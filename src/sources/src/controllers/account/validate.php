<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/user.php';

function get() {
	if ($_SESSION['user']['validated_at'] !== null)
		header('Location: /');

	$token = strtolower(substr(filter_input(INPUT_GET, 'token', FILTER_SANITIZE_SPECIAL_CHARS), 0, 255));
	if (!empty($token)) {
		if (validateUser($token)) {
			$_SESSION['user'] = fetchById($_SESSION['user']['id']);
			header('Location: /');
			return;
		} else {
			$_SESSION['errors'] = ['Validation impossible'];
		}
	}
	require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/account/validate.php';
}
