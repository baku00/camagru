const superposables = [
	`/storage/moustache`,
	`/storage/couronne`,
];

const superposition = {
	element: undefined,
}

function selectSuperposable(event) {
	const superposable = event.target;
	setSuperpositionImage(superposable.src);
}

function setSuperpositionElement(element) {
	superposition.element = element;
}

function setSuperpositionImage(src) {
	superposition.element.src = src;

	if (camera.exist)
	{
		const takePictureButton = document.getElementById('take-picture');
		!!src ? takePictureButton.removeAttribute('disabled') : takePictureButton.setAttribute('disabled', true);
	}
	document.querySelectorAll('[data-picture="publish"]').forEach(element => {
		!!src ? element.removeAttribute('disabled') : element.setAttribute('disabled', true);
	});
}

function loadSuperposables() {
	superposables.forEach(superposable => {
		const element = createSuperposable(`${Config.getInstance().getApiUrl()}${superposable}`);
		document.querySelector('#superposables').appendChild(element);
	});
}

function hideSuperposables() {
	document.querySelector('#superposables').classList.add('d-none');
}

function showSuperposables() {
	document.querySelector('#superposables').classList.remove('d-none');
}

function resetSuperposition() {
	setSuperpositionImage('');
}
