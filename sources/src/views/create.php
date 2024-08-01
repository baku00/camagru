<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/head.php'; ?>
		<style>
			#pictures {
				width: 100px;
			}
		</style>
	</head>
	<body>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/navbar.php'; ?>
		<div class="container pt-3">
			<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/alert.php'; ?>
			<div class="row col-12 m-auto">
				<div class="card col-lg-9">
					<div class="card-body">
						<div class="screen">
							<video data-mode="webcam" id="video" class="d-block" style="height:500px;background-color:black;margin:auto;"></video>
							<img data-mode="view-picture" id="selected-picture" class="d-block" style="height:500px;background-color:black;margin:auto;" src="" alt="">
							<img id="img-superposition-image" class="d-block" style="position:absolute;width:112.5px;inset:30% 56% 70% 44%;" src="" alt="">
						</div>
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
		<script src="<?= $_ENV['BASE_URL'] ?>/assets/js/camera.js"></script>
		<script src="<?= $_ENV['BASE_URL'] ?>/assets/js/picture.js"></script>
		<script>
			let selectedPicture = '';
			const pictures = [
				"<?= $_ENV['BASE_URL'] ?>/storage/moustache",
				"<?= $_ENV['BASE_URL'] ?>/storage/couronne",
			];

			pictures.forEach(picture => {
				const img = document.createElement('img');
				img.src = picture;
				img.style.width = '100%';
				img.style.cursor = 'pointer';
				img.addEventListener('click', () => {
					setSuperposition(picture);
				});
				document.querySelector('#pictures').appendChild(img);
			})
		</script>
	</body>
</html>
