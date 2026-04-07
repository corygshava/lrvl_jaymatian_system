<?php
	/*
	require_once __DIR__.'/piece_task_view_styles.php';
	require_once __DIR__.'/piece_samples_extras.php';
	require_once __DIR__.'/piece_payments_xtras.php';
	require_once __DIR__.'/piece_reports_xtras.php';
	require_once __DIR__.'/piece_simple_table.php';
	require_once __DIR__.'/piece_worksheet_xtras.php';
	// */
?>

{{-- <!-- --}}
@include('pieces.task_view_styles')
@include('pieces.samples_xtras')
@include('pieces.payments_xtras')
@include('pieces.reports_xtras')
@include('pieces.worksheet_xtras')
@include('pieces.codes_simpletables')
{{-- --> --}}

<style>
	.profile_card{
		max-width: 100vw;
		/*overflow: hidden;*/
	}

	/* worksheet styles */
		.worksheet_doc{
			--r_border: #999;
			--w: 824px;
			width: var(--w);
			padding: 48px 32px;
			/*border: 1px solid var(--clr-border);*/
		}
		.worksheet_doc .header{
			border: 1px solid var(--r_border);
		}
		.worksheet_doc .header img{
			/*max-width: calc(var(--w) * 0.25);*/
		}
		.worksheet_doc .header .info{
			width: 100%;
			/*flex: 1 1 400px;*/
		}

		.sample_selects{
			/*overflow: hidden;*/
		}
		.sample_selects .mybar{
			display: flex;
			flex-direction: column;
			gap: 8px;
		}
		.sample_selects .tabnav {
			border: none;
			border-radius: var(--roundness);
			padding: 8px 26px;
			text-align: left;
		}
		.sample_selects .tabnav.active{
			background: var(--themecolor);
			color: #fff;
			font-weight: 600;
		}

		.job_deets{
			display: flex;
			align-items: flex-start;
			flex-wrap: wrap;
			gap: 12px;
		}
		.job_deets>div{
			flex: 0 1 300px;
		}
		.job_deets>.uialert{
			flex: 1 1 100%;
		}

	/* Custom Tab Navigation */
		.custom-tabs {
			background: transparent;
			padding: 12px 20px;
			border: 1px solid transparent;
			display: flex;
			flex-direction: row;
			gap: 12px;
		}
		.custom-tabs .nav-link {
			border: none;
			padding: 12px 24px;
			font-weight: 800;
			font-size: 1.2rem;
			transition: all 0.3s ease;
			background: transparent;
			border-radius: var(--roundness);
			position: relative;
		}
		.custom-tabs .nav-item {
			flex: 1 0 200px;
		}
		.custom-tabs .nav-link i {
			margin-right: 0.5rem;
			font-size: 14px;
		}
		.custom-tabs .nav-link:hover {
			color: var(--themecolor);
			background: rgba(102, 126, 234, 0.05);
		}
		.custom-tabs .nav-link.active {
			color: white;
			background: var(--themecolor);
		}

	[data-openthis="taskinfo"]{
		/*display: none !important;*/
		opacity: 0.3;
		pointer-events: none;
	}
</style>

