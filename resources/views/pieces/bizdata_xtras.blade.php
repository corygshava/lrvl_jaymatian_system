<style>
	.filter-box{opacity: 0;}

	.file-input-wrapper {
		position: relative;
		display: inline-block;
		width: 100%;
		margin-bottom: 20px;
	}

	.file-input-wrapper input[type="file"] {
		position: absolute;
		left: -9999px;
	}

	.file-input-label {
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 12px;
		color: white;
		background: linear-gradient(80deg,var(--themecolor),var(--seccolor));
		border-radius: var(--roundness);
		cursor: pointer;
		font-size: 1.1em;
		font-weight: 600;
	}
	.file-input-label:hover {
		box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
	}

	.preview-section {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 20px;
		margin-bottom: 30px;
	}

	.preview-section .preview-container {
		background: #f8f9fa;
		border-radius: 12px;
		padding: 20px;
		min-height: 300px;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: space-around;
	}

	.preview-section .preview-container h3 {
		margin-bottom: 15px;
		color: #555;
		font-size: 1.2em;
	}
	.preview-section .preview-container img, .preview-section .preview-container canvas {
		max-width: 100%;
		max-height: 400px;
		border-radius: 8px;
		box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
	}
	.preview-section .preview-container .actions{
		display: flex;
		flex-direction: row;
		gap: 12px;
	}

	.empty-state {
		color: #999;
		text-align: center;
	}

	.empty-state i {
		font-size: 4em;
		margin-bottom: 15px;
		opacity: 0.3;
	}

	.button-group {
		display: flex;
		gap: 15px;
		justify-content: center;
		flex-wrap: wrap;
	}

	button:disabled {
		opacity: 0.5;
		cursor: not-allowed;
	}

	@media (max-width: 768px) {
		.preview-section {
			grid-template-columns: 1fr;
		}

		h1 {
			font-size: 2em;
		}

		.container {
			padding: 25px;
		}
	}
</style>

	<button class="mybtn primary w3-hide" id="add_edit_photos_trig" data-toggle="modal" data-target="#add_edit_photos_modal">
		<i class="fas fa-plus"></i>
		edit photo
	</button>

	<div class="modal fade" id="add_edit_photos_modal" tabindex="-1" role="dialog" aria-labelledby="add_edit_photos_title" aria-hidden="true" style="z-index: 102;">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content themeround borderless">
				<div class="modal-header">
					<span class="h3 modal-title" id="add_edit_photos_title">Edit report photos</span>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div>
						<div class="file-input-wrapper">
							<!-- <input type="file" id="fileInput" class="w3-hide" accept="image/*"> -->
							<label for="fileInput" id="change_photo_label" class="file-input-label">
								<i class="fas fa-cloud-upload-alt mr-2"></i>
								Choose Image to Upload
							</label>
						</div>
					</div>

					<div class="preview-section">
						<div class="preview-container">
							<h3>Original Preview</h3>
							<div id="previewDiv" class="empty-state distance-sm">
								<i class="fas fa-image"></i>
								<p>No image selected</p>
							</div>
							<div class="in_fullwidth">
								<div class="actions in_fullwidth flowline center">
									<button data-runme='filterImg' id="darkenBtn" class="mybtn primary sm" disabled>
										<i class="fas fa-magic"></i>
										process Image
									</button>
									<button data-runme="useimg" data-who="original" class="mybtn secondary sm">
										<i class="fas fa-check-circle"></i>
										Use original
									</button>
								</div>
							</div>
						</div>

						<div class="preview-container">
							<h3>Processed Preview</h3>
							<div id="canvasContainer" class="empty-state distance-sm">
								<i class="fas fa-palette"></i>
								<p>Click <b>Process Image</b> to scan image</p>
							</div>
							<div>
								<div class="spacy-sm centerline flowline gap-sm slide-up" style="display: none;" data-shown="0" id="filter_range_op">
									<div class="spacy-sm centerline flowline gap-sm">
										<label>Filter amount: </label>
										<input type="range" class="custom-range" max="200" min="45" value="110" onchange="changethreshold(this)">
									</div>
								</div>
								<div class="actions">
									<button data-toggler="#filter_range_op" data-who="processed" class="mybtn primary sm">
										<i class="fas fa-cog"></i>
										settings
									</button>
									<button data-runme="useimg" data-who="processed" class="mybtn bg-success w3-text-white sm">
										<i class="fas fa-check-circle"></i>
										Use processed
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="mybtn secondary" data-dismiss="modal">Cancel</button>
					<!-- <button type="button" class="mybtn primary" data-submitme="#add_edit_photos_form" id="add_edit_photos_submitbtn">Save</button> -->
				</div>
			</div>
		</div>
	</div>
