<?php
	// require_once __DIR__.'/piece_task_view_styles.php';
	// require_once __DIR__.'/piece_samples_extras.php';
	// require_once __DIR__.'/piece_payments_xtras.php';
	// require_once __DIR__.'/datatables_refactored.php';
?>

{{-- <!-- --}}
@include('pieces.task_view_styles')
@include('pieces.samples_xtras')
@include('pieces.payments_xtras')
{{-- --> --}}

<style>
	.profile_card{
		max-width: 100vw;
		/*overflow: hidden;*/
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

	/*td{border-bottom: 1px solid #eee !important;}*/
	.table-modern thead{
		/*border-radius: var(--roundness);*/
		/*overflow: hidden;*/
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
			<span class="h4">Task Summary</span>
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
			<!--  -->
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
				<a class="nav-link active" id="samples-tab" data-toggle="tab" href="#samples" role="tab">
					<i class="fas fa-flask"></i> Samples
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="payments-tab" data-toggle="tab" href="#payments" role="tab">
					<i class="fas fa-money-bill"></i> Payments
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="reports-tab" data-toggle="tab" href="#reports" role="tab">
					<i class="fas fa-file-export"></i> Reports
				</a>
			</li>
		</ul>

		<div class="tab-content border in_fullwidth panelbg themeround custom-tab-content container-fluid" id="myTabContent">
			<!-- Overview Tab -->
			<div class="tab-pane fade show active" id="samples" role="tabpanel">
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
							<textarea class="form-control-custom" id="note" name="note" rows="4" required>
							</textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="mybtn secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="mybtn primary" data-submitme="#add_note_form">Add payment</button>
			</div>
		</div>
	</div>
</div>

<script>
	let task = {};
	let c_pld = {};

	let task_state_atlas = {
		0 : 'pending',
		1 : 'awaiting_payment',
		2 : 'complete',
	};
	let task_payment_state_atlas = {
		0 : 'pending',
		1 : 'incomplete',
		2 : 'paid',
	};

	let sample_state_atlas = {
		0 : 'pending',
		1 : 'incomplete',
		2 : 'complete',
	};

	window['task_info_data'] = {};

	window['runOnAwake'] = () => {
		delete(window['refreshData']);

		setTimeout(() => {
			init_task();
			// alert_info('runing autoload');
			// refreshUI(300);
		},400);
	}

	window['runOnLeave'] = () => {
		// alert_danger('leaving the pages');
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
		});
	}

	window['view_rec'] = (el) => {
		if(el == undefined){
			alert_danger('invalid view_rec call');
			return;
		}
		let wot = el.dataset.rectype || 'nothing';
		let n = el.dataset.myid || 0;
		let callme = `view_${wot}`;

		alert_dark(`viewing record for: ${wot}`);

		window[callme](el,n,wot);
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
		add_note_form.note.value = note || '';

		add_note_trig.click();
	}

	window['refreshTaskAssocs'] = () => {
		showmainLoader();
		refreshAllData();
	}
	window['refreshAllData'] = () => {
		init_task();
	}

	// renderers
	window['render_all_data'] = () => {
		// alert_dark('hiding loader');
		// hidemainLoader();
		renderTaskBrief();

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
				</div>
			</div>
			<div class="w3-display-right spacy-md" style="z-index: 102">
				<div class="dropdown">
					<button class="btn btn-link text-white" type="button" data-toggle="dropdown">
						<i class="fas fa-ellipsis-v"></i>
					</button>
					<div class="dropdown-menu dropdown-menu-right themeround panelbg border-0 w3-card">
						${paylink}
						<a class="dropdown-item spacy-tn" href="#" onclick="add_notes()" id="option_note_btn">
							<i class="fas fa-sticky-note mr-2 text-muted"></i>Add Notes
						</a>
					</div>
				</div>
			</div>
		`;

		// extra stuff (for convenience planning)
		payment_ops.style.display = task_info_data.taskData.balance <= 0 ? 'none' : 'block';
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
		renderTable(data);
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
		renderTable(data);
	}
	window['renderReports'] = () => {
		const data = {
			data: task_info_data.reportData,
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
				},
				/*{
					field: 'status',
					cap: 'Status',
					show: (v) => {
						return `<b>${sample_state_atlas[v]}</b>`;
					}
				},*/
			],
			tablename: 'reports_table',
			caption: 'reports',
			viewcall: 'view_rec',
			rowprops: 'data-rectype="report"',
		};
		renderTable(data);
	}

	window['renderTable'] = (dta) => {
		let dft = {
			data: {},
			cols: [],
			tablename: 'items_table',
			caption: 'items',
			viewcall: 'view_record',
			rowprops: 'data-genrecord'
		};

		dta = {...dft,...dta};

		// alert_info(`showing ${dta.caption}`,12);

		let data = dta.data;
		let cols = dta.cols;
		let tablename = dta.tablename;
		let sel = `[data-role="${tablename}"]`;
		let table = document.querySelector(sel);

		if(table !== null){
			let thead = table.querySelector(`[data-subrole="tableHead"]`);
			let tbody = table.querySelector(`[data-subrole="tableBody"]`);

			outht = '';

			cols.forEach(h => {
				outht += `<th>${h.cap}</th>`;
			});

			thead.innerHTML = outht;
			outht = '';

			data.forEach((s,id) => {
				outht += `<tr style="cursor:pointer;" data-runme="${dta.viewcall}" data-myid="${id}" ${dta.rowprops}>`;

				cols.forEach(c => {
					let f = s[c.field];
					let dta = c.show == undefined ? f : c.show(f,s,id);
					outht += `<td>${dta}</td>`;
				})
				outht += '</tr>';
			})

			if(data.length == 0){
				let l = cols.length == 0 ? 1 : cols.length;
				outht = `
					<td colspan="${l}" class="spacy-md w3-center">
						<i>no ${dta.caption} found</i>
					</td>
				`;
			}

			tbody.innerHTML = outht;

			let n = data.length;
			let myreport = `${dta.tablename}_report`;
			let rpt = document.querySelector(`#${myreport}`);
			let reportht = `showing <b class="themetxt">${n}</b> ${plural('result',n)}`;

			if(rpt == undefined){
				let summary = document.createElement('div');
				summary.className = "spacy-sm border-top border-secondary";
				summary.innerHTML = reportht;
				summary.id = myreport;

				document.querySelector(sel).parentElement.appendChild(summary);
			} else {
				rpt.innerHTML = reportht;
			}
		} else {
			alert_danger('no table found',12);
		}
	}

	// MUST BE LAST
	window['add_note_form_afterfx'] = () => {
		let el = d.el;
		let cbk = 'init_task';

		add_note_trig.click();
		el.reset();
		el.pay_method.value = '';

		if(cbk !== undefined){
			window[cbk]();
		}
	}
	window['refreshStuff'] = () => {
		refreshTaskAssocs();
	}
</script>
