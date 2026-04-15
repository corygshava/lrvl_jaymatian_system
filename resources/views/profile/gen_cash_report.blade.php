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
			position: absolute;
			top: 0;
			right: 0;
			transform: translate(61%,-68px) scale(0.7);
			/* transform: rotate(12deg); */
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
			/* transform: rotate(10deg) scale(0.8); */
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

	/* custom info rows */
		.info_row_guy{
			display: flex;
		}
		.info_row_guy>div{
			flex: 1 1 400px;
		}
		.info-row{
			border: none;
			padding: 4px 0;
		}
		.info-row .info-label{
			color: #000;
		}

		.cli_tab{
			border: none;
		}
	/* misc */
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
			/*position: sticky;*/
			position: fixed;
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

		h3.heading{
			text-align: left;
			text-transform: uppercase;
			font-size: 1.2rem;
		}

		.mylist b {width: 50px;display: inline-block;}
		.printTxt{color: #000;}

		/*.smallerCells{font-size: 0.9rem;}*/
		/*.smallerCells td{font-size: 0.9rem;padding: 1px 3px;}*/

		tr td{padding: 1px 3px !important;}
		tr td>*{transform: translateY(-7px);}
	/* masonry layout for the new daily report design */
		.dynacols{columns: 2;margin-top: 12px;}
		.dynacols>div{margin-bottom: 12px;display: inline-block;width: 100%;}
		.dynacols>div .hed{color: var(--darkcolor);font-size: 1.1rem;font-weight: 700;}
</style>

<div class="container-fluid">
	<div class="page-header">
		<div class="page-title">
			<span class="h4" data-role="pagename">--</span>
			<p>Here you can generate a printed version of a saved daily report</p>
		</div>

		<div class="header-actions">
			<button class="mybtn secondary" data-runme="load_page" data-link="defined:cash_reports" data-sidenote="defined:cash_reports">
				<i data-myicon="cash_reports"></i>
				Cash Reports
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
					<div class="step-circle"><i class="fas fa-check"></i></div>
					<div class="step-label">Generated Document</div>
				</div>
				<div class="step w3-hide" data-step="2" data-runme="pick_step_2">
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
				<div data-role="report_setup" id="wanteddesign">
				</div>

				<div data-role="report_setup" class="w3-hide_ mt-4" id="newdesign">
					<div class="page-section header rendercorrect" style="padding: 0 !important;">
						<h1 class="w3-center dark_themetxt">JEYMATIAN MINERAL LABORATORY (JML) pre render</h1>
						<div class="flowline gap-sm mylist">
							<div class="thelogo">
								<!-- <img src="./_assets/img/logo.png"> -->
								<img src="">
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

					<div data-role="datasection">
					</div>

					<div class="page-section invoice_summary">
						<div data-subrole="deetsui">
						</div>
					</div>

					<div class="page-section w3-hide">
						<div id="signs">
							<div class="flowline in_fullwidth signsection">
								<img src="" class="signature_image">
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
								<img src="" class="signature_image">
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

						<div class="stamp_space" id="stamp">
							<img src="" class="stamp_image">
						</div>
					</div>
				</div>

				<div data-role="report_setup" class="mt-4">
					<div class="page-section" id="debtors_xtraht">
					</div>
				</div>
			</div>

			<div class="actions flowline spread slide-up" style="animation-delay: 300ms;opacity: 0;animation-fill-mode: forwards;">
				<!-- <button class="mybtn secondary hideme"><i class="fa fa-arrow-left"></i> previous</button> -->
				<button class="mybtn primary" data-runme="pick_step_3" data-step="1" data-msg="rendering Cash report">render Cash report <i class="fa fa-arrow-right"></i></button>
			</div>
		</div>

		<div class="tab slide-up">
			<div id="canvasHolder">
				<div class="spacy-sm w3-center text-muted section-title">
					<span class="h4">Generated document</span>
				</div>

				<div class="canvasguy slide-up flow centroid">
				</div>
				<div>
					<div class="distance-sm w3-center flowline gap-sm center">
						<button class="mybtn secondary" data-runme="download_me" data-me="mainone" data-myid="0"><i class="fa fa-download"></i> download image</button>
						<button class="mybtn primary" data-runme="print_me" data-me="mainone" data-myid="0"><i class="fa fa-print"></i> print image</button>
					</div>
				</div>

				<div class="canvasguy_2 slide-up flow centroid mt-4">
				</div>
				<div>
					<div class="distance-sm w3-center flowline gap-sm center">
						<button class="mybtn secondary" data-runme="download_me" data-me="system" data-myid="1"><i class="fa fa-download"></i> download image</button>
						<button class="mybtn primary" data-runme="print_me" data-me="debtors" data-myid="1"><i class="fa fa-print"></i> print image</button>
					</div>
				</div>

				<div class="canvasguy_3 slide-up flow centroid mt-4">
				</div>
				<div>
					<div class="distance-sm w3-center flowline gap-sm center">
						<button class="mybtn secondary" data-runme="download_me" data-me="debtors" data-myid="2"><i class="fa fa-download"></i> download image</button>
						<button class="mybtn primary" data-runme="print_me" data-me="debtors" data-myid="2"><i class="fa fa-print"></i> print image</button>
					</div>
				</div>

				<div class="distance-sm border panelbg themeround collapser slide-up">
					<a class="text-decoration-none spacy-sm flowline spread centerline" href="#statsguy" data-toggle="collapse" data-target="#statsguy">
						<span class="text-uppercase text-muted font-weight-bold">Capture stats (main cash report)</span>
						<i class="fa fa-chevron-down myicon"></i>
					</a>
					<div class="collapse panelbg themeround spacy-sm" id="statsguy">
					</div>
				</div>

				<div class="distance-sm border panelbg themeround collapser slide-up">
					<a class="text-decoration-none spacy-sm flowline spread centerline" href="#statsguy2" data-toggle="collapse" data-target="#statsguy2">
						<span class="text-uppercase text-muted font-weight-bold">Capture stats (System information)</span>
						<i class="fa fa-chevron-down myicon"></i>
					</a>
					<div class="collapse panelbg themeround spacy-sm" id="statsguy2">
					</div>
				</div>

				<div class="distance-sm border panelbg themeround collapser slide-up">
					<a class="text-decoration-none spacy-sm flowline spread centerline" href="#statsguy3" data-toggle="collapse" data-target="#statsguy3">
						<span class="text-uppercase text-muted font-weight-bold">Capture stats (Debtors extended)</span>
						<i class="fa fa-chevron-down myicon"></i>
					</a>
					<div class="collapse panelbg themeround spacy-sm" id="statsguy3">
					</div>
				</div>
			</div>

			<div class="actions flowline spread slide-up" style="animation-delay: 300ms;opacity: 0;animation-fill-mode: forwards;">
				<button class="mybtn secondary" data-runme="pick_step_2" data-step="0"><i class="fa fa-arrow-left"></i> previous</button>
				<button class="mybtn primary w3-hide" data-runme="pick_step_3" data-step="2" data-msg="generating pdf">finalize document <i class="fa fa-arrow-right"></i></button>
			</div>
		</div>

		<div class="tab slide-up">
			<div class="spacy-sm panelbg themeround mt-4">
				<span class="h4">Cash report Generation successful</span>
				<p>the Cash report has been generated, pick an option below to proceed</p>
				<div class="flowline gap-tn left">
					<button class="mybtn primary sm" data-runme="load_page" data-link="defined:invoices_mgt" data-label="defined:invoices_mgt"><i class="fa fa-file-export"></i> manage Invoices</button>
					<button class="mybtn secondary sm" data-runme="load_page" data-link="defined:samples_mgt" data-label="defined:samples_mgt"><i class="fa fa-plus"></i> generate another</button>
					<button class="mybtn secondary sm" data-runme="download_rendered_img"><i class="fa fa-download"></i> download Cash report (image)</button>
					<button class="mybtn secondary sm" data-runme="printPdf"><i class="fa fa-print"></i> print Cash report</button>
				</div>
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
						<p>click the button to save the Cash report to the database</p>
					</div>
					<form action="defined:save_invoice" class="border themeround spacy-sm" data-afterfx="aftersave_ops" data-onsubmit="upload_stuff" data-blockdefault="yes">
						<div class="notactive">
							<input type="hidden" name="invoice_no">
							<input type="hidden" name="file_slug">
							<input type="hidden" name="client_id">
							<input type="hidden" name="admin_password_mine">
							<input type="hidden" name="bizdata">
							<div class="w3-hide">
								<input type="file" name="fpreview" id="fpreview">
							</div>
							<div class="form-group w3-hide">
								<label class="form-label" for="client_name">Generated PDF</label>
								<input type="file" name="genfile" id="genfile" required>
							</div>
							<div class="form-group">
								<label class="form-label" for="client_name">Generated Cash report</label>
								<input type="file" name="genimage" id="genimage" required>
								<input type="file" name="genimage2" id="genimage2" required>
								<input type="file" name="genimage3" id="genimage3" required>
							</div>
						</div>
						<button class="mybtn primary"><i class="fa fa-save"></i> save Cash report</button>
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
				<span class="h4">Uploading Cash report</span>
				<div>saving to database</div>
				<div class="loader"></div>
			</div>
		</div>
	</div>

	<div class="w3-hide" id="oldactions">
		<div>
			<span class="h4">Actions</span>
			<p>Here are actions you can perform on the document</p>
		</div>
		<div>
			<button class="mybtn primary" data-runme="renderCanvas"><i class="fa fa-image"></i> render Cash report</button>
			<button class="mybtn primary" data-runme="gen_download"><i class="fa fa-image"></i> generate pdf</button>
		</div>
	</div>
</div>

<script>
	window['cashreport_runtime_data'] = {};
	window['killOnLeave'] = [
		'initstuff',
		'showerror',
		'showloader',
		'render_all_data',
		'gen_html',
		'toggle_doctype',
		'renderCanvas',
		'generatePdf',
		'gen_download',
		'setuppdf_invoice',
		'download_invoice_pdf',
		'download_rendered_img',
		'printPdf',
		'generateSerial',
		'pick_step',
		'pick_step_2',
		'pick_step_3',
		'upload_stuff',
		'download_me',
		'print_me',
	];

	/*
		cashreport_runtime_data = {
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
	let spans = [0,86,100];

	const steps = [
		'do_nothing',
		'renderCanvas',
		'gen_download',
	];

	window['runOnAwake'] = () => {
		initstuff();
	}

	window['runOnLeave'] = () => {
		if(window[variableAtlas['cash_report_data']] != undefined){
			delete(window[variableAtlas['cash_report_data']]);
			delete(window[variableAtlas['cash_report_id']]);
			delete(window[variableAtlas['cash_report_type']]);
		};
	}

	// initialisers
		window['initstuff'] = () => {
			alert_dark('loading stuff');
			let outht = '';
			let errshown = false;
			let cr_id = window[variableAtlas['cash_report_id']] ;
			let cr_type = window[variableAtlas['cash_report_type']] ;
			let cr_data = window[variableAtlas['cash_report_data']];

			const step1 = () => {
				if(cr_id == undefined){
					alert_danger('pick a cash report first');
					outht = `
						pick a cash report first
					`;
					showerror(outht);
					return;
				}

				showloader(true);

				setTimeout(() => {
					if(cr_data != undefined){
						step2({sendme: cr_data});
						return;
					}

					console.log('cr_data: ',cr_data);
					window[fetch_bypass](linkAtlas['get_cash_report'],{id: cr_id,type: cr_type},'POST').then(dta => {
						responseHandler(dta, step2, dta);

						if(!dta.success || !dta.result){
							throw new Error(dta.message);
						}
					}).catch(err => {
						outht = `There was an error fetching the Cash Report: <br><b class="${err.result !== undefined ? 'w3-text-orange' : 'w3-text-red'}">${err.message}</b>`;
						showerror(outht);
						console.error(err);
						errshown = true;
					})
				},min_loadtime)
			}

			// validate and save sample data
			const step2 = (d) => {
				cashreport_runtime_data.cash_report_data = d.sendme;
				step3();
			}

			const step3 = (d) => {
				window[fetch_bypass_fyls](linkAtlas['get_bizdata'],{},'POST').then(dta => {
					responseHandler(dta,step4,dta);

					if(!dta.success || !dta.result){
						throw new Error(dta.message);
					}
				}).catch(err => {
					outht = `There was an error fetching business information: <br><b class="w3-text-red">${err.message}</b>`;
					console.error(err);
					showerror(outht);
					errshown = true;
				})
			}

			// get bizdata
			const step4 = (d) => {
				cashreport_runtime_data.bizdata = d.sendme;
				window['bizdata'] = parseBizdata(d.sendme);

				final_step();
			}

			const final_step = () => {
				cashreport_runtime_data.reporttype = cr_type;

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
					<button class="mybtn primary sm" data-runme="load_page" data-link="defined:cash_reports" data-label="defined:cash_reports"><i class="${iconAtlas['cash_reports']}"></i> pick another one</button>
					<button class="mybtn secondary sm" data-runme="initstuff"><i class="fas fa-sync"></i> retry</button>
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
			// alert_dark('rendering header');
			let outht = '';
			let advanced = cashreport_runtime_data.reporttype == 'monthly';
			let crd = cashreport_runtime_data.cash_report_data;
			let crd_typ = cashreport_runtime_data.reporttype;

			// elements
			let holder = document_Holder;
			let reportguy = holder.querySelector('[data-role="report_setup"]#newdesign');
			let datasect = holder.querySelector('[data-role="datasection"]');

			// reportguy.classList.add('w3-red');
			// return;
			let header = reportguy.querySelector('.page-section.header');


			let b_data = bizdata;
			let bizdeets = b_data.business_details;
			let bizimgs = b_data.business_images;

			// render the wanted design
			wanteddesign.innerHTML = '';
			gen_html_wanted(crd,crd_typ,wanteddesign);

			// render the other guys
			// header
			header.innerHTML = `
				<h1 class="w3-center themetxt" style="font-size:2rem;">${bizdeets.biz_name}</h1>
				<div class="flowline" style="gap: 10px !important;">
					<div class="thelogo">
						<img src="${linkAtlas['bizimages']}/${bizimgs.company_logo}" style="width: 102px;">
					</div>
					<div class="flow gap-tn mylist" style="gap: 2px !important;font-size:0.8rem;">
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
						<div style="color: orange;">
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

			// render the cash report info
			let templateht = `
				<div class="page-section invoice_details">
					<h3 class="heading">Client Cash report</h3>
					<div data-subrole="deetsui">
					</div>
				</div>
			`;

			// generate html
			gen_html(crd,crd_typ,datasect);

			// the signature and stamp section
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
				<div id="dateguy" class="w3-text-red">${('<?php echo date("d M Y")?>').toUpperCase()}</div>
			`;
			stamp.innerHTML = outht;

			debtors_xtraht.innerHTML = `<div data-copyme='[data-subrole="extrapage"]'></div>`;
		}

		window['gen_html_wanted'] = (d,range='today',item) => {
			let drd = d;
			let thedate = '';

			if(drd.created_at == undefined){
				thedate = `<b class="themetxt">${range.replace('_',' ')}</b>`;

				if(drd.context !== undefined){
					thedate = `<b class="themetxt">${drd.context}</b>`;
				}
			} else {
				thedate = `<b class="themetxt">${formatDate(new Date(drd.created_at))}</b>`;
			}

			// debt payments subsection
				let samples_outht = `
					<table class="mytable">
						<thead>
							<tr>
								<th>${mekSpan('Total samples today')}</th>
								<th>${mekSpan('Soil')}</th>
								<th>${mekSpan('Rock')}</th>
								<th>${mekSpan('carbon')}</th>
								<th>${mekSpan('solution')}</th>
								<th>${mekSpan('paid tasks')}</th>
								<th>${mekSpan('unpaid tasks')}</th>
								<th>${mekSpan('monthly')}</th>
							</tr>
						</thead>
						<tbody>
				`;

				let s_dta = drd.sample_data;

				samples_outht += `
						<tr>
							<td class="w3-right-align"><strong>${drd.samples_count || 0}</strong></td>
							<td class="w3-right-align"><strong>${s_dta['soil'] || 0}</strong></td>
							<td class="w3-right-align"><strong>${s_dta['rock'] || 0}</strong></td>
							<td class="w3-right-align"><strong>${s_dta['carbon'] || 0}</strong></td>
							<td class="w3-right-align"><strong>${s_dta['solution'] || 0}</strong></td>
							<td class="w3-right-align"><strong>${s_dta['paid_tasks'] || 0}</strong></td>
							<td class="w3-right-align"><strong>${s_dta['unpaid_tasks'] || 0}</strong></td>
							<td class="w3-right-align"><strong>${s_dta['monthly'] || 0}</strong></td>
						</tr>
					</tbody>
				</table>
				`;

			// top finances section
				let toshow_1 = {
					// "total_debitors" : `<b>KES ${formatNumber(drd.total_owed)}</b>`,
					"carry_over" : `<b>KES ${formatNumber(drd.carry_over)}</b>`,
					"income" : `<b>KES ${formatNumber(drd.total_income)}</b>`,
					"cash_from_debtors" : `<b>KES ${formatNumber(drd.cash_from_debtors)}</b>`,
					"total_daily_income" : `<b>KES ${formatNumber(drd.total_daily_cash)}</b>`,
				};
				let finances_outht = `
					<table class="mytable">
						<thead>
							<tr>
								<th colspan="12" class="hed">${mekSpan('Income Report')}</th>
							</tr>
							<tr>
								<th>${mekSpan('Item')}</th>
								<th>${mekSpan('Amount')}</th>
							</tr>
						</thead>
						<tbody>
				`;

				Object.keys(toshow_1).forEach(f => {
					finances_outht += `
							<tr>
								<td>${mekSpan(f.split("_").join(' '))}</td>
								<td>${mekSpan(toshow_1[f])}</td>
							</tr>
					`;
				});

				finances_outht += `
						</tbody>
					</table>
				`;

			// outht setups
				let expenses_outht = '';
				let cash_report_outht = '';
				let debtors_outht = '';
				let debtpay_outht = '';

			// expenses setup
				expenses_outht = `
					<table class="mytable">
						<thead>
							<tr>
								<th colspan="12" class="hed">${mekSpan('Expenses Report')}</th>
							</tr>
							<!${'--'}
							<tr>
								<th>${mekSpan('Item')}</th>
								<th>${mekSpan('Amount')}</th>
							</tr>
							${'--' + '>'}
						</thead>
						<tbody>
				`;

				toshow_1 = drd.spend_data;

				let stuff = {};
				expenditure_categories.forEach(d => {
					stuff[d] = toshow_1 != undefined ? (toshow_1[d] != undefined ? toshow_1[d] : 0) : 0;
				});
				stuff['<b>Total_expenses</b>'] = `<b>${mekSpan(formatNumber(drd.expenditure))}</b>`;

				Object.keys(stuff).forEach(f => {
					expenses_outht += `
							<tr>
								<td>${mekSpan(f.split("_").join(' '))}</td>
								<td>${mekSpan(stuff[f])}</td>
							</tr>
					`;
				});

				if(Object.keys(toshow_1).length == 0 && false){
					expenses_outht += `
						<tr>
							<td colspan="12">${mekSpan('no expenses recorded')}</td>
						</tr>
					`;
				}

				expenses_outht += `
						</tbody>
					</table>
				`;

			// cash report setup
				cash_report_outht = `
					<table class="mytable">
						<thead>
							<tr>
								<th colspan="12" class="hed">${mekSpan('Cash Report')}</th>
							</tr>
							<tr>
								<th>${mekSpan('Item')}</th>
								<th>${mekSpan('Amount')}</th>
							</tr>
						</thead>
						<tbody>
				`;

				toshow_1 = {
					"total_cash" : `<b class="themetxt">KES ${formatNumber(drd.total_cash)}</b>`,
					"expenditure" : `<b class="w3-text-orange">KES ${formatNumber(drd.expenditure)}</b>`,
					"<b>total_balance_available</b>" : `<b class="w3-text-green">KES ${formatNumber(drd.total_balance_forward)}</b>`,
				};

				Object.keys(toshow_1).forEach(f => {
					cash_report_outht += `
							<tr>
								<td>${mekSpan(f.split("_").join(' '))}</td>
								<td>${mekSpan(toshow_1[f])}</td>
							</tr>
					`;
				});

				cash_report_outht += `
						</tbody>
					</table>
				`;



			// debt payments subsection
				debtpay_outht = `
					<table class="mytable">
						<thead>
							<tr>
								<th colspan="12" class="hed">${mekSpan('Debt payments')}</th>
							</tr>
							<tr>
								<th>${mekSpan('Name')}</th>
								<th class="w3-hide">${mekSpan('Contact')}</th>
								<th class="w3-hide">${mekSpan('unpaid invoices')}</th>
								<th>${mekSpan('Total Paid')}</th>
								<th>${mekSpan('Balance')}</th>
							</tr>
						</thead>
						<tbody>
				`;

				if(drd.debt_pay_data !== undefined && drd.debt_pay_data.length > 0){
					let tot_balance = 0;
					drd.debt_pay_data.forEach(f => {
						debtpay_outht += `
							<tr>
								<td><span style="/*font-size: 1.1rem;*/">${f.client_name}</span></td>
								<td class="w3-right-align w3-hide"><strong>${processNumber(f.client_contact)}</strong></td>
								<td class="w3-right-align w3-hide"><strong>${formatNumber(f.invoices_count)}</strong></td>
								<td class="w3-right-align"><strong>KES ${formatNumber(f.payment_amount)}</strong></td>
								<td class="w3-right-align"><strong>KES ${formatNumber(f.credit_balance,2)}</strong></td>
							</tr>
						`;

						tot_balance += Number(f.credit_balance);
					});

					debtpay_outht += `
							<tr>
								<td colspan="1"><b style="font-size: 1.1rem;">Total</b></td>
								<td class="themetxt w3-right-align"><strong>KES ${formatNumber(drd.cash_from_debtors,2)}</strong></td>
								<td class="themetxt w3-right-align"><strong>KES ${formatNumber(tot_balance,2)}</strong></td>
							</tr>
						</tbody>
					</table>
					`;
				} else {
					debtpay_outht += `
							<tr>
								<td colspan="12">
									<span style="font-size: 1.1rem;">
										<i>${drd.created_at == undefined ? 'no debt payments found' : 'no debt payments recorded'}</i>
									</span>
								</td>
							</tr>
						</tbody>
					</table>
					`;
				}

			// debtors subsection
				debtors_outht = `
					<table class="mytable">
						<thead>
							<tr>
								<th colspan="12" class="hed">${mekSpan('Debtors')}</th>
							</tr>
							<tr>
								<th>${mekSpan('Name')}</th>
								<th class="w3-hide">${mekSpan('Contact')}</th>
								<th>${mekSpan('unpaid invoices')}</th>
								<th>${mekSpan('Total Owed')}</th>
							</tr>
						</thead>
						<tbody>
				`;

				if(drd.debtors !== undefined && drd.debtors.length > 0){
					drd.debtors.forEach(f => {
						debtors_outht += `
							<tr>
								<td><span style="/*font-size: 1.1rem;*/">${f.client_name}</span></td>
								<td class="w3-right-align w3-hide"><strong>${processNumber(f.client_contact)}</strong></td>
								<td class="w3-right-align"><strong>${formatNumber(f.invoices_count)}</strong></td>
								<td><strong>KES ${formatNumber(f.credit_balance,2)}</strong></td>
							</tr>
						`;
					});

					debtors_outht += `
							<tr>
								<td colspan="2"><b style="font-size: 1.1rem;">Total</b></td>
								<td><strong class="themetxt">KES ${formatNumber(drd.total_owed,2)}</strong></td>
							</tr>
						</tbody>
					</table>
					`;
				} else {
					debtors_outht += `
							<tr>
								<td colspan="12">
									<span style="font-size: 1.1rem;"><i>${drd.created_at == undefined ? 'no debtors found' : 'no debtors recorded'}</i></span>
								</td>
							</tr>
						</tbody>
					</table>
					`;
				}

			let outht = `
				<div class="w3-center mb-2">
					<h3 class="heading w3-center">JEYMATIAN MINERALS LABORATORY PRODUCTION SHEET [${thedate}]</h3>
				</div>

				<div>
					${samples_outht}
				</div>

				<div class="dynacols">
					<div>
						${finances_outht}
					</div>
					<div>
						${expenses_outht}
					</div>
					<div>
						${cash_report_outht}
					</div>
				<!${'--'}
				</div>
				<div class="dynacols">
				${'--' + '>'}
					<div>
						${debtors_outht}
					</div>

					<div>
						${debtpay_outht}
					</div>
				</div>
			`;

			item.innerHTML = outht;
		};

		window['gen_html'] = (d,range = 'today',item) => {
			// let container = document.querySelector(sel);
			let container = item;
			let drd = d;

			if(drd == undefined){
				alert_danger('invalid `gen_html` function call');
				return;
			}
			if(container == undefined){
				alert_danger('invalid container for `gen_html`');
				return;
			}

			alert_info('rendering...');

			outht = `
				renderin...
			`;

			container.classList.add('printTxt');

			// top finances section
			let xtra = drd.time_difference !== undefined ? {
				// "time_since_last_report" : `${formatNumber(drd.time_difference) || '--'} ${plural('hour',Math.floor(Number(drd.time_difference)))}`,
			} : {};
			let toshow_1 = {
				...xtra,
				"total_owed" : `<b>KES ${formatNumber(drd.total_owed)}</b>`,
				"carry_over" : `<b>KES ${formatNumber(drd.carry_over)}</b>`,
				"total_income" : `<b>KES ${formatNumber(drd.total_income)}</b>`,
				"cash_from_debtors" : `<b>KES ${formatNumber(drd.cash_from_debtors)}</b>`,
				"total_daily_cash" : `<b>KES ${formatNumber(drd.total_daily_cash)}</b>`,
				"total_cash" : `<b class="themetxt">KES ${formatNumber(drd.total_cash)}</b>`,
				"expenditure" : `<b class="w3-text-orange">KES ${formatNumber(drd.expenditure)}</b>`,
				"total_balance_available" : `<b class="w3-text-green">KES ${formatNumber(drd.total_balance_forward)}</b>`,
			};
			let deets_1_outht = '';

			Object.keys(toshow_1).forEach(f => {
				deets_1_outht += `
					<div class="info-row">
						<span class="info-label">${f.split("_").join(' ')}:</span>
						<span class="info-value">${toshow_1[f]}</span>
					</div>
				`;
			});

			// samples subsection
			let toshow_2 = {
				"total_samples_delivered" : formatNumber(drd.samples_count),
				"soil samples" : formatNumber(drd.sample_data['soil']),
				"rock samples" : formatNumber(drd.sample_data['rock']),
				"solution samples" : formatNumber(drd.sample_data['solution']),
				"carbon samples" : formatNumber(drd.sample_data['carbon']),
				"carbon activity samples" : formatNumber(drd.sample_data['carbon activity']),
			};
			let the_samples_outht = '';

			Object.keys(toshow_2).forEach(f => {
				the_samples_outht += `
					<div class="info-row">
						<span class="info-label">${f.split("_").join(' ')}:</span>
						<span class="info-value"><b>${toshow_2[f]}</b></span>
					</div>
				`;
			});


			// cash flow subsection
			let toshow_3 = {
				"income via mpesa" : `<b>KES ${formatNumber(drd.cash_data["income mpesa"])}</b>`,
	            "income via cash" : `<b>KES ${formatNumber(drd.cash_data["income cash"])}</b>`,
	            "debt paid via mpesa" : `<b>KES ${formatNumber(drd.cash_data["debt mpesa"])}</b>`,
	            "debt paid via cash" : `<b>KES ${formatNumber(drd.cash_data["debt cash"])}</b>`,
	            "total mpesa" : `<b>KES ${formatNumber(drd.cash_data["total mpesa"])}</b>`,
	            "total cash" : `<b>KES ${formatNumber(drd.cash_data["total cash"])}</b>`,
			};
			let deets_3_outht = '';

			Object.keys(toshow_3).forEach(f => {
				deets_3_outht += `
					<div class="info-row">
						<span class="info-label">${f.split("_").join(' ')}:</span>
						<span class="info-value">${toshow_3[f]}</span>
					</div>
				`;
			});

			// system usage subsection
			let newxtra = drd.paid_tasks == undefined ? {} : {
				"paid_tasks" : drd.paid_tasks,
				"unpaid_tasks" : drd.unpaid_tasks,
			};
			let toshow_4 = {
				"users_registered" : drd.users_count,
				"clients_registered" : drd.clients_count,
				...newxtra,
				"tasks_created" : drd.tasks_count,
				"samples_delivered" : drd.samples_count,
				"task payments" : drd.total_payments_task_count,
				"invoice payments" : drd.total_payments_invoice_count,
				"reports_generated" : drd.reports_count,
				"invoices_generated" : drd.invoices_count,
				"expense_records entered" : drd.expenses_count,
			};
			let sys_usage_outht = '';

			Object.keys(toshow_4).forEach(f => {
				sys_usage_outht += `
					<div class="info-row">
						<span class="info-label">${f.split("_").join(' ')}:</span>
						<span class="info-value"><b>${toshow_4[f]}</b></span>
					</div>
				`;
			});


			// system usage subsection
				let gen_from_spend_Data = {};
				Object.keys(drd.spend_data).forEach(s => {
					gen_from_spend_Data[s] = drd.spend_data[s];
				});
				let toshow_5 = {
					"Expenditure Entries" : `${formatNumber(drd.expenses_count)}`,
					...gen_from_spend_Data,
					"Total Expenditure" : `${formatNumber(drd.expenditure)}`,
				};
				let expenditure_outht = '';

				Object.keys(toshow_5).forEach(f => {
					expenditure_outht += `
						<div class="info-row">
							<span class="info-label">${f.split("_").join(' ')}:</span>
							<span class="info-value"><b>${toshow_5[f]}</b></span>
						</div>
					`;
				});


			// debt payments subsection
				let debtpay_outht = `
					<table class="mytable">
						<thead>
							<tr>
								<th>${mekSpan('Name')}</th>
								<th>${mekSpan('Contact')}</th>
								<th>${mekSpan('unpaid invoices')}</th>
								<th>${mekSpan('Total Paid')}</th>
								<th>${mekSpan('Balance')}</th>
							</tr>
						</thead>
						<tbody>
				`;

				if(drd.debt_pay_data !== undefined && drd.debt_pay_data.length > 0){
					let tot_balance = 0;
					drd.debt_pay_data.forEach(f => {
						debtpay_outht += `
							<tr>
								<td><span style="/* font-size: 1.1rem; */">${f.client_name}</span></td>
								<td class="w3-right-align"><strong>${processNumber(f.client_contact)}</strong></td>
								<td class="w3-right-align"><strong>${formatNumber(f.invoices_count)}</strong></td>
								<td class="w3-right-align"><strong>KES ${formatNumber(f.payment_amount)}</strong></td>
								<td class="w3-right-align"><strong>KES ${formatNumber(f.credit_balance,2)}</strong></td>
							</tr>
						`;

						tot_balance += Number(f.credit_balance);
					});

					debtpay_outht += `
							<tr>
								<td colspan="3"><b style="font-size: 1.1rem;">Total</b></td>
								<td class="themetxt w3-right-align"><strong>KES ${formatNumber(drd.cash_from_debtors,2)}</strong></td>
								<td class="themetxt w3-right-align"><strong>KES ${formatNumber(tot_balance,2)}</strong></td>
							</tr>
						</tbody>
					</table>
					`;
				} else {
					debtpay_outht += `
							<tr>
								<td colspan="12">
									<span style="font-size: 1.1rem;">
										<i>${drd.created_at == undefined ? 'no debt payments found' : 'no debt payments recorded'}</i>
									</span>
								</td>
							</tr>
						</tbody>
					</table>
					`;
				}

			// debtors subsection
				let debtors_outht = `
					<table class="mytable">
						<thead>
							<tr>
								<th>${mekSpan('Name')}</th>
								<th>${mekSpan('Contact')}</th>
								<th>${mekSpan('unpaid invoices')}</th>
								<th>${mekSpan('Total Owed')}</th>
							</tr>
						</thead>
						<tbody>
				`;

				if(drd.debtors !== undefined && drd.debtors.length > 0){
					drd.debtors.forEach(f => {
						debtors_outht += `
							<tr>
								<td><span style="font-size: 1.1rem;">${f.client_name}</span></td>
								<td class="w3-right-align"><strong>${processNumber(f.client_contact)}</strong></td>
								<td class="w3-right-align"><strong>${formatNumber(f.invoices_count)}</strong></td>
								<td><strong>KES ${formatNumber(f.credit_balance,2)}</strong></td>
							</tr>
						`;
					});

					debtors_outht += `
							<tr>
								<td colspan="3"><b style="font-size: 1.1rem;">Total</b></td>
								<td><strong class="themetxt">KES ${formatNumber(drd.total_owed,2)}</strong></td>
							</tr>
						</tbody>
					</table>
					`;
				} else {
					debtors_outht += `
							<tr>
								<td colspan="12">
									<span style="font-size: 1.1rem;"><i>${drd.created_at == undefined ? 'no debtors found' : 'no debtors recorded'}</i></span>
								</td>
							</tr>
						</tbody>
					</table>
					`;
				}

			// visual hints to show system info
			let thedate = '';
			if(drd.created_at == undefined){
				thedate = `<b class="themetxt">${range.replace('_',' ')}</b>`;

				if(drd.context !== undefined){
					thedate = `<b class="themetxt">${drd.context}</b>`;
				}
			} else {
				thedate = `<b class="themetxt">${formatDate(new Date(drd.created_at))}</b>`;
			}

			let btns = drd.created_at == undefined ? `
				<button class="mybtn primary ${window['is_admin_page'] ? 'is_ghost' : ''}" data-runme="save_daily_report"><i class="fa fa-save"></i> Save Cash Report</button>
			` : `
				<button class="mybtn primary" data-runme="print_daily_report" data-myid="${drd.id}" data-mytype="${drd.rectype || 'daily'}"><i class="fa fa-print"></i> Print Report</button>
			`;
			let report_title = drd.context == undefined ?
			`
				${drd.rectype || 'Daily'} cash report for ${thedate}
			` : `
				${drd.rectype || 'Daily'} cash report [${thedate}]
			`;

			// console.log('samples html: ',the_samples_outht);
			outht = `
				<div class="mb-3">
					<span class="h4">${report_title}</span>
				</div>
				<div class="page-section invoice_details">
					<h3 class="heading w3-hide">Cash Summary</h3>
					<div data-subrole="cash_summary">
						${deets_1_outht}
					</div>
				</div>

				<div class="info_row_guy spread gap-sm">
					<div class="page-section invoice_details">
						<h3 class="heading">Cash channels report</h3>
						<div data-subrole="cash_channels_summary">
							${deets_3_outht}
						</div>
					</div>

					<div class="page-section invoice_details">
						<h3 class="heading">Expenditure report</h3>
						<div data-subrole="expenditure_summary">
							${expenditure_outht}
						</div>
					</div>
				</div>

				<div class="info_row_guy spread gap-sm">
					<div class="page-section invoice_details">
						<h3 class="heading">System Usage Report</h3>
						<div data-subrole="expenditure_summary">
							${sys_usage_outht}
						</div>
					</div>

					<div class="page-section invoice_details">
						<h3 class="heading">Samples Information</h3>
						<div data-subrole="samples_summary">
							${the_samples_outht}
						</div>
					</div>
				</div>

				<div data-subrole="extrapage" class="w3-hide">
					<div class="page-section invoice_details">
						<span class="h4 mb-3">Debtors report for ${thedate}</span>
						<h3 class="heading">Debt payments</h3>
						<p>
							this is a list of all debt payments for the day
						</p>
						<div data-subrole="debt_payments_summary" class="mb-4">
							${debtpay_outht}
						</div>

						<h3 class="heading">Current Debtors</h3>
						<p>
							this is a list of all current debtors who are yet to pay their credit balance
						</p>
						<div data-subrole="debtors_summary">
							${debtors_outht}
						</div>
					</div>
				</div>
			`;

			container.innerHTML = outht;
			refreshUI(300);
		};
		window['toggle_doctype'] = (el) => {
			cashreport_runtime_data.doctype = el.dataset.mytype == 'detailed' ? 'detailed' : 'plain';
			render_all_data();

			let btns = document.querySelectorAll('.doctype_toggler');

			btns.forEach(b => {
				b.classList.remove('primary');
				b.classList.add('secondary');
			})

			el.classList.add('primary');
			el.classList.remove('secondary');
		}

	// operations
		window['renderCanvas'] = async () => {
			let els = document.querySelectorAll('#document_Holder>[data-role="report_setup"]');
			let el1 = els[0];
			let el2 = els[1];
			let el3 = els[2];

			outguy = document.querySelector('#canvasHolder>.canvasguy');
			outguy2 = document.querySelector('#canvasHolder>.canvasguy_2');
			outguy3 = document.querySelector('#canvasHolder>.canvasguy_3');

			statsDiv = document.querySelector('#statsguy');
			statsDiv2 = document.querySelector('#statsguy2');
			statsDiv3 = document.querySelector('#statsguy3');

			if(el1 == undefined){alert_danger('main data panel not found');return;}
			if(el2 == undefined){alert_danger('detors panel not found');return;}
			if(el3 == undefined){alert_danger('detors panel not found');return;}

			if(outguy == undefined){alert_danger('main output holder not found');return;}
			if(outguy2 == undefined){alert_danger('detors output holder not found');return;}
			if(outguy3 == undefined){alert_danger('detors output holder not found');return;}

			if(genimage == undefined){alert_danger('main image holder not found');return;}
			if(genimage2 == undefined){alert_danger('debtors image holder not found');return;}
			if(genimage3 == undefined){alert_danger('debtors image holder not found');return;}


			alert_dark('rendering images, please wait...',22);

			const options = {
				allowTaint: true,
				backgroundColor: null,
				logging: false,
				scale: 2,
				useCORS: false
			};

			delete(cashreport_runtime_data.canvases);

			let mycanvases = [];
			const renderme = async (guy,output,stats,input,saveprop = 'savedcanvas',filename=`gen_img_${mekRandomString(3)}`) => {
				// return;
				// render the main one
				output.innerHTML = 'rendering, please wait';
				stats.innerHTML = 'rendering, please wait';

				const startTime = performance.now();
				const canvas = await html2canvas(guy, options);
				const endtime = performance.now();
				const duration = (endtime - startTime).toFixed(2);

				output.innerHTML = '';
				output.appendChild(canvas);

				let canva = {
					'mycanvas' : canvas,
				};

				// /*
				canva.mycanvas.toBlob((blob) => {
					const fyl = new File([blob],filename,{type: 'image/png'});
					let dtr = new DataTransfer();
					dtr.items.add(fyl);
					input.files = dtr.files;
				});
				// */

				let dur = duration + 400;
				stats.innerHTML = `
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

				// mycanvases.push(canva);
				// console.log('adding to mycanvases');
				return canva;
			}

			// outguy.classList.add('w3-hide');
			// loader_render.classList.remove('w3-hide');

			// setTimeout(async () => {
				try{
					console.log('canvases before: ', mycanvases);

					const [canvas1, canvas2, canvas3] = await Promise.all([
						renderme(el1,outguy,statsDiv,genimage,'saved_image_main'),
						renderme(el2,outguy2,statsDiv2,genimage2,'saved_image_system'),
						renderme(el3,outguy3,statsDiv3,genimage3,'saved_image_debtors'),
					]);

					mycanvases = [
						canvas1,
						canvas2,
						canvas3,
					];

					cashreport_runtime_data.canvases = mycanvases;
					clear_alerts();
					alert_info('all images rendered successfully');
				} catch (error) {
					output.innerHTML = `<div class="empty-state" style="color: #e53e3e;">Error: ${error.message}</div>`;
					console.error('Capture failed:', error);
				}
			// },400);
		}

		window['generatePdf'] = async () => {
			if(cashreport_runtime_data.renderedCanvas_main == undefined){
				alert_danger('no document has been rendered yet');
				return;
			}

			let rcv = cashreport_runtime_data.renderedCanvas_main;
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
				if(cashreport_runtime_data.renderedCanvas_main == undefined){
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

				// create a render of the first page to create a hash from
				setuppdf_invoice(pdf,'fpreview').then((img) => {
					cashreport_runtime_data.filemade = true;
					cashreport_runtime_data.medFile = pdf;

					// Download the PDF
					// pdf.save('gen_doc.pdf');
					// alert_danger('PDF downloaded successfully!', 'success');
				});
			} catch (error) {
				console.error('Error:', error);
				alert_danger('Error generating PDF: ' + error.message);
			}
		}
		window['setuppdf_invoice'] = async (pdfFile,inputId = 'fileInput') => {
		    try {
		    	return null;
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

		window['download_invoice_pdf'] = () => {
			return;
			if(cashreport_runtime_data.medFile == undefined){
				alert_danger('no pdf has been generated yet');
				return;
			}

			if(cashreport_runtime_data.aggregates == undefined){
				alert_danger('tasks data missing, refresh the page to fix',4);
				return;
			}

			cashreport_runtime_data.medFile.save(`[${cashreport_runtime_data.aggregates.invoice_id}] generated_invoice.pdf`);

			alert_info('downloading generated pdf');
		}
		window['download_rendered_img'] = () => {
			const invdata = {...cashreport_runtime_data};
			const canvas = cashreport_runtime_data.renderedCanvas_main;

			// Create a new window for printing
			const printWindow = window.open('', '_blank');
			const dataUrl = canvas.toDataURL();

			let a = document.createElement('a');
			a.href = dataUrl;
			a.download = `${encodeURIComponent(formatDate(new Date()).replaceAll('/',''))} generated cash report.png`;
			document.body.appendChild(a);

			a.click();
			document.body.removeChild(a);
		}
		window['printPdf'] = () => {
			// Get the canvas element
			// const canvasId = 'mycanvas';
			// const canvas = document.getElementById(canvasId);
			const canvas = cashreport_runtime_data.renderedCanvas_main;

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
						<img src="${dataUrl}" onload="printme()" />
						<script>const printme=()=>{setTimeout(()=>{window.print(); window.close();},1200)};<\/script>
					</body>
				</html>
			`);

			printWindow.document.close();
		}
		window['generateSerial'] = (b) => {
			// let serial = `${b.sample_no}_${b.reports_count}`;
			// cashreport_runtime_data.invoice_no = cashreport_runtime_data.aggregates.invoices_id;
			return '--xx--';
		}

		window['download_me'] = (el) => {
			// alert_dark(`im doing it for ${el.dataset.me}`);
			let me = el.dataset.me;
			let c_id = Number(el.dataset.myid);
			let canva = cashreport_runtime_data.canvases[c_id];
			let drd = cashreport_runtime_data.cash_report_data;

			let filenames = {
				'debtors' : `[${(new Date()).getTime()}] ${drd.context == undefined ? '' : '(' + drd.context + ') - '} debtors_report.png`,
				'system' : `[${(new Date()).getTime()}] ${drd.context == undefined ? '' : '(' + drd.context + ') - '} system_report.png`,
				'mainone' : `[${(new Date()).getTime()}] ${drd.context == undefined ? '' : '(' + drd.context + ') - '} cash_report.png`,
			}

			if(canva == undefined){
				alert_danger('invalid canvas, render the document again')
				return;
			}
			const imageDataUrl = canva.mycanvas.toDataURL('image/png');
			let a = document.createElement('a');
			a.href = imageDataUrl;
			a.download = filenames[me];
			document.body.appendChild(a);
			a.click();
			document.body.removeChild(a);
		};
		window['print_me'] = (el) => {
			// alert_dark(`im doing it for ${el.dataset.me}`);
			let c_id = Number(el.dataset.myid);
			let canva = cashreport_runtime_data.canvases[c_id];

			if(canva == undefined){
				alert_danger('invalid canvas, render the document again')
				return;
			}

			const imageDataUrl = canva.mycanvas.toDataURL('image/png');

			// Open in new tab
			let printtxt = `
				onload="printme()"
			`;
			const newTab = window.open();
			newTab.document.write(`
				<html>
					<head>
						<title>Printed Element</title>
						<style>
							body { margin: 0;}
							img { max-width: 100%; height: auto;}
						</style>
					</head>
					<body>
						<img src="${imageDataUrl}" alt="Captured element" ${printtxt}/>
						<script>const printme=()=>{setTimeout(()=>{window.print();},600);}<\/script>
					</body>
				</html>
			`);
			newTab.document.close();
		};

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

			// if(!skipchecks){
				if(cashreport_runtime_data.canvases == undefined && n > 0){
					alert_danger('render a Cash report first');
					return;
				}
				if(cashreport_runtime_data.canvases.length < 2 && n > 0){
					alert_danger('render a Cash report first');
					return;
				}
				if(cashreport_runtime_data.filemade == undefined && n > 1){
					alert_danger('finalize the document first');
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
				alert_info('uploading Cash report...');
				el.invoice_no.value = cashreport_runtime_data.aggregates.invoice_id;
				el.file_slug.value = cashreport_runtime_data.file_slug;
				el.client_id.value = cashreport_runtime_data.clientData.id;
				el.admin_password_mine.value = d.admin_password;
				el.bizdata.value = JSON.stringify(bizdata);

				if(cashreport_runtime_data.invoice_id !== undefined){
					el.action = "defined:update_invoice_image";
					el.invoice_no.value = cashreport_runtime_data.invoice_id;
				} else {
					el.action = "defined:save_invoice";
				}

				pick_step(3);

				sendform(el,step3,step2b);
			}

			const step2b = (d) => {
				pick_step(2);
				alert_danger('there was an error uploading the Cash report');
			}

			const step3 = (d) => {
				pick_step(4);
				// confirmAction(mekHeading('Cash report generated successfully','h4'),'the Cash report has been generated successfully',() => {});
				alert_success('Cash report uploaded successfully');
			}

			const step1 = () => {
				awaitConfirmPass(step2);
			}

			step1();
		}
</script>
