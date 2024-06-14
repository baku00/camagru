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
				<div class="card m-auto mb-3" style="width: 18rem;">
					<img src="https://localhost/storage/<?= $post['path'] ?>.png" alt="">
					<div class="card-body">
						<p class="card-title">Autheur: <?= $post['username'] ?></p>
						<p class="card-text"><small class="text-muted"><?= $post['created_at'] ?></small></p>
					</div>
				</div>
			</div>
			<div id="comments">
				<?php foreach ($comments as $comment): ?>
					<div class="card m-auto mb-3" style="width: 18rem;">
						<div class="card-body">
							<p class="card-text"><?= $comment['content'] ?></p>
							<p class="card-text"><small class="text-muted"><?= $comment['created_at'] ?></small></p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="card m-auto mb-3" style="width: 18rem;">
				<div class="card-body">
					<div class="mb-3">
						<form data-form-add-comment>
							<label for="content" class="form-label">Commentaire</label>
								<input type="text" class="form-control" id="content" name="content" data-comment-content>
							</div>
							<button type="button" data-button-comment class="btn btn-primary">Commenter</button>
						</form>
					</div>
				</div>
			</div>
		<script>
			const input = document.querySelector('[data-comment-content]');
			const button = document.querySelector('[data-button-comment]');
			const form = document.querySelector('[data-form-add-comment]');

			form.addEventListener('submit', function(event) {
				event.preventDefault();
				if (!button.disabled)
					button.click();
			});

			input.addEventListener('input', function() {
				button.disabled = !this.value;
			});
			button.addEventListener('click', function() {
				const content = document.querySelector('[data-comment-content]').value;
				fetch(`https://localhost/comments`, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded',
					},
					body: `content=${content}&postId=<?= $post['id'] ?>`,
				})
				.then(async (_) => {
					const response = await _.json();
					addComment(response.id, response.content, response.author, response.created_at);
					input.value = '';
				})
			});

			function addComment(id, content, author, created_at) {
				const card = document.createElement('div');
				card.classList.add('card', 'm-auto', 'mb-3');
				card.style.width = '18rem';
				card.innerHTML = `
					<div class="card-body">
						<p id="comment-${id}-content" class="card-text"></p>
						<p id="comment-${id}-author" class="card-text"></p>
						<p class="card-text"><small class="text-muted" id="comment-${id}-date"></small></p>
					</div>
				`;

				document.querySelector('#comments').appendChild(card);

				document.querySelector(`#comment-${id}-content`).innerText = content;
				document.querySelector(`#comment-${id}-author`).innerText = author;
				document.querySelector(`#comment-${id}-date`).innerText = created_at;
			}
		</script>
	</body>
</html>