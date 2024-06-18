async function uploadPicture() {
	const formData = new URLSearchParams();
	formData.append('source', document.getElementById('source-64').value);
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

function refreshPictures() {
	const picturesDiv = document.getElementById('pictures');
	picturesDiv.innerHTML = '';
	pictures.forEach((picture) => {
		const img = document.createElement('img');
		img.src = picture;
		img.style.width = '100%';
		img.style.height = '100%';
		img.style.cursor = 'pointer';
		img.addEventListener('click', () => {
			selectPicture(pictures.indexOf(picture));
		});
		picturesDiv.appendChild(img);
	});
}

function selectPicture(index) {
	selectedPicture = pictures[index];
	document.getElementById('source-64').value = selectedPicture;
	displayPicture();
	const ctx = canvas.getContext("2d");
	const image = new Image();
	image.src = selectedPicture;
	image.onload = function () {
		canvas.width = this.width;
		canvas.height = this.height;
		ctx.drawImage(image, 0, 0, this.width, this.height);
	};

	canvas.src = selectedPicture;
}

const fileInput = document.querySelector('#select-picture');
fileInput.addEventListener('change', async (event) => {
	const f = event.target.files[0];
	file = await convertImageToBase64(f);
	const canvas = document.createElement("canvas");
	const ctx = canvas.getContext("2d");
	const image = new Image();
	image.src = file;
	image.onload = function () {
		canvas.width = this.width;
		canvas.height = this.height;
		ctx.drawImage(image, 0, 0, this.width, this.height);
		const dataUrl = canvas.toDataURL();
		pictures.push(dataUrl);
		refreshPictures();
	};
	canvas.src = file;
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