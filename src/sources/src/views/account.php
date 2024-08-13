<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/head.php'; ?>
	</head>
	<body>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/navbar.php'; ?>
		<div class="container pt-3">
			<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/alert.php'; ?>
			<form action="/account" method="post">
				<div class="d-none">
					<input type="text" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
				</div>
				<div class="mb-3">
					<label for="username" class="form-label">Nom d'utilisateur</label>
					<input type="text" class="form-control" id="username" name="username" value="<?= $username ?? $_SESSION['user']['username'] ?>" required>
				</div>
				<div class="mb-3">
					<label for="email" class="form-label">Adresse mail</label>
					<input type="email" class="form-control" id="email" name="email" value="<?= $email ?? $_SESSION['user']['email'] ?>" required>
				</div>
				<div class="mb-3">
					<label for="old-password" class="form-label">Ancien mot de passe</label>
					<input type="password" class="form-control" id="old-password" name="old_password"  value="<?= $old_password ?? '' ?>">
				</div>
				<div class="mb-3">
					<label for="password" class="form-label">Mot de passe</label>
					<input type="password" class="form-control" id="password" name="password"  value="<?= $password ?? '' ?>">
				</div>
				<div class="mb-3">
					<label for="confirm-password" class="form-label">Confirmation</label>
					<input type="password" class="form-control" id="confirm-password" name="confirm_password"  value="<?= $confirm_password ?? '' ?>">
				</div>
				<div class="mb-3">
					<div class="form-check">
						<input class="form-check-input" type="checkbox" <?= $_SESSION['user']['notify'] ? 'checked' : '' ?> id="notify">
						<label class="form-check-label" for="notify">
							Notifications
						</label>
					</div>
				</div>
				<button type="submit" class="btn btn-primary">Modifier</button>
				<button type="button" id="remove-button" class="btn btn-danger">Supprimer</button>
			</form>
			<form id="remove-account" action="/account/delete" method="post">
				<div class="d-none">
					<input type="text" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
				</div>
			</form>
		</div>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/script.php'; ?>
		<script>
			DOMContentLoaded(() => {
				document.querySelector('#notify').addEventListener('change', async (e) => {
					await fetch('/account/notify', {
							method: 'POST',
							headers: {
								'Content-Type': 'application/x-www-form-urlencoded',
							},
							body: `notify=${e.target.checked ? '1' : '0'}&csrf=<?= $_SESSION['csrf'] ?>`,
						})
						.then(async (_) => {
							if (!(_.status >= 200 && _.status <= 299))
								return alert('Une erreur est survenue');
						});
				})
				document.querySelector('.btn-danger').addEventListener('click', () => {
					if (confirm('Voulez-vous vraiment supprimer votre compte ?'))
						document.getElementById('remove-account').submit();
				});
			});
		</script>
	</body>
</html>