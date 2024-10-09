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
		<main>
			<div class="container pt-3">
				<div id="posts">
					<?php if (empty($posts)): ?>
						<div class="alert alert-danger" role="alert">
							Aucun post n'a été trouvé
						</div>
					<?php else: ?>
						<?php foreach ($posts as $post): ?>
							<a href="<?= $_ENV['BASE_URL'] ?>/posts?id=<?= $post['id'] ?>">
								<div class="card m-auto mb-3" style="width: 18rem;">
									<img src="<?= $_ENV['BASE_URL'] ?>/storage/<?= $post['path'] ?>.png" alt="">
									<div class="card-body">
										<p class="card-text"><small class="text-muted"><?= $post['created_at'] ?></small></p>
									</div>
								</div>
							</a>
						<?php endforeach; ?>
						<nav aria-label="Page navigation example">
							<ul class="pagination d-flex justify-content-center">
								<li class="page-item"><a class="page-link <?= $pageNumber - 1 == 0 ? 'd-none' : '' ?>" href="/?page=<?= $pageNumber - 1 ?>">Page précédente</a></li>
								<li class="page-item"><a class="page-link <?= $pageNumber == $total_pages ? 'd-none' : '' ?>" href="/?page=<?= $pageNumber + 1 ?>">Page suivante</a></li>
							</ul>
						</nav>
					<?php endif; ?>
				</div>
			</div>
		</main>
	</body>
	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/footer.php'; ?>
	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/script.php'; ?>
</html>