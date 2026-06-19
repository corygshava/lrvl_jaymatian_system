<?php
	/*
	require_once __DIR__.'/piece_worksheet_xtras.php';
	require_once __DIR__.'/piece_samples_extras.php';
	require_once __DIR__.'/datatables_code.php';
	// */
?>

{{-- <!-- --}}
@include('pieces.codes_datatables')
@include('pieces.worksheet_xtras')
@include('pieces.samples_xtras')
{{-- --> --}}

<style>
	/* stats (keep here, ill figure them out later) */
		.stat-card-modern {
			background: white;
			border-radius: var(--radius);
			padding: 20px;
			display: flex;
			align-items: center;
			gap: 15px;
			box-shadow: var(--shadow-sm);
			transition: all 0.3s;
			border: 1px solid var(--border);
		}
		.stat-card-modern:hover {
			transform: translateY(-3px);
			box-shadow: var(--shadow);
		}
		.stat-icon-modern {
			width: 50px;
			height: 50px;
			border-radius: var(--radius-sm);
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 20px;
		}

		.stat-icon-modern.total { background: var(--primary-light); color: var(--primary); }
		.stat-icon-modern.active { background: #d1fae5; color: var(--success); }
		.stat-icon-modern.inactive { background: #fee2e2; color: var(--danger); }
		.stat-icon-modern.new { background: #fef3c7; color: var(--warning); }

		.stat-info-modern h3 {
			font-size: 24px;
			font-weight: 700;
			margin: 0;
			color: var(--dark);
		}
		.stat-info-modern p {
			margin: 0;
			color: var(--gray);
			font-size: 13px;
		}

	/* Filter Bar */
		.filter-bar {
			background: white;
			border-radius: var(--roundness);
			padding: 20px;
			margin-bottom: 20px;
			/*box-shadow: var(--shadow-sm);*/
			/*border: 1px solid var(--border);*/
		}
		.filter-row {
			display: flex;
			gap: 15px;
			flex-wrap: wrap;
			align-items: end;
		}
		.filter-group {
			flex: 1;
			min-width: 200px;
		}
		.filter-group label {
			display: block;
			font-size: 12px;
			font-weight: 600;
			color: var(--gray);
			margin-bottom: 6px;
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}
		.filter-control {
			width: 100%;
			padding: 10px 15px;
			border: 1px solid var(--clr-border);
			border-radius: var(--roundness);
			font-size: 14px;
			transition: all 0.3s;
			background: white;
		}
		.filter-control:focus {
			outline: none;
			border-color: var(--primary);
			box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
		}

	/* Data Container */
		.data-container {
			background: white;
			border-radius: var(--roundness);
			box-shadow: var(--shadow-sm);
			/*border: 1px solid var(--light-grey);*/
			overflow: hidden;
		}
		.data-header {
			padding: 20px;
			border-bottom: 1px solid var(--clr-border);
			display: flex;
			justify-content: space-between;
			align-items: center;
			flex-wrap: wrap;
			gap: 15px;
		}
		.data-title {
			font-size: 16px;
			font-weight: 600;
			color: var(--dark);
		}
		.data-actions {
			display: flex;
			gap: 10px;
			align-items: center;
		}

	/* Action Buttons */
		.action-btns {
			display: flex;
			flex-direction: row;
			/*flex-wrap: wrap;*/
			gap: 8px;
			width: 142px;
		}

		.btn-action {
			border-radius: 6px;
			border: none;
			display: flex;
			align-items: center;
			justify-content: center;
			cursor: pointer;
			transition: all 0.3s;
			font-size: 13px;
			padding: 8px 12px;
			gap: 9px;
		}

		.btn-view { background: #e0e7ff; color: var(--primary); }
		.btn-edit { background: #fef3c7; color: var(--text); }
		.btn-delete { background: #fee2e2; color: var(--danger); }

		.btn-action:hover {
			transform: scale(1.1);
		}

	/* Pagination */
		.pagination-container {
			padding: 20px;
			border-top: 1px solid var(--border);
			display: flex;
			justify-content: space-between;
			align-items: center;
			flex-wrap: wrap;
			gap: 15px;
		}

		.pagination-info {
			font-size: 14px;
			color: var(--gray);
		}

		.pagination {
			display: flex;
			gap: 5px;
			margin: 0;
			padding: 0;
			list-style: none;
		}

		.page-item {
			list-style: none;
		}

		.page-link {
			display: flex;
			align-items: center;
			justify-content: center;
			width: 36px;
			height: 36px;
			border-radius: 8px;
			border: 1px solid var(--border);
			background: white;
			color: var(--gray);
			font-size: 14px;
			cursor: pointer;
			transition: all 0.3s;
			text-decoration: none;
		}

		.page-link:hover, .page-link.active {
			background: var(--primary);
			color: white;
			border-color: var(--primary);
		}

		.page-link:disabled {
			opacity: 0.5;
			cursor: not-allowed;
		}

	/* Loading Spinner */
		.loading-overlay {
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: rgba(255, 255, 255, 0.9);
			display: none;
			align-items: center;
			justify-content: center;
			z-index: 10;
		}

		.loading-overlay.show {
			display: flex;
		}

		.spinner {
			width: 50px;
			height: 50px;
			border: 4px solid var(--border);
			border-top-color: var(--primary);
			border-radius: 50%;
			animation: spin 1s linear infinite;
		}

		@keyframes spin {
			to { transform: rotate(360deg); }
		}

	/* Empty State */
		.empty-state {
			text-align: center;
			padding: 60px 20px;
			display: none;
		}
		.empty-state.show {
			display: block;
		}
		.empty-icon {
			width: 80px;
			height: 80px;
			background: var(--primary-light);
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			margin: 0 auto 20px;
			color: var(--primary);
			font-size: 32px;
		}
		.empty-state h3 {
			font-size: 18px;
			color: var(--dark);
			margin-bottom: 10px;
		}
		.empty-state p {
			color: var(--gray);
			font-size: 14px;
		}

	/* Toast Notification */
		.toast-container {
			position: fixed;
			top: 20px;
			right: 20px;
			z-index: 3000;
			display: flex;
			flex-direction: column;
			gap: 10px;
		}
		.toast_ {
			background: white;
			border-radius: var(--radius-sm);
			padding: 16px 20px;
			box-shadow: var(--shadow-lg);
			display: flex;
			align-items: center;
			gap: 12px;
			min-width: 300px;
			border-left: 4px solid;
		}
		.toast.success { border-left-color: var(--success); }
		.toast.error { border-left-color: var(--danger); }
		.toast.info { border-left-color: var(--info); }
		.toast-icon {
			width: 24px;
			height: 24px;
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 12px;
		}
		.toast.success .toast-icon { background: #d1fae5; color: var(--success); }
		.toast.error .toast-icon { background: #fee2e2; color: var(--danger); }
		.toast.info .toast-icon { background: #dbeafe; color: var(--info); }
		.toast-content {
			flex: 1;
		}
		.toast-title {
			font-weight: 600;
			font-size: 14px;
			color: var(--dark);
		}
		.toast-message {
			font-size: 13px;
			color: var(--gray);
			margin-top: 2px;
		}
		.toast-close {
			background: none;
			border: none;
			color: var(--gray);
			cursor: pointer;
			padding: 4px;
		}

	/* Responsive */
		@media (max-width: 768px) {
			.filter-row {
				flex-direction: column;
			}

			.filter-group {
				min-width: 100%;
			}

			.search-table input {
				width: 100%;
			}
		}
</style>

<div class="toast-container" id="toastContainer"></div>

<div class="container-fluid">
	<div class="page-header">
		<div class="page-title">
			<span class="h4">Sample management</span>
			<p>Here you can view, manage and process sample entries</p>
		</div>
		<div class="header-actions">
			<button class="mybtn secondary" onclick="refreshData()">
				<i class="fas fa-sync-alt"></i>
				Refresh
			</button>
			<!-- <button class="mybtn primary" data-runme="add_record"> -->
			<button class="mybtn primary" id="add_record_btn" data-runme="load_page" data-label="User management">
				<i class="fas fa-plus"></i>
				Add New task
			</button>
		</div>
	</div>

	<div class="stats-row w3-hide themeround" id="statsContainer">
	</div>

	<div class="filter-bar w3-hide_ w3-display-container themeround" id="filterContainer">
		<span class="h4">Filters</span>
		<div class="filter-row"></div>
	</div>

	<div class="data-container" style="position: relative;">
		<div class="loading-overlay" id="loadingOverlay">
			<div class="spinner"></div>
		</div>

		<div class="data-header">
			<div class="d-flex align-items-center gap-3">
				<h5 class="data-title" id="tableTitle">Sample Records</h5>
			</div>
			<div class="data-actions">
				<div class="search-box">
					<input type="text" placeholder="Quick search..." id="quickSearch">
					<button><i class="fas fa-search"></i></button>
				</div>
			</div>
		</div>

		<div class="table-responsive" id="tableView">
			<table class="table-modern">
				<thead id="tableHead">
				</thead>
				<tbody id="tableBody">
				</tbody>
			</table>
		</div>

		<div class="empty-state" id="emptyState">
			<div class="empty-icon">
				<i class="fas fa-inbox"></i>
			</div>
			<h3>No data found</h3>
			<p>Try adjusting your filters or search query</p>
		</div>

		<!-- Pagination -->
		<div class="pagination-container">
			<div class="pagination-info">
				Showing <span id="showingStart">0</span> to <span id="showingEnd">0</span> of <span id="totalItems">0</span> entries
			</div>
			<ul class="pagination" id="pagination">
				<!-- Pagination will be generated dynamically -->
			</ul>
		</div>
	</div>
</div>

<script>
	// view specific data
	let state_classes = [
		'w3-black',
		'w3-blue',
		'w3-green'
	]
	let state_filter = [];

	let sample_types = [
		'soil',
		'rock',
		'solution',
		'carbon',
	];
	let samples_filter = [];
	let pay_state_filter = [];

	window['killOnLeave'] = [
		'viewRecord',
		'viewRecordInfo',
		'add_record',
		'edit_record',
		'form_afterfx',
		'view_acts',
	];

	window['runOnAwake'] = () => {
		Object.keys(sample_state_atlas).forEach((k,id) => {
			// if(id == 0){return;}
			let tmp = {value: k, label: sample_state_atlas[k]};
			state_filter.push(tmp);
		});
		sample_types = Object.keys(bizdata.pricing_data);
		sample_types.forEach(k => {
			let tmp = {value: k, label: k};
			samples_filter.push(tmp);
		});

		Object.keys(payment_state_atlas).forEach((k,id) => {
			// if(id == 0){return;}
			let tmp = {value: `${k}`, label: payment_state_atlas[k]};
			pay_state_filter.push(tmp);
		});

		const initstuff = (m) => {
			add_record_btn.dataset.link = linkAtlas['add_task'];

			// alert_info('loading details');
			// return;

			LaravelDataTable.init({
				// Required: Laravel API endpoint
				apiEndpoint: './data/samples',
				sortDirection: 'desc',

				// Required: Column definitions
				columns: [
					{
						title: 'ID',
						data: 'id',
						width: '50px',
						sortable: true,
						render: (v,r,id) => {
							return `<b>${v}</b>`;
						}
					},
					{
						title: 'type',
						data: 'sample_type',
						sortable: true,
						render: function(value) {
							return `<span class="sample-type-badge type-${("" + value).toLowerCase()}">${value}</span>`;
						}
					},
					{
						title: 'Samples',
						data: 'sample_count',
						sortable: true,
						render: function(value) {
							return `<strong>${value}</strong>`;
						}
					},
					{
						title: 'Sample number',
						data: 'sample_tag',
						sortable: true,
						render: function(value) {
							return `<strong>${value}</strong>`;
						}
					},
					{
						title: 'Client name',
						data: 'owner',
						sortable: true,
						render: function(value) {
							return `<strong>${value.client_name}</strong>`;
						}
					},
					{
						title: 'Payment status',
						data: 'task',
						sortable: false,
						render: function(value,row) {
							let pid = value.payment_status;
							let pstate = task_pay_state[pid];
							let cls = pstate_classes[pid];
							let xtraht = value.payment_status == 2 ? '' : `
								<div class="w3-small" style="margin-top: 4px;">
									<b class="themetxt">${Number(row.task.paid).toLocaleString()}</b> / ${Number(row.task.cost).toLocaleString()}
								</div>
							`;
							return `
								<strong class="${cls}">${pstate}</strong>
								${xtraht}
							`;
						}
					},
					{
						title: 'Date added',
						data: 'created_at',
						sortable: true,
						render: function(value) {
							return formatDate0(value);
							// return `<span class="badge ${value}">${value}</span>`;
						}
					},
					{
						title: 'Processing Status',
						data: 'status',
						sortable: true,
						render: function(value) {
							let cls = state_classes[value];
							// let con = value == 'monthly' ? "yes" : "no";
							return `<strong class="w3-tag themeround ${cls}">${sample_state_atlas[value]}</strong>`;
						}
					},
					{
						title: 'Has worksheet',
						data: 'id',
						sortable: false,
						render: function(value, row) {
							let s_con = row.worksheets.length > 0;
							let cls = s_con == true ? "w3-green" : "w3-black";
							let con = s_con == true ? "yes" : "no";
							return `<strong class="w3-tag themeround ${cls}">${con}</strong>`;
						}
					},
					{
						title: 'Has report',
						data: 'id',
						sortable: false,
						render: function(value, row) {
							let s_con = row.has_report;
							let cls = s_con == true ? "w3-green" : "w3-black";
							let con = s_con == true ? "yes" : "no";
							return `<strong class="w3-tag themeround ${cls}">${con}</strong>`;
						}
					},
					// */
					// /*
					{
						title: 'Actions',
						data: 'id',
						sortable: false,
						width: '320px',
						render: function(value, row) {
							let acts_0 = (row.task.payment_status != 2) ?
								`
									<button class="mybtn secondary tn" data-runme='mek_payment' data-myid="${value}" title="Edit">
										<i class="${iconAtlas['payments']}"></i> pay up
									</button>
								` : ``;
							let acts_1 = (row.status == 2) ? '' : `
								<button class="mybtn secondary tn" onclick="fix_tag(${value})" title="View">
									<i class="fas fa-wrench"></i> fix tag
								</button>
							`;

							let acts_2 = `
								<button class="mybtn secondary tn" onclick="view_acts(${value})" title="View">
									<i class="fas fa-cog"></i> actions
								</button>
							`;

							return `
								<div class="action-btns">
									<button class="mybtn primary tn" onclick="viewRecord(${value})" title="View">
										<i class="fas fa-eye"></i> view
									</button>
									${acts_2}
								</div>
							`;
						},
					},
					// */
					/*
					{
						title: 'Actions',
						data: 'id',
						sortable: false,
						width: '250px',
						render: function(value, row) {
							let acts = (row.status == 2) ?
								`
									<button class="btn themeround btn-secondary btn-sm" data-runme='mek_report' data-myid="${value}" title="Edit">
										<i class="fa fa-file-export"></i> make report
									</button>
								` : ``;
							let acts_2 = (row.task.payment_status != 2) ?
								`
									<button class="btn themeround themebg w3-hover-black w3-text-white btn-sm" data-runme='mek_payment' data-myid="${value}" title="Edit">
										<i class="${iconAtlas['payments']}"></i> pay up
									</button>
								` : ``;

							let acts_3 = (row.status == 2) ? '' : `
								<button class="btn themeround btn-dark w3-hover-yellow btn-sm" onclick="fix_tag(${value})" title="View">
									<i class="fas fa-wrench"></i> fix tag
								</button>
							`;

							return `
								<div class="action-btns">
									<b class="w3-hide">${row.status}</b>
									<button class="mybtn primary sm w3-hide" onclick="viewRecord(${value})" title="View">
										<i class="fas fa-cog"></i> Manage
									</button>
									${acts_3}
									${acts_2}
									${acts}
								</div>
							`;
						}
					}
					// */
				],

				// Optional: Items per page (max 300)
				perPage: 20,

				// Optional: Enable features
				enableSearch: true,
				enableSort: true,
				enableFilters: true,

				// Optional: Filter definitions
				filters: [
					{
						name: 'status',
						label: 'processing status',
						rawname: 'status',
						options: state_filter
					},
					{
						name: 'payment_status',
						label: 'payment status',
						rawname: 'status',
						options: pay_state_filter
					},
					{
						name: 'with_results',
						label: 'Result Status',
						rawname: 'with_results',
						options: [
							{value: 'yes',label: 'with results'},
							{value: 'no',label: 'without results'},
						]
					},
					{
						name: 'sample_type',
						label: 'Sample Type',
						rawname: 'sample type',
						options: samples_filter
					},
				],

				// Optional: Enable stats
				// enableStats: false,
				// statsEndpoint: '/api/students/stats',

				// Optional: Callbacks
				onDataLoaded: function(data) {
					console.log('Data loaded:', data);

					window['curRecordData'] = data.data
				},

				onError: function(error) {
					console.error('Error occurred:', error);
					alert_danger('Error: ' + error.message);
				},

				onRowClick: function(row, index, e) {
					window['lastRowClickEvent'] = e;

					if(e.target.closest('.action-btns') == null){
						console.log('Row clicked:', row);

						viewRecord(row.id);
					}
				}
			});

			// updateUI();
			// setup_form();
		}

		initstuff();

		/*
		window[fetch_bypass]('./data/user_roles',{},'get').then((d) => {
			responseHandler(d,initstuff,d,true);
			// alert_info('render this');
		})
		// */
	};

	// Page-specific action handlers
	function viewRecord(id) {
		// showToast('info', 'View User', `Viewing User ID: ${id}`);

		let myn = window['curRecordData'].filter(f => {return f.id.toString().toLowerCase() == ("" + id).toLowerCase();})[0];

		if(myn == undefined){
			alert_danger('invalid record');
			return;
		}

		let cur = {
			rectype: 'sample',
			data: myn,
		}

		// copytext1(JSON.stringify(cur,null,4));
		viewRecordInfo(cur);

		// alert_dark(`viewing ${myn.id}'s details`);
		// alert_info('set current user: ' + myn.name);
		// alert_dark('set current userid: ' + myn.id);
		// window['cur_record'] = cur;

		// loadPage({template: linkAtlas['view_task'],sidenote: 'Task Information'});
	}
	function viewRecordInfo(who) {
		view_samples_modal_trig.click();
		renderSampleData(who);
	}
	window['add_record'] = (e) => {
		roleop = "add";
		add_edit_form.recid.value = "";
		add_edit_form.reset();
		add_edit_modal_trig.click();
        // password_field_input.style.display = "block";
		// add_edit_form.user_password.removeAttribute('readonly');
		add_edit_title.innerHTML = "Register new Client";
	}
	window['edit_record'] = (e) => {
		if(e === undefined){return;}

		roleop = "edit";
		rec_id = e.dataset.myid;
		add_edit_form.recid.value = rec_id;

		let myn = window['curRecordData'].filter(f => {return f.id.toString().toLowerCase() == rec_id.toLowerCase();})[0];
		myn.rec_type = "task";

		window['recordBeingEdited'] = myn;

		// open the add page but with the job's deets
	}

	// extra stuff
	window['view_acts'] = (n) => {
		if(n == null){
			alert_danger('invalid procedure call');
			return;
		}
		alert_info('something is happening');

		let myn = window['curRecordData'].filter(f => {return f.id.toString().toLowerCase() == ("" + n).toLowerCase();})[0];
		let value = n;

		if(myn == undefined){
			alert_danger('invalid record');
			return;
		}

		let btn_1 = mekButton({
			caption: `<i class="fa fa-wrench"></i> fix tag`,
			btype: 'primary'
		});

		let acts_0 = (myn.task.payment_status != 2) ?
			`
				<button data-dismiss="modal" class="mybtn secondary sm" data-runme='mek_payment' data-myid="${value}" title="Edit">
					<i class="${iconAtlas['payments']}"></i> pay up
				</button>
			` : ``;
		let acts_1 = (myn.status == 2) ? '' : `
			<button data-dismiss="modal" class="mybtn secondary sm" onclick="fix_tag(${value})" title="View">
				<i class="fas fa-wrench"></i> fix tag
			</button>
		`;

		let finht = `
			<div class="actions flow gap-sm left" style="align-items: stretch;">
				${acts_0}
				${acts_1}
			</div>
		`;

		acts_ht = `${finht}`;

		mekModal({
			title: 'sample actions',
			sub: 'what actions do you want to perform on this sample',
			content: acts_ht,
			has_cancel: false,
			has_continue: false,
		});
	}

	// acts of the, well ...., act buttons
	function fix_tag(n) {
		// id = el.dataset.myid || null;
		let id = n;

		if(id == null){
			alert_danger('invalid procedure call');
			return;
		}

		alert_dark('this is twirkin');
		// return;

		let myn = window['curRecordData'].filter(f => {return f.id.toString().toLowerCase() == (`${id}`).toLowerCase();})[0];

		if(myn == undefined){
			alert_danger('invalid record');
			return;
		}

		if(myn.status == 2){
			alert_danger('you cant change the tag of a sample with readings');
			return;
		}

		// alert_dark('got to input wirks');

		// send to server
		let tosend = {};
		let inputs = [
			{
				label: "new sample tag",
				field: "prompt",
				typ:"text",
				placeholder: 'enter the sample tag',
				props: undefined,
				value: myn.sample_tag,
				_inp_props: undefined,
				_inp_classes: undefined,
				required: true
			}
		];

		awaitConfirmPass(get_new_tag);

		function get_new_tag(dta) {
			let ppw = dta.admin_password;
			tosend['pw'] = ppw;
			tosend['admin_password'] = ppw;
			tosend['email'] = cur_user.email;

			get_input('change sample tag',inputs, send_the_tag);
		}
		function send_the_tag(el) {
			alert_info('submitting the tag');

			if(el.prompt.toLowerCase() == myn.sample_tag.toLowerCase()){
				alert_warning('the new sample tag is the same as the old one');
				return;
			}

			let shape = {
				sample_tag: el.prompt,
				sample_id: myn.id,
			};

			tosend = {
				...tosend,
				...shape,
			};

			send_the_tag_fr();
		}
		function send_the_tag_fr() {
			alert_info('changing the sample tag');

			window[fetch_bypass](linkAtlas['change_sample_tag'],tosend,'POST')
			.then(() => {
				if(window['refreshData'] !== undefined){
					refreshData();
				}
			})

			add_bg_worker(tmp);
		}
		function save_it_here(w) {
			let tmp_1 = {
				complete_msg: 'sample saved to local tools',
				fail_msg: 'local sample saving failed, please retry',
				data: w.sendme,
				is_formdata: false,
				destination: linkAtlas['save_sample'],
				ui_title: 'Saving related sample to datastore',
				mytype: 'worksheets',
				ui_desc: `saving sample <b class="themetxt">${w.sendme.sample_no} [${w.sendme.sample_no}]</b> to the datastore`,
				afterfx: (m) => {
					// alert_success('')
					alert_silent('sample added, we good cheese');
					if(window['refreshData'] !== undefined){
						refreshData();
					}
				},
			};

			add_bg_worker(tmp_1);
		}

		// save returned
	}

	window['form_afterfx'] = (m) => {
		// alert_danger('runnin afterfx');
		add_edit_modal_trig.click();

		// alert_dark('its done editin or addin');
		window['last_user_Add_Edit_result'] = m;

		// window['refresh_stuff']();
		refreshData();
	}
</script>
