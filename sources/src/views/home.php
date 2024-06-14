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
			<div id="posts">
				<?php foreach ($posts as $post): ?>
					<a href="https://localhost/posts?id=<?= $post['id'] ?>">
						<div class="card m-auto mb-3" style="width: 18rem;">
							<img src="https://localhost/storage/<?= $post['path'] ?>.png" alt="">
							<div class="card-body">
								<p class="card-text"><small class="text-muted"><?= $post['created_at'] ?></small></p>
							</div>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</body>
</html>