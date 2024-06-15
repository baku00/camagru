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
			<form action="/auth/reset-password?token=<?= $token ?? '' ?>" method="post">
				<div class="d-none">
					<input type="text" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
				</div>
				<div class="mb-3">
					<label for="username" class="form-label">Nom d'utilisateur</label>
					<input type="username" class="form-control" id="username" name="username" required>
				</div>
				<div class="mb-3">
					<label for="password" class="form-label">Mot de passe</label>
					<input type="password" class="form-control" id="password" name="password" required>
				</div>
				<div class="mb-3">
					<label for="confirm_password" class="form-label">Confirmation</label>
					<input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
				</div>
				<button type="submit" class="btn btn-primary me-3">Appliquer</button>
				<a href="/auth/login" class="me-3">Se connecter</a>
				<a href="/auth/register">S'inscrire</a>
			</form>
		</div>
	</body>
</html>