<?php

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/head.php'; ?>
	</head>
	<body>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/navbar.php'; ?>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/alert.php'; ?>
		<div class="container pt-3">
			<div class="alert alert-success">Un email de vérification vous a été envoyé</div>
		</div>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/script.php'; ?>
	</body>
</html>