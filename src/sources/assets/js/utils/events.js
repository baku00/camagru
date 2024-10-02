const prefix = {
	event: 'on-',
}

document.addEventListener('DOMContentLoaded', () => {
	const eventElement = document.querySelectorAll('[data-event]');
	eventElement.forEach(element => {
		registerEvents(element);
	})
});

function registerEvents(element) {
	for (let i = 0; i < element.attributes.length; i++) {
		const name = element.attributes[i].name;
		const event = element.getAttribute(name);

		if (!name.startsWith(prefix.event))
			continue;

		const functionEvent = typeof window[event] === 'function' ? window[event] : function () { eval (event) };
		element.addEventListener(name.replace(prefix.event, ''), functionEvent);
	}
}
