<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/head.php'; ?>
	</head>
	<body>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/navbar.php'; ?>
		<div class="container pt-3">
			<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/alert.php'; ?>
			<div class="row col-12">
				<div class="card col-lg-9">
					<div class="card-body">
						<div class="screen">
							<canvas style="width:100%;background-color:black;"></canvas>
						</div>
						<video id="video" class="d-none"></video>
						<button class="btn btn-primary me-3" id="take-picture" disabled>Prendre une capture</button>
						<button class="btn btn-primary me-3 d-none" id="back-webcam">Retour à la caméra</button>
						<button class="btn btn-primary me-3 d-none" id="publish-picture">Publier</button>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="card">
						<div class="card-body">
							<h5 class="card-title">Importer une image</h5>
							<button class="btn btn-primary me-3" onclick="document.querySelector('#select-picture').click()">Sélectionner</button>
							<input type="file" id="select-picture" class="d-none">
							<form id="upload-picture" class="d-none">
								<input type="text" id="source-64">
								<input type="text" id="superposition-image">
								<div class="d-none">
									<input type="text" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
								</div>
							</form>
						</div>
					</div>
					<div id="pictures">
					</div>
				</div>
			</div>
		</div>
		<script src="<?= $_ENV['BASE_URL'] ?>/assets/js/canvas.js"></script>
		<script src="<?= $_ENV['BASE_URL'] ?>/assets/js/camera.js"></script>
		<script src="<?= $_ENV['BASE_URL'] ?>/assets/js/picture.js"></script>
		<script>
			let selectedPicture = '';
			const pictures = [
				{
					link: "https://10.0.0.3/storage/moustache",
					dimension: {
						canvasWidth: 2.5,
						canvasHeight: 2,
						width: 156,
						height: 130
					}
				},
				{
					link: "https://10.0.0.3/storage/couronne",
					dimension: {
						canvasWidth: 4,
						canvasHeight: 5,
						width: 300,
						height: 216
					}
				}
			];

			pictures.forEach(picture => {
				console.log(picture);
				const img = document.createElement('img');
				img.src = picture.link;
				img.style.width = '100%';
				img.style.cursor = 'pointer';
				img.addEventListener('click', () => {
					selectedPicture = picture;
					document.querySelector('#superposition-image').value = picture.link.split('/').pop();
					setSuperposition(picture);
				});
				document.querySelector('#pictures').appendChild(img);
			})
		</script>
	</body>
</html>
