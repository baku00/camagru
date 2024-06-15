<?php

function fetchByEmail($email)
{
	global $pdo;
	$stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
	$stmt->execute(['email' => $email]);
	return $stmt->fetch();
}

function fetchByUsername($username)
{
	global $pdo;
	$stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
	$stmt->execute(['username' => $username]);
	return $stmt->fetch();
}

function fetchById($id)
{
	global $pdo;
	$stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
	$stmt->execute(['id' => $id]);
	return $stmt->fetch();
}

function fetchUser($value)
{
	if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
		return fetchByEmail($value);
	}
	if (filter_var($value, FILTER_VALIDATE_INT)) {
		return fetchById($value);
	}
	return fetchByUsername($value);
}

function sendResetPasswordEmail($username) {
	global $pdo;
	$token = bin2hex(random_bytes(32));
	$stmt = $pdo->prepare('UPDATE users SET token_reset_password = :token_reset_password WHERE username = :username');
	$stmt->execute([
		'username'=> $username,
		'token_reset_password'=> $token
	]);
	sendMail(fetchByUsername($username)['email'], 'Réinitialisation de votre mot de passe', 'Cliquez sur ce lien pour réinitialiser votre mot de passe : <a href="' . $_ENV['BASE_URL'] . '/auth/reset-password?token=' . $token . '">Réinitialiser</a>');
	return $stmt->rowCount();
}

function validatePasswordPolicy($password) {
	return !empty($password) && strlen($password) >= 8 && strlen($password) <= 255 && preg_match('/[0-9]/', $password) && preg_match('/[A-Z]/', $password) && preg_match('/[a-z]/', $password) && preg_match('/[$@!%*?&]/', $password);
}

function validateUsernamePolicy($username) {
	return !empty($username) && strlen($username) >= 3 && strlen($username) <= 255 && preg_match('/^[a-zA-Z]+$/', $username);
}

function validateEmailPolicy($email) {
	return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
}

function refreshEmailValidationIfNeeded($user, $email) {
	global $pdo;

	$token = bin2hex(random_bytes(32));
	$stmt = $pdo->prepare('UPDATE users SET email = :email, token_validation = :token_validation, validated_at = NULL WHERE id = :id');
	$stmt->execute([
		'email' => $email,
		'token_validation' => $token,
		'id' => $user['id']
	]);
	$user['validated_at'] = NULL;
}

function updateWithPassword($username, $email, $password) {
	global $pdo;
	$user = fetchById($_SESSION['user']['id']);
	$stmt = $pdo->prepare('UPDATE users SET username = :username, email = :email, password = :password WHERE id = :id');
	$stmt->execute([
		'username' => $username,
		'email' => $email,
		'password' => password_hash($password . $_ENV['SALT_PASSWORD'], PASSWORD_DEFAULT),
		'id' => $_SESSION['user']['id']
	]);
	return fetchById($_SESSION['user']['id']);
}

function updateWithoutPassword($username, $email) {
	global $pdo;
	$stmt = $pdo->prepare('UPDATE users SET username = :username, email = :email WHERE id = :id');
	$stmt->execute([
		'username' => $username,
		'email' => $email,
		'id' => $_SESSION['user']['id']
	]);
	$_SESSION['messages'][] = 'Mise à jour effectuée';
	return fetchById($_SESSION['user']['id']);
}

function deleteUser($id) {
	global $pdo;
	$stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
	$stmt->execute(['id' => $id]);
}

function validateUser($token) {
	try {
		global $pdo;
		$stmt = $pdo->prepare('UPDATE users SET validated_at = NOW() WHERE token_validation = :token_validation AND validated_at IS NULL AND id = :id');
		$stmt->execute([
			'token_validation'=> $token,
			'id'=> $_SESSION['user']['id'],
		]);

		return true;
	} catch (\Throwable $th) {
		echo $th->getMessage();
		// return false;
		die();
	}

}