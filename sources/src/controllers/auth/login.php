<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/user.php';

function get()
{
	require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/auth/login.php';
}

function post()
{
	global $pdo;
	$email = strtolower(substr(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL), 0, 255));
	$password = substr(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS), 0, 255);
	$_SESSION['errors'] = [];

	if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL))
		$_SESSION['errors'][] = 'Email invalide';

	if (empty($email) || empty($password))
		$_SESSION['errors'][] = 'Veuillez remplir tous les champs';

	if (!empty($_SESSION['errors'])) {
		header('Location: /auth/login');
		return;
	}

	$user = fetchUser($email);
	if (!$user || !password_verify($password . $_ENV['SALT_PASSWORD'], $user['password'])) {
		$_SESSION['errors'][] = 'Email ou mot de passe incorrect';
		header('Location: /auth/login');
		return;
	}

	$_SESSION['user'] = $user;
	unset($_SESSION['user']['password']);
	header('Location: /');
}