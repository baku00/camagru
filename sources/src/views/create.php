<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/head.php'; ?>
	</head>
	<body>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/navbar.php'; ?>
		<div class="container pt-3">
			<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/components/alert.php'; ?>
			<div class="card" style="width: 20rem;">
				<div class="d-flex">
					<img src="" id="preview" alt="">
					<video id="video" class="d-none"></video>
				</div>
				<div class="card-body">
					<h5 class="card-title">Ajout d'image</h5>
					<p class="card-text">Sélectionner une image depuis votre périphérique (Ordinateur ou téléphone)</p>
					<input type="file" id="select-picture" class="d-none">
					<form id="upload-picture" class="d-none">
						<input type="text" id="source-64">
					</form>
					<div class="d-flex">
						<button class="btn btn-primary me-3" id="take-picture">Caméra</button>
						<button class="btn btn-primary me-3" onclick="document.querySelector('#select-picture').click()">Importer</button>
						<button class="btn btn-primary" onclick="uploadPicture()">Publier</button>
					</div>
				</div>
			</div>
		</div>
		<script>
			async function uploadPicture() {
				const formData = new URLSearchParams();
				formData.append('source', document.getElementById('source-64').value);

				const response = await fetch('/posts', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded',
					},
					credentials: 'same-origin',
					body: formData
				});
				if (response.status === 400)
					alert(await response.text());
				else if (response.status === 200)
				{
					const post = await response.json();
					window.location.href = `/posts?id=${post.id}`;
				}
				else
					console.error(response);
			}

			const fileInput = document.querySelector('#select-picture');
			fileInput.addEventListener('change', async (event) => {
				const f = event.target.files[0];
				file = await convertImageToBase64(f);
				const canvas = document.createElement("canvas");
				const ctx = canvas.getContext("2d");
				const image = new Image();
				image.src = file;
				image.onload = function () {
					canvas.width = this.width;
					canvas.height = this.height;
					ctx.drawImage(image, 0, 0, this.width, this.height);
					document.getElementById("preview").src = canvas.toDataURL();
					document.getElementById("source-64").value = canvas.toDataURL();
				};

				canvas.src = file;
				console.log(file);
			});

			function convertImageToBase64(file) {
				return new Promise((resolve, reject) => {
					const reader = new FileReader();

					reader.onload = () => {
						resolve(reader.result);
					};

					reader.onerror = (error) => {
						reject(error);
					};

					reader.readAsDataURL(file);
				});
			}


			// const video = document.getElementById('video');
			// const context = canvas.getContext('2d');
			// canvas.width = 318;
			// canvas.height = 238.5;
			// context.fillStyle = 'black';
			// context.fillRect(0, 0, canvas.width, canvas.height);

			// function drawVideoFrame() {
			// 	if (video.paused || video.ended) {
			// 		return;
			// 	}
			// 	context.save(); // Sauvegarder l'état actuel du contexte
			// 	context.scale(-1, 1); // Inverser horizontalement
			// 	context.drawImage(video, -canvas.width, 0, canvas.width, canvas.height); // Dessiner l'image vidéo inversée
			// 	context.restore(); // Restaurer l'état précédent du contexte
			// 	requestAnimationFrame(drawVideoFrame); // Reprendre la boucle de dessin
			// }

			// // Demander l'accès à la caméra
			// navigator.mediaDevices.getUserMedia({ video: true, preferCurrentTab: false })
			// 	.then((stream) => {
			// 		// Attacher le flux vidéo à l'élément vidéo
			// 		video.srcObject = stream;
			// 		video.play();
			// 		video.addEventListener('play', () => {
			// 			drawVideoFrame();
			// 		});
			// 	})
			// 	.catch((err) => {
			// 		console.error("Erreur lors de l'accès à la caméra : ", err);
			// 	});

			// // Ajouter un écouteur d'événement pour le bouton de capture
			// document.getElementById('take-picture').addEventListener('click', () => {
			// 	// Dessiner l'image vidéo sur le canvas
			// 	context.drawImage(video, 0, 0, 300, 300);
			// });
		</script>
	</body>
</html>
