const video = document.querySelector('video');

navigator.mediaDevices.getUserMedia({ video: true, preferCurrentTab: false })
.then((stream) => {
	video.srcObject = stream;
	video.play();
	video.addEventListener('play', () => {
		drawVideoFrame();
	});
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
	const back_webcam = document.getElementById('back-webcam');

	if (back_webcam.classList.contains('d-none')) {
		displayWebcam();
	} else {
		displayPicture();
	}
}

function displayWebcam() {
	const back_webcam = document.getElementById('back-webcam');
	const take_picture = document.getElementById('take-picture');
	const publish_picture = document.getElementById('publish-picture');

	back_webcam.classList.add('d-none');
	take_picture.classList.remove('d-none');
	publish_picture.classList.add('d-none');

	video.play();
}

function displayPicture() {
	const back_webcam = document.getElementById('back-webcam');
	const take_picture = document.getElementById('take-picture');
	const publish_picture = document.getElementById('publish-picture');

	back_webcam.classList.remove('d-none');
	take_picture.classList.add('d-none');
	publish_picture.classList.remove('d-none');

	video.pause();
}