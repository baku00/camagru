<div class="preview">
	<video id="video" class="picture"></video>
	<img src="" alt="" class="picture d-none" data-webcam-picture>
	<img data-superposition="webcam" src="" alt="" class="superposition">
</div>
<div data-webcam="enable">
	<button class="btn btn-primary me-3" id="take-picture" disabled data-event on-click="PictureManager.getInstance().takePicture()">Prendre une capture</button>
</div>
<div data-webcam="disable" class="d-flex">
	<button class="btn btn-primary me-3" id="back-webcam" data-event on-click="showCamera">Retour à la caméra</button>
	<button data-picture="publish" class="btn btn-primary me-3" disabled data-event on-click="uploadPicture">Publier</button>
</div>
