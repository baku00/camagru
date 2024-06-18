<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/head.php'; ?>
		<style>
			.screen {
				position: relative;
				text-align: center;
			}
			.screen > img {
				position: absolute;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
				z-index: 1;
			}
			.screen > img {
				width: 150px;
			}
		</style>
	</head>
	<body>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/navbar.php'; ?>
		<div class="container pt-3">
			<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/alert.php'; ?>
			<div class="row col-12">
				<div class="card col-lg-9">
					<div class="card-body">
						<div class="screen">
							<!-- <img src="https://10.0.0.3/storage/moustache" alt=""> -->
							<canvas style="width:100%;background-color:black;"></canvas>
						</div>
						<video id="video" class="d-none"></video>
						<button class="btn btn-primary me-3" id="take-picture">Prendre une capture</button>
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
						<img src="https://localhost/storage/moustache" alt="">
					</div>
				</div>
			</div>
		</div>
		<script src="<?= $_ENV['BASE_URL'] ?>/assets/js/canvas.js"></script>
		<script src="<?= $_ENV['BASE_URL'] ?>/assets/js/camera.js"></script>
		<script src="<?= $_ENV['BASE_URL'] ?>/assets/js/picture.js"></script>
		<script>
			let selectedPicture = '';
			const pictures = [];
		</script>
	</body>
</html>
