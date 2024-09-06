<div class="text-center">
	<div class="preview">
		<img data-picture="picture" src="" style="width: 286px;" alt="" class="picture flipHorizontal" />
		<img data-superposition="picture" src="" alt="" class="superposition" />
	</div>
	<div class="d-block">
		<div class="mt-3">
			<button data-picture="publish" class="btn btn-primary me-3" id="publish-picture" disabled data-event on-click="uploadPicture">Publier</button>
			<button data-picture="select" class="btn btn-primary me-3" onclick="document.querySelector('#select-picture').click()">Importer une image</button>
			<input type="file" id="select-picture" data-event on-change="changePicture" class="d-none" accept="image/jpeg,image/jpg,image/png">
			<form id="upload-picture" class="d-none">
				<input type="text" id="source-64">
				<input type="text" id="superposition-image">
				<div class="d-none">
					<input type="text" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
				</div>
			</form>
		</div>
	</div>
</div>