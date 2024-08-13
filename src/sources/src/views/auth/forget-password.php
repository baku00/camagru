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
			<form action="/auth/forget-password" method="post">
				<div class="d-none">
					<input type="text" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
				</div>
				<div class="mb-3">
					<label for="username" class="form-label">Nom d'utilisateur</label>
					<input type="username" class="form-control" id="username" name="username" required>
				</div>
				<button type="submit" class="btn btn-primary me-3">Recevoir un lien</button>
				<a href="/auth/login" class="me-3">Se connecter</a>
				<a href="/auth/register">S'inscrire</a>
			</form>
		</div>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/script.php'; ?>
	</body>
</html>