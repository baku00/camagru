class Picture {
	#src = '';
	#superposition = '';

	constructor(src, superposition) {
		this.#src = src;
		this.#superposition = superposition;
	}

	get src() {
		return this.#src;
	}

	get superposition() {
		return this.#superposition;
	}
}

function takePicture() {
	const pictureManager = PictureManager.getInstance();
	const picture = createImageForCanvas(camera.video);
	const dataURL = '';
	setPicture(picture.toDataURL());
	pictureManager.pictureTemplate(picture)
	hideCamera();
}

function setPicture(src) {
	document.querySelector('[data-picture="picture"]').src = src;
	// document.querySelector('#selected-picture').src = src;
	document.querySelector('#source-64').value = src;
	document.querySelectorAll('[data-picture="publish"]').forEach(element => {
		element.classList.remove('d-none');
		element.classList.add('d-block');
	});
	document.querySelector('[data-picture="select"]').classList.add('d-none');
	document.querySelector('[data-picture="select"]').classList.remove('d-block');
}

async function uploadPicture() {
	const formData = new URLSearchParams();
	formData.append('source', document.getElementById('source-64').value);
	formData.append('superposition-image', superposition.element.src.split('/').pop());
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

async function changePicture(event) {
	const f = event.target.files[0];
	file = await convertImageToBase64(f);
	setPicture(file);
}

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
		picture.width = this.width;
		picture.height = this.height;
	};
	context.drawImage(video, 0, 0, picture.width, picture.height);
	return picture;
}