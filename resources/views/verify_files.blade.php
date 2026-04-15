<x-layouts.mainlayout>
	<style>
		body{
			background: #eee;

			--shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
			--clr-border: #ddd;
		}
		.container-fluid>*{
			width: 100%;
			max-width: 1200px;
		}
		.logo_img_ img {
			/* aspect-ratio: auto; */
			height: 150px;
			width: auto;
		}

		#intro .steps_list .litem{
			padding: 8px 16px;
			border-radius: var(--roundness);
			border: 1px solid var(--clr-border);
			margin: 0 0 12px 0;
			display: flex;
			flex-direction: row;
			align-items: center;
			justify-content: flex-start;
			gap: 12px;
		}
		#intro .steps_list .litem .num {
			--w: 36px;
			padding: 16px;
			background: var(--clr-text-muted);
			height: var(--w);
			width: var(--w);
			display: flex;
			align-items: center;
			justify-content: center;
			font-weight: 700;
			color: var(--clr-alttext);
			border-radius: 50%;
		}

		/* form area */
		.formarea .section-title {
			/*font-size: 0.75rem;*/
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 0.1em;
			color: var(--clr-text-muted);
			margin-bottom: 1rem;
			display: flex;
			align-items: center;
			gap: 0.5rem;
		}
		.formarea .section-title_::before {
			content: '';
			width: 3px;
			height: 14px;
			background: var(--themecolor);
			border-radius: 2px;
		}

		.formarea .docsetup .type-selector, .formarea .docsetup .format-selector {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 1rem;
			margin-bottom: 2rem;
		}
		.formarea .docsetup .type-btn {
			padding: 24px 16px;
			border: 2px solid var(--clr-border);
			border-radius: var(--roundness);
			cursor: pointer;
			text-align: center;
			font-weight: 700;
		}
		.formarea .docsetup .type-btn:hover {
			border-color: var(--themecolor);
			background: white;
			transform: translateY(-2px);
			box-shadow: var(--shadow-md);
		}
		.formarea .docsetup .type-btn.active {
			--c: var(--darkcolor);
			border-color: var(--c);
			background: var(--c);
			color: #fff;
			box-shadow: var(--shadow-md);
		}
		.formarea .docsetup .type-btn i {
			font-size: 2rem;
			display: block;
			margin-bottom: 0.5rem;
		}

		.formarea .docsetup .format-btn {
			padding: 1rem;
			border: 2px solid var(--clr-border);
			border-radius: var(--roundness);
			cursor: pointer;
			display: flex;
			align-items: center;
			gap: 0.75rem;
			font-weight: 700;
		}
		.formarea .docsetup .format-btn:hover {
			border-color: var(--themecolor);
			transform: translateY(-2px);
		}
		.formarea .docsetup .format-btn.active {
			--c: var(--darkcolor);
			border-color: var(--c);
			background: var(--c);
			color: white;
		}
		.formarea .docsetup .format-btn i {
			font-size: 1.5rem;
		}


		.formarea .upload-zone {
			border: 3px dashed var(--clr-border);
			border-radius: 12px;
			padding: 3rem 2rem;
			text-align: center;
			cursor: pointer;
			margin-bottom: 2rem;
		}
		.formarea .upload-zone:hover {
			border-color: var(--themecolor);
			background: white;
		}

		.formarea .upload-zone.dragover {
			border-color: var(--seccolor);
			background: #eff6ff;
			border-style: solid;
		}

		.formarea .upload-zone i {
			font-size: 3rem;
			/*color: var(--clr-text-muted);*/
			margin-bottom: 1rem;
		}

		.formarea .upload-zone p {
			margin: 16px 0;
			color: var(--clr-text-muted);
			font-weight: 700;
		}

		.formarea .upload-zone .btn-upload {
			margin-top: 1rem;
		}

		.file-info {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 0.75rem;
			background: white;
			border-radius: 8px;
			margin-bottom: 1rem;
			border: 1px solid var(--clr-border);
		}
		.file-info-left {
			display: flex;
			align-items: center;
			gap: 0.75rem;
		}
		.file-info-left i {
			font-size: 1.5rem;
		}
		.file-info .file-name {
			font-weight: 500;
			color: var(--clr-text-muted);
		}
		.file-info .file-size {
			font-size: 0.85rem;
			color: var(--clr-text-muted);
			font-family: 'IBM Plex Mono', monospace;
		}

		.preview-container #imagePreview{
			max-width: 80%;
			max-height: 80vh;
			aspect-ratio: auto;
			width: auto;
		}

		#loader_ui{display: none;}

		/* finalize */
			.info-row .info-label{width: 240px;}

		@media (max-width: 768px) {
			.progress-steps{--l: 32px !important;}
			.page-header{
				text-align: center;
			}
		}
	</style>

	<div class="container-fluid flow centroid mt-4">

		<div class="page-header w3-animate-opacity w3-display-container">
			<?php
				if(isset($_GET['fromdash']) && $_GET['fromdash'] == 'yes'){
			?>
				<div class="w3-display-topright spacy-sm">
					<a href="#" class="mybtn primary" onclick="window.close()"><i class="fa fa-times"></i> leave</a>
				</div>
			<?php
				}
			?>
			<div class="flowline centerline overflow">
				<div class="logo_img_ flow centroid">
					<img src="./_assets/img/logo_full.png">
				</div>

				<div>
					<div class="page-title mb-4">
						<span class="h3" data-role="pagename">Jaymatian Mineral laboratory File verification</span>
						<span class="text-muted">This page enables you to find out if a document originated from us. or is a counterfiet document</span>
					</div>
				</div>
			</div>
		</div>

		<div class="panelbg themeround distance-sm spacy-sm slide-up" id="intro">
			<div>
				<span class="h4">Instructions</span>
				<p>The page will take you through all the necessary steps required to verify the document</p>
			</div>
			<div>
				<!-- <p>Here are the 4 easy steps</p> -->
				<div class="steps_list">
					<div class="litem">
						<div class="num">1</div>
						<div class="content">Specify the type of document</div>
					</div>
					<div class="litem">
						<div class="num">2</div>
						<div class="content">Upload the document</div>
					</div>
					<div class="litem">
						<div class="num">3</div>
						<div class="content">Enter additional information (should be in the document provided)</div>
					</div>
					<div class="litem">
						<div class="num">4</div>
						<div class="content">Find out if its <b class="w3-text-green">valid</b> or <b class="w3-text-red">fake</b></div>
					</div>
				</div>
			</div>

			<div class="distance-sm flowline spread">
				<button class="mybtn primary is_ghost">get started <i class="fa fa-angle-double-right"></i></button>
				<button class="mybtn primary" data-runme="get_started">get started <i class="fa fa-angle-double-right"></i></button>
			</div>
		</div>

		<div id="thesteps" class="w3-hide slide-up">
			<div class="panelbg themeround distance-tn" id="progress_panel">
				<div class="progress-indicator">
					<div class="progress-steps">
						<div class="progress-line themegrad" id="progressLine" style="width: 20%;"></div>
						<div class="step" data-step="0" data-runme="pick_step_2">
							<div class="step-circle"><i class="fas fa-user"></i></div>
							<div class="step-label w3-hide-small">Document setup</div>
						</div>
						<div class="step" data-step="1" data-runme="pick_step_2">
							<div class="step-circle"><i class="fas fa-upload"></i></div>
							<div class="step-label w3-hide-small">File upload</div>
						</div>
						<div class="step" data-step="2" data-runme="pick_step_2">
							<div class="step-circle"><i class="fas fa-info"></i></div>
							<div class="step-label w3-hide-small">Document Details</div>
						</div>
						<div class="step" data-step="3" data-runme="pick_step_2">
							<div class="step-circle"><i class="fas fa-stamp"></i></div>
							<div class="step-label w3-hide-small">Finalize</div>
						</div>
					</div>
				</div>
			</div>

			<form class="tabs formarea" id="veriform" data-onsubmit="send_form_data" data-callback="form_afterfx" data-onfail="form_onfail" data-blockdefault="yes">
				<div class="w3-hide">
					<input type="hidden" name="doctype" value="invoice">
					<input type="hidden" name="filetype" value="image">
					<input type="file" id="fileInput" name="fileInput" class="w3-hide" accept="image/*,.pdf">
					@csrf
				</div>

				<div id="docsetup_tab" class="tab docsetup slide-up">
					<div class="panelbg themeround spacy-sm">
						<span class="h4 themetxt border-bottom spacy-sm">Document setup</span>

						<div>
							<div class="section-title">
								<span>Document Type (select one)</span>
							</div>

							<div class="type-selector">
								<div class="type-btn" data-subrole="doctype_selector" data-runme="select_doctype" data-type="report">
									<i class="fas fa-file-alt"></i>
									<span>Report</span>
								</div>
								<div class="type-btn" data-subrole="doctype_selector" data-runme="select_doctype" data-type="invoice">
									<i class="fas fa-file-invoice-dollar"></i>
									<span>Invoice</span>
								</div>
							</div>

							<div class="section-title">
								<span>File Format (select one)</span>
							</div>

							<div class="format-selector">
								<div class="format-btn" data-subrole="filetype_selector" data-runme="select_filetype" data-type="image">
									<i class="fas fa-image"></i>
									<span>Image (JPG, PNG)</span>
								</div>
								<div class="format-btn w3-hide" data-subrole="filetype_selector" data-runme="select_filetype" data-type="pdf">
									<i class="fas fa-file-pdf"></i>
									<span>PDF Document</span>
								</div>
							</div>
						</div>
					</div>

					<div class="distance-sm flowline spread themeround panelbg spacy-sm slide-right">
						<a class="mybtn primary" data-runme="just_stop">
							<i class="fa fa-angle-double-left"></i> start over
						</a>
						<a class="mybtn primary" data-runme="pick_step_2" data-step="1">
							upload files <i class="fa fa-angle-double-right"></i>
						</a>
					</div>
				</div>

				<div id="file_upload_tab" class="tab slide-up">
					<div class="panelbg themeround spacy-sm">
						<span class="h4 themetxt border-bottom spacy-sm">File upload</span>

						<div>
							<div class="section-title">
								<span>Upload document File</span>
							</div>

							<div class="upload-zone" id="uploadZone" onclick="fileInput.click()">
								<i class="fas fa-cloud-upload-alt"></i>
								<p>Drag and drop your file here</p>
								<!-- <p style="font-size: 0.85rem; color: #6b7280; margin-top: 0.5rem;">or</p> -->
								<button type="button" class="mybtn secondary">
									Browse Files
								</button>
							</div>
						</div>

						<div id="fileInfoSection" class="w3-hide">
							<div class="file-info">
								<div class="file-info-left">
									<i class="fas fa-file" id="fileIcon"></i>
									<div>
										<div class="file-name" id="fileName"></div>
										<div class="file-size" id="fileSize"></div>
									</div>
								</div>
								<button class="btn-remove w3-hide">
									<i class="fas fa-times"></i>
								</button>
							</div>
						</div>

						<div class="preview-section w3-hide slide-up" id="previewSection">
							<div class="section-title">
								<span>Image Preview</span>
							</div>
							<div class="preview-container">
								<img id="imagePreview" src="" alt="File preview">
							</div>
						</div>
					</div>

					<div class="distance-sm flowline spread themeround panelbg spacy-sm slide-right">
						<a class="mybtn secondary" data-runme="pick_step_2" data-step="0">
							<i class="fa fa-angle-double-left"></i> document setup
						</a>
						<a class="mybtn primary" data-runme="pick_step_2" data-step="2">
							Document Details <i class="fa fa-angle-double-right"></i>
						</a>
					</div>
				</div>

				<div id="details_tab" class="tab slide-up">
					<div class="panelbg themeround spacy-sm">
						<span class="h4 themetxt border-bottom spacy-sm">Document Details</span>
						<p>provided the document is valid it should have all this information indicated on it</p>

						<div id="forminputs">
						</div>
					</div>

					<div class="distance-sm flowline spread themeround panelbg spacy-sm slide-right">
						<a class="mybtn secondary" data-runme="pick_step_2" data-step="1">
							<i class="fa fa-angle-double-left"></i> file uploads
						</a>
						<a class="mybtn primary" data-runme="saveinputs" data-step="3">
							Finalize <i class="fa fa-angle-double-right"></i>
						</a>
					</div>
				</div>

				<div id="finalize_tab" class="tab slide-up">
					<div class="panelbg themeround spacy-sm">
						<span class="h4 themetxt border-bottom spacy-sm">Data summary</span>
						<p>this is all the data you have provided, confirm that it is true before proceeding</p>

						<div id="formsummary">
						</div>

						<div class="distance-sm">
							<button class="mybtn primary">submit Information <i class="fa fa-paper-plane"></i></button>
						</div>
					</div>

					<div class="distance-sm flowline spread themeround panelbg spacy-sm slide-right">
						<a class="mybtn secondary" data-runme="pick_step_2" data-step="2">
							<i class="fa fa-angle-double-left"></i> Document Details
						</a>
						<a class="mybtn primary is_ghost" data-runme="saveinputs" data-step="3">
							Finalize <i class="fa fa-angle-double-right"></i>
						</a>
					</div>
				</div>

				<div class="tab slide-up">
					<div class="panelbg themeround spacy-sm">
						<div class="spacy-mg w3-center" id="submitter_loader_ui">
							<p>submitting Information, please wait</p>
							<div class="flow centroid">
								<div class="loader"></div>
							</div>
						</div>
					</div>
				</div>

				<div id="final_report" class="tab slide-up">
					<div class="panelbg themeround spacy-sm mb-4">
						<span class="h4 themetxt border-bottom spacy-sm">Verdict</span>
						<p>here are the results from the server</p>

						<div id="results_summary" class="mb-4">
						</div>

						<div>
							<div id="verdict_holder" class="spacy-sm themeround flowline left gap-sm alert-success slide-right">
								<div>
									<i data-role="icon" class="fa fa-check-circle" style="scale: 1.7"></i>
								</div>
								<div data-role="content">
									<span>valid document</span>
								</div>
							</div>
						</div>

						<div class="distance-sm">
							<!-- <button class="mybtn primary">submit Information <i class="fa fa-paper-plane"></i></button> -->
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>

	<script>
		const verify_runtime_data = {};

		let tabs = document.querySelectorAll('.tabs .tab');

		let checks = document.querySelectorAll('.progress-steps>.step');
		const steps = 4;
		const spans = [0,31,63,90,90];
		const fields = {
			'invoice' : [
				{
					field: 'invoice_no',
					label: 'Invoice number',
					placeholder: 'INV_0000',
					typ: 'text',
					required: true,
				},
				{
					field: 'invoice_date',
					label: 'date of issue',
					placeholder: 'enter the given invoice date',
					typ: 'date',
					required: true,
				},
				{
					field: 'client_name',
					label: 'Invoice Recipient',
					placeholder: 'the name in the [invoice to] section',
					typ: 'text',
					required: true,
					_inp_props: 'step=0.01',
				},
				{
					field: 'client_contact',
					label: 'Reciepient Contact',
					placeholder: 'the contact in the [invoice to] section',
					typ: 'text',
					required: true,
					_inp_props: 'step=0.01',
				},
				{
					field: 'invoice_amt',
					label: 'Invoice Amount',
					placeholder: 'enter the given invoice amount',
					typ: 'number',
					required: true,
					_inp_props: 'step=0.01',
				},
				{
					field: 'mpesa_number',
					label: 'Mpesa Number',
					placeholder: 'enter the Mpesa number specified on the Invoice',
					typ: 'text',
					required: true,
					// _inp_props: 'step=0.01',
				},
				{
					field: 'invoice_tasks',
					label: 'Number of tasks',
					placeholder: 'number of items written as JML_***',
					typ: 'number',
					required: true,
					// _inp_props: 'step=0.01',
				},
			],
			'report' : [
				/*
				job_number
				date_of_issue
				sample_count
				attendant_name
				*/
				{
					field: 'task_no',
					label: 'Job number',
					placeholder: 'JML_0000',
					typ: 'text',
					required: true,
				},
				{
					field: 'sample_type',
					label: 'sample type',
					placeholder: 'the indicated sample type',
					typ: 'text',
					required: true,
				},
				{
					field: 'sample_count',
					label: 'Number of samples',
					placeholder: 'Number of sample results delivered',
					typ: 'text',
					required: true,
				},
				{
					field: 'client_name',
					label: 'Receiving client',
					placeholder: 'name of the client it was meant for',
					typ: 'text',
					required: true,
				},
				{
					field: 'report_date',
					label: 'date of issue',
					placeholder: 'Date it was issued',
					typ: 'date',
					required: true,
				},
				{
					field: 'authorizer',
					label: 'Authorizing staff',
					placeholder: 'Name of authorizer',
					typ: 'text',
					required: true,
				},
			]
		};
		Object.freeze(fields);

		window['just_stop'] = () => {
			thesteps.classList.add('w3-hide');
			intro.classList.remove('w3-hide');
		}

		window['get_started'] = (el) => {
			intro.classList.add('w3-hide');
			thesteps.classList.remove('w3-hide');

			pick_step(0);
		}

		// navigations
		function pick_step(n) {
			progressLine.style.width = `${spans[n]}%`;
			// render_all_data();

			checks.forEach((w,id) => {
				if(id == n){
					w.classList.add('active');
				}else {
					w.classList.remove('active');
				}

				if(id < n){
					w.classList.add('completed');
				} else {
					w.classList.remove('completed');
				}
			})

			tabs.forEach(el => {
				el.style.display = 'none';
			})

			let it = tabs[n];
			if(it !== undefined){
				it.style.display = 'block';
			}
		}
		window['pick_step_2'] = (g) => {
			let n = Number(g.dataset.step);

			if(verify_runtime_data.doctype == undefined && n > 0){
				alert_danger('pick a document type first');
				return;
			}
			if(verify_runtime_data.filetype == undefined && n > 0){
				alert_danger('specify a file type first');
				return;
			}

			if(verify_runtime_data.selectedFile == undefined && n > 1){
				alert_danger('pick a file first');
				return;
			}
			pick_step(n);
		}

		// doc setup ops
			window['select_doctype'] = (el) => {
				let mtype = el.dataset.type;

				if(mtype == undefined){
					alert_danger('invalid function call');
					return;
				}

				let items = document.querySelectorAll('[data-subrole="doctype_selector"]');
				items.forEach(el => {
					el.classList.remove('active');
				})
				el.classList.add('active');

				verify_runtime_data.doctype = mtype;
				veriform.doctype.value = mtype;

				let allowed = Object.keys(fields);
				if(!allowed.includes(mtype.toLowerCase())){
					alert_danger('invalid field type chosen');
					return;
				}

				removefyl();
				setupinputs(mtype);
				setupformaction(mtype)
			}
			window['select_filetype'] = (el) => {
				let mtype = el.dataset.type;

				if(mtype == undefined){
					alert_danger('invalid function call');
					return;
				}

				let items = document.querySelectorAll('[data-subrole="filetype_selector"]');
				items.forEach(el => {
					el.classList.remove('active');
				})
				el.classList.add('active');

				if (mtype == 'image') {
					fileInput.accept = 'image/*';
				} else if (mtype == 'pdf') {
					fileInput.accept = '.pdf,application/pdf';
				}

				verify_runtime_data.filetype = mtype;
				veriform.filetype.value = mtype;

				removefyl();
			}
			window['setupinputs'] = (t) => {
				t = t.toLowerCase();

				console.log(fields);

				let allowed = Object.keys(fields);
				if(!allowed.includes(t.toLowerCase())){
					alert_danger('invalid field type chosen');
					return;
				}

				let outht = '';
				let inputs = [];

				switch(t){
					case 'report':
						inputs = fields[t];

						inputs.forEach((npt,id) => {
							outht += mekInputholder(npt);
						})
						break;
					case 'invoice':
						inputs = fields[t];

						inputs.forEach((npt,id) => {
							outht += mekInputholder(npt);
						})
						break;
					default:
						outht = '';
						break;
				}

				forminputs.innerHTML = outht;
			}
			window['setupformaction'] = (t) => {
				let acts = {
					'report' : './op/verify_report',
					'invoice' : './op/verify_invoice',
				}

				let allowed = Object.keys(acts);
				if(!allowed.includes(t)){
					alert_danger('invalid field type');
					return;
				}

				veriform.action = acts[t];
			}

		// file upload ops
			fileInput.addEventListener('change',(e) => {
				pickfyl(e.target.files[0]);
			})
			uploadZone.addEventListener('dragover', function(e) {
				e.preventDefault();
				this.classList.add('dragover');
			});

			uploadZone.addEventListener('dragleave', function(e) {
				e.preventDefault();
				this.classList.remove('dragover');
			});

			uploadZone.addEventListener('drop', function(e) {
				e.preventDefault();
				this.classList.remove('dragover');

				let f = e.dataTransfer.files[0];
				let dtr = new DataTransfer();
				dtr.items.add(f);
				fileInput.files = dtr.files;

				pickfyl(f);
			});

			function pickfyl(file) {
				verify_runtime_data.selectedFile = undefined;

				if (!file) return;

				// Validate file type
				if (verify_runtime_data.filetype === 'image' && !file.type.startsWith('image/')) {
					alert_danger('Please select an image file');
					removefyl();
					return;
				}

				if (verify_runtime_data.filetype === 'pdf' && file.type !== 'application/pdf') {
					alert_danger('Please select a PDF file');
					removefyl();
					return;
				}

				verify_runtime_data.selectedFile = file;

				if(verify_runtime_data.filetype === 'pdf'){
					setuppdf(file).then((f) => {
						file = f;
						next_steps();
					})
				} else {
					next_steps();
				}

				function next_steps(){
					// Show file info
					fileInfoSection.classList.remove('w3-hide');
					document.getElementById('fileName').textContent = file.name;
					document.getElementById('fileSize').textContent = formatFileSize(file.size);

					// Update icon
					const fileIcon = document.getElementById('fileIcon');
					if (verify_runtime_data.filetype === 'image') {
						fileIcon.className = 'fas fa-image';
					} else {
						fileIcon.className = 'fas fa-file-pdf';
					}

					// Show preview for images
					if (verify_runtime_data.filetype === 'image' || true) {
						const reader = new FileReader();

						previewSection.classList.remove('w3-hide');

						reader.onload = function(e) {
							imagePreview.src = e.target.result;
							previewSection.classList.add('active');
						};
						reader.readAsDataURL(file);
					} else {
						previewSection.classList.add('w3-hide');
						previewSection.classList.remove('active');
					}

					// validateForm();
				}
			}

			window['setuppdf'] = async (pdfFile,inputId = 'fileInput') => {
			    try {
			        // Read the PDF file
			        const arrayBuffer = await pdfFile.arrayBuffer();
			        const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
			        const page = await pdf.getPage(1);

			        // Set up canvas for rendering
			        const viewport = page.getViewport({ scale: 2.0 }); // 2x scale for better quality
			        const canvas = document.createElement('canvas');
			        const context = canvas.getContext('2d');
			        canvas.width = viewport.width;
			        canvas.height = viewport.height;

			        // Render PDF page to canvas
			        await page.render({
			            canvasContext: context,
			            viewport: viewport
			        }).promise;

			        // Convert canvas to blob
			        const blob = await new Promise(resolve => {
			            canvas.toBlob(resolve, 'image/png', 0.95);
			        });

			        // Create new file for the read page
			        const originalName = pdfFile.name.replace('.pdf', '');
			        const newFileName = `${originalName} [converted]`;
			        const imageFile = new File([blob], newFileName, { type: 'image/png' });

			        // Create a DataTransfer object to assign to the input
			        const dataTransfer = new DataTransfer();
			        dataTransfer.items.add(imageFile);

			        // Assign to file input
			        const fileInput = document.getElementById(inputId);
			        if(fileInput !== undefined){
			        	fileInput.files = dataTransfer.files;
			        }

			        // fileInput.dispatchEvent(new Event('change', { bubbles: true }));
			        return imageFile;
			    } catch (error) {
			        console.error('Error converting PDF to image:', error);
			        throw error;
			    }
			}
			function removefyl(){
				fileInput.value = '';
				verify_runtime_data.selectedFile = undefined;
				fileInfoSection.classList.add('w3-hide');
				previewSection.classList.add('w3-hide');
				previewSection.classList.remove('active');
			}

		// document info section
			window['saveinputs'] = (el) => {
				let inputs = fields[verify_runtime_data.doctype];
				let mafields = [];

				inputs.forEach(np => {
					let tmp = {
						name: np.field,
						label: np.label,
						value: veriform[np.field].value,
						mytype: np.typ
					}

					mafields.push(tmp);
				});

				let invalid = mafields.filter(f => {return (f.value == '' || f.value == null || f.value == undefined);});
				verify_runtime_data.documentDetails = mafields;

				if(invalid.length == 0){
					render_summary();
					pick_step_2(el);
				} else {
					let np = invalid[0];
					alert_danger(`fill in the ${np.label} field first`);
				}
			}

		// finalize section
			window['render_summary'] = (d) => {
				let doctype = verify_runtime_data.doctype;
				let filetype = verify_runtime_data.filetype;
				let docdeets = verify_runtime_data.documentDetails;

				let warnin = '';

				if(filetype == 'pdf'){
					warnin = `
						<div id="warnin_holder" class="spacy-sm themeround flowline left gap-sm alert-warning slide-right">
							<div>
								<i data-role="icon" class="fa fa-exclamation-triangle" style="scale: 1.7"></i>
							</div>
							<div data-role="content">
								<span>Due to how different applications and browsers process pdfs, the result may not be accurate. Please use the image we sent you for verification</span>
							</div>
						</div>
					`;
				}

				let outht = `
					${warnin}
					<div class="info-row">
						<span class="info-label">Document Type:</span>
						<span class="info-value">${doctype}</span>
					</div>
					<div class="info-row">
						<span class="info-label">File Type:</span>
						<span class="info-value">${verify_runtime_data.filetype}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Uploaded file:</span>
						<span class="info-value">${verify_runtime_data.selectedFile.name}</span>
					</div>
				`;

				// get doc details
				docdeets.forEach(d => {
					outht += `
						<div class="info-row">
							<span class="info-label">${d.label}:</span>
							<span class="info-value">${d.value}</span>
						</div>
					`;
				})

				formsummary.innerHTML = outht;
			}
			window['send_form_data'] = (frm) => {
				let myel = {dataset: {step: 4}};
				pick_step_2(myel);

				saveinputs(myel);
				progress_panel.classList.add('is_inactive');

				setTimeout(() => {
					sendform(frm);
				},1200);
			}
			window['form_onfail'] = () => {
				progress_panel.classList.remove('is_inactive');
				pick_step_2({dataset: {step: 3}});
			}
			window['form_afterfx'] = (d) => {
				let el = d.el;
				let dta = d.data;

				progress_panel.classList.remove('is_inactive');
				results_summary.innerHTML = '';

				const showverdict = (ver,txt) => {
					let v = ver == 'good';
					let c1 = v ? 'add' : 'remove';
					let c2 = !v ? 'add' : 'remove';

					verdict_holder.classList[c1]('alert-success');
					verdict_holder.classList[c2]('alert-danger');

					let ico = verdict_holder.querySelector('[data-role="icon"]');
					let con = verdict_holder.querySelector('[data-role="content"]');

					con.innerHTML = `<b>${txt}</b>`;
					ico.className = v ? 'fa fa-check-circle' : 'fa fa-times-circle';
				}

				if(dta.wegood == false){
					showverdict('bad',`Invalid document: ${dta.message}`);
					// return;
				} else {

					let outres = dta.sendme;
					let vrf = {...verify_runtime_data};
					let docdeets = vrf.documentDetails;

					showverdict('good',dta.message);

					const clscompare = (sent, rec, ftype = 'alphanum') => {
						let con = false;

						// let con = ftype.toLowerCase() == 'date' ? (new Date(sent)) == (new Date(rec)) : (sent + "").toLocaleString().toLowerCase() == (rec + "").toLocaleString().toLowerCase();
						if(ftype.toLowerCase() == 'date'){
							con = formatDate(new Date(sent)) == formatDate(new Date(rec));
						} else if(ftype.toLowerCase() == 'number'){
							con = Number(sent) == Number(rec);
						} else {
							con = (sent + "").toLocaleString().toLowerCase() == (rec + "").toLocaleString().toLowerCase();
						}

						let cls = con ? 'w3-text-green good' : 'w3-text-red bad';
						let icon = con ? '<i class="fa fa-check-circle"></i>' : '<i class="fa fa-times-circle"></i>';

						return {
							class : cls,
							icon : icon,
						}
					}

					let outshape = [
						{
							extracls: clscompare(true,outres.matchfile).class,
							title: `${clscompare(true,outres.matchfile).icon} Files Match`,
							val: `<b class="w3-tag themeround ${!outres.matchfile ? 'w3-red' : 'w3-green'}">${outres.matchfile ? 'yes' : 'no'}</b>`,
						},
					]
					let outht = ``;

					outshape.forEach(f => {
						outht += `
							<div class="info-row ${f.extracls || ''}">
								<span class="info-label">${f.title}:</span>
								<span class="info-value">${f.val}</span>
							</div>
						`;
					});

					docdeets.forEach(dts => {
						let mcl = clscompare(dts.value,outres[dts.name], dts.mytype);
						let xcls = mcl.class;
						let ico = mcl.icon;

						outht += `
							<div class="info-row ${xcls}">
								<span class="info-label">${ico} ${dts.label}:</span>
								<span class="info-value">${dts.value}</span>
							</div>
						`;
					})

					results_summary.innerHTML = outht;

					let totals = results_summary.querySelectorAll('.info-row');
					console.log('totals: ', totals);

					let good = [...totals].filter(t => {return t.className.includes('good');});
					let ver = totals.length == good.length;

					if(ver){
						showverdict('good',`valid document`);
					} else {
						showverdict('bad',`Invalid document: some information was altered`);
					}

					results_summary.innerHTML += `
						<div class="info-row">
							<span class="info-label">File Link:</span>
							<span class="info-value"><a href="./media/${vrf.doctype}s/img/${outres.file_link}" target="_blank" class="mybtn secondary">view original file <i class="fa fa-angle-double-right"></i></a></span>
						</div>
					`;
				}

				pick_step_2({dataset: {step: 5}});
			}

		// utilities
			function formatFileSize(bytes) {
				if (bytes === 0) return '0 Bytes';
				const k = 1024;
				const sizes = ['Bytes', 'KB', 'MB', 'GB'];
				const i = Math.floor(Math.log(bytes) / Math.log(k));
				return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
			}

			function sendform(m,callback,onfail,tosend = undefined) {
				let hasencrypted = false;
				let ps = '';

				if(m.dataset.submitting === true){
					alert_danger('already submitting...');
					return;
				} else {
					alert_info('submitting form...')
				}

				// callback mgt
				if(callback == undefined){
					if(m.dataset.callback){
						console.log('callback: ',m.dataset.callback);
						// alert_dark('callback: ' + m.dataset.callback);

						callback = window[m.dataset.callback.trim()];
					} else {
						console.log('no fallback to the callback');
						// alert_dark('no fallback to the callback');
					}
				} else {
					if(typeof callback != "function"){
						console.log('trying to read from a window variable');
						// alert_dark('trying to read from a window variable');
						callback = window[callback];
					}
				}

				if(typeof callback == "function"){
					console.log('valid callback')
					// alert_dark('valid callback')
				} else {
					callback = (a) => {
						alert_success('process successful');
						console.log('default callback results: ',a);
					};
					console.log('not a function')
					// alert_dark('not a function')
				}

				// onfail mgt
				if(onfail == undefined){
					if(m.dataset.onfail){
						console.log('onfail: ',m.dataset.onfail);
						// alert_dark('onfail: ' + m.dataset.onfail);

						onfail = window[m.dataset.onfail.trim()];
					} else {
						console.log('no fallback to the onfail');
						// alert_dark('no fallback to the onfail');
					}
				} else {
					if(typeof onfail != "function"){
						console.log('trying to read from a window variable');
						// alert_dark('trying to read from a window variable');
						onfail = window[onfail];
					}
				}

				if(typeof onfail == "function"){
					console.log('valid onfail')
					// alert_dark('valid onfail')
				} else {
					onfail = (a) => {alert_danger('process failed')};
					console.log('not a function')
					// alert_dark('not a function')
				}

				// return;

				// callback = callback == undefined ? (m.dataset.callback ? window[m.dataset.callback] : undefined) : callback;

				callback = typeof callback == 'function' ? callback : () => {alert_info('invalid callback passed to sendform')};

				m.style.pointerEvents = "none";
				m.style.opacity = 0.2;
				m.dataset.submitting = true;

				// /*
				if(m.admin_password !== undefined){
					hasencrypted = true;
					ps = m.admin_password.value;
					m.admin_password.value = encryptme(ps,encsetup.offset,encsetup.salt,encsetup.chunks);
				}
				// */

				let fdata = tosend == undefined ? getFormdata(m) : tosend;
				const sender = tosend == undefined ? window[fetch_bypass_fyls] : window[fetch_bypass];
				const as_is = tosend == undefined;
				let myres = undefined;
				let myaction = m.action;
				// console.log('fdata: ',fdata,'use as is: ',as_is);

				if(myaction.includes('defined:')){
					// myaction = linkAtlas[myaction.split(':')[1]];
				}

				// add_edit_form.style.pointerEvents = "all";
				sender(myaction,fdata,'POST',true,as_is).then(d => {
					myres = d;

					// console.log(callback);
					// return;
					responseHandler(d,callback,{'el': m,'data': d});

					if(!d.success || !d.result){
						onfail(d);
					}
					// console.log("finally, the result is: ",myres);
					// callback(m);
				})
				.catch(err => {
					alert_danger(err.message);
					console.error(err);

					if(onfail !== undefined){
						onfail(err);
					}
				})
				.finally(() => {
					m.style.pointerEvents = "all";
					m.style.opacity = 1;
					m.dataset.submitting = false;

					if(hasencrypted){
						m.admin_password.value = ps;
					}
				})
			}
	</script>
</x-layouts.mainlayout>
