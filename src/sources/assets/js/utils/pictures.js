class PictureManager {
	#pictures = [];
	#picturesElement;

	static #instance = new PictureManager();

	constructor() {
		this.#picturesElement = document.querySelector('#pictures');
	}

	static getInstance() {
		return PictureManager.#instance;
	}

	takePicture() {
		const canvas = createImageForCanvas(camera.video);
		const dataURL = canvas.toDataURL();
		const picture = new Picture(dataURL, superposition.element.src);
		document.querySelector('[data-webcam-picture]').src = dataURL;
		this.addPicture(picture);
		hideCamera();
	}

	addPicture(picture) {
		this.#pictures.push(picture);
		this.refreshPicture();
	}

	refreshPicture() {
		this.#picturesElement.innerHTML = '';
		this.#pictures.forEach((picture, index) => {
			this.#picturesElement.appendChild(this.pictureTemplate(picture, index));
		});
	}

	pictureTemplate(picture, index)
	{
		const div = document.createElement('div');
		div.classList.add('preview');
		div.style.cursor = 'pointer';
		div.appendChild(this.createPicture(picture.src));
		div.appendChild(this.createSuperposition(picture.superposition));
		div.setAttribute('data-event', true);
		div.setAttribute(`on-click`, `PictureManager.getInstance().selectPicture(${index})`);
		registerEvents(div);
		return div;
	}

	createPicture(src) {
		const img = document.createElement('img');
		img.classList.add('picture');
		img.src = src;
		return img;
	}

	createSuperposition(src) {
		const picture = this.createPicture(src);
		picture.classList.remove('picture');
		picture.classList.add('superposition');
		return picture;
	}

	selectPicture(index) {
		hideCamera();
		const picture = this.#pictures[index];
		setPicture(picture.src);
		setSuperpositionImage(picture.superposition);
	}
}
