<style>
	/* doc styles */
		.signsection{
			display: flex;
			margin: 12px 0;
		}
		.signsection .anc{
			padding: 5px;
			width: 300px;
		}
		.signsection .deets{
			width: 100%;
		}
		.signsection .deets .myrow{
			padding: 5px 0;
			/*border-bottom: 1px solid var(--r_border);*/
		}

		.stamp_space{
			height: 300px;
			width: 100%;
			/*background: red;*/
			position: relative;
			transform: rotate(12deg);
		}
		.stamp_image {
			height: 178px;
			aspect-ratio: 1;
			/* border-radius: 50%; */
			object-fit: cover;
			display: inline-block;
			width: 178px;
			position: absolute;
			top: 12%;
		}
		.stamp_space #dateguy {
			font-family: monospace;
			font-weight: 800;
			position: absolute;
			top: 111px;
			left: 35px;
			background: #fff;
			padding: 1px 1px;
			transform: rotate(10deg) scale(0.8);
			font-size: 1.2rem;
			border-radius: 5px;
		}

		.signature_image {
			height: auto;
			width: 126px;
			position: absolute;
			mix-blend-mode: multiply;
			transform: translate(65px, -20px) scale(0.8);
		}

	/* collapse */
		.info-row {
			display: flex;
			padding: 8px 0;
			border-bottom: 1px solid #f0f0f0;
		}
		.info-row:last-child {
			border-bottom: none;
		}
		.info-row .info-label {
			font-weight: 600;
			color: #555;
			width: 180px;
			flex-shrink: 0;
		}
		.info-row .info-value {
			color: #333;
			flex-grow: 1;
		}

	.notactive{
		opacity: 0.3;
		pointer-events: none;
	}
	.hideme{
		opacity: 0;
		pointer-events: none;
	}

	.tab{
		position: relative;
	}
	.tab .actions{
		position: sticky;
		bottom: 0;
		/*background-image: linear-gradient(0deg,var(--clr-panelbg),transparent);*/
		background-color: var(--clr-panelbg);
		box-shadow: 0 0 16px rgba(120, 120, 120, 0.2);
		padding: 12px;
		border-radius: var(--roundness);
		margin-top: 24px;
	}
	.tab .section-title{
		font-weight: 700;
	}

	.mylist b {width: 50px;display: inline-block;}
</style>

