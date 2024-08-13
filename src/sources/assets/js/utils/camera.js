const camera = {
	video: document.querySelector('video'),
	exist: !!navigator.mediaDevices,
	is_visible: true,
	enable: document.querySelector('[data-webcam="enable"]'),
	disable: document.querySelector('[data-webcam="disable"]'),
};

document.addEventListener('DOMContentLoaded', () => {
	if (camera.exist)
		initCamera();
});

function initCamera() {
	navigator.mediaDevices.getUserMedia({ video: true, preferCurrentTab: false })
	.then((stream) => {
		camera.video.srcObject = stream;
		setSuperpositionElement(document.querySelector(`[data-superposition="webcam"]`));
		showCamera();
		document.querySelector('[data-mode="webcam"]').classList.add('d-block');
		document.querySelector('[data-mode="picture"]').classList.add('d-none');
	})
	.catch((err) => {
		setSuperpositionElement(document.querySelector(`[data-superposition="picture"]`));
		hideCamera();
		showSuperposables();
		document.querySelector('[data-mode="webcam"]').classList.add('d-none');
		document.querySelector('[data-mode="picture"]').classList.add('d-block');
	});
}

function hideCamera() {
	hideSuperposables();
	video.classList.add('d-none');
	document.querySelector('[data-webcam-picture]').classList.remove('d-none');
	camera.video && camera.video.pause();
	camera.enable.classList.remove('d-block');
	camera.enable.classList.add('d-none');
	camera.disable.classList.remove('d-none');
	camera.disable.classList.add('d-block');
	camera.is_visible = false;
}

function showCamera() {
	showSuperposables();
	video.classList.remove('d-none');
	document.querySelector('[data-webcam-picture]').classList.add('d-none');
	camera.video && camera.video.play();
	camera.enable.classList.remove('d-none');
	camera.enable.classList.add('d-block');
	camera.disable.classList.remove('d-block');
	camera.disable.classList.add('d-none');
	camera.is_visible = true;
	resetSuperposition();
}
