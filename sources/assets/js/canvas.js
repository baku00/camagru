const canvas = document.querySelector('canvas');
const context = canvas.getContext('2d');
let superposition = {}
let superpositionImage = new Image();
let haveToMirror = true;

function setSuperposition(_) {
	superposition = _;
	superpositionImage.src = _.link;
	const takePictureButton = document.getElementById('take-picture');
	if (!!_.link)
		takePictureButton.removeAttribute('disabled');
	else
		takePictureButton.setAttribute('disabled', true);
}

function drawSuperposition() {
	if (!superposition.link)
		return;
	context.drawImage(superpositionImage, canvas.width / superposition.dimension.canvasWidth, canvas.height / superposition.dimension.canvasHeight, superposition.dimension.width, superposition.dimension.height);
}

function drawVideoFrame() {
	if (video.paused || video.ended) {
		return;
	}
	const picture = createImageForCanvas(video);
	context.save();
	context.translate(picture.width, 0);
	context.scale(-1, 1);
	context.drawImage(video, 0, 0, picture.width, picture.height);
	context.restore();
	document.getElementById('source-64').value = canvas.toDataURL();
	drawSuperposition();
	requestAnimationFrame(drawVideoFrame);
}

function takePicture() {
	const picture = createImageForCanvas(video);
	const image = new Image();
	image.src = picture.toDataURL();
	displayPicture();
	image.onload = function () {
		context.save();
		context.translate(picture.width, 0);
		context.scale(-1, 1);
		context.drawImage(video, 0, 0, picture.width, picture.height);
		context.restore();
		document.getElementById('source-64').value = canvas.toDataURL();
		drawSuperposition();
	}
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