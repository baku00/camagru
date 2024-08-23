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

	static getRatio(dimension = { width: 0, height: 0}) {
		const ratio = { width: 0, height: 0 };
		if (dimension.width > 400 && dimension.width > dimension.height) {
			ratio.width = 400;
			ratio.height = dimension.height * (400 / dimension.width);
		}
		else if (dimension.height > 400 && dimension.height > dimension.width) {
			ratio.width = dimension.width * (400 / dimension.height);
			ratio.height = 400;
		}
		else {
			ratio.width = dimension.width;
			ratio.height = dimension.height;
		}
		return ratio;
	}
}

function takePicture() {
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

function getDimension(src) {
	return new Promise((resolve, reject) => {
		const image = new Image();
		image.src = src;
		image.onload = function() {
			resolve({ width: this.width, height: this.height });
		}

		image.onerror = (error) => {
			reject(error);
		};
	});
}

function createImageForCanvas(src) {
	return new Promise(async (resolve, reject) => {
		const picture = document.createElement('canvas');
		const context = picture.getContext('2d');

		const dimensions = src instanceof HTMLVideoElement ?
		{ width: camera.video.videoWidth, height: camera.video.videoHeight } :
		await getDimension(src);

		const ratio = Picture.getRatio(dimensions);
		picture.width = ratio.width;
		picture.height = ratio.height;

		if (src instanceof HTMLVideoElement) {
			context.drawImage(src, 0, 0, picture.width, picture.height);
			resolve(picture);
		}
		else {
			const image = new Image();
			image.onload = () => {
				this.width = ratio.width;
				this.height = ratio.height;
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