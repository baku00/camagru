<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/head.php'; ?>
		<style>
			.picture-history {
				width: 100%;
			}
			.preview {
				position: relative;
				max-width: 400px;
				width: 100%;
				max-height: 585px;
				max-height: 600px;
			}
			.preview .picture {
				width: 100%;
				height: 100%;
				object-fit: cover;
				transform: scaleX(-1);
				pointer-events: none;
			}
			.preview .superposition {
				max-width: 150px;
				width: auto;
				max-height: 100%;
				height: auto;
				position: absolute;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%) scaleX(-1);
				object-fit: cover;
				pointer-events: none;
			}
		</style>
	</head>
	<body>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/navbar.php'; ?>
		<div class="container pt-3">
			<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/alert.php'; ?>
			<div class="row">
				<div class="col-lg-9 col-12">
					<div class="card">
						<div class="card-body text-center">
							<div data-mode="webcam">
								<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/picture/mode/webcam.php'; ?>
							</div>
							<div data-mode="picture">
								<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/picture/mode/import.php'; ?>
							</div>
						</div>
						<div id="superposables" class="w-100 d-flex">
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-12 mt-3">
					<div class="card">
						<div id="pictures" class="card-body"></div>
					</div>
				</div>
			</div>
		</div>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/script.php'; ?>
		<script src="<?= $_ENV['BASE_URL'] ?>/assets/js/utils/superposition.js?token<?= mt_rand() ?>" defer></script>
		<script src="<?= $_ENV['BASE_URL'] ?>/assets/js/components/superposable.js?token<?= mt_rand() ?>" defer></script>
		<script src="<?= $_ENV['BASE_URL'] ?>/assets/js/utils/camera.js?token<?= mt_rand() ?>" defer></script>
		<script src="<?= $_ENV['BASE_URL'] ?>/assets/js/utils/picture.js?token<?= mt_rand() ?>" defer></script>
		<script src="<?= $_ENV['BASE_URL'] ?>/assets/js/utils/pictures.js?token<?= mt_rand() ?>" defer></script>
		<script>
			document.addEventListener('DOMContentLoaded', () => {
				loadSuperposables();
				new Ajaxify({
					
				})
			});
		</script>
	</body>
</html>
