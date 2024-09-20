class Camera {
	constructor() {
		this.video = document.querySelector('video');
		this.exist = !!navigator.mediaDevices;
		this.is_visible = true;
		this.canvas = document.querySelector('[data-canvas-picture]');
		this.picture = document.querySelector('[data-webcam-picture]');
		this.enable = document.querySelector('[data-webcam="enable"]');
		this.disable = document.querySelector('[data-webcam="disable"]');
		this.context = this.canvas.getContext('2d');

		this.video.addEventListener('loadedmetadata', () => {
			console.log('loadedmetadata');
			
			this.resizeVideo();
			this.configureCanvas();
		});
		this.video.addEventListener('timeupdate', () => {
			this.context.drawImage(this.video, 0, 0, this.canvas.width, this.canvas.height);
		});
	}

	configureCanvas() {
		const ratio = Picture.getRatio({
			width: this.video.videoWidth,
			height: this.video.videoHeight
		});
		
		this.canvas.width = ratio.width;
		this.canvas.height = ratio.height;
	}

	init() {
		this.startPictureMode();
		this.exist = false;

		navigator.mediaDevices.getUserMedia({ video: true, preferCurrentTab: false })
		.then((stream) => {
			this.video.srcObject = stream;
			this.startWebcamMode();
			this.exist = true;
		})
		.catch((err) => {
			console.error(`Erreur lors de l'activation de la webcam: ${err}`);
		});
	}

	startWebcamMode() {
		document.querySelector('[data-mode="webcam"]').classList.remove('d-none');
		document.querySelector('[data-mode="webcam"]').classList.add('d-block');
		document.querySelector('[data-mode="picture"]').classList.remove('d-block');
		document.querySelector('[data-mode="picture"]').classList.add('d-none');
		Superposable.getInstance().setElement(document.querySelector(`[data-superposition="webcam"]`));
		this.show();
	}

	startPictureMode() {
		Superposable.getInstance().setElement(document.querySelector(`[data-superposition="picture"]`));
		this.hide();
		Superposable.getInstance().show();
		document.querySelector('[data-mode="webcam"]').classList.remove('d-block');
		document.querySelector('[data-mode="webcam"]').classList.add('d-none');
		document.querySelector('[data-mode="picture"]').classList.remove('d-none');
		document.querySelector('[data-mode="picture"]').classList.add('d-block');
	}

	getCurrentPicture() {
		this.context.drawImage(this.video, 0, 0, this.canvas.width, this.canvas.height);
		const dataURL = this.canvas.toDataURL('image/png');
		return dataURL;
	}

	show() {
		Superposable.getInstance().show();
		this.video.classList.remove('d-none');
		document.querySelector('[data-webcam-picture]').classList.add('d-none');
		this.video && this.video.play();
		this.enable.classList.remove('d-none');
		this.enable.classList.add('d-block');
		this.disable.classList.remove('d-block');
		this.disable.classList.add('d-none');
		this.is_visible = true;
		// Superposable.getInstance().reset;
	}

	hide() {
		Superposable.getInstance().hide();
		this.video.classList.add('d-none');
		document.querySelector('[data-webcam-picture]').classList.remove('d-none');
		this.video && this.video.pause();
		this.enable.classList.remove('d-block');
		this.enable.classList.add('d-none');
		this.disable.classList.remove('d-none');
		this.disable.classList.add('d-block');
		this.is_visible = false;
	}

	ratioVideo() {
		const ratio = { width: 0, height: 0 };
		if (this.video.videoWidth > 400) {
			ratio.width = 400;
			ratio.height = this.video.videoHeight * (400 / this.video.videoWidth);
		}
		else if (this.video.videoHeight > 400) {
			ratio.width = this.video.videoWidth * (400 / this.video.videoHeight);
			ratio.height = 400;
		}
		else {
			ratio.width = this.video.videoWidth;
			ratio.height = this.video.videoHeight;
		}
	
		return ratio;
	}

	resizeVideo() {
		const ratio = Picture.getRatio({
			width: this.video.videoWidth,
			height: this.video.videoHeight
		});
		this.video.width = ratio.width;
		this.video.height = ratio.height;
	}
}
