<?php
	/*
	require_once __DIR__.'/piece_task_view_styles.php';
	// require_once __DIR__.'/view_task_0.php';
	// */
?>

{{-- <!-- --}}
@include('pieces.task_view_styles')
{{-- --> --}}

<div id="to_tasks_mgt_btn">
	<a href="#" class="backbtn" data-runme="load_page" data-label="Tasks management"><i class="fa fa-arrow-left"></i> back to <b>tasks management</b></a>
</div>

<!-- Task Profile Card Component -->
<div id="task-profile-container" class="container-fluid p-0 w3-hide">
	<div class="profile_card">
		<div class="card themeround border-0 panelbg shadow-md">
			<div class="card-header panelbg themegrad flowline overflow left centerline">
				<div>
					<div class="flowline overflow left">
						<strong class="h3" id="task-number" style="display: inline-block;width: auto !important;">--</strong>
						<button class="mybtn trans border" data-copythis="#task-number"><i class="fa fa-copy"></i></button>
					</div>
					<div class="distance-sm">
						<span class="badge status-pending" id="task-status-badge">
							<i class="fas fa-clock"></i> Pending
						</span>
						<span class="badge payment-unpaid ml-2" id="payment-status-badge">
							<i class="fas fa-credit-card"></i> Unpaid
						</span>
					</div>
				</div>
				<div class="w3-display-right spacy-md" style="z-index: 102">
					<div class="dropdown">
						<button class="btn btn-link text-white" type="button" data-toggle="dropdown">
							<i class="fas fa-ellipsis-v"></i>
						</button>
						<div class="dropdown-menu dropdown-menu-right themeround panelbg border-0 w3-card">
							<a class="dropdown-item spacy-tn" href="#" onclick="recordPayment()" id="option_payment_btn">
								<i class="fas fa-money-bill-wave mr-2 text-muted"></i>Record Payment
							</a>
							<a class="dropdown-item spacy-tn" href="#" onclick="addNotes()" id="option_note_btn">
								<i class="fas fa-sticky-note mr-2 text-muted"></i>Add Notes
							</a>
							<!-- <div class="dropdown-divider"></div> -->
							<a class="dropdown-item text-danger spacy-tn" id="cancel_task_btn" href="#" onclick="cancelTask()">
								<i class="fas fa-ban mr-2"></i>Cancel Task
							</a>
						</div>
					</div>
				</div>
			</div>

			<div class="card-body">
				<div class="flowline overflow w3-center stats">
					<div class="stat">
						<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Client</small>
						<span class="font-weight-bold" data-copyme="#client-name">--</span>
					</div>
					<div class="stat w3-hide">
						<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Task ID</small>
						<b class="text-primary font-weight-bold"><span id="task-id">--</span></b>
					</div>
					<div class="stat">
						<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Created</small>
						<span class="text-dark font-weight-bold" id="task-created">--</span>
					</div>
					<div class="stat">
						<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Last Updated</small>
						<span class="text-dark font-weight-bold" id="task-updated">--</span>
					</div>
					<div class="stat">
						<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Status</small>
						<span class="font-weight-bold" id="task-status-text">--</span>
					</div>
				</div>

				<!-- task based stats -->
				<div class="flowline overflow w3-center stats border-top distance-sm w3-padding-16">
					<div class="stat">
						<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Payment status</small>
						<span class="font-weight-bold hideicon" data-copyme="#payment-status-badge">--</span>
					</div>
					<div class="stat">
						<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Samples</small>
						<span class="font-weight-bold" id="samples_count">--</span>
					</div>
					<div class="stat">
						<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Payments</small>
						<span class="font-weight-bold" id="payments_count">--</span>
					</div>
					<div class="stat">
						<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Reports</small>
						<span class="font-weight-bold" id="reports_count">--</span>
					</div>
				</div>

				<div class="flowline overflow w3-center stats border-top distance-sm w3-padding-16">
					<div class="stat">
						<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Price</small>
						<span class="font-weight-bold" id="task_cost">3,000</span>
					</div>
					<div class="stat">
						<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Amount Paid</small>
						<span class="font-weight-bold" id="task_paid">1,250</span>
					</div>
					<div class="stat">
						<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Balance</small>
						<span class="font-weight-bold" id="task_balance">1,750</span>
					</div>
					<div class="stat">
						<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Payment Report</small>
						<span class="font-weight-bold hideicon" id="task_payment_report"><b>1,250 / 3,000</b></span>
					</div>
				</div>

				<!-- Quick Actions Section -->
				<div class="distance-sm">
					<span class="text-uppercase text-muted font-weight-bold">Quick Actions</span>
					<div class="row mt-3">
						<div class="col-lg-3 col-md-3 col-sm-6 mb-3">
							<div class="action-card card themeround text-center p-3" onclick="viewSamples()">
								<div class="action-icon bg-warning text-white mx-auto">
									<i class="fas fa-flask"></i>
								</div>
								<h6 class="mb-0 font-weight-bold">Samples</h6>
								<small class="text-muted">View samples</small>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6 mb-3">
							<div class="action-card card themeround text-center p-3" onclick="viewPayments()">
								<div class="action-icon bg-success text-white mx-auto">
									<i class="fas fa-dollar-sign"></i>
								</div>
								<h6 class="mb-0 font-weight-bold">Payments</h6>
								<small class="text-muted">Payment history</small>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6 mb-3">
							<div class="action-card card themeround text-center p-3" onclick="viewReports()">
								<div class="action-icon bg-info text-white mx-auto">
									<i class="fas fa-file-alt"></i>
								</div>
								<h6 class="mb-0 font-weight-bold">Reports</h6>
								<small class="text-muted">View reports</small>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6 mb-3">
							<div class="action-card card themeround text-center p-3" onclick="viewClient()">
								<div class="action-icon bg-primary text-white mx-auto">
									<i class="fas fa-user"></i>
								</div>
								<h6 class="mb-0 font-weight-bold">Client</h6>
								<small class="text-muted">View details</small>
							</div>
						</div>
					</div>
				</div>

				<span class="text-uppercase text-muted font-weight-bold">Client Information</span>

				<div class="flowline gap-sm overflow distance-sm">
					<div class="media gap-sm" style="cursor: pointer;" onclick="viewClient()">
						<div>
							<div class="icon-circle">
								<i class="fa fa-user"></i>
							</div>
						</div>
						<div class="media-body">
							<small class="text-muted text-uppercase d-block">Client Name</small>
							<span class="font-weight-medium" id="client-name">--</span>
							<a href="#" class="ml-2 small">
								<i class="fas fa-external-link-alt"></i>
							</a>
						</div>
					</div>

					<div class="media gap-sm">
						<div>
							<div class="icon-circle">
								<i class="fa fa-phone"></i>
							</div>
						</div>
						<div class="media-body">
							<small class="text-muted text-uppercase d-block">Client Contact</small>
							<span class="font-weight-medium" id="client-contact">--</span>
						</div>
					</div>

					<div class="media gap-sm">
						<div>
							<div class="icon-circle">
								<i class="fa fa-credit-card"></i>
							</div>
						</div>
						<div class="media-body">
							<small class="text-muted text-uppercase d-block">Payment Plan</small>
							<span class="font-weight-medium text-capitalize" id="payment-plan">--</span>
						</div>
					</div>

					<div class="media gap-sm">
						<div>
							<div class="icon-circle">
								<i class="fa fa-money-bill"></i>
							</div>
						</div>
						<div class="media-body">
							<small class="text-muted text-uppercase d-block">Credit balance</small>
							<span class="font-weight-medium text-capitalize" id="credit-balance">--</span>
						</div>
					</div>
				</div>

				<span class="text-uppercase text-muted font-weight-bold">Task Creator</span>

				<div class="distance-sm">
					<div class="media gap-sm" style="cursor: pointer;" onclick="viewCreator()">
						<div>
							<div class="icon-circle">
								<i class="fa fa-user-tie"></i>
							</div>
						</div>
						<div class="media-body">
							<small class="text-muted text-uppercase d-block">Created By</small>
							<span class="font-weight-medium" id="creator-name">--</span>
							<a href="#" class="ml-2 small" onclick="viewCreator(); return false;">
								<i class="fas fa-external-link-alt"></i>
							</a>
							<div class="distance-sm w3-hide">
								<small class="text-muted">
									<i class="fas fa-envelope mr-1"></i><span id="creator-email">--</span>
								</small>
								<small class="text-muted ml-3">
									<i class="fas fa-phone mr-1"></i><span id="creator-phone">--</span>
								</small>
							</div>
						</div>
					</div>
				</div>

				<!-- Task Notes Section -->
				<div class="distance-sm border themeround">
					<a class="text-decoration-none spacy-sm flowline spread centerline" href="#taskNotes" data-toggle="collapse" data-target="#taskNotes">
						<span class="text-uppercase text-muted font-weight-bold">Task Notes</span>
						<i class="fa fa-chevron-down"></i>
					</a>
					<div class="collapse panelbg distance-sm spacy-sm" id="taskNotes">
						<div id="notes-content" class="p-2">
							<p class="text-muted mb-0" id="task-notes">No notes added yet</p>
						</div>
						<button class="btn btn-sm btn-outline-primary mt-2" onclick="addNotes()">
							<i class="fas fa-plus mr-1"></i> Add/Edit Notes
						</button>
					</div>
				</div>

				<!-- Additional Details Section -->
				<div class="distance-sm border themeround">
					<a class="text-decoration-none spacy-sm flowline spread centerline" href="#additionalDetails" data-toggle="collapse" data-target="#additionalDetails">
						<span class="text-uppercase text-muted font-weight-bold">Additional Details</span>
						<i class="fa fa-chevron-down"></i>
					</a>
					<div class="collapse panelbg distance-sm spacy-sm" id="additionalDetails">
						<div class="row">
							<div class="col-md-6">
								<small class="text-muted">Task Number</small>
								<p class="font-weight-bold mb-2 font-monospace" id="task-no-detail">--</p>
							</div>
							<div class="col-md-6">
								<small class="text-muted">Added By (User ID)</small>
								<p class="font-weight-bold mb-2" id="added-by">--</p>
							</div>
						</div>
						<div class="row mt-2">
							<div class="col-md-6">
								<small class="text-muted">Client ID</small>
								<p class="mb-0" id="client-id-detail">--</p>
							</div>
							<div class="col-md-6">
								<small class="text-muted">Payment Status Code</small>
								<p class="mb-0" id="payment-status-code">--</p>
							</div>
						</div>
						<div class="row mt-2">
							<div class="col-md-6">
								<small class="text-muted">Status Code</small>
								<p class="mb-0" id="status-code">--</p>
							</div>
							<div class="col-md-6">
								<small class="text-muted">Is Paid</small>
								<p class="mb-0">
									<span class="badge" id="is-paid-badge">--</span>
								</p>
							</div>
						</div>
						<div class="row mt-2">
							<div class="col-md-6">
								<small class="text-muted">Date created</small>
								<p class="mb-0" data-copyme="#task-created">--</p>
							</div>
							<div class="col-md-6">
								<small class="text-muted">Date updated</small>
								<p class="mb-0" data-copyme="#task-updated">--</p>
							</div>
						</div>
					</div>
				</div>

				<div>
					<div class="spacy-sm w3-center">
						<small class="text-muted">
							<i class="fas fa-clock mr-1"></i>
							Task loaded at <b id="load-time" class="themetxt">--</b>
						</small>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="blank">
	<div class="spacy-lg w3-center">
		<span class="h3" id="err_hed">Info</span>
		<p id="error_par">loading task record ...</p>
	</div>
