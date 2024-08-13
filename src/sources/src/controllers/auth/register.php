<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/user.php';

function get()
{
	require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/auth/register.php';
}

function post()
{
	global $pdo;
	$email = strtolower(substr(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL), 0, 255));
	$username = substr(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS), 0, 255);
	$password = substr(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS), 0, 255);
	$confirm_password = substr(filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_SPECIAL_CHARS), 0, 255);
	$_SESSION['errors'] = [];

	if (empty($email) || empty($username) || empty($password))
		$_SESSION['errors'][] = 'Veuillez remplir tous les champs';

	if (!validateUsernamePolicy($username))
		$_SESSION['errors'][] = 'Le nom d\'utilisateur ne doit contenir que des minuscules et des majuscules et doit être compris entre 3 et 255 caractères';

	if (!validatePasswordPolicy($password))
		$_SESSION['errors'][] = 'Le mot de passe doit contenir au moins 8 caractères et max 255, une majuscule, une minuscule, un chiffre et un caractère spécial ($, @, !, %, *, ?, &)';

	if (!validateEmailPolicy($email))
		$_SESSION['errors'][] = 'Email invalide';

	if ($password !== $confirm_password)
		$_SESSION['errors'][] = 'Les mots de passe ne correspondent pas';

	if ($_SESSION['errors']) {
		header('Location: /auth/register');
		return;
	}

	$user = fetchUser($email) || fetchUser($username);
	if ($user) {
		$_SESSION['errors'][] = 'Email ou nom d\'utilisateur déjà utilisé';
		header('Location: /auth/register');
		return;
	}

	$token = bin2hex(random_bytes(32));
	$stmt = $pdo->prepare('INSERT INTO users (email, username, password, token_validation) VALUES (:email, :username, :password, :token_validation)');
	$stmt->execute([
		'email' => $email,
		'username' => $username,
		'password' => password_hash($password . $_ENV['SALT_PASSWORD'], PASSWORD_DEFAULT),
		'token_validation'=> $token,
	]);

	sendMail($email, 'Validation de votre compte', "Cliquez sur ce lien pour valider votre compte : <a href='" . $_ENV['BASE_URL'] . "/account/validate?token=$token'>Valider</a>");

	$user = fetchUser($email) ?? fetchUser($username);

	$_SESSION['user'] = $user;
	unset($_SESSION['user']['password']);
	header('Location: /account/validate');
}