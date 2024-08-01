function setSuperposition(_) {
	document.getElementById('img-superposition-image').src = _;

	const takePictureButton = document.getElementById('take-picture');
	if (!!_)
		takePictureButton.removeAttribute('disabled');
	else
		takePictureButton.setAttribute('disabled', true);
}

function takePicture() {
	const picture = createImageForCanvas(camera.video);
	setPicture(picture.toDataURL());
	switchMode();
}

function setPicture(src) {
	document.querySelector('#selected-picture').src = src;
	document.querySelector('#source-64').value = src;
}


async function uploadPicture() {
	const formData = new URLSearchParams();
	formData.append('source', document.getElementById('source-64').value);
	formData.append('superposition-image', document.getElementById('img-superposition-image').src.split('/').pop());
	formData.append('csrf', document.querySelector('[name="csrf"]').value);

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
	setPicture(file);
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

function createImageForCanvas(video) {
	const picture = document.createElement('canvas');
	const context = picture.getContext('2d');
	picture.width = video.videoWidth;
	picture.height = video.videoHeight;
	const image = new Image();
	image.src = picture.toDataURL();
	image.onload = function () {
		canvas.width = this.width;
		canvas.height = this.height;
	};
	context.drawImage(video, 0, 0, picture.width, picture.height);
	return picture;
}