</div>

<script>
	let taskData = {
		"rectype": "task",
		"data": {
			"id": 10,
			"task_no": "TSK_20260301_001",
			"added_by": 1,
			"client_id": 4,
			"client_name": "Buvan nakaveli jr",
			"status": 0,
			"payment_status": 1,
			"is_paid": false,
			"notes": null,
			"created_at": "2026-03-01T14:08:31.000000Z",
			"updated_at": "2026-03-01T14:08:31.000000Z",
			"creator": {
				"id": 1,
				"name": "CoryG",
				"email": "corygshava@gmail.com",
				"phone_number": "+254754079047",
				"email_verified_at": null,
				"created_at": "2026-02-14T13:04:28.000000Z",
				"updated_at": "2026-03-01T10:56:14.000000Z",
				"user_roles_id": 1,
				"last_login_at": "2026-03-01T10:56:14.000000Z",
				"is_active": 1
			},
			"client": {
				"id": 4,
				"added_by": 1,
				"client_name": "Buvan nakaveli jr",
				"client_contact": "0634228577",
				"client_gender": "male",
				"payment_plan": "monthly",
				"credit_balance": "0.00",
				"kra_pin": null,
				"id_number": "",
				"extra_details": null,
				"created_at": "2026-02-21T15:33:50.000000Z",
				"updated_at": "2026-03-01T14:08:32.000000Z"
			}
		}
	};

	// Status mapping
	const statusMap = {
		0: { label: 'Pending', class: 'w3-white', icon: 'fa-clock' },
		1: { label: 'awaiting payment', class: 'w3-orange', icon: 'fa-spinner' },
		2: { label: 'Complete', class: 'w3-green', icon: 'fa-check-circle' },
		3: { label: 'Cancelled', class: 'w3-red', icon: 'fa-times-circle' }
	};

	const paymentStatusMap = {
		0: { label: 'Unpaid', class: 'payment-unpaid', icon: 'fa-times' },
		1: { label: 'payment incomplete', class: 'payment-partial', icon: 'fa-exclamation' },
		2: { label: 'Fully Paid', class: 'payment-paid', icon: 'fa-check' }
	};

	window['runOnAwake'] = () => {
		to_tasks_mgt_btn.querySelector('a').dataset.link = linkAtlas['view_tasks'];

		if(!mypermissions.includes('MANAGE_TASKS')){
			to_tasks_mgt_btn.remove();
		}

		if(!mypermissions.includes('CANCEL_TASK')){
			cancel_task_btn.remove();
		}

		err_hed.innerHTML = 'Error';
		taskData = taskData.data;

		// /*
		if(window['cur_record'] !== undefined){
			if(window['cur_record'].rectype == 'task'){
				taskData = window['cur_record'].data;
			} else {
				error_par.innerHTML = 'Invalid record type, try again';
				return;
			}
		} else {
			error_par.innerHTML = 'No task data available';
			return;
		}
		// */

		// dynamic_ui.innerHTML = dd(taskData);
		// return;
		initializeTaskProfile(taskData);
		refreshUI(300);
	}

	window['runOnLeave'] = () => {
		// alert_danger('leaving task viewer');

		// remove cur_record to prevent issues when viewing clients or users
		// delete(window['cur_record']);
		delete(window['initializeTaskProfile']);
		delete(window['openinfo']);
		delete(window['updateStatus']);
		delete(window['openclient']);
		delete(window['recordPayment']);
		delete(window['addNotes']);
		delete(window['cancelTask']);
		delete(window['viewClient']);
		delete(window['viewSamples']);
		delete(window['viewPayments']);
		delete(window['viewReports']);
		delete(window['viewCreator']);
		delete(window['viewTaskDetails']);
		delete(window['edit_task']);
		delete(window['runOnLeave']);
	}

	window['initializeTaskProfile'] = (data) => {
		console.log("task profile passed: ", data);

		// options
		let declass = 'add';

		declass = (data.payment_status == 2) ? 'add' : 'remove';
		option_payment_btn.classList[declass]('w3-hide');

		declass = (data.status == 2 || data.status == 3) ? 'add' : 'remove';
		option_note_btn.classList[declass]('w3-hide');

		blank.classList.add('w3-hide');
		document.querySelector('#task-profile-container').classList.remove('w3-hide');

		// basic info
		document.getElementById('task-number').textContent = data.task_no.toLocaleString();
		document.getElementById('task_cost').textContent = Math.floor(data.cost).toLocaleString();
		document.getElementById('task_paid').textContent = Math.floor(data.paid).toLocaleString();
		document.getElementById('task_balance').textContent = Math.floor(data.balance).toLocaleString();
		document.getElementById('task_payment_report').innerHTML = `<b class="themetxt">${Math.floor(data.paid).toLocaleString()}</b> / ${data.cost.toLocaleString()}`;

		// Status badges
		const statusInfo = statusMap[data.status] || statusMap[0];
		const paymentInfo = paymentStatusMap[data.payment_status] || paymentStatusMap[0];

		console.log("pay info: ",paymentInfo, data.payment_status);
		// return;

		const statusBadge = document.getElementById('task-status-badge');
		statusBadge.className = `mybadge ${statusInfo.class}`;
		statusBadge.innerHTML = `<i class="fas ${statusInfo.icon}"></i> ${statusInfo.label}`;

		const paymentBadge = document.getElementById('payment-status-badge');
		paymentBadge.className = `mybadge ${paymentInfo.class} ml-2`;
		paymentBadge.innerHTML = `<i class="fas ${paymentInfo.icon}"></i> ${paymentInfo.label}`;

		document.getElementById('task-status-text').textContent = statusInfo.label;

		// Format dates
		const createdDate = new Date(data.created_at);
		const updatedDate = new Date(data.updated_at);

		document.getElementById('task-created').textContent = formatDate0(createdDate);
		document.getElementById('task-updated').textContent = formatDate0(updatedDate);

		// Client information
		document.getElementById('client-name').textContent = data.client_name;
		document.getElementById('client-contact').textContent = data.client.client_contact;
		document.getElementById('payment-plan').textContent = data.client.payment_plan.replace('_', ' ');
		document.getElementById('credit-balance').textContent = data.client.credit_balance.replace('_', ' ');

		// Creator information
		document.getElementById('creator-name').textContent = data.creator.name;
		// document.getElementById('creator-email').textContent = data.creator.email;
		// document.getElementById('creator-phone').textContent = data.creator.phone_number;

		// task stats
		document.querySelector('#samples_count').textContent = data.samples_count;
		document.querySelector('#payments_count').textContent = data.payments_count;
		document.querySelector('#reports_count').textContent = data.reports_count || 0;

		// Task notes
		if(data.notes) {
			document.getElementById('task-notes').textContent = data.notes;
			document.getElementById('task-notes').classList.remove('text-muted');
		}

		// Additional details
		document.getElementById('task-no-detail').textContent = data.task_no;
		document.getElementById('added-by').textContent = `User #${data.added_by}`;
		document.getElementById('client-id-detail').textContent = `Client #${data.client_id}`;
		document.getElementById('payment-status-code').textContent = data.payment_status;
		document.getElementById('status-code').textContent = data.status;

		const isPaidBadge = document.getElementById('is-paid-badge');
		if(data.is_paid) {
			isPaidBadge.textContent = 'Yes';
			isPaidBadge.className = 'badge badge-success';
		} else {
			isPaidBadge.textContent = 'No';
			isPaidBadge.className = 'badge badge-danger';
		}

		// Update load time
		document.getElementById('load-time').textContent = new Date().toLocaleTimeString();
	}

	window['openinfo'] = (p,x) => {
		window[variableAtlas['task_id']] = taskData.id;
		window[variableAtlas['t_i_act']] = p || undefined;
		window[variableAtlas['t_i_args']] = x || undefined;

		// alert_dark(`will run: ${p}`);

		let senddeets = {
			template: linkAtlas['task_info'],
			sidenote: pageNames['task_info'],
		};

		loadPage(senddeets);
	}
	window['openclient'] = () => {
		window['cur_client_id'] = taskData.client.id;

		let senddeets = {
			template: linkAtlas['client_info'],
			sidenote: pageNames['client_info'],
			// afterfx: p || undefined,
		};

		loadPage(senddeets);
	}

	// Action handlers
	window['updateStatus'] = () => {
		alert_danger('sorry but task status is done automatically as you process its samples');
	}

	window['recordPayment'] = () => {
		alert_info('enter details to record payments');
		openinfo('add_payment');
	}

	window['addNotes'] = () => {
		// alert_info('enter the notes you want to add');
		openinfo('add_notes');
	}

	// [vetline] --------------------------------------------------------------------------------------------------

	window['cancelTask'] = () => {
		confirmAction('Cancel Task', 'Are you sure you want to cancel this task? This action cannot be undone.');
	}

	// Quick Action handlers
	window['viewClient'] = () => {
		alert_info(`Viewing client: ${taskData.client_name}`);
		window['openclient']();
	}

	window['viewSamples'] = () => {
		alert_info(`Viewing samples for task ${taskData.task_no}`);
		openinfo('showthis','samples');
		// Navigate to samples view
	}

	window['viewPayments'] = () => {
		alert_info(`Viewing payment history for task ${taskData.task_no}`);
		openinfo('showthis','payments');
		// Navigate to payments view
	}

	window['viewReports'] = () => {
		alert_info(`Viewing reports for task ${taskData.task_no}`);
		openinfo('showthis','reports');
	}

	window['viewCreator'] = () => {
		alert_info(`Viewing user profile: ${taskData.creator.name}`);
		openinfo('showthis','user');
	}

	window['viewTaskDetails'] = () => {
		alert_info('Detailed task information view would open here');
		openinfo();
	}

	window['edit_task'] = () => {
		// Add your edit task form logic here
		console.log('Opening edit form for task:', taskData);
	}
</script>
