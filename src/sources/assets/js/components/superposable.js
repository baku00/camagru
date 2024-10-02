function createSuperposable(src) {
	const superposable = document.createElement('img');
	superposable.src = src;
	superposable.classList.add('superposable');
	superposable.style.width = '100px';
	superposable.style.cursor = 'pointer';
	superposable.setAttribute('data-event', 'true');
	superposable.setAttribute('on-click', 'selectSuperposable');

	return superposable;
}
