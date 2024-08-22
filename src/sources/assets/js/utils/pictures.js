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

	async takePicture() {
		const dataURL = camera.getCurrentPicture();
		console.log(camera.video);
		console.log(dataURL);
		const picture = new Picture(dataURL, Superposable.getInstance().getElement().src);
		document.querySelector('[data-webcam-picture]').src = dataURL;
		this.addPicture(picture);
		this.selectPicture(this.#pictures.length - 1);
		camera.hide();
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
		const picture = this.#pictures[index];
		console.log(picture);
		
		document.querySelector('[data-webcam-picture]').src = picture.src;
		Superposable.getInstance().select(picture.superposition);
	}
}
