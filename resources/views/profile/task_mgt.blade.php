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

	/* Modern Table */
		.table-modern {
			width: 100%;
			border-collapse: separate;
			border-spacing: 0;
		}
		.table-modern thead th {
			background: var(--light-gray);
			padding: 14px 20px;
			font-size: 12px;
			font-weight: 600;
			color: var(--gray);
			text-transform: uppercase;
			letter-spacing: 0.5px;
			border-bottom: 1px solid var(--clr-border);
			white-space: nowrap;
			cursor: pointer;
			user-select: none;
			transition: background 0.3s;
		}
		.table-modern thead th:hover {
			background: #e2e8f0;
		}
		.table-modern thead th i {
			margin-left: 5px;
			font-size: 10px;
		}
		.table-modern tbody tr {
			transition: all 0.3s;
		}
		.table-modern tbody tr:hover {
			background: #f8fafc;
		}
		.table-modern tbody td {
			padding: 16px 20px;
			border-bottom: 1px solid var(--clr-border);
			font-size: 14px;
			vertical-align: middle;
		}
		.table-modern tbody tr:last-child td {
			border-bottom: none;
		}
		.checkbox-custom {
			width: 18px;
			height: 18px;
			border: 2px solid var(--border);
			border-radius: 4px;
			cursor: pointer;
			transition: all 0.3s;
			appearance: none;
			position: relative;
		}
		.checkbox-custom:checked {
			background: var(--primary);
			border-color: var(--primary);
		}
		.checkbox-custom:checked::after {
			content: '\f00c';
			font-family: 'Font Awesome 5 Free';
			font-weight: 900;
			color: white;
			font-size: 10px;
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
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
			<span class="h4">Task management</span>
			<p>Here you can add, view, find and modify all Task records</p>
		</div>
		<div class="header-actions">
			<button class="mybtn secondary" onclick="refreshData()">
				<i class="fas fa-sync-alt"></i>
				Refresh
			</button>
			<!-- <button class="mybtn primary" data-runme="add_record"> -->
			<button class="mybtn primary" id="add_record_btn" data-runme="load_page" data-label="User management">
				<i class="fas fa-plus"></i>
				Add New
			</button>
		</div>
	</div>

	<div class="stats-row w3-hide themeround" id="statsContainer">
		<!-- Stats will be populated dynamically -->
	</div>

	<!-- Filter Bar (Optional - can be configured) -->
	<div class="filter-bar w3-hide_ themeround" id="filterContainer">
		<span class="h4">Filters</span>
		<div class="filter-row">
			<!-- Filters will be populated dynamically -->
		</div>
	</div>

	<!-- Data Container -->
	<div class="data-container" style="position: relative;">
		<!-- Loading Overlay -->
		<div class="loading-overlay" id="loadingOverlay">
			<div class="spinner"></div>
		</div>

		<!-- Data Header -->
		<div class="data-header">
			<div class="d-flex align-items-center gap-3">
				<h5 class="data-title" id="tableTitle">Task records</h5>
			</div>
			<div class="data-actions">
				<div class="search-box">
					<input type="text" placeholder="Quick search..." id="quickSearch">
					<button><i class="fas fa-search"></i></button>
				</div>
			</div>
		</div>

		<!-- Table View -->
		<div class="table-responsive" id="tableView">
			<table class="table-modern">
				<thead id="tableHead">
					<!-- Table headers will be generated dynamically -->
				</thead>
				<tbody id="tableBody">
					<!-- Table body will be populated by LaravelDataTable -->
				</tbody>
			</table>
		</div>

		<!-- Empty State -->
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

<?php
	/*
	require_once __DIR__.'/piece_add_client_form.php';
	require_once __DIR__.'/datatables_code.php';
	// */
?>


{{-- <!-- --}}
@include('pieces.codes_datatables')
{{-- --> --}}

<script>
	// Initialize the data table with your configuration
	window['runOnAwake'] = () => {
		// view specific data
		let state_atlas = {
			0 : 'pending',
			1 : 'awaiting_payment',
			2 : 'complete',
		};
		let payment_state_atlas = {
			0 : 'pending',
			1 : 'incomplete',
			2 : 'paid',
		};
		let payment_state_filter = [];
		let state_filter = [];
		Object.keys(payment_state_atlas).forEach(k => {
			let tmp = {value: k, label: payment_state_atlas[k]};
			payment_state_filter.push(tmp);
		});
		Object.keys(state_atlas).forEach(k => {
			let tmp = {value: k, label: state_atlas[k]};
			state_filter.push(tmp);
		});

		const initstuff = (m) => {
			add_record_btn.dataset.link = linkAtlas['add_task'];

			// alert_info('loading details');
			// return;

			LaravelDataTable.init({
				// Required: Laravel API endpoint
				apiEndpoint: './data/tasks', // Change this to your endpoint

				// Required: Column definitions
				columns: [
					{
						title: 'ID',
						data: 'id',
						width: '50px',
						sortable: true
					},
					{
						title: 'Task number',
						data: 'task_no',
						sortable: true,
						render: function(value) {
							return `<strong>${value}</strong>`;
						}
					},
					{
						title: 'Client name',
						data: 'client_name',
						sortable: true,
						render: function(value) {
							return `<strong>${value}</strong>`;
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
						title: 'payment status',
						data: 'payment_status',
						sortable: true,
						render: function(value) {
							let cls = value == '2' ? "w3-text-green" : "w3-black_";
							// let con = value == 'monthly' ? "yes" : "no";
							return `<strong class="${cls}">${payment_state_atlas[value]}</strong>`;
						}
					},
					{
						title: 'Status',
						data: 'status',
						sortable: true,
						render: function(value) {
							let cls = value == '2' ? "w3-green" : "w3-blue";
							// let con = value == 'monthly' ? "yes" : "no";
							return `<strong class="w3-tag themeround ${cls}">${state_atlas[value]}</strong>`;
						}
					},
					{
						title: 'is paid',
						data: 'is_paid',
						sortable: true,
						render: function(value) {
							let cls = value == true ? "w3-green" : "w3-black";
							let con = value == true ? "yes" : "no";
							return `<strong class="w3-tag themeround ${cls}">${con}</strong>`;
						}
					},
					{
						title: 'Actions',
						data: 'id',
						sortable: false,
						width: '200px',
						render: function(value, row) {
							return `
								<div class="action-btns">
									<button class="mybtn primary sm" onclick="viewRecord(${value})" title="View">
										<i class="fas fa-cog"></i> Manage
									</button>
									<button class="btn-action btn-edit w3-hide" data-runme='edit_record' data-myid="${value}" title="Edit">
										<i class="fas fa-edit"></i> edit
									</button>
								</div>
							`;
						}
					}
				],

				// Optional: Items per page (max 300)
				perPage: 10,

				// Optional: Enable features
				enableSearch: true,
				enableSort: true,
				enableFilters: true,

				// Optional: Filter definitions
				filters: [
					{
						name: 'payment_status',
						label: 'Payment Status',
						rawname: 'Payment Status',
						options: payment_state_filter
					},
					{
						name: 'Status',
						label: 'status',
						rawname: 'status',
						options: state_filter
					},
					{
						name: 'is_paid',
						label: 'fully paid',
						rawname: 'fully paid',
						options: [
							{value: 'true',label: 'paid'},
							{value: 'false',label: 'not paid'},
						]
					}
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

		window[fetch_bypass]('./data/user_roles',{},'get').then((d) => {
			responseHandler(d,initstuff,d,true);
			// alert_info('render this');
		})
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
			rectype: 'task',
			data: myn,
		}

		// alert_dark(`viewing ${myn.id}'s details`);

		// alert_info('set current user: ' + myn.name);
		// alert_dark('set current userid: ' + myn.id);
		window['cur_record'] = cur;

		loadPage({template: linkAtlas['view_task'],sidenote: 'Task Information'});
	}

	function deleteRecord(id) {
		if (confirmAction('Delete User?','Are you sure you want to delete this User?')) {
			// Add your delete logic here
			showToast('success', 'Deleted', `User ${id} deleted successfully`);
			LaravelDataTable.refresh();
		}
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

	window['delete_user'] = (e) => {
		roleop = "edit";
		rec_id = e.dataset.id;

		confirmAction();
		alert_info(e.textContent);
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
