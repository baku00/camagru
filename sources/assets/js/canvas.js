const canvas = document.querySelector('canvas');
const context = canvas.getContext('2d');
const superposition = new Image();
superposition.src = "https://10.0.0.3/storage/moustache";

function drawSuperposition(superposition_src) {
	superposition.src = superposition_src;
	context.drawImage(superposition, canvas.width / 3, canvas.height / 3, 150 * (window.screen.availWidth / 100), 150 * (window.screen.availHeight / 100));
}

function drawVideoFrame() {
	if (video.paused || video.ended) {
		return;
	}
	const picture = createImageForCanvas(video);
	context.drawImage(video, 0, 0, picture.width, picture.height);
	document.getElementById('source-64').value = canvas.toDataURL();
	drawSuperposition(superposition.src);
	requestAnimationFrame(drawVideoFrame);
}

function takePicture(video) {
	const dataUrl = canvas.toDataURL();
	pictures.push(dataUrl);
	refreshPictures();
}

function createImageForCanvas(video) {
	const picture = document.createElement('canvas');
	picture.width = video.videoWidth;
	picture.height = video.videoHeight;
	const image = new Image();
	image.src = picture.toDataURL();
	image.onload = function () {
		canvas.width = this.width;
		canvas.height = this.height;
	};

	return picture;
}