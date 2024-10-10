<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/user.php';

function get()
{
	require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/auth/login.php';
}

function post()
{
	global $pdo;
	$username = substr(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS) ?? '', 0, 255);
	$password = substr(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS) ?? '', 0, 255);
	$_SESSION['errors'] = [];

	if (empty($username) || empty($password))
		$_SESSION['errors'][] = 'Veuillez remplir tous les champs';

	if (!validateUsernamePolicy($username))
		$_SESSION['errors'][] = 'Le nom d\'utilisateur ne doit contenir que des minuscules et des majuscules et doit être compris entre 3 et 255 caractères';

	if (!empty($_SESSION['errors'])) {
		header('Location: /auth/login');
		return;
	}

	$user = fetchUser($username);
	if (!$user || !checkPassword($password, $user['password'])) {
		$_SESSION['errors'][] = 'Email ou mot de passe incorrect';
		header('Location: /auth/login');
		return;
	}

	$_SESSION['user'] = $user;
	unset($_SESSION['user']['password']);

	if ($_SESSION['user']['validated_at'] === null) {
		sendVerificationLink($user, $user['email']);
		header('Location: /account/validate');
		return;
	}
	header('Location: /');
}
