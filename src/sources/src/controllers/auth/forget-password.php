<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/user.php';

function get() {
	require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/auth/forget-password.php';
}

function post() {
	global $pdo;
	$username = substr(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS), 0, 255);
	$_SESSION['errors'] = [];

	if (empty($username))
		$_SESSION['errors'][] = 'Veuillez saisir un nom d\'utilisateur';

	if (!validateUsernamePolicy($username))
		$_SESSION['errors'][] = 'Nom d\'utilisateur invalide';

	if (!empty($_SESSION['errors']))
	{
		header('Location: /auth/forget-password');
		return;
	}

	$user = fetchUser($username);
	if (!$user)
	{
		$_SESSION['errors'][] = 'Nom d\'utilisateur inconnu';
		header('Location: /auth/forget-password');
		return;
	}

	sendResetPasswordEmail($username);
	$_SESSION['messages'] = ['Un email vous a été envoyé'];
	header('Location: /auth/login');
}