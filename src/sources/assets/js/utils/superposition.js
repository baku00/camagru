class Superposable {
	static #instance = undefined;

	#superposables = [
		'/storage/moustache',
		'/storage/chauve',
		'/storage/chien',
		'/storage/anneau_fleur_bleu',
	];
	#element = undefined;

	constructor() {
		if (!Superposable.#instance)
			Superposable.#instance = this;
	}

	static getInstance() {
		if (!Superposable.#instance) {
			Superposable.#instance = new Superposable();
		}

		return Superposable.#instance;
	}

	setElement(element) {
		this.#element = element;
	}

	getElement() {
		return this.#element;
	}

	select(src) {
		if (camera.exist)
		{
			const takePictureButton = document.getElementById('take-picture');
			!!src ? takePictureButton.removeAttribute('disabled') : takePictureButton.setAttribute('disabled', true);
		}
		console.log('src', src);
		
		document.querySelectorAll('[data-picture="publish"]').forEach(element => {
			!!src ? element.removeAttribute('disabled') : element.setAttribute('disabled', true);
		});
		this.#element.src = src;
	}

	hide() {
		document.querySelector('#superposables').classList.add('d-none');
	}

	show() {
		document.querySelector('#superposables').classList.remove('d-none');
	}

	load() {
		this.#superposables.forEach(superposable => {
			const element = this.create(`${Config.getInstance().getApiUrl()}${superposable}`);
			document.querySelector('#superposables').appendChild(element);
		});
	}

	reset() {
		this.select(-1);
	}

	create(src) {
		const superposable = document.createElement('img');
		superposable.src = src;
		superposable.classList.add('superposable');
		superposable.style.width = '100px';
		superposable.style.cursor = 'pointer';
		superposable.setAttribute('data-event', 'true');
		superposable.setAttribute('on-click', `Superposable.getInstance().select('${src}')`);
		return superposable;
	}
}
