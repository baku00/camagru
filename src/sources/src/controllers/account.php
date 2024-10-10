<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/user.php';

function get()
{
	require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/account.php';
}

function post()
{
	global $pdo;
	$username = strtolower(substr(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS) ?? '', 0, 255));
	$email = strtolower(substr(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '', 0, 255));
	$old_password = substr(filter_input(INPUT_POST, 'old_password', FILTER_SANITIZE_SPECIAL_CHARS) ?? '', 0, 255);
	$password = substr(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS) ?? '', 0, 255);
	$confirm_password = substr(filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_SPECIAL_CHARS) ?? '', 0, 255);

	$_SESSION['errors'] = [];
	$_SESSION['messages'] = [];

	if (!validateUsernamePolicy($username))
		$_SESSION['errors'][] = 'Nom d\'utilisateur invalide';

	if (!validateEmailPolicy($email))
		$_SESSION['errors'][] = 'Adresse mail invalide';

	if (!empty($password)) {
		if (empty($old_password))
			$_SESSION['errors'][] = 'Veuillez entrer votre mot de passe actuel';

		if (!validatePasswordPolicy($password))
			$_SESSION['errors'][] = 'Mot de passe invalide';

		if ($password !== $confirm_password)
			$_SESSION['errors'][] = 'Les mots de passe ne correspondent pas';
	}

	if (!empty($_SESSION['errors'])) {
		header('Location: /account');
		return;
	}

	$user = fetchByUsername($username);
	if ($user && $user['id'] !== $_SESSION['user']['id'])
		$_SESSION['errors'][] = 'Nom d\'utilisateur déjà utilisé';

	$user = fetchByEmail($email);
	if ($user && $user['id'] !== $_SESSION['user']['id'])
		$_SESSION['errors'][] = 'Adresse mail déjà utilisée';

	$user = fetchById($_SESSION['user']['id']);

	if (!empty($password))
	{
		if (empty($old_password) || !!checkPassword($password, $user['password']))
			$_SESSION['errors'][] = 'Mot de passe incorrect';
	}

	if (!empty($_SESSION['errors'])) {
		header('Location: /account');
		return;
	}

	
	if (!empty($old_password) && !empty($password)) {
		$user = updateWithPassword($username, $email, $password);
	}
	else {
		$user = updateWithoutPassword($username, $email);
	}

	// if ($_SESSION['user']['email'] !== $email) {
	// 	$token = bin2hex(random_bytes(32));
	// 	$stmt = $pdo->prepare('UPDATE users SET token_validation = :token_validation, validated_at = NULL WHERE id = :id');
	// 	$stmt->execute([
	// 		'token_validation'=> $token,
	// 		'id'=> $user['id'],
	// 	]);

	// 	sendMail($email, 'Validation de votre compte', "Cliquez sur ce lien pour valider votre compte : <a href='" . $_ENV['BASE_URL'] . "/account/validate?token=$token'>Valider</a>");
	// 	$_SESSION['user'] = fetchById($user["id"]);
	// 	unset($_SESSION['user']['password']);
	// 	header("Location: /account/validate");
	// 	exit();
	// }
	$_SESSION['user'] = $user;
	unset($_SESSION['user']['password']);
	header('Location: /account');
}