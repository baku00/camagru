const camera = {
	video: document.querySelector('video'),
	exist: navigator.mediaDevices,
	is_visible: true,
};

camera.exist && navigator.mediaDevices.getUserMedia({ video: true, preferCurrentTab: false })
.then((stream) => {
	camera.video.srcObject = stream;
	camera.video.play();
})
.catch((err) => {
	console.error("Erreur lors de l'accès à la caméra : ", err);
});

document.getElementById('take-picture').addEventListener('click', () => {
	takePicture();
});

document.getElementById('back-webcam').addEventListener('click', () => {
	displayWebcam();
});

document.getElementById('publish-picture').addEventListener('click', async () => {
	await uploadPicture();
});

function switchMode() {
	if (!camera.is_visible) {
		displayWebcam();
	} else {
		displayPicture();
	}
	camera.is_visible = !camera.is_visible;
}

function displayWebcam() {
	const webcam_mode_elements = document.querySelectorAll('[data-mode="webcam"]');
	const view_picture_mode_elements = document.querySelectorAll('[data-mode="view-picture"]');

	webcam_mode_elements.forEach(element => {
		element.classList.remove('d-none');
		element.classList.add('d-block');
	})

	view_picture_mode_elements.forEach(element => {
		element.classList.add('d-none');
		element.classList.remove('d-block');
	})

	if (camera.video)
		camera.video.play();
}

function displayPicture() {
	const webcam_mode_elements = document.querySelectorAll('[data-mode="webcam"]');
	const view_picture_mode_elements = document.querySelectorAll('[data-mode="view-picture"]');

	webcam_mode_elements.forEach(element => {
		element.classList.add('d-none');
		element.classList.remove('d-block');
	})

	view_picture_mode_elements.forEach(element => {
		element.classList.remove('d-none');
		element.classList.add('d-block');
	})

	if (camera.video)
		camera.video.pause();
}