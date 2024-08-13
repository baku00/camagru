<?php

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/head.php'; ?>
	</head>
	<body>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/navbar.php'; ?>
		<div class="container pt-3">
			<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/alert.php'; ?>
			<form action="/auth/login" method="post">
				<div class="d-none">
					<input type="text" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
				</div>
				<div class="mb-3">
					<label for="username" class="form-label">Nom d'utilisateur</label>
					<input type="username" class="form-control" id="username" name="username" required>
				</div>
				<div class="mb-3">
					<label for="password" class="form-label">Password</label>
					<input type="password" class="form-control" id="password" name="password" required>
				</div>
				<button type="submit" class="btn btn-primary me-3">Se connecter</button>
				<a href="/auth/forget-password" class="me-3">Mot de passe oublié ?</a>
				<a href="/auth/register">S'inscrire</a>
			</form>
		</div>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/script.php'; ?>
	</body>
</html>