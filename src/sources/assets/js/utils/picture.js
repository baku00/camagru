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
	console.log('TAKE PICTURE');
	
	const pictureManager = PictureManager.getInstance();
	const picture = {
		src: camera.getCurrentPicture(camera.video),
		superposition: superposition.element.src
	};
	setPicture(picture.src);
	pictureManager.addPicture(picture)
	hideCamera();
}

function setPicture(src) {
	document.querySelector('[data-picture="picture"]').src = src;
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
	const selector = camera.exist ? '[data-webcam-picture]' : '[data-picture="picture"]';
	formData.append('source', document.querySelector(selector).src);
	formData.append('superposition-image', Superposable.getInstance().getElement().src.split('/').pop());
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
	const picture = await createImageForCanvas(file);
	console.log(picture);
	
	setPicture(picture.toDataURL());
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

function createImageForCanvas(src) {
	return new Promise((resolve, reject) => {
		const picture = document.createElement('canvas');
		const context = picture.getContext('2d');
		picture.width = 400;
		picture.height = 400;

		if (src instanceof HTMLVideoElement) {
			picture.width = src.videoWidth;
			picture.height = src.videoHeight;
			context.drawImage(src, 0, 0, picture.width, picture.height);
			resolve(picture);
		}
		else {
			const image = new Image();
			image.onload = () => {
				context.drawImage(image, 0, 0, picture.width, picture.height);
				resolve(picture);
			};
			image.onerror = (error) => {
				reject(new Error('Failed to load image'));
			};
			image.src = src;
		}
	});
}