<div class="container-fluid">
	<div class="page-header">
		<div class="page-title">
			<span class="h4">Generate Report</span>
			<p>Here you can generate a report for a sample that has finished processing</p>
		</div>

		<div class="header-actions">
			<button class="mybtn secondary" data-runme="load_page" data-link="defined:biz_data">
				<i class="fas fa-edit"></i>
				Edit business info
			</button>
			<button class="mybtn secondary" data-runme="initstuff" data-link="defined:gen_report" data-label="defined:gen_report">
				<i class="fas fa-sync"></i>
				refresh
			</button>
		</div>
	</div>

	<div class="spacy-lg w3-center flow centroid gap-md slide-up" id="loader_init">
		<div>loading information</div>
		<div class="loader"></div>
	</div>

	<div class="spacy-lg w3-center flow centroid gap-sm slide-up" id="errorbox">
		<span class="h3">Error</span>
		<p class="errtxt">an error occured</p>
	</div>

	<div class="panelbg themeround distance-tn slide-down" id="progress_panel">
		<div class="progress-indicator">
			<div class="progress-steps">
				<div class="progress-line themegrad" id="progressLine" style="width: 20%;"></div>
				<div class="step" data-step="0" data-runme="pick_step_2">
					<div class="step-circle"><i class="fas fa-file"></i></div>
					<div class="step-label">Document preview</div>
				</div>
				<div class="step" data-step="1" data-runme="pick_step_2">
					<div class="step-circle"><i class="fas fa-file-export"></i></div>
					<div class="step-label">Generated document</div>
				</div>
				<div class="step" data-step="2" data-runme="pick_step_2">
					<div class="step-circle"><i class="fas fa-check"></i></div>
					<div class="step-label">Finalize</div>
				</div>
			</div>
		</div>
	</div>

	<div class="tabs" id="tabs_panel">
		<div class="tab slide-up">
			<div class="spacy-sm w3-center text-muted section-title">
				<span class="h4">document preview</span>
			</div>

			<div id="document_Holder" class="flow centroid">
				<div data-role="report_setup">
					<div class="page-section header">
						<h1 class="w3-center themetxt">JEYMATIAN MINERAL LABORATORY (JML) pre render</h1>
						<div class="flowline gap-sm mylist">
							<div class="thelogo">
								<!-- <img src="./_assets/img/logo.png"> -->
								<img src="./_assets/img/logo.jpg">
							</div>
							<div class="flow gap-tn">
								<div>
									<b>Branch</b>
									<strong data-subrole="branch" class="text-uppercase">Migori Office</strong>
								</div>
								<div>
									<b>Location:</b>
									<span data-subrole="location">Nyamome - Muhuru Road, opposite Nyamome primary school, Suna west, Migori, Kenya</span>
								</div>
								<div>
									<b>Email:</b>
									<span data-subrole="email">jeymatminerals@gmail.com</span>
								</div>
								<div>
									<b>Phone:</b>
									<span data-subrole="phone">+254 769 035 640</span>
								</div>
							</div>
						</div>
					</div>

					<div class="page-section task_details">
						<table class="mytable table-border" id="taskinfo">
							<tr>
								<td>
									<b>Attending Technician: </b>
									<span data-subrole="user_name">Asha</span>
								</td>
								<td>
									<b>Number of samples: </b>
									<span data-subrole="user_name">7</span>
								</td>
							</tr>
							<tr>
								<td>
									<b>Company contact: </b>
									<span data-subrole="user_name">+254 769 035 640</span>
								</td>
								<td>
									<b>Sample Type: </b>
									<span data-subrole="user_name">Soil</span>
								</td>
							</tr>
							<tr>
								<td>
									<b>Recieved Date: </b>
									<span data-subrole="user_name">30/01/2026 12:34PM</span>
								</td>
								<td>
									<b>Completion date: </b>
									<span data-subrole="user_name">30/01/2026 18:43PM</span>
								</td>
							</tr>
							<tr>
								<td>
									<b>Job No: </b>
									<span data-subrole="user_name">TSK_ZXCV_001</span>
								</td>
								<td>
									<b>Status: </b>
									<span data-subrole="user_name">final report</span>
								</td>
							</tr>
						</table>
					</div>

					<div class="page-section">
						<h3 class="heading">Analytical report</h3>
						<div>
							<table class="mytable">
								<tr>
									<th style="width: 40px;">SN</th>
									<th>Sample ID</th>
									<th style="width: 140px;">Au ppm</th>
									<th style="width: 140px;">Cu ppm</th>
								</tr>
								<tbody id="resultsguy"></tbody>
							</table>
						</div>
					</div>

					<div class="page-section">
						<h3 class="heading">Deviation summary</h3>
						<div>
							<table class="mytable">
								<tr>
									<th style="width: 177px;">Sample type</th>
									<th>Analytical method</th>
									<th style="width: 140px;">Tolerance limit</th>
								</tr>
								<tbody id="deviationguy"></tbody>
							</table>
						</div>
					</div>

					<div class="page-section">
						<div id="signs">
							<div class="flowline in_fullwidth signsection">
								<img src="./_assets/img/sign_2.png" class="signature_image">
								<div class="anc">
									<b>Technical Staff</b>
								</div>
								<div class="deets">
									<div class="myrow">
										<div>
											<b>Name: </b>
											<span>Witness Stanslaus</span>
										</div>
									</div>
									<div class="myrow">
										<b>signature:</b>
									</div>
								</div>
							</div>
							<div class="flowline in_fullwidth signsection">
								<img src="./_assets/img/sign_1.png" class="signature_image">
								<div class="anc">
									<b>Authorizer</b>
								</div>
								<div class="deets">
									<div class="myrow">
										<div>
											<b>Name: </b>
											<span>Asha Stanslaus</span>
										</div>
									</div>
									<div class="myrow">
										<b>signature:</b>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="page-section">
						<div class="stamp_space" id="stamp">
							<img src="./_assets/img/stamp_1.png" class="stamp_image">
						</div>
					</div>
				</div>
			</div>

			<div class="actions flowline spread slide-up" style="animation-delay: 300ms;opacity: 0;animation-fill-mode: forwards;">
				<button class="mybtn secondary hideme"><i class="fa fa-arrow-left"></i> previous</button>
				<button class="mybtn primary" data-runme="pick_step_3" data-step="1" data-msg="rendering report">render report <i class="fa fa-arrow-right"></i></button>
			</div>
		</div>

		<div class="tab slide-up">
			<div id="canvasHolder">
				<div class="spacy-sm w3-center text-muted section-title">
					<span class="h4">Generated document</span>
				</div>

				<div class="canvasguy slide-up flow centroid">
				</div>

				<div class="distance-sm border panelbg themeround collapser slide-up">
					<a class="text-decoration-none spacy-sm flowline spread centerline" href="#statsguy" data-toggle="collapse" data-target="#statsguy">
						<span class="text-uppercase text-muted font-weight-bold">Capture stats</span>
						<i class="fa fa-chevron-down myicon"></i>
					</a>
					<div class="collapse panelbg themeround spacy-sm" id="statsguy">
					</div>
				</div>
			</div>

			<div class="actions flowline spread slide-up" style="animation-delay: 300ms;opacity: 0;animation-fill-mode: forwards;">
				<button class="mybtn secondary" data-runme="pick_step_2" data-step="0"><i class="fa fa-arrow-left"></i> previous</button>
				<button class="mybtn primary" data-runme="pick_step_3" data-step="2" data-msg="generating pdf">generate pdf <i class="fa fa-arrow-right"></i></button>
			</div>
		</div>

		<div class="tab slide-up">
			<div>
				<div class="spacy-sm w3-center text-muted section-title">
					<span class="h4">final steps</span>
				</div>

				<div class="spacy-sm panelbg themeround">
					<div class="distance-sm">
						<p>Here are the generated files, both image and pdf file. You can check the generated files by navigating to the previous steps.</p>
						<p>click the button to save the report to the database</p>
					</div>
					<form action="defined:save_report" class="border themeround spacy-sm" data-afterfx="aftersave_ops" data-onsubmit="upload_stuff" data-blockdefault="yes">
						<div class="notactive">
							<input type="hidden" name="report_no">
							<input type="hidden" name="sampleid">
							<input type="hidden" name="admin_password_mine">
							<input type="hidden" name="bizdata">
							<div class="form-group">
								<label class="form-label" for="client_name">Generated PDF</label>
								<input type="file" name="genfile" id="genfile" required>
							</div>
							<div class="form-group">
								<label class="form-label" for="client_name">Generated PDF</label>
								<input type="file" name="genimage" id="genimage" required>
							</div>
						</div>
						<button class="mybtn primary"><i class="fa fa-save"></i> save Report</button>
					</form>
				</div>
			</div>

			<div class="actions flowline spread slide-up" style="animation-delay: 300ms;opacity: 0;animation-fill-mode: forwards;">
				<button class="mybtn secondary" data-runme="pick_step_2" data-step="1"><i class="fa fa-arrow-left"></i> previous</button>
				<button class="mybtn primary hideme" data-runme="pick_step_2" data-step="1">next <i class="fa fa-arrow-right"></i></button>
			</div>
		</div>

		<div class="tab slide-up">
			<div class="spacy-lg w3-center flow centroid gap-sm panelbg themeround mt-4 w3-hide_" id="loader_render">
				<span class="h4">Uploading Report</span>
				<div>saving to database</div>
				<div class="loader"></div>
			</div>
		</div>

		<div class="tab slide-up">
			<div class="spacy-sm panelbg themeround mt-4">
				<span class="h4">Report Generation successful</span>
				<p>the report has been generated, pick an option below to proceed</p>
				<div class="flowline gap-tn left">
					<button class="mybtn primary sm" data-runme="load_page" data-link="defined:reports_mgt" data-label="defined:reports_mgt"><i class="fa fa-file-export"></i> manage reports</button>
					<button class="mybtn secondary sm" data-runme="load_page" data-link="defined:samples_mgt" data-label="defined:samples_mgt"><i class="fa fa-plus"></i> generate another</button>
					<button class="mybtn secondary sm" data-runme="downloadPdf"><i class="fa fa-download"></i> download report</button>
					<button class="mybtn secondary sm" data-runme="printPdf"><i class="fa fa-print"></i> print report</button>
				</div>
			</div>
		</div>
	</div>

	<div class="w3-hide" id="oldactions">
		<div>
			<span class="h4">Actions</span>
			<p>Here are actions you can perform on the document</p>
		</div>
		<div>
			<button class="mybtn primary" data-runme="renderCanvas"><i class="fa fa-image"></i> render report</button>
			<button class="mybtn primary" data-runme="gen_download"><i class="fa fa-image"></i> generate pdf</button>
		</div>
	</div>
</div>

<script>
	window['report_runtime_data'] = {};
	window['killOnLeave'] = [
		'initstuff',
		'showerror',
		'showloader',
		'render_all_data',
		'processNumber',
		'renderCanvas',
		'generatePdf',
		'gen_download',
		'downloadPdf',
		'printPdf',
		'generateSerial',
		'pick_step',
		'pick_step_2',
		'pick_step_3',
		'upload_stuff',
	];

	/*
	report_runtime_data = {
		tosend: {
			'task_id' : undefined,
			'sample_id' : undefined,
			'created_by' : undefined,
			'client_id' : undefined,

			'company_contact' : undefined,
			'sample_type' : undefined,
			'sample_count' : undefined,
			'date_received' : undefined,
			'date_compiled' : undefined,
			'task_no' : undefined,
			'technical_staff_name' : undefined,
			'analytical_report' : undefined,
			'deviation_data' : undefined,
			'business_logo_img' : undefined,
			'pdf_link' : undefined,
			'image_link' : undefined,
		}
	}
	*/

	let tabs = document.querySelectorAll('.tabs .tab');
	let checks = document.querySelectorAll('.progress-steps>.step');
	let spans = [0,45,92];

	const steps = [
		'do_nothing',
		'renderCanvas',
		'gen_download',
	];

	window['runOnAwake'] = () => {
		initstuff();
	}

	// initialisers
	window['initstuff'] = () => {
		alert_dark('loading stuff');
		let outht = '';
		let errshown = false;
		sid = window[variableAtlas['sample_id']];

		const step1 = () => {
			if(sid == undefined){
				alert_danger('pick a sample first');
				outht = `
					pick a sample first
				`;
				showerror(outht);
				return;
			}

			showloader(true);

			setTimeout(() => {
				window[fetch_bypass](linkAtlas['get_sample'],{sample_id: sid},'POST').then(dta => {
					responseHandler(dta, step2, dta);

					if(!dta.success || !dta.result){
						throw new Error(dta.message);
					}
				}).catch(err => {
					outht = `There was an arror fetching sample information: <br><b class="w3-text-red">${err.message}</b>`;
					showerror(outht);
					errshown = true;
				})
			},min_loadtime)
		}

		// validate and save sample data
		const step2 = (d) => {
			if(d.sendme.task.payment_status !== 2){
				throw new Error('task payment has not been completed');
			}

			if(d.sendme.status !== 2){
				throw new Error('sample has not finished processing');
			}

			report_runtime_data.sampleData = d.sendme;

			window[fetch_bypass_fyls](linkAtlas['get_bizdata'],{},'POST').then(dta => {
				responseHandler(dta,step3,dta);

				if(!dta.success || !dta.result){
					throw new Error(dta.message);
				}
			}).catch(err => {
				outht = `There was an arror fetching business information: <br><b class="w3-text-red">${err.message}</b>`;
				showerror(outht);
				errshown = true;
			})
		}

		// get bizdata
		const step3 = (d) => {
			report_runtime_data.bizdata = d.sendme;
			window['bizdata'] = parseBizdata(d.sendme);

			final_step();
		}

		const final_step = () => {
			showloader(false);
			render_all_data();
			pick_step(0);
			refreshUI(300);
		};

		step1();
	}

	window['showerror'] = (err) => {
		c = true;
		let cls = c ? 'add' : "remove";
		let altcls = !c ? 'add' : "remove";
		let txt = errorbox.querySelector('.errtxt');

		txt.innerHTML = `
			<p>${err}</p>
			<div class="spacy-sm flowline gap-sm">
				<button class="mybtn primary sm" data-runme="load_page" data-link="defined:samples_mgt" data-label="sample management"><i class="fas fa-flask"></i> pick another sample</button>
				<button class="mybtn secondary sm" data-runme="initstuff" data-link="defined:gen_report" data-label="defined:gen_report"><i class="fas fa-sync"></i> refresh</button>
			</div>
		`;

		loader_init.classList[cls]('w3-hide');

		errorbox.classList[altcls]('w3-hide');
		progress_panel.classList[cls]('w3-hide');
		tabs_panel.classList[cls]('w3-hide');

		refreshUI(300);
	}
	window['showloader'] = (c = true) => {
		let cls = c ? 'add' : "remove";
		let altcls = !c ? 'add' : "remove";

		loader_init.classList[altcls]('w3-hide');

		errorbox.classList['add']('w3-hide');
		progress_panel.classList[cls]('w3-hide');
		tabs_panel.classList[cls]('w3-hide');
	}
	window['render_all_data'] = () => {
		alert_dark('rendering header');

		let outht = '';

		let holder = document_Holder;
		let reportguy = holder.querySelector('[data-role="report_setup"]');
		let header = reportguy.querySelector('.page-section.header');

		let b_data = bizdata;
		let bizdeets = b_data.business_details;
		let bizimgs = b_data.business_images;

		let s_data = report_runtime_data.sampleData;

		// header
		header.innerHTML = `
			<h1 class="w3-center themetxt">${bizdeets.biz_name}</h1>
			<div class="flowline gap-sm">
				<div class="thelogo">
					<img src="${linkAtlas['bizimages']}/${bizimgs.company_logo}">
				</div>
				<div class="flow gap-tn mylist">
					<div>
						<b>Branch</b>
						<strong data-subrole="branch" class="text-uppercase">
							${bizdeets.branch_name}
						</strong>
					</div>
					<div>
						<b>Location:</b>
						<span data-subrole="location">
							${bizdeets.location}
						</span>
					</div>
					<div>
						<b>Email:</b>
						<span data-subrole="email">
							${bizdeets.email}
						</span>
					</div>
					<div>
						<b>Phone:</b>
						<span data-subrole="phone">
							${processNumber(bizdeets.phone)}
						</span>
					</div>
				</div>
			</div>
		`;

		// the second section (aka the creditsguy)
		taskinfo.innerHTML = `
			<tr>
				<td>
					<b>Attending Technician: </b>
					<span data-subrole="user_name">${cur_user.name}</span>
				</td>
				<td>
					<b>Report No: </b>
					<span>${generateSerial(s_data)}</span>
				</td>
			</tr>
			<tr>
				<td>
					<b>Company contact: </b>
					<span>${processNumber(bizdeets.phone)}</span>
				</td>
				<td>
					<b>Client: </b>
					<span>${s_data.owner.client_name}</span>
				</td>
			</tr>
			<tr>
				<td>
					<b>Number of samples: </b>
					<span>${s_data.sample_count}</span>
				</td>
				<td>
					<b>Sample Type: </b>
					<span>${s_data.sample_type}</span>
				</td>
			</tr>
			<tr>
				<td>
					<b>Recieved Date: </b>
					<span>${formatDate0(s_data.created_at)}</span>
				</td>
				<td>
					<b>Completion date: </b>
					<span>${formatDate0(s_data.updated_at)}</span>
				</td>
			</tr>
			<tr>
				<td>
					<b>Job No: </b>
					<span>${s_data.task.task_no}</span>
				</td>
				<td>
					<b>Status: </b>
					<span>final report</span>
				</td>
			</tr>
		`;

		// the results
		outht = '';
		resultsguy.innerHTML = '';

		s_data.results.forEach((r,i) => {
			outht += `
				<tr>
					<td>${i + 1}</td>
					<td>${r.sample_name}</td>
					<td>${r.au_ppm}</td>
					<td>${r.cu_ppm}</td>
				</tr>
			`;
		})

		resultsguy.innerHTML = outht;

		// the deviation data
		outht = '';
		deviationguy.innerHTML = '';

		b_data.deviation_data.forEach(d => {
			outht += `
				<tr>
					<td>${d.type}</td>
					<td>${d.method}</td>
					<td>${d.tolerance}</td>
				</tr>
			`;
		})

		deviationguy.innerHTML = outht;

		// the signature section
		outht = '';
		signs.innerHTML = '';

		outht = `
			<div class="flowline in_fullwidth signsection">
				<img src="${linkAtlas['bizimages']}/${bizimgs.Technician_signature}" class="signature_image">
				<div class="anc">
					<b>Technical Staff</b>
				</div>
				<div class="deets">
					<div class="myrow">
						<div>
							<b>Name: </b>
							<span>${bizdeets.tech_staff}</span>
						</div>
					</div>
					<div class="myrow">
						<b>signature:</b>
					</div>
				</div>
			</div>
			<div class="flowline in_fullwidth signsection">
				<img src="${linkAtlas['bizimages']}/${bizimgs.supervisor_signature}" class="signature_image">
				<div class="anc">
					<b>Authorizer</b>
				</div>
				<div class="deets">
					<div class="myrow">
						<div>
							<b>Name: </b>
							<span>${bizdeets.auth_staff}</span>
						</div>
					</div>
					<div class="myrow">
						<b>signature:</b>
					</div>
				</div>
			</div>
		`;
		signs.innerHTML = outht;

		outht = '';
		stamp.innerHTML = '';

		outht = `
			<img src="${linkAtlas['bizimages']}/${bizimgs.official_stamp}" class="stamp_image">
			<div id="dateguy">${('<?php echo date("d M Y")?>').toUpperCase()}</div>
		`;
		stamp.innerHTML = outht;
	}
	window['processNumber'] = (n) => {
		let xtra = "";
		let num = n;
		let proc = [];

		num.split("").map((l,id) => {
			let xt = id % 3 == 0 && id != 0 ? " " : "";
			let n = `${l}${xt}`;
			proc.push(n);
		})

		return `${xtra}${proc.join("")}`;
	}

	// operations
	window['renderCanvas'] = async () => {
		el = document.querySelector('#document_Holder>[data-role="report_setup"]');
		outguy = document.querySelector('#canvasHolder>.canvasguy');
		statsDiv = document.querySelector('#statsguy');

		if(el == undefined){alert_danger('document holder not found');return;}
		if(outguy == undefined){alert_danger('output holder not found');return;}
		if(genimage == undefined){alert_danger('generated image holder not found');return;}

		alert_dark('rendering canvas');

		const options = {
			allowTaint: true,
			backgroundColor: null,
			logging: false,
			scale: 2,
			useCORS: false
		};

		outguy.innerHTML = 'rendering, please wait';
		statsDiv.innerHTML = 'rendering, please wait';

		// outguy.classList.add('w3-hide');
		// loader_render.classList.remove('w3-hide');

		// setTimeout(async () => {
			try{
				const startTime = performance.now();

				// capture element
				const canvas = await html2canvas(el, options);
				const endtime = performance.now();
				const duration = (endtime - startTime).toFixed(2);

				// outguy.classList.remove('w3-hide');
				// loader_render.classList.add('w3-hide');
				outguy.innerHTML = '';
				outguy.appendChild(canvas);

				report_runtime_data.renderedCanvas = canvas;

				// /*
				report_runtime_data.renderedCanvas.toBlob((blob) => {
					const fyl = new File([blob],'generated_doc_image.png',{type: 'image/png'});
					let dtr = new DataTransfer();
					dtr.items.add(fyl);
					genimage.files = dtr.files;
				});
				// */

				let dur = duration + 400;
				statsDiv.innerHTML = `
					<div class="info-row">
						<span class="info-label">Size:</span>
						<span class="info-value">${canvas.width} × ${canvas.height} px</span>
					</div>
					<div class="info-row">
						<span class="info-label">Render Duration:</span>
						<span class="info-value">${(dur / 1000).toFixed(2)} s</span>
					</div>
					<div class="info-row">
						<span class="info-label">Scale:</span>
						<span class="info-value">${options.scale}x</span>
					</div>
					<div class="info-row">
						<span class="info-label">Background:</span>
						<span class="info-value">${options.backgroundColor || 'initial'}</span>
					</div>
				`;

				alert_success('report rendered');
			} catch (error) {
				output.innerHTML = `<div class="empty-state" style="color: #e53e3e;">Error: ${error.message}</div>`;
				console.error('Capture failed:', error);
			}
		// },400);
	}

	window['generatePdf'] = async () => {
		if(report_runtime_data.renderedCanvas == undefined){
			alert_danger('no document has been rendered yet');
			return;
		}

		let rcv = report_runtime_data.renderedCanvas;
		let fwidth = rcv.width;
		let fheight = rcv.height;

		const imgData = rcv.toDataURL('image/png');

		const { jsPDF } = window.jspdf;
		const pdf = new jsPDF({
			orientation: fwidth > fheight ? 'landscape' : 'portrait',
			unit: 'px',
			format: [fwidth, fheight]
		});

		pdf.addImage(imgData, 'png',0, 0, fwidth, fheight);

		return pdf;
	}

	window['gen_download'] = async () => {
		try {
			if(report_runtime_data.renderedCanvas == undefined){
				throw new Error('no document has been rendered yet');
			}
			if(genfile == undefined){
				throw new Error('the element to hold the document is missing');
			}

			const pdf = await generatePdf();

			if(!pdf){
				throw new Error('something went wrong with generating the document.');
			}

			const theblob = pdf.output('blob');
			const thefile = new File(
				[theblob],
				'generated_doc.pdf',
				{type: 'application/pdf'}
			);

			const dtr = new DataTransfer();
			dtr.items.add(thefile);

			genfile.files = dtr.files;

			report_runtime_data.filemade = true;
			report_runtime_data.medFile = pdf;

			// Download the PDF
			// pdf.save('gen_doc.pdf');
			// alert_danger('PDF downloaded successfully!', 'success');
		} catch (error) {
			console.error('Error:', error);
			alert_danger('Error generating PDF: ' + error.message);
		}
	}

	window['downloadPdf'] = () => {
		if(report_runtime_data.medFile == undefined){
			alert_danger('no pdf has been generated yet');
			return;
		}

		if(report_runtime_data.sampleData == undefined){
			alert_danger('sample data missing, refresh the page to fix',4);
			return;
		}

		report_runtime_data.medFile.save(`${report_runtime_data.sampleData.task.task_no}_generated_report.pdf`);

		alert_info('downloading generated pdf');
	}
	window['printPdf'] = () => {
		// Get the canvas element
		// const canvasId = 'mycanvas';
		// const canvas = document.getElementById(canvasId);
		const canvas = report_runtime_data.renderedCanvas;

		// Create a new window for printing
		const printWindow = window.open('', '_blank');

		// Get the canvas as a data URL
		const dataUrl = canvas.toDataURL();

		// Write HTML to the print window
		printWindow.document.write(`
			<!DOCTYPE html>
			<html>
				<head>
					<title>Print Canvas</title>
					<style>
						body {
							margin: 0;
							padding: 0;
							display: flex;
							justify-content: center;
							align-items: center;
							min-height: 100vh;
						}
						img {
							max-width: 100%;
							height: auto;
						}
						@media print {
							body {
								margin: 0;
							}
							img {
								max-width: 100%;
								height: auto;
								page-break-inside: avoid;
							}
						}
					</style>
				</head>
				<body>
					<img src="${dataUrl}" onload="window.print(); window.close();" />
				</body>
			</html>
		`);

		printWindow.document.close();
	}
	window['generateSerial'] = (b) => {
		let serial = `${b.sample_no}_${b.reports_count}`;
		report_runtime_data.report_no = serial;
		return serial;
	}

	// navops
	window['pick_step'] = (n) => {
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
		} else {
			alert('tab doesnt exist');
		}
	}
	window['pick_step_2'] = (g, skipchecks = false) => {
		let n = Number(g.dataset.step);

		// alert_warning('knadwjnwadkjwad');

		// if(!skipchecks){
			if(report_runtime_data.renderedCanvas == undefined && n > 0){
				alert_danger('render a report first');
				return;
			}
			if(report_runtime_data.filemade == undefined && n > 1){
				alert_danger('generate a pdf first');
				return;
			}
		// }

		pick_step(n);
	}
	window['pick_step_3'] = async (g) => {
		let n = Number(g.dataset.step);
		alert_info(g.dataset.msg || 'running operation...');
		// alert('whaadadadad');

		if(n < steps.length){
			let mystep = steps[n];

			if(window[mystep] !== undefined){
				alert_dark(`running: ${mystep}`);
				// pick_step_2(g,true);

				window[mystep]().then(w => {
					pick_step_2(g);
				});
			}
		}
	}

	// afterwork stuff
	window['upload_stuff'] = (el) => {
		const step2 = (d) => {
			alert_info('uploading report...');
			el.report_no.value = report_runtime_data.report_no;
			el.sampleid.value = report_runtime_data.sampleData.id;
			el.admin_password_mine.value = d.admin_password;
			el.bizdata.value = JSON.stringify(bizdata);
			pick_step(3);

			sendform(el,step3,step2b);
		}

		const step2b = (d) => {
			pick_step(2);
			alert_danger('there was an error uploading the report');
		}

		const step3 = (d) => {
			pick_step(4);
			confirmAction(mekHeading('report generated successfully','h4'),'the report has been generated successfully',() => {});
			alert_success('report uploaded successfully');
		}

		const step1 = () => {
			awaitConfirmPass(step2);
		}

		step1();
	}
</script>
