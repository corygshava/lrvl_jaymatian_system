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

		h3.heading{
			text-align: left;
			text-transform: uppercase;
			font-size: 1.2rem;
		}

		.mylist b {width: 50px;display: inline-block;}
		/*.smallerCells{font-size: 0.9rem;}*/
		/*.smallerCells td{font-size: 0.9rem;padding: 1px 3px;}*/

		tr td{padding: 1px 3px !important;}
		tr td>*{transform: translateY(-7px);}
</style>

<div class="container-fluid">
	<div class="page-header">
		<div class="page-title">
			<span class="h4">Generate Invoice</span>
			<p>Here you can generate an invoice for a client with a <b>credit balance</b></p>
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

				<!-- <span class="h4">Invoice Type</span> -->
				<div class="flowline center gap-sm in_fullwidth distance-sm">
					<button class="mybtn secondary sm doctype_toggler" data-runme="toggle_doctype" data-mytype="detailed">detailed</button>
					<button class="mybtn primary sm doctype_toggler" data-runme="toggle_doctype" data-mytype="plain">plain</button>
				</div>
			</div>

			<div id="document_Holder" class="flow centroid">
				<div data-role="report_setup">
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

					<div class="page-section invoice_details">
						<h3 class="heading">Client Invoice</h3>
						<div data-subrole="deetsui">
						</div>
					</div>

					<div class="page-section results_area">
						<!-- <h3 class="heading w3-hide">Analytical Invoice</h3> -->
						<div>
							<table class="mytable" style="width: 80%;">
								<thead>
									<tr class="rslts_headings">
										<th style="width: 40px;"><span></span></th>
										<th style="width: 160px;"><span>date created</span></th>
										<th style="width: 400px;"><span>Task No</span></th>
										<!-- <th style="width: 400px;"><span>Sample type</span></th> -->
										<th style="width: 190px;"><span>Samples delivered</span></th>
										<th style="width: 90px;"><span>balance</span></th>
									</tr>
								</thead>
								<tbody id="resultsguy"></tbody>
							</table>
						</div>

						<div class="w3-display-topright">
							<img src="" id="myqrcodeium" onload="img_qr_loader.classList.add('w3-hide')">
							<i id="img_qr_loader">Generating QR code, Please wait</i>
						</div>
					</div>

					<div class="page-section invoice_summary">
						<div data-subrole="deetsui">
						</div>
					</div>

					<div class="page-section">
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
			</div>

			<div class="actions flowline spread slide-up" style="animation-delay: 300ms;opacity: 0;animation-fill-mode: forwards;">
				<button class="mybtn secondary hideme"><i class="fa fa-arrow-left"></i> previous</button>
				<button class="mybtn primary" data-runme="pick_step_3" data-step="1" data-msg="rendering Invoice">render Invoice <i class="fa fa-arrow-right"></i></button>
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
				<button class="mybtn primary" data-runme="pick_step_3" data-step="2" data-msg="generating pdf">finalize document <i class="fa fa-arrow-right"></i></button>
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
						<p>click the button to save the Invoice to the database</p>
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
								<label class="form-label" for="client_name">Generated PDF</label>
								<input type="file" name="genimage" id="genimage" required>
							</div>
						</div>
						<button class="mybtn primary"><i class="fa fa-save"></i> save Invoice</button>
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
				<span class="h4">Uploading Invoice</span>
				<div>saving to database</div>
				<div class="loader"></div>
			</div>
		</div>

		<div class="tab slide-up">
			<div class="spacy-sm panelbg themeround mt-4">
				<span class="h4">Invoice Generation successful</span>
				<p>the Invoice has been generated, pick an option below to proceed</p>
				<div class="flowline gap-tn left">
					<button class="mybtn primary sm" data-runme="load_page" data-link="defined:invoices_mgt" data-label="defined:invoices_mgt"><i class="fa fa-file-export"></i> manage Invoices</button>
					<button class="mybtn secondary sm" data-runme="load_page" data-link="defined:samples_mgt" data-label="defined:samples_mgt"><i class="fa fa-plus"></i> generate another</button>
					<button class="mybtn secondary sm" data-runme="download_invoice_pdf"><i class="fa fa-download"></i> download Invoice (pdf)</button>
					<button class="mybtn secondary sm" data-runme="download_invoice_img"><i class="fa fa-download"></i> download Invoice (image)</button>
					<button class="mybtn secondary sm" data-runme="printPdf"><i class="fa fa-print"></i> print Invoice</button>
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
			<button class="mybtn primary" data-runme="renderCanvas"><i class="fa fa-image"></i> render Invoice</button>
			<button class="mybtn primary" data-runme="gen_download"><i class="fa fa-image"></i> generate pdf</button>
		</div>
	</div>
