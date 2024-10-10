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

function setTokenResetPassword($token, $username)
{
	global $pdo;
	$stmt = $pdo->prepare('UPDATE users SET token_reset_password = :token_reset_password WHERE username = :username');
	$stmt->execute([
		'token_reset_password' => $token,
		'username' => $username
	]);
	return $stmt->rowCount();
}

function sendResetPasswordEmail($username) {
	global $pdo;
	$token = generateToken();
	$updated = setTokenResetPassword($token, $username);
	sendMail(fetchByUsername($username)['email'], 'Réinitialisation de votre mot de passe', 'Cliquez sur ce lien pour réinitialiser votre mot de passe : <a href="' . $_ENV['BASE_URL'] . '/auth/reset-password?token=' . $token . '">Réinitialiser</a>');
	return $updated;
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

function generateToken() {
	return bin2hex(random_bytes(32));
}

function getToken($username) {
	global $pdo;
	$stmt = $pdo->prepare('SELECT token_validation FROM users WHERE username = :username');
	$stmt->execute(['username' => $username]);
	return $stmt->fetch()['token_validation'];
}

function updateToken($token, $username) {
	global $pdo;
	$stmt = $pdo->prepare('UPDATE users SET token_validation = :token_validation WHERE username = :username');
	$stmt->execute([
		'token_validation' => $token,
		'username' => $username
	]);
}
function sendVerificationLink($user, $email) {
	$token = getToken($user['username']);
	var_dump($token);
	if (!$token) {
		$token = generateToken();
		updateToken($token, $user['username']);
	}
	sendMail($email, 'Validation de votre compte', "Cliquez sur ce lien pour valider votre compte : <a href='" . $_ENV['BASE_URL'] . "/account/validate?token=$token'>Valider</a>");
}

function isUserValidated($user) {
	return $user && $user['validated_at'] !== null;
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
		return false;
	}
}

function checkPassword($password, $hash) {
	return password_verify($password . $_ENV['SALT_PASSWORD'], $hash);
}

function notifyUser($notify, $userId) {
	if (!in_array($notify, [0,1]))
		send_http_error("La notification doit être 1 ou 0", 400);

	$_SESSION['user']['notify'] = $notify;
	global $pdo;
	$stmt = $pdo->prepare('UPDATE users SET notify = :notify WHERE id = :id');
	$stmt->execute([
		'notify'=> $notify,
		'id'=> $_SESSION['user']['id'],
	]);
	echo json_encode([
		'notify' => $notify
	]);
}