<div class="container-fluid">
	<div class="page-header">
		<div class="page-title">
			<span class="h4">Task Information</span>
			<p>Here you can view all information regarding a task. from <b>samples</b>, to <b>payments</b> and to <b>reports</b></p>
		</div>
		<div class="header-actions">
			<button class="mybtn secondary" data-runme="refresh_data">
				<i class="fas fa-sync-alt"></i>
				Refresh
			</button>
		</div>
	</div>

	<div class="profile_card themeround panelbg w3-hide slide-up" id="task_brief">
		<div class="card-header panelbg themegrad flowline overflow left centerline themeround w3-display-container" data-subrole="taskpanel">
		</div>

		<!-- task brief -->
		<div class="flowline overflow w3-center stats" data-subrole="stats">
		</div>

		<div class="flowline overflow w3-center stats border-top distance-sm w3-padding-16" data-subrole="xtra_stats">
		</div>
	</div>

	<div id="loaderInterface" class="spacy-md flow centroid gap-sm">
		<span id="load_txt">loading task information</span>
		<div class="loader"></div>
	</div>

	<div id="err_box" class="w3-hide slide-up">
		<div class="spacy-lg w3-center">
			<span class="h3" id="err_hed">Info</span>
			<p id="error_par">loading task record ...</p>
		</div>
	</div>

	<div id="info_stuff" class="w3-hide flow centroid">
		<ul class="nav nav-tabs custom-tabs" id="myTabs" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" id="worksheet-tab" data-toggle="tab" href="#worksheet" role="tab">
					<i data-myicon="worksheets"></i> Worksheets
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="samples-tab" data-toggle="tab" href="#samples" role="tab">
					<i data-myicon="Samples"></i> Samples
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="payments-tab" data-toggle="tab" href="#payments" role="tab">
					<i data-myicon="Payments"></i> Payments
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="reports-tab" data-toggle="tab" href="#reports" role="tab">
					<i data-myicon="Reports"></i> Reports
				</a>
			</li>
		</ul>

		<div class="tab-content border in_fullwidth panelbg themeround custom-tab-content container-fluid" id="myTabContent">
			<div class="tab-pane fade show active" id="worksheet" role="tabpanel">
				<div class="tab-inner-content spacy-sm w3-display-container">
					<div>
						<h4>Task Worksheets</h4>
						<p>worksheets for each sample involved in the task</p>
						<div class="spacy-sm w3-display-topright" id="doc_ops">
							<button class="mybtn primary" data-runme="print_worksheet" data-subrole="add_pay_btn"><i class="fa fa-print"></i> print current worksheet</button>
						</div>
					</div>

					<div class="flowline left overflow">
						<div class="sample_selects themeround" style="width: 250px;flex: 1 0 120px;">
							<div class="mybar">
								<button class="tabnav active" data-runme="change_sample" data-myid="0">
									Sample 1 <span class="sample-type-badge type-soil">soil</span>
								</button>
							</div>
						</div>
						<div style="width:100%;flex: 1 0 850px;" data-role="worksheet_tabs">
							<div class="slide-up" data-role="worksheet_edidor" data-subrole="mytab">
								<div class="flowline spread mb-4">
									<button class="mybtn primary sm is_ghost" data-runme="show_sheet" data-tabid="0">preview print</button>
									<button class="mybtn primary sm" data-runme="show_sheet" data-tabid="1">preview print <i class="fa fa-chevron-right"></i></button>
								</div>

								<div class="border spacy-sm themeround">
									<div class="job_deets" data-subrole="job_deets">
									</div>

									<div class="table-responsive distance-sm themeround border" data-subrole="thetable">
										<form id="worksheetform" action="defined:save_worksheet" data-onsubmit="save_worksheet_sampleids" data-callback="worksheet_update_callback" data-blockdefault="yes">
											<table class="table table-hover mb-0 table-modern">
												<thead data-subrole="tableHead">
												</thead>
												<tbody data-subrole="tableBody">
												</tbody>
											</table>
										</form>
									</div>
									<div class="distance-sm">
										<button class="mybtn primary" data-submitme="#worksheetform"><i class="fa fa-save"></i> save worksheet</button>
									</div>
								</div>
							</div>
							<div class="slide-up w3-hide" data-role="worksheet_display" data-subrole="mytab">
								<div class="flowline spread mb-4">
									<button class="mybtn primary sm" data-runme="show_sheet" data-tabid="0"><i class="fa fa-chevron-left"></i> edit worksheet</button>
									<button class="mybtn primary sm is_ghost" data-runme="show_sheet" data-tabid="1">preview print <i class="fa fa-chevron-right"></i></button>
								</div>
								<div class="table-responsive themeround border">
									<div data-role="document_Holder" class="flow centroid">
										<div data-role="document" class="worksheet_doc">
											<div class="header flowline left gap-sm w3-center mb-4">
												<div class="logo">
													<img src="./media/bizimg/[pkKZt]_company_logo.png" style="max-width: 120px;">
												</div>
												<div class="info">
													<h2>KENYA BRANCH (MIGORI)</h2>
													<div>
														<b>Location: </b>
														<strong>Muhuru road</strong>
													</div>
												</div>
											</div>

											<div class="job_stats border mb-4">
												<table class="mytable ">
													<tr>
														<td>
															<b>Job No: </b>
															<span>LKDNMWDKN</span>
														</td>
														<td>
															<b>Client Name: </b>
															<span>Drakis makali</span>
														</td>
													</tr>
													<tr>
														<td>
															<b>Sample Type: </b>
															<span>sandy</span>
														</td>
														<td>
															<b>Phone No: </b>
															<span>+254800999000</span>
														</td>
													</tr>
													<tr>
														<td>
															<b>Payment Status: </b>
															<span>not paid</span>
														</td>
														<td>
															<b>Recieved Date and time: </b>
															<span>25/02/2026</span>
														</td>
													</tr>
													<tr>
														<td style="opacity: 0;">
															<b>Payment Status: </b>
															<span>not paid</span>
														</td>
														<td>
															<b>Number Of Samples: </b>
															<span>0</span>
														</td>
													</tr>
												</table>
											</div>

											<div class="sample_stats border">
												<table class="mytable ">
													<thead>
														<tr>
															<th style="width:35px;">sn</th>
															<th style="width:60px;">beaker</th>
															<th>Sample ids</th>
															<th style="width:78px;">Weight</th>
															<th style="width:78px;">AU reading</th>
															<th style="width:78px;">CU reading</th>
															<th style="width:96px;">Remark</th>
														</tr>
													</thead>
													<tbody class="thesamples">
														<tr>
															<td>1</td>
															<td></td>
															<td>sample 1 (Rock)</td>
															<td></td>
															<td></td>
															<td></td>
															<td></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="tab-pane fade" id="samples" role="tabpanel">
				<div class="tab-inner-content spacy-sm">
					<h4>Task Samples</h4>
					<p>here are the samples brought in with this task</p>

					<div class="table-responsive themeround border">
						<table class="table table-hover mb-0 table-modern" data-role="samples_table">
							<thead data-subrole="tableHead" class="thead-light">
							</thead>
							<tbody data-subrole="tableBody">
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<!-- Details Tab -->
			<div class="tab-pane fade" id="payments" role="tabpanel">
				<div class="tab-inner-content spacy-sm w3-display-container">
					<h4>Task Payments</h4>
					<p>here are the payments made for this task</p>

					<div class="spacy-sm w3-display-topright" id="payment_ops">
						<button class="mybtn primary" data-runme="add_payment" data-subrole="add_pay_btn"><i class="fa fa-plus"></i> add payment</button>
					</div>

					<div class="table-responsive themeround border">
						<table class="table table-hover mb-0 table-modern" data-role="payments_table">
							<thead data-subrole="tableHead" class="thead-light">
							</thead>
							<tbody data-subrole="tableBody">
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<!-- History Tab -->
			<div class="tab-pane fade" id="reports" role="tabpanel">
				<div class="tab-inner-content spacy-sm">
					<h4>Generated reports</h4>
					<p>here are the generated reports for this task</p>

					<div class="table-responsive themeround border">
						<table class="table table-hover mb-0 table-modern" data-role="reports_table">
							<thead data-subrole="tableHead" class="thead-light">
							</thead>
							<tbody data-subrole="tableBody">
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div data-role="modals">
	<button class="mybtn primary w3-hide" id="add_note_trig" data-toggle="modal" data-target="#add_note_modal">
		<i class="fas fa-plus"></i>
		Add payment
	</button>

	<div class="modal fade" id="add_note_modal" tabindex="-1" role="dialog" aria-labelledby="add_edit_title" aria-hidden="true" style="z-index: 102;">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content themeround borderless">
				<div class="modal-header">
					<span class="h3 modal-title" id="add_edit_title">Add Note</span>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<form id="add_note_form" action="defined:add_task_note" data-onsubmit="sendform" data-blockdefault="yes" data-callback="add_note_form_afterfx" method="post">
						<input type="hidden" name="taskid" value="">
						<div class="input-group-custom">
							<div class="form-group">
								<label class="form-label" for="note">your Note</label>
								<textarea class="form-control-custom" id="note" name="note" rows="4" placeholder="enter the task notes here" required>
								</textarea>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="mybtn secondary" data-dismiss="modal">Cancel</button>
					<button type="button" class="mybtn primary" data-submitme="#add_note_form">Add note</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	let task = {};
	let c_pld = {};
	let cur_sample_id = 0;

	let task_payment_state_atlas = payment_state_atlas;

	window['task_info_data'] = {};
	window['killOnLeave'] = [];

	window['runOnAwake'] = () => {
		// delete(window['refreshData']);

		setTimeout(() => {
			init_task();
			// alert_info('runing autoload');
			// refreshUI(300);
		},400);
	}

	window['runOnLeave'] = () => {
		// alert_danger('leaving the pages');
		killghost('showthis');
		killghost('task_info_data');
	}

	// initialisers
	window['init_task'] = () => {
		let t_id = window[variableAtlas['task_id']];
		let nextact = window[variableAtlas['t_i_act']];
		let nextact_vars = window[variableAtlas['t_i_args']];

		c_pld = {task_id: t_id};

		// if(nextact !== undefined){alert_dark(`afterrun: ${nextact}`,12);}
		console.log(nextact);

		// prevents issues during reloads
		delete(window[variableAtlas['t_i_act']]);
		delete(window[variableAtlas['t_i_args']]);

		// get task
		const step1 = () => {
			window[fetch_bypass](linkAtlas['get_task'],{id: t_id},'POST').then((d) => {
				responseHandler(d,step2,d);

				if(d.result){
					showError(false);
				} else {
					showError(true,{heading: 'Error',text: d.message});
				}
			})
		};

		// get payments first
		const step2 = (d) => {
			// render task info
			task_info_data.taskData = d.sendme;

			renderTaskBrief();
			load_txt.innerHTML = 'loading task samples';

			// alert_info('loading individual stuff');
			loadMe('samples',linkAtlas['get_samples'],c_pld,step3,'POST');
		}

		const step3 = (d) => {
			task_info_data.samplesData = d.sendme;
			// render samples
			// load payments
			loadMe('payments',linkAtlas['get_payments'],c_pld,step4,'POST');
		}

		const step4 = (d) => {
			task_info_data.paymentData = d.sendme;
			// render payments
			// load reports
			loadMe('reports',linkAtlas['get_reports'],c_pld,step5,'POST');
		}

		const step5 = (d) => {
			task_info_data.reportData = d.sendme;
			// render payments
			// load reports
			loadMe('worksheets',linkAtlas['get_worksheets'],c_pld,step6,'POST');
		}

		const step6 = (d) => {
			task_info_data.worksheets = d.sendme;
			// render reports
			// run final step
			final_step();

			initUIs();
		}

		const final_step = (d) => {
			// alert_dark('rendering stuff');
			pick_step(0);
			showstuffUI();

			if(typeof(window[nextact]) == 'function'){
				window[nextact](nextact_vars);
			}
		}

		load_txt.innerHTML = 'loading task information';
		showmainLoader();
		showError(false);

		setTimeout(() => {
			step1();
		},1200);
	}
	window['refreshData'] = () => {};

	// ops
	window['load_samples'] = () => {}

	// navops
	window['pick_step'] = (n) => {
		// alert_info('loading step: ' + n);
		// refreshUI(300);
	}
	window['initUIs'] = (m) => {
		// ---
	}

	// utilities
	window['showError'] = (con = true,d) => {
		let dft = {
			heading: 'Error',
			text: 'something went wrong, reload page'
		};
		d = {...dft,...d};

		let cl = con ? 'remove' : 'add';
		err_box.classList[cl]('w3-hide');
		err_box.querySelector('span').innerHTML = d.heading;
		err_box.querySelector('p').innerHTML = d.text;
	}
	window['showmainLoader'] = () => {
		loaderInterface.classList.remove('w3-hide');
		info_stuff.classList.add('w3-hide');
		task_brief.classList.add('w3-hide');
	}
	window['hidemainLoader'] = () => {
		loaderInterface.classList.add('w3-hide');
	}
	window['showstuffUI'] = () => {
		render_all_data();
		hidemainLoader();
		info_stuff.classList.remove('w3-hide');
	}

	// actions
		window['refresh_data'] = () => {
			alert_info('refreshing information');
			init_task();
		}
		window['loadMe'] = (wot,path,payld,f,method='post') => {
			let fn = typeof f === 'function' ? f : (d) => {alert_info(`${wot} loaded`)};

			window[fetch_bypass](path,payld,method).then((d) => {
				responseHandler(d,fn,d,true);
			}).catch(d => {
				hidemainLoader();
				showError(true,{heading: `Error Getting ${wot}`,text: d.message});
				console.error(d);
			});
		}

		window['view_nothing'] = (el,id,w) => {alert_danger('invalid record procedure!');}

		window['view_sample'] = (el,id,w) => {
			let who = {
				rectype: w,
			}

			let rec = task_info_data.samplesData[id];
			who.data = rec;

			view_samples_modal_trig.click();
			renderSampleData(who);
		}
		window['view_payment'] = (el,id,w) => {
			let who = {
				rectype: w,
			}

			let rec = task_info_data.paymentData[id];
			who.data = rec;

			view_payment_modal_trig.click();
			renderPayData(who);
		}
		window['view_report'] = (el,id,w) => {
			let myn = task_info_data.reportData[id];

			let cur = {
				rectype: 'report',
				data: myn,
			}

			// copytext1(JSON.stringify(cur,null,4));
			view_report_modal_trig.click();
			renderReportData(cur);
		}

		window['showthis'] = (wot) => {
			// alert_dark('showthis called');

			let theid = `#${wot}-tab`;
			let tabid = `#${wot}`;
			let btn = document.querySelector(theid);

			if(btn !== undefined){
				btn.click();

				setTimeout(() => {
					window.location.assign(btn.href);
				},200);
			}
		}

		window['add_payment'] = (el) => {
			let task = task_info_data.taskData;
			let tid = task.id;

			if(task.balance <= 0){
				alert_danger('all payments were completed');
				return;
			}

			add_payment_trig.click();
			setup_payment_form(tid,'init_task');
		}
		window['add_notes'] = (el) => {
			let task = task_info_data.taskData;
			let tid = task.id;
			let note = task.notes;

			// setup_note_form(tid,'init_task');
			add_note_form.taskid.value = tid;

			if(note !== '' || note !== undefined){
				add_note_form.note.value = note;
			}

			add_note_trig.click();
		}

	window['refreshTaskAssocs'] = () => {
		showmainLoader();
		refreshAllData();
	}
	window['refreshAllData'] = () => {
		init_task();
	}

	// worksheet ops
		window['change_sample'] = (el) => {
			cur_sample_id = el.dataset.myid || 0;

			let sels = document.querySelectorAll('.sample_selects .tabnav');

			sels.forEach(s => {
				s.classList.remove('active');
			})

			el.classList.add('active');

			/*
			let ws = document.querySelector('.worksheet_doc');
			ws.style.opacity = 0;
			// */

			renderWorksheet();

			/*
			setTimeout(d => {
				ws.style.opacity = 1;
			},400);
			// */
		}
		window['show_sheet'] = (el) => {
			if(el == undefined){
				alert_danger('invalid show_sheet procedure call');
				return;
			}

			let tid = el.dataset.tabid;

			if(tid == undefined){
				alert_danger('invalid tabid');
				return;
			}

			show_sheet_tab(Number(tid));
		}
		window['show_sheet_tab'] = (n) => {
			let s_tabsholder = document.querySelector('[data-role="worksheet_tabs"]');
			let s_tabs = s_tabsholder.querySelectorAll('[data-subrole="mytab"]');

			let _n = Number(n) % s_tabs.length;

			s_tabs.forEach((t,id) => {
				let cls = id == _n ? 'remove' : 'add';
				t.classList[cls]('w3-hide');
			});
		}
		window['print_worksheet'] = (el) => {
			show_sheet_tab(1);
			alert_info('printing worksheet');

			setTimeout(() => {
				printElement('.worksheet_doc',true).then(w => {
					alert_info('worksheet printed successfully');
				});
			},500);
		}
		window['getmysheet'] = (sample) => {
			let sid = sample.id;
			let scount = sample.sample_count;

			let dft = {
				id: 1,
				sample_type: sample.sample_type,
				sample_data: [],
				sample_count: scount
			};
			let res = {};

			let sheets = task_info_data.worksheets;
			let _f_sheets = sheets.filter(s => {return s.sample_id == sid;});
			let has_sheet = _f_sheets.length > 0;
			let thesheet = !has_sheet ? {was_autogen: true} : _f_sheets[0];

			res = thesheet;

			if(!has_sheet){
				let s_dta = [];

				for (var i = 0; i < scount; i++) {
					s_dta.push({
						sample_id : `sample ${i + 1}`,
						weight: null,
						au_reading: null,
						cu_reading: null,
						remarks: "",
					});
				}

				res.sample_data = s_dta;
			}

			res = {...dft,...res};
			return res;
		}

	// renderers
		window['render_all_data'] = () => {
			// alert_dark('hiding loader');
			// hidemainLoader();
			renderTaskBrief();

			renderNotifications();
			renderWorksheetXtras();
			renderWorksheet();
			renderStats();
			renderSamples();
			renderPayments();
			renderReports();

			refreshUI(200);
		}

		window['renderTaskBrief'] = () => {
			// alert_info('showing task brief');

			task_brief.classList.remove('w3-hide');

			let panel = task_brief.querySelector('[data-subrole="taskpanel"]');
			let task = task_info_data.taskData;

			let paylink = task.payment_status == 2 ? '' : `
				<a class="dropdown-item spacy-tn" href="#" onclick="add_payment()" id="option_payment_btn">
					<i class="fas fa-money-bill-wave mr-2 text-muted"></i>Record Payment
				</a>
			`;

			panel.innerHTML = `
				<div>
					<div class="flowline overflow left">
						<strong class="h3" id="task-number" style="display: inline-block;width: auto !important;">${task.task_no}</strong>
						<button class="mybtn trans border" data-copythis="#task-number"><i class="fa fa-copy"></i></button>
					</div>
					<div class="distance-sm">
						<span class="mybadge status-${task_state_atlas[task.status]}" id="task-status-badge">
							<i class="fas fa-clock"></i> ${task_state_atlas[task.status]}
						</span>
						<span class="mybadge payment-${task_payment_state_atlas[task.payment_status]} ml-2" id="payment-status-badge">
							<i class="fas fa-credit-card"></i> ${task_payment_state_atlas[task.payment_status]}
						</span>
						<div class="distance-sm">
							<i>${task.notes || "--"}</i>
						</div>
					</div>
				</div>
				<div class="w3-display-right spacy-md" style="z-index: 2">
					<div class="dropdown">
						<button class="btn btn-link text-white" type="button" data-toggle="dropdown">
							<i class="fas fa-ellipsis-v"></i>
						</button>
						<div class="dropdown-menu dropdown-menu-right themeround panelbg border-0 w3-card">
							${paylink}
							<a class="dropdown-item spacy-tn" href="#" onclick="add_notes()" id="option_note_btn">
								<i class="fas fa-sticky-note mr-2 text-muted"></i>Add Notes
							</a>
							<a class="dropdown-item spacy-tn" href="#" data-runme="view_task">
								<i class="fas fa-info-circle mr-2 text-muted"></i>view all details
							</a>
						</div>
					</div>
				</div>
			`;

			// extra stuff (for convenience planning)
			payment_ops.style.display = task_info_data.taskData.balance <= 0 ? 'none' : 'block';
		}
		window['renderNotifications'] = () => {
			// show notification of how many worksheets havent been created
			let wsheet_tab_btn = document.querySelector('#worksheet-tab');
			let samples = [...task_info_data.samplesData];
			let sheets = [...task_info_data.worksheets];

			let withsheets = [],nosheets = [];

			samples.forEach((s,id) => {
				let shts = sheets.filter(sh => {return sh.sample_id == s.id;});
				let has_sheet = shts.length > 0;

				console.log('sample_no: ',s.sample_no,'has sheet: ',has_sheet);

				if(!has_sheet){
					nosheets.push(id);
				} else {
					withsheets.push(id);
				}
			})

			wsheet_tab_btn.dataset.notifications = `${nosheets.length}`;

			// show notification of which samples dont have worksheets
			// assumes renderWorksheet will take less than 0.4s to execute
			setTimeout((e) => {
				// let s_tabs_holder = document.querySelector('.sample_selects');
				let s_tabs = document.querySelectorAll('.sample_selects .tabnav');

				s_tabs.forEach((st,id) => {
					let showme = 0;
					let is_lackin = nosheets.includes(id);

					if(is_lackin){
						showme = '!';
					}

					st.dataset.notifications = showme;
				})
			},400);
		}
		window['renderWorksheetXtras'] = () => {
			let wsxtras = document.querySelector('.sample_selects');
			let btns = '';
			let samples = task_info_data.samplesData;

			samples.forEach((s,x) => {
				btns += `
					<button class="tabnav ${x == 0 ? 'active' : ''}" data-runme="change_sample" data-myid="${x}">
						Sample ${x + 1} <span class="sample-type-badge type-${s.sample_type}">${s.sample_type}</span>
					</button>
				`;
			})

			wsxtras.innerHTML = `
				<div class="mybar">
					${btns}
				</div>
			`;
		}
		window['renderWorksheet'] = () => {
			let wsheet = document.querySelector('[data-role="document_Holder"] .worksheet_doc');
			let worksheet_tabs = document.querySelector('[data-role="worksheet_tabs"]');

			if(wsheet == undefined){
				alert_danger('invalid worksheet display');
				return;
			} else {
				alert_dark('rendering worksheet');
			}

			let fadeframes = [
				{opacity: 0},
				{opacity: 1}
			];
			let timin = {
				duration: 300,
				easing: 'ease-out',
				fill: "forwards",
			};

			worksheet_tabs.animate([...fadeframes].reverse(),{...timin,duration: 2});

			// print setup elements
				let w_head = wsheet.querySelector('.header');
				let w_tinfo = wsheet.querySelector('.job_stats .mytable');
				let w_sinfo = wsheet.querySelector('.sample_stats .mytable');

			// edit setup elements
				let editui = document.querySelector('[data-role="worksheet_edidor"]');
				let e_jinfo = editui.querySelector('[data-subrole="job_deets"]');
				let e_table = editui.querySelector('[data-subrole="thetable"]');

			let b_data = bizdata;
			let bizdeets = b_data.business_details;
			let bizimgs = b_data.business_images;
			let task = task_info_data.taskData;
			let thesample = task_info_data.samplesData[cur_sample_id];
			let outht = '';
			let alertht = mekUiAlert('this sample doesnt have a worksheet yet');
			let maxno = thesample.sample_count > 12 ? thesample.sample_count : 12;

			const mek_my_row = (x,prefix='',_data) => {
				if(thesample.sample_type.toLowerCase() == 'solution'){
					return `
						<tr>
							<td><span>${x}</span></td>
							<td><span><b>${prefix}</b> ${_data.sample_id}</span></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					`;
				}
				return `
					<tr>
						<td><span>${x}</span></td>
						<td></td>
						<td><span><b>${prefix}</b> ${_data.sample_id}</span></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				`;
			}

			cur_sample = thesample;
			cur_rec_sample_id = cur_sample.id;
			cur_sheet = getmysheet(thesample);

			// render stats
			e_jinfo.innerHTML = '';

			if(!cur_sheet.was_autogen){
				alertht = '';

				if(cur_sheet.has_readings) {
					alertht += mekUiAlert('this sample has readings so it cant be edited','warning');
				}

				if(cur_sheet.is_published){
					alertht += mekUiAlert('this sample has already been processed','success','fa fa-check');
				}
			}
			outht = `
				${alertht}
				<div>
					<b>Job No:</b>
					<span>${task.task_no}</span>
				</div>
				<div>
					<b>Client Name: </b>
					<span>${task.client.client_name}</span>
				</div>
				<div>
					<b>Sample Type: </b>
					<span>${thesample.sample_type}</span>
				</div>
				<div>
					<b>Phone No: </b>
					<span>${task.client.client_contact}</span>
				</div>
				<div>
					<b>Payment Status: </b>
					<span><b class="text-muted">${pay_state_atlas[task.payment_status]}</b></span>
				</div>
				<div>
					<b>Recieved Date and time: </b>
					<span>${formatDate0(thesample.created_at)}</span>
				</div>
				<div>
					<b>Number Of Samples: </b>
					<span>${thesample.sample_count}</span>
				</div>
			`;
			e_jinfo.innerHTML = outht;

			mekmyTable('create','[data-role="worksheet_edidor"]');
			// mekResultsTable(cur_sheet);

			// render printable setup

			w_head.innerHTML = `
				<div class="logo">
					<img src="${linkAtlas['bizimages']}/${bizimgs.company_logo}" style="max-width: 120px;">
				</div>
				<div class="info rendercorrect">
					<h2>${bizdeets.branch_name}</h2>
					<div>
						<b>Location: </b>
						<strong>${bizdeets.location}</strong>
					</div>
				</div>
			`;
			w_tinfo.innerHTML = `
				<tr>
					<td>
						<b>Job No: </b>
						<span>${task.task_no}</span>
					</td>
					<td>
						<b>Client Name: </b>
						<span>${task.client.client_name}</span>
					</td>
				</tr>
				<tr>
					<td>
						<b>Sample Type: </b>
						<span>${thesample.sample_type}</span>
					</td>
					<td>
						<b>Phone No: </b>
						<span>${task.client.client_contact}</span>
					</td>
				</tr>
				<tr>
					<td>
						<b>Payment Status: </b>
						<span><b class="text-muted">${pay_state_atlas[task.payment_status]}</b></span>
					</td>
					<td>
						<b>Recieved Date and time: </b>
						<span>${formatDate0(thesample.created_at)}</span>
					</td>
				</tr>
				<tr>
					<td style="opacity: 0.7;">
						<b>Sample No: </b>
						<span>${thesample.sample_no}</span>
					</td>
					<td>
						<b>Number Of Samples: </b>
						<span>${thesample.sample_count}</span>
					</td>
				</tr>
			`;

			let mytype = thesample.sample_type.toLowerCase();

			if(mytype == 'carbon activity'){
				w_sinfo.innerHTML = `
					<tr>
						<th style="width:35px;">${mekSpan('sn')}</th>
						<th style="width:60px;">${mekSpan('beaker')}</th>
						<th>${mekSpan('Sample ids')}</th>
						<th style="width:78px;">${mekSpan('initial reading')}</th>
						<th style="width:92px;">${mekSpan('Sample reading')}</th>
						<th style="width:92px;">${mekSpan('Virgin reading')}</th>
						<th style="width:96px;">${mekSpan('Remark')}</th>
					</tr>
				`;
			} else if(mytype == 'solution'){
				w_sinfo.innerHTML = `
					<tr>
						<th style="width:35px;">${mekSpan('sn')}</th>
						<th>${mekSpan('Sample ids')}</th>
						<th style="width:92px;">${mekSpan('AU reading')}</th>
						<th style="width:92px;">${mekSpan('CU reading')}</th>
						<th style="width:96px;">${mekSpan('Remark')}</th>
					</tr>
				`;
			} else {
				w_sinfo.innerHTML = `
					<tr>
						<th style="width:35px;">${mekSpan('sn')}</th>
						<th style="width:60px;">${mekSpan('beaker')}</th>
						<th>${mekSpan('Sample ids')}</th>
						<th style="width:78px;">${mekSpan('Weight')}</th>
						<th style="width:92px;">${mekSpan('AU reading')}</th>
						<th style="width:92px;">${mekSpan('CU reading')}</th>
						<th style="width:96px;">${mekSpan('Remark')}</th>
					</tr>
				`;
			}

			outht = '';

			let _lno = 0;
			cur_sheet.sample_data.forEach((sdt) => {
				_lno += 1;
				outht += mek_my_row(_lno, '', sdt);

				if(thesample.sample_type.toLowerCase() == 'soil' || thesample.sample_type.toLowerCase() == 'rock'){
					_lno += 1;
					outht += mek_my_row(_lno,'RPT',sdt);
				} else if(thesample.sample_type.toLowerCase() == 'carbon') {
					_lno += 1;
					outht += mek_my_row(_lno,'RPT',sdt);
					_lno += 1;
					outht += mek_my_row(_lno,'RPT',sdt);
				}
			})

			let xtra = '<td></td>';

			if(thesample.sample_type.toLowerCase() == 'soil' || thesample.sample_type.toLowerCase() == 'rock'){
				xtra = `<td><span><b>reference material</b></span></td>`;
			} else if(thesample.sample_type.toLowerCase() == 'carbon') {
				xtra = `<td><b>Carbon Standard solution</b></td>`;
			}

			if(['soil','rock','carbon'].includes(thesample.sample_type.toLowerCase())){
				outht += `
					<tr>
						<td><span>STD</span></td>
						<td></td>
						${xtra}
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				`;
			}

			w_sinfo.innerHTML += outht;

			setTimeout(() => {
				worksheet_tabs.animate([...fadeframes], timin);
			},400);
		}
		window['renderStats'] = () => {
			let stats = task_brief.querySelector('[data-subrole="stats"]');
			let xtra_stats = task_brief.querySelector('[data-subrole="xtra_stats"]');

			let task = task_info_data.taskData;
			let client = task.client.client_name;
			let samples = task_info_data.samplesData;
			let payments = task_info_data.paymentData;
			let reports = task_info_data.reportData;

			stats.innerHTML = `
				<div class="stat">
					<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Client</small>
					<span class="font-weight-bold" id="client-name">${client}</span>
				</div>
				<div class="stat">
					<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Status</small>
					<span class="font-weight-bold" id="task-status-text">${task_state_atlas[task.status]}</span>
				</div>
				<div class="stat">
					<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Samples</small>
					<span class="font-weight-bold" id="samples_count">${samples.length}</span>
				</div>
				<div class="stat">
					<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Payments</small>
					<span class="font-weight-bold" id="payments_count">${payments.length}</span>
				</div>
				<div class="stat">
					<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Reports</small>
					<span class="font-weight-bold" id="reports_count">${reports.length}</span>
				</div>
			`;

			let paybtn = task.payment_status == 2 ? '' : `
				<div class="stat">
					<button class="mybtn primary" data-runme="add_payment"><i class="fa fa-plus"></i> add payment</button>
				</div>
			`;

			xtra_stats.innerHTML = `
				<div class="stat">
					<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Payment status</small>
					<span class="font-weight-bold hideicon" data-copyme="#payment-status-badge"></span>
				</div>
				<div class="stat">
					<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Price</small>
					<span class="font-weight-bold" id="task_cost">${Math.floor(task.cost).toLocaleString()}</span>
				</div>
				<div class="stat">
					<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Amount Paid</small>
					<span class="font-weight-bold" id="task_paid">${Math.floor(task.paid).toLocaleString()}</span>
				</div>
				<div class="stat">
					<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Balance</small>
					<span class="font-weight-bold" id="task_balance">${Math.floor(task.balance).toLocaleString()}</span>
				</div>
				<div class="stat">
					<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Payment Report</small>
					<span class="font-weight-bold hideicon" id="task_payment_report"><b class="themetxt">${Math.floor(task.paid).toLocaleString()}</b> / ${Math.floor(task.cost).toLocaleString()}</span>
				</div>
				${paybtn}
			`;
		}
		window['renderSamples'] = () => {
			const data = {
				data: task_info_data.samplesData,
				cols: [
					{
						field: 'id',
						cap: '',
						show: (v,row,n) => {
							return n + 1;
						}
					},
					{
						field: 'sample_no',
						cap: 'sample Number',
					},
					{
						field: 'sample_type',
						cap: 'sample type',
						show: (v,r) => {
							return `
								<span class="sample-type-badge type-${v}">${v}</span>
							`;
						}
					},
					{
						field: 'sample_count',
						cap: 'processed',
						show: (v,row) => {
							return `<b>${row['results_count']} / ${row['sample_count']}</b>`;
						}
					},
					{
						field: 'status',
						cap: 'Status',
						show: (v) => {
							return `<b>${sample_state_atlas[v]}</b>`;
						}
					},
				],
				tablename: 'samples_table',
				caption: 'samples',
				viewcall: 'view_rec',
				rowprops: 'data-rectype="sample"',
			};
			render_simpleTable(data);
		}
		window['renderPayments'] = () => {
			const data = {
				data: task_info_data.paymentData,
				cols: [
					{
						field: 'id',
						cap: '',
						show: (v,row,n) => {
							return n + 1;
						}
					},
					{
						field: 'owner',
						cap: 'Client name',
						show: (v) => {
							return v.client_name;
						}
					},
					{
						field: 'amount',
						cap: 'Amount',
						show: (v) => {
							return `<b>KES ${Math.floor(v).toLocaleString()}</b>`;
						}
					},
					{
						field: 'method',
						cap: 'Method',
					},
					{
						field: 'transaction_time',
						cap: 'Transaction Time',
						show: (v) => {
							return formatDate0(v);
						}
					},
					/*{
						field: 'status',
						cap: 'Status',
						show: (v) => {
							return `<b>${sample_state_atlas[v]}</b>`;
						}
					},*/
				],
				tablename: 'payments_table',
				caption: 'payments',
				viewcall: 'view_rec',
				rowprops: 'data-rectype="payment"',
			};

			payment_ops.querySelector('[data-subrole="add_pay_btn"]').style.display = task_info_data.taskData.payment_status == 2 ? 'none' : 'block';
			render_simpleTable(data);
		}
		window['renderReports'] = () => {
			const data = {
				data: task_info_data.reportData,
				cols: [
					{
						cap: 'ID',
						field: 'id',
						width: '50px',
					},
					{
						cap: 'Sample number',
						field: 'sample',
						show: function(value) {
							return `<strong>${value.sample_no}</strong>`;
						}
					},
					{
						cap: 'Client name',
						field: 'owner',
						show: function(value) {
							return `<strong>${value.client_name}</strong>`;
						}
					},
					{
						cap: 'Date added',
						field: 'created_at',
						show: function(value) {
							return formatDate0(value);
							// return `<span class="badge ${value}">${value}</span>`;
						}
					},
					/*
					{
						cap: 'Actions',
						field: 'id',
						width: '200px',
						show: function(value, row) {
							return `
								<div class="action-btns">
									<button class="mybtn primary sm" onclick="viewRecord(${value})" title="View">
										<i class="fas fa-eye"></i> View
									</button>
								</div>
							`;
						}
					}
					// */
				],
				tablename: 'reports_table',
				caption: 'reports',
				viewcall: 'view_rec',
				rowprops: 'data-rectype="report"',
			};
			render_simpleTable(data);
		}

	// MUST BE LAST (utilities all functions call at some point, put last for easy access)
		// form aftereffects
		window['add_note_form_afterfx'] = (d) => {
			let el = d.el;
			let cbk = 'init_task';

			add_note_trig.click();
			el.reset();

			if(cbk !== undefined){
				window[cbk]();
			}
		}
		window['worksheet_update_callback'] = (d) => {
			window[variableAtlas['t_i_act']] = 'showthis';
			window[variableAtlas['t_i_args']] = 'worksheet';

			init_task();
		}

		window['refreshStuff'] = () => {
			refreshTaskAssocs();
		}
		window['view_task'] = () => {
			let cur = {
				rectype: 'task',
				data: task_info_data.taskData,
			}

			window['cur_record'] = cur;
			loadPage({
				template: linkAtlas['view_task'],
				sidenote: pageNames['view_task'],
			});
		}
</script>
