<?php

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/head.php'; ?>
	</head>
	<body>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/navbar.php'; ?>
		<main>
			<div class="container pt-3">
				<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/alert.php'; ?>
				<form action="/auth/register" method="post">
					<div class="d-none">
						<input type="text" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
					</div>
					<div class="mb-3">
						<label for="username" class="form-label">Nom d'utilisateur</label>
						<input type="text" class="form-control" id="username" name="username" value="<?= $username ?? '' ?>" required>
					</div>
					<div class="mb-3">
						<label for="email" class="form-label">Adresse mail</label>
						<input type="email" class="form-control" id="email" name="email" value="<?= $email ?? '' ?>" required>
					</div>
					<div class="mb-3">
						<label for="password" class="form-label">Mot de passe</label>
						<input type="password" class="form-control" id="password" name="password"  value="<?= $password ?? '' ?>" required>
					</div>
					<div class="mb-3">
						<label for="confirm-password" class="form-label">Confirmation</label>
						<input type="password" class="form-control" id="confirm-password" name="confirm_password"  value="<?= $confirm_password ?? '' ?>" required>
					</div>
					<button type="submit" class="btn btn-primary">S'inscrire</button>
					<a href="/auth/login">Se connecter</a>
				</form>
			</div>
		</main>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/script.php'; ?>
	</body>
	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/footer.php'; ?>
</html>