</div>

<script>
	window['invoice_runtime_data'] = {};
	window['killOnLeave'] = [
		'initstuff',
		'showerror',
		'showloader',
		'render_all_data',
		'processNumber',
		'renderCanvas',
		'generatePdf',
		'gen_download',
		'download_invoice_pdf',
		'printPdf',
		'generateSerial',
		'pick_step',
		'pick_step_2',
		'pick_step_3',
		'upload_stuff',
	];

	/*
		invoice_runtime_data = {
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
		let c_id = window[variableAtlas['client_id']];

		const step1 = () => {
			if(c_id == undefined){
				alert_danger('pick a client first');
				outht = `
					pick a client first
				`;
				showerror(outht);
				return;
			}

			showloader(true);

			setTimeout(() => {
				window[fetch_bypass](linkAtlas['get_client'],{id: c_id},'POST').then(dta => {
					responseHandler(dta, step2, dta);

					if(!dta.success || !dta.result){
						throw new Error(dta.message);
					}
				}).catch(err => {
					outht = `There was an error fetching Client information: <br><b class="${err.result !== undefined ? 'w3-text-orange' : 'w3-text-red'}">${err.message}</b>`;
					showerror(outht);
					errshown = true;
				})
			},min_loadtime)
		}

		// validate and save sample data
		const step2 = (d) => {
			// alert
			// return;
			let inv_id = window[variableAtlas['invoice_id']];

			if(d.sendme.credit_balance <= 0 && inv_id === undefined){
				throw new Error('Client has no credit balance');
			}

			invoice_runtime_data.clientData = d.sendme;

			let querycall = inv_id != undefined ?
				window[fetch_bypass](linkAtlas['get_specific_invoice_data'],{invoice_id: inv_id, client_id: c_id},'POST')
				:
				window[fetch_bypass](linkAtlas['get_invoice_data'],{client_id: c_id},'POST')
			;

			if(inv_id !== undefined){
				invoice_runtime_data.invoice_id = inv_id;
				delete(window[variableAtlas['invoice_id']]);
			}

			querycall.then(dta => {
				responseHandler(dta,step3,dta);

				if(!dta.success || !dta.result){
					throw new Error(dta.message);
				}
			}).catch(err => {
				outht = `There was an error fetching client task information: <br><b class="w3-text-red">${err.message}</b>`;
				showerror(outht);
				errshown = true;
			})
		}

		const step3 = (d) => {
			invoice_runtime_data.incomplete = d.sendme;
			invoice_runtime_data.invoices = d.xtra.invoices_count || 0;

			// alert_dark(`${d.sendme.length} found`);

			if(d.sendme.length == 0){
				outht = `There was an error fetching business information: <br><b class="w3-text-red">there are no unpaid tasks for this client</b>`;
				showerror(outht);
				return;
			}

			// infer samples from fetched data
			let smp = [];

			d.sendme.forEach((s,id) => {
				let ss = s.samples;
				let len = ss.length;

				ss.forEach((sm, n) => {
					let tmp = {...sm};
					tmp.task_no = s.task_no;
					tmp.isbig = n == 0;
					tmp.mycount = len;
					tmp.balance = s.balance;

					smp.push(tmp);
				});
			})

			invoice_runtime_data.samples = smp;

			window[fetch_bypass_fyls](linkAtlas['get_bizdata'],{},'POST').then(dta => {
				responseHandler(dta,step4,dta);

				if(!dta.success || !dta.result){
					throw new Error(dta.message);
				}
			}).catch(err => {
				outht = `There was an error fetching business information: <br><b class="w3-text-red">${err.message}</b>`;
				showerror(outht);
				errshown = true;
			})
		}

		// get bizdata
		const step4 = (d) => {
			invoice_runtime_data.bizdata = d.sendme;
			window['bizdata'] = parseBizdata(d.sendme);

			final_step();
			// skip to prevent failure when internet is down
			// return;

			step5().catch(err => {
				alert_danger('QR code generation error: ' + err.message);
				console.error(err);
				showerror(`error generating QR code: ${err.message}`);
			});
		}

		const step5 = async (d) => {
			// assign image src
			invoice_runtime_data.file_slug = `_${invoice_runtime_data.invoices}_${mekRandomString(7).toUpperCase()}`;
			img_qr_loader.classList.remove('w3-hide');
			let pagelink = [...window.location.href.split('/').map((l,id,arr) => {return id == (arr.length - 1) ? '' : l})].join('/');
			let filelink = `${pagelink}media/invoices/img/${invoice_runtime_data.file_slug}`;

			invoice_runtime_data.file_link = filelink;

			let encodedText = encodeURIComponent(filelink);
			let imglink = `https://quickchart.io/qr?text=${encodedText}&size=100`;

			let meimg = await fetch(`https://quickchart.io/qr?text=${encodedText}&size=100`);
			let blb = await meimg.blob();
			let objUrl = URL.createObjectURL(blb);
			myqrcodeium.src = objUrl;

			final_step();
		}

		const final_step = () => {
			// invoice_runtime_data.file_slug = `_${invoice_runtime_data.invoices}_${mekRandomString(7).toUpperCase()}`;
			invoice_runtime_data.doctype = 'plain';

			showloader(false);
			generateAggregates();
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
				<button class="mybtn primary sm" data-runme="load_page" data-link="defined:view_clients" data-label="defined:view_clients"><i class="${iconAtlas['clients']}"></i> pick another client</button>
				<button class="mybtn secondary sm" data-runme="initstuff" data-link="defined:gen_report" data-label="defined:gen_report"><i class="fas fa-sync"></i> retry</button>
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
	window['generateAggregates'] = () => {
		let agg = {};
		let _dta = invoice_runtime_data.incomplete;

		let total = 0;
		let tasks = _dta.length;
		let invs = invoice_runtime_data.invoices + 1;
		let inv = invs.toString().padStart(4,'0');

		_dta.forEach(d => {
			total += Number(d.balance);
		})

		agg = {
			total: total.toFixed(2),
			tasks: tasks,
			invoice_no: inv,
			invoice_id: `INV_${inv}`,
		};

		invoice_runtime_data.aggregates = agg;
	}
	window['render_all_data'] = () => {
		// alert_dark('rendering header');

		let outht = '';
		let advanced = invoice_runtime_data.doctype == 'detailed';

		// elements
		let holder = document_Holder;
		let reportguy = holder.querySelector('[data-role="report_setup"]');
		let header = reportguy.querySelector('.page-section.header');
		let invoice_details = holder.querySelector('.page-section.invoice_details>[data-subrole="deetsui"]');
		let invoice_summary = holder.querySelector('.page-section.invoice_summary>[data-subrole="deetsui"]');
		let invTableHeadings = holder.querySelector('.rslts_headings');

		let b_data = bizdata;
		let bizdeets = b_data.business_details;
		let bizimgs = b_data.business_images;

		let c_data = invoice_runtime_data.clientData;
		let t_data = invoice_runtime_data.incomplete;
		let s_data = invoice_runtime_data.samples;
		let agg = invoice_runtime_data.aggregates;

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

		// the second section (aka the creditsguy)
		invoice_details.style.textTransform = "uppercase";
		// invoice_details.classList.add('smallerCells');
		// invoice_details.innerHTML = `damn, thats awesome`;

		invoice_details.innerHTML = `
			<div class="row">
				<div class="col-md-8">
					<div>
						<strong>Invoice to</strong>
					</div>
					<div>
						<b>Name: </b> <span>${c_data.client_name}</span>
					</div>
					<div>
						<b>Phone: </b> <span>${c_data.client_contact}</span>
					</div>
				</div>
				<div class="col-md-4">
					<div>
						<strong>Invoice details</strong>
					</div>
					<div>
						<b>Invoice No: </b> <span>INV_${agg.invoice_no}</span>
					</div>
					<div>
						<b>Date: </b> <span>${formatDate0(new Date())}</span>
					</div>
					<div>
						<b>Total Due: </b> <strong>${formatNumber(agg.total)}</strong>
					</div>
				</div>
			</div>
		`;

		// the results
		outht = '';
		resultsguy.innerHTML = '';
		let scounts = [];

		t_data.forEach((r,i) => {
			scounts.push(r);
		})

		console.log('scc: ',scounts);

		if(!advanced){
			invTableHeadings.innerHTML = `
				<th style="width: 40px;"><span></span></th>
				<th style="width: 160px;"><span>date created</span></th>
				<th style="width: 400px;"><span>Task No</span></th>
				<th style="width: 190px;"><span>Samples delivered</span></th>
				<th style="width: 90px;"><span>balance</span></th>
			`;
			t_data.forEach((r,i) => {
				outht += `
					<tr>
						<td><span>${i + 1}</span></td>
						<td>${mekSpan(formatDate0(r.created_at))}</td>
						<td>${mekSpan(r.task_no)}</td>
						<td>${mekSpan(r.sample_count)}</td>
						<td>${mekSpan(formatNumber(r.balance))}</td>
					</tr>
				`;
			});
		} else {
			invTableHeadings.innerHTML = `
				<th style="width: 40px;"><span></span></th>
				<th style="width: 120px;"><span>Task No</span></th>
				<th style="width: 200px;"><span>date delivered</span></th>
				<th style="width: 190px;"><span>Samples type</span></th>
				<th style="width: 190px;"><span>Samples delivered</span></th>
				<th style="width: 90px;"><span>balance</span></th>
			`;
			s_data.forEach((r,i) => {
				/*
					tmp.task_no = s.task_no;
					tmp.isbig = n == 0;
					tmp.mycount = len;
					tmp.balance = s.balance;
				// */
				let bigone = `rowspan="${r.mycount}" class="${r.isbig ? '' : 'w3-hide'}" style="vertical-align: baseline;"`;
				outht += `
					<tr>
						<td><span>${i + 1}</span></td>
						<td ${bigone}>${mekSpan(r.task_no)}</td>
						<td>${mekSpan(formatDate0(r.created_at))}</td>
						<td>${mekSpan(r.sample_type)}</td>
						<td>${mekSpan(r.sample_count)}</td>
						<td ${bigone}>${mekSpan(formatNumber(r.balance))}</td>
					</tr>
				`;
			})
		}

		let inv_results_headings = advanced ? 6 : 5;

		outht += `
			<tr>
				<td colspan="${inv_results_headings - 1}"><b>Total</b></td>
				<td>${mekSpan(formatNumber(agg.total))}</td>
			</tr>
		`;
		resultsguy.innerHTML = outht;

		invoice_summary.innerHTML = `
			<h3 class="heading">Payment Details</h3>
			<div class="row">
				<div class="col-md-8">
					<div>
						<b>Mpesa number: </b> <br><b class="text-success_" style="font-size: 1rem">${processNumber(bizdeets.mpesa)}</b>
					</div>
				</div>
				<div class="col-md-4">
					<div>
						<strong>Total Due</strong>
					</div>
					<div>
						<strong class="h4 themetxt">KES ${formatNumber(agg.total)}</strong>
					</div>
				</div>
			</div>
			<div>
				<h3 class="heading" style="font-size: 1.3rem;">Disclaimer</h3>
				<p>
					please confirm the validity of the invoice by scanning the QR code before proceeding to send money. When sent please forward the <b>invoice number</b> and <b>transaction code</b>
					to our office whatsapp at <b style="display: inline-block;" class="themetxt">${processNumber(bizdeets.phone)}</b>
				</p>
				<p>
					All payments must be made via cash or to our official Mpesa <b class="themetxt">(${processNumber(bizdeets.mpesa)})</b> our office and the transaction code provided is what will be used to verify your payment
				</p>
			</div>
		`;

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
	}
	window['toggle_doctype'] = (el) => {
		invoice_runtime_data.doctype = el.dataset.mytype == 'detailed' ? 'detailed' : 'plain';
		render_all_data();

		let btns = document.querySelectorAll('.doctype_toggler');

		btns.forEach(b => {
			b.classList.remove('primary');
			b.classList.add('secondary');
		})

		el.classList.add('primary');
		el.classList.remove('secondary');
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

				invoice_runtime_data.renderedCanvas = canvas;

				// /*
				invoice_runtime_data.renderedCanvas.toBlob((blob) => {
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

				alert_success('Invoice rendered');
			} catch (error) {
				output.innerHTML = `<div class="empty-state" style="color: #e53e3e;">Error: ${error.message}</div>`;
				console.error('Capture failed:', error);
			}
		// },400);
	}

	window['generatePdf'] = async () => {
		if(invoice_runtime_data.renderedCanvas == undefined){
			alert_danger('no document has been rendered yet');
			return;
		}

		let rcv = invoice_runtime_data.renderedCanvas;
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
			if(invoice_runtime_data.renderedCanvas == undefined){
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
				invoice_runtime_data.filemade = true;
				invoice_runtime_data.medFile = pdf;

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
		if(invoice_runtime_data.medFile == undefined){
			alert_danger('no pdf has been generated yet');
			return;
		}

		if(invoice_runtime_data.aggregates == undefined){
			alert_danger('tasks data missing, refresh the page to fix',4);
			return;
		}

		invoice_runtime_data.medFile.save(`[${invoice_runtime_data.aggregates.invoice_id}] generated_invoice.pdf`);

		alert_info('downloading generated pdf');
	}
	window['download_invoice_img'] = () => {
		const invdata = {...invoice_runtime_data};
		const canvas = invoice_runtime_data.renderedCanvas;

		// Create a new window for printing
		const printWindow = window.open('', '_blank');
		const dataUrl = canvas.toDataURL();

		let a = document.createElement('a');
		a.href = dataUrl;
		a.download = `[INV_${(invdata.invoices).toString().padStart(4,'0')}] generated_invoice`;
		document.body.appendChild(a);

		a.click();
		document.body.removeChild(a);
	}
	window['printPdf'] = () => {
		// Get the canvas element
		// const canvasId = 'mycanvas';
		// const canvas = document.getElementById(canvasId);
		const canvas = invoice_runtime_data.renderedCanvas;

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
		// let serial = `${b.sample_no}_${b.reports_count}`;
		invoice_runtime_data.invoice_no = invoice_runtime_data.aggregates.invoices_id;
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
			if(invoice_runtime_data.renderedCanvas == undefined && n > 0){
				alert_danger('render a Invoice first');
				return;
			}
			if(invoice_runtime_data.filemade == undefined && n > 1){
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
			alert_info('uploading Invoice...');
			el.invoice_no.value = invoice_runtime_data.aggregates.invoice_id;
			el.file_slug.value = invoice_runtime_data.file_slug;
			el.client_id.value = invoice_runtime_data.clientData.id;
			el.admin_password_mine.value = d.admin_password;
			el.bizdata.value = JSON.stringify(bizdata);

			if(invoice_runtime_data.invoice_id !== undefined){
				el.action = "defined:update_invoice_image";
				el.invoice_no.value = invoice_runtime_data.invoice_id;
			} else {
				el.action = "defined:save_invoice";
			}

			pick_step(3);

			sendform(el,step3,step2b);
		}

		const step2b = (d) => {
			pick_step(2);
			alert_danger('there was an error uploading the Invoice');
		}

		const step3 = (d) => {
			pick_step(4);
			// confirmAction(mekHeading('Invoice generated successfully','h4'),'the Invoice has been generated successfully',() => {});
			alert_success('Invoice uploaded successfully');
		}

		const step1 = () => {
			awaitConfirmPass(step2);
		}

		step1();
	}
</script>