<script>
	alert_dark('loaded img_processor component');

	let fileInput = undefined;
	const previewDiv = document.getElementById('previewDiv');
	const canvasContainer = document.getElementById('canvasContainer');
	const darkenBtn = document.getElementById('darkenBtn');

	let originalImage = null;
	let processedCanvas = null;

	let threshold = 110; // Lower threshold = more black
	let rangeinter = undefined;

	let demoprocess = `
		<i class="fas fa-palette"></i>
		<p>Click <b>Process Image</b> to process</p>
	`;
	let demopreview = `
		<i class="fas fa-image"></i>
		<p>No image selected</p>
	`;

	window['showpreview'] = (e) => {
		const file = e.files[0];
		alert_dark('show preview');

		if (file && file.type.startsWith('image/')) {
			const reader = new FileReader();

			reader.onload = function(event) {
				// Clear previous preview
				previewDiv.innerHTML = '';

				// Create and display image
				const img = document.createElement('img');
				img.src = event.target.result;
				img.onload = function() {
					originalImage = img;
					previewDiv.appendChild(img);
					darkenBtn.disabled = false;
					// filternpt.style.opacity = 1;

					// Clear processed preview
					canvasContainer.innerHTML = demoprocess;
					canvasContainer.classList.add('empty-state');
					// setProcessedBtn.disabled = true;
				};
			};

			reader.readAsDataURL(file);
		} else {
			previewDiv.innerHTML = demopreview;
		}
	}
	window['useimg'] = (el) => {
		let which = el.dataset.who;
		select_photo(which);
	}
	window['select_photo'] = (who) => {
		const final_step = () => {
			add_edit_photos_trig.click();
			previewDiv.innerHTML = demopreview;
			canvasContainer.classList.add('empty-state');
			canvasContainer.innerHTML = demoprocess;

			show_photo_preview(fileInput);
		}

		if(who == 'original'){
			alert_silent({'select_photo fileinput' : fileInput});
			console.log('passed file input: ',fileInput);
			final_step();
		} else {
			// alert_dark('picking processed image');
			if(!processedCanvas){
				alert_danger('no image has been processed yet');
				return;
			}

			processedCanvas.toBlob((blob) => {
				const fyl = new File([blob], 'scan_process_img.png', {type: 'image/png'});
				const dtr = new DataTransfer();
				dtr.items.add(fyl);
				fileInput.files = dtr.files;

				console.log("dtr files: ",dtr.files);
				console.log("fileinput files: ",fileInput.files);

				final_step();
			}, 'image/png');
		}
	}
	window['changethreshold'] = (e) => {
		console.log(e.value);
		threshold = Number(e.value);

		// alert_dark(e.value);

		if(rangeinter !== undefined){
			clearTimeout(rangeinter);
		}
		rangeinter = setTimeout(d => {
			filterImg();
		},200)
	}
	window['filterImg'] = (e) => {
		if (!originalImage){
			alert_danger('pick an image first');
			return;
		}

		// Create canvas
		const canvas = document.createElement('canvas');
		const ctx = canvas.getContext('2d');

		// Set canvas size to match image
		canvas.width = originalImage.naturalWidth;
		canvas.height = originalImage.naturalHeight;

		// Draw image to canvas
		ctx.drawImage(originalImage, 0, 0);

		// Get image data
		const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
		const data = imageData.data;

		// Process pixels - high contrast black and white
		for (let i = 0; i < data.length; i += 4) {
			// Calculate grayscale value
			const gray = (data[i] + data[i + 1] + data[i + 2]) / 3;

			// Apply high contrast threshold (lower threshold for "darken" effect)
			const value = gray > threshold ? 255 : 0;

			data[i] = value;     // Red
			data[i + 1] = value; // Green
			data[i + 2] = value; // Blue
			// Alpha channel (i + 3) stays the same
		}

		// Put processed data back
		ctx.putImageData(imageData, 0, 0);

		// Display processed canvas
		canvasContainer.innerHTML = '';
		canvasContainer.classList.remove('empty-state');
		canvasContainer.appendChild(canvas);

		processedCanvas = canvas;
		// setProcessedBtn.disabled = false;
	}
</script>
