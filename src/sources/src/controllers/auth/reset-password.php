<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/user.php';

function get() {
	$token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_SPECIAL_CHARS);
	require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/auth/reset-password.php';
}

function post() {
	global $pdo;

	$token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_SPECIAL_CHARS);
	$username = substr(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS), 0, 255);
	$password = substr(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS), 0, 255);
	$confirm_password = substr(filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_SPECIAL_CHARS), 0, 255);

	$_SESSION['errors'] = [];

	if (empty($password) || empty($confirm_password) || empty($username))
		$_SESSION['errors'][] = 'Veuillez remplir tous les champs';
	
	if (!validateUsernamePolicy($username))
		$_SESSION['errors'][] = 'Le nom d\'utilisateur est invalide';

	if (!validatePasswordPolicy($password))
		$_SESSION['errors'][] = 'Le mot de passe doit contenir au moins 8 caractères et max 255, une majuscule, une minuscule, un chiffre et un caractère spécial ($, @, !, %, *, ?, &)';

	if ($password !== $confirm_password)
		$_SESSION['errors'][] = 'Les mots de passe ne correspondent pas';

	if ($_SESSION['errors']) {
		require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/auth/reset-password.php';
		return;
	}

	$user = fetchUser($username);
	if (!$user) {
		$_SESSION['errors'][] = 'Nom d\'utilisateur introuvable';
		require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/auth/reset-password.php';
		return;
	}

	if ($user['token_reset_password'] !== $token) {
		$_SESSION['errors'][] = 'Token invalide';
		require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/auth/reset-password.php';
		return;
	}

	$stmt = $pdo->prepare('UPDATE users SET password = :password, token_reset_password = NULL WHERE id = :id');
	$stmt->execute([
		'password' => password_hash($password . $_ENV['SALT_PASSWORD'], PASSWORD_DEFAULT),
		'id' => $user['id']
	]);

	sendMail($user['email'], 'Mot de passe réinitialiser', "Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.");
	$_SESSION['user'] = $user;
	unset($_SESSION['user']['password']);
	header('Location: /');
}