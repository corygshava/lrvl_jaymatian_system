<?php
	/*
	// require_once __DIR__.'/piece_invoices_xtras.php';
	require_once __DIR__.'/datatables_code.php';
	require_once __DIR__.'/piece_debtors_xtras.php';
	// require_once __DIR__.'/piece_simple_table.php';
	// */
?>

{{-- <!-- --}}
@include('pieces.codes_datatables')
@include('pieces.invoices_xtras')
@include('pieces.codes_simpletables')
@include('pieces.debtors_xtras')
{{-- --> --}}

<style>
	/* stats (keep here, ill figure them out later) */
		.stat-card-modern {
			background: white;
			border-radius: var(--roundness);
			padding: 20px;
			display: flex;
			align-items: center;
			gap: 15px;
			/*box-shadow: 0 0 2px var(--clr-shadow);*/
			border: 1px solid var(--clr-border);
			--c: var(--clr-text);
			width: 100%;
			/*max-width: 200px;*/
			flex: 1 0 300px;
		}
		.stat-card-modern:hover {
			transform: translateY(-3px);
			border: 1px solid var(--themecolor);
		}
		.stat-icon-modern {
			width: 50px;
			height: 50px;
			border-radius: var(--roundness-sm);
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 20px;
		}

		.stat-icon-modern.total,.stat-icon-modern.primary { background: var(--themecolor); color: var(--light); }
		.stat-icon-modern.active,.stat-icon-modern.success { background: #d1fae5; color: var(--success); }
		.stat-icon-modern.inactive,.stat-icon-modern.danger { background: #fee2e2; color: var(--danger);}
		.stat-icon-modern.new { background: #fef3c7; color: var(--warning); }

		.stat-card-modern:has(.stat-icon-modern.danger){--c: var(--danger);}
		.stat-card-modern:has(.stat-icon-modern.success){--c: var(--success);}
		.stat-card-modern:has(.stat-icon-modern.primary){--c: var(--themecolor);}

		.stat-info-modern h3 {
			font-size: 24px;
			font-weight: 700;
			margin: 0;
			color: var(--c);
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
			flex-wrap: wrap;
			gap: 8px;
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
			border-radius: var(--roundness-sm);
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
			<span class="h4" data-role="pagename"></span>
			<p>Here you can view and manage debtor information</p>
		</div>
		<div class="header-actions">
			<button class="mybtn secondary" data-runme="just_render_debtors">
				<i data-myicon="debtors"></i>
				view unrefined list
			</button>
			<button class="mybtn primary" onclick="refreshData()">
				<i class="fas fa-sync-alt"></i>
				Refresh
			</button>
		</div>
	</div>

	<div class="stats-row w3-hide_ themeround" id="statsContainer">
	</div>

	<div class="filter-bar w3-hide_ themeround" id="filterContainer">
		<span class="h4">Filters</span>
		<div class="filter-row"></div>
	</div>

	<div class="data-container" style="position: relative;">
		<div class="loading-overlay flow centroid" id="loadingOverlay">
			<div>
				<div class="loader"></div>
			</div>
		</div>

		<div class="data-header">
			<div class="d-flex align-items-center gap-3">
				<h5 class="data-title" id="tableTitle">Invoice Records</h5>
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
	let state_classes = [
		'w3-black',
		'w3-blue',
		'w3-green'
	];

	let meth_map = [];

	// Initialize the data table with your configuration
	window['runOnAwake'] = () => {
		// view specific data
		meth_map = [];

		Object.keys(invoice_state_atlas).forEach((m,id) => {
			meth_map.push({value: m,label: invoice_state_atlas[m]});
		})

		const initstuff = (m) => {
			// add_record_btn.dataset.link = linkAtlas['add_task'];

			// alert_info('loading details');
			// return;

			LaravelDataTable.init({
				// Required: Laravel API endpoint
				apiEndpoint: linkAtlas['get_all_debtors'],

				// Required: Column definitions
				columns: [
					{
						title: 'ID',
						data: 'id',
						width: '50px',
						sortable: true
					},
					{
						title: 'Client Name',
						data: 'client_name',
						sortable: true,
						render: function(value, row) {
							return `
								<div class="d-flex align-items-center gap-sm">
									<div>
										<div style="font-weight: 600;">${value}</div>
										<span style="font-size: 12px; color: var(--clr-text-muted);">${row.client_contact}</span>
									</div>
								</div>
							`;
						}
					},
					{
						title: 'payment plan',
						data: 'payment_plan',
						sortable: true,
						render: function(value) {
							let cls = value == 'monthly' ? "w3-green" : "w3-blue";
							// let con = value == 'monthly' ? "yes" : "no";
							return `<strong class="w3-tag themeround ${cls}">${value}</strong>`;
						}
					},
					{
						title: 'Credit balance',
						data: 'credit_balance',
						sortable: true,
						render: function(value) {
							let amt = Number(value);
							return `<strong class="${amt > 0 ? 'w3-text-orange' : 'text-muted'}">KES <b>${formatNumber(Number(value))}</b></strong>`;
							// return `<strong>KES ${value}</strong>`;
						}
					},
					{
						title: 'Timestamps',
						data: 'created_at',
						sortable: true,
						render: function(value, row) {
							return `
								<div class="flow gap-tn">
									<small>Registered: <b>${formatDate0(value)}</b></small>
									<small>last update: <b>${formatDate0(row.updated_at)}</b></small>
								</div>
							`;
						}
					},
					{
						title: 'Actions',
						data: 'id',
						sortable: false,
						// width: '200px',
						render: function(value, row) {
							return `
								<div class="action-btns">
									<button class="btn-action mybtn primary sm" data-runme='view_info' data-myid="${value}" title="Edit">
										<i class="fas fa-info"></i> my data
									</button>
								</div>
							`;
						}
					}
				],

				// Optional: Items per page (max 300)
				perPage: 25,

				// Optional: Enable features
				enableSearch: true,
				enableSort: true,
				enableFilters: true,

				// Optional: Filter definitions
				filters: [
					{
						name: 'status',
						label: 'Status',
						rawname: 'method',
						options: meth_map
					},
					{
						name: 'has_invoice',
						label: 'Invoices',
						options: [
							{ value: 'has', label: 'Have invoices' },
							{ value: 'none', label: 'Without Invoices' },
							{ value: 'unpaid', label: 'with Unpaid Invoices' },
							{ value: 'paid', label: 'with Paid Invoices' },
						]
					},
					{
						name: 'payment_plan',
						label: 'Payment Plan',
						options: [
							{ value: 'per_visit', label: 'Per visit' },
							{ value: 'monthly', label: 'Monthly' }
						]
					}
				],

				// Optional: Enable stats
				enableStats: true,
				statsEndpoint: linkAtlas['get_all_debtors_stats'],

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
			rectype: 'client',
			data: myn,
		}

		alert_dark(`viewing ${myn.client_name}'s details`);

		// alert_info('set current user: ' + myn.name);
		// alert_dark('set current userid: ' + myn.id);
		window['cur_record'] = cur;

		loadPage({template: linkAtlas['view_client'],sidenote: pageNames['view_client']});
	}
	window['view_info'] = (btn) => {
		let id = btn.dataset.myid;
		let myn = window['curRecordData'].filter(f => {return f.id.toString().toLowerCase() == ("" + id).toLowerCase();})[0];
		if(myn == undefined){
			alert_danger('invalid record');
			return;
		}

		window[variableAtlas['client_id']] = id;
		loadPage({template: linkAtlas['client_info'],sidenote: pageNames['client_info']});
	}

	/*
	window['form_afterfx'] = (m) => {
		// alert_danger('runnin afterfx');
		add_edit_modal_trig.click();

		// alert_dark('its done editin or addin');
		window['last_user_Add_Edit_result'] = m;

		// window['refresh_stuff']();
		refreshData();
	}
	*/
</script>
