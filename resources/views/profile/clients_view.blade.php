<?php
	// require_once __DIR__.'/piece_add_client_form.php';
?>

{{-- <!-- --}}
@include('pieces.forms_clientops')
{{-- --> --}}

<style>
	.samples_holder{
		display: flex;
		justify-content: flex-start;
	}
	.samples_holder .samplebox{
		flex: 1 0 180px;
	}
</style>

<div class="container-fluid">
	<div class="page-header">
		<div class="page-title">
			<span class="h4" data-role="pagename">--</span>
			<p>Use this page to add a new task to the database</p>
		</div>
		<div id="to_clients_mgt_btn">
			<a href="#" class="mybtn primary" data-runme="load_page" data-label="Client management"><i data-myicon="clients"></i> client management</a>
		</div>
	</div>

	<!-- Client Profile Card Component -->
	<div id="client-profile-container" class="container-fluid p-0 w3-hide">
		<div class="profile_card">
			<div class="card themeround border-0 panelbg shadow-md">
				<div class="card-header panelbg themegrad flowline overflow left centerline">
					<div class="avatar-circle bg-white text-primary">
						<span id="client-initials">M</span>
					</div>
					<div>
						<strong class="h3" id="client-name"></strong>
						<div>
							<span class="badge badge-light" id="client-gender-badge">Male</span>
							<span class="badge badge-info ml-2" id="payment-plan-badge">Per Visit</span>
						</div>
					</div>
					<div class="w3-display-right spacy-md" style="z-index: 102">
						<div class="dropdown">
							<button class="btn btn-link text-white" type="button" data-toggle="dropdown">
								<i class="fas fa-ellipsis-v"></i>
							</button>
							<div class="dropdown-menu dropdown-menu-right themeround panelbg border-0 w3-card">
								<a class="dropdown-item spacy-tn" href="#" onclick="edit_client()">
									<i class="fas fa-edit mr-2 text-muted"></i>Edit Profile
								</a>
								<a class="dropdown-item spacy-tn w3-hide" href="#" onclick="changePaymentPlan()">
									<i class="fas fa-money-check-alt mr-2 text-muted"></i>Change Payment Plan
								</a>
								<a class="dropdown-item spacy-tn w3-hide" id="credit_mgt_btn" href="#" onclick="manageCreditBalance()">
									<i class="fas fa-wallet mr-2 text-muted"></i>Manage Credit
								</a>
								<a class="dropdown-item spacy-tn" href="#" onclick="alert_danger('feature not ready yet')">
									<i class="fa fa-file-invoice mr-2 text-muted"></i>make invoice
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="card-body">
					<div class="flowline overflow w3-center stats">
						<div class="stat">
							<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Client ID</small>
							<b class="text-primary font-weight-bold"><span id="client-id">2</span></b>
						</div>
						<div class="stat">
							<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Credit Balance</small>
							<span class="text-success font-weight-bold">KES <span id="credit-balance">0.00</span></span>
						</div>
						<div class="stat">
							<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Member Since</small>
							<span class="text-dark font-weight-bold" id="member-since">--</span>
						</div>
						<div class="stat">
							<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Last Updated</small>
							<span class="text-dark font-weight-bold" id="last-updated">--</span>
						</div>
					</div>

					<!-- Quick Actions Section -->
					<div class="distance-sm">
						<span class="text-uppercase text-muted font-weight-bold">Quick Actions</span>
						<div class="row mt-3">
							<div class="col-md-3 col-sm-6 mb-3">
								<div class="action-card card themeround text-center p-3" onclick="createNewTask()">
									<div class="action-icon bg-primary text-white mx-auto">
										<i data-myicon="add_task"></i>
									</div>
									<h6 class="mb-0 font-weight-bold">New Task</h6>
									<small class="text-muted">Create task</small>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 mb-3 w3-hide">
								<div class="action-card card themeround text-center p-3" onclick="viewTasks()">
									<div class="action-icon bg-success text-white mx-auto">
										<i data-myicon="tasks"></i>
									</div>
									<h6 class="mb-0 font-weight-bold">View Tasks</h6>
									<small class="text-muted">All client Tasks</small>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 mb-3">
								<div class="action-card card themeround text-center p-3" onclick="viewSamples()">
									<div class="action-icon bg-warning text-white mx-auto">
										<i data-myicon="samples"></i>
									</div>
									<h6 class="mb-0 font-weight-bold">View Samples</h6>
									<small class="text-muted">Sample history</small>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 mb-3">
								<div class="action-card card themeround text-center p-3" onclick="viewReports()">
									<div class="action-icon bg-info text-white mx-auto">
										<i data-myicon="reports"></i>
									</div>
									<h6 class="mb-0 font-weight-bold">View Reports</h6>
									<small class="text-muted">Client reports</small>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 mb-3">
								<div class="action-card card themeround text-center p-3" onclick="viewPayments()">
									<div class="action-icon bg-info text-white mx-auto">
										<i data-myicon="payments"></i>
									</div>
									<h6 class="mb-0 font-weight-bold">View Payments</h6>
									<small class="text-muted">All payments made</small>
								</div>
							</div>
						</div>
					</div>

					<span class="text-uppercase text-muted font-weight-bold">Contact Information</span>

					<div class="flowline gap-sm overflow distance-sm">
						<div class="media gap-sm">
							<div>
								<div class="icon-circle">
									<i class="fa fa-phone"></i>
								</div>
							</div>
							<div class="media-body">
								<small class="text-muted text-uppercase d-block">Phone Number</small>
								<span class="font-weight-medium" id="client-phone">--</span>
							</div>
						</div>

						<div class="media gap-sm">
							<div>
								<div class="icon-circle">
									<i class="fa fa-venus-mars"></i>
								</div>
							</div>
							<div class="media-body">
								<small class="text-muted text-uppercase d-block">Gender</small>
								<span class="font-weight-medium text-capitalize" id="client-gender">--</span>
							</div>
						</div>
					</div>

					<span class="text-uppercase text-muted font-weight-bold">Pricing Information</span>

					<div class="distance-sm">
						<div class="samples_holder" id="sampleDataContainer">
							<div class="samplebox border slide-up" style="animation-delay: 0ms;animation-fill-mode: forwards;opacity: 0;">
								<div class="info">
									<b>Soil</b>
									<span>1,500</span>
								</div>
							</div>
						</div>
						<div class="spacy-sm">
							<button class="mybtn primary" data-runme="edit_client_pricing"><i class="fa fa-edit"></i> edit pricing</button>
						</div>
					</div>

					<span class="text-uppercase text-muted font-weight-bold">Additional Information</span>

					<div class="distance-sm border themeround">
						<a class="text-decoration-none spacy-sm flowline spread centerline" href="#additionalInfo" data-toggle="collapse" data-target="#additionalInfo">
							<span class="text-uppercase text-muted font-weight-bold">Tax & ID Details</span>
							<i class="fa fa-chevron-down"></i>
						</a>
						<div class="collapse panelbg distance-sm spacy-sm" id="additionalInfo">
							<div class="row">
								<div class="col-md-6">
									<small class="text-muted">KRA PIN</small>
									<p class="font-weight-bold mb-2" id="kra-pin-detail">--</p>
								</div>
								<div class="col-md-6">
									<small class="text-muted">ID Number</small>
									<p class="font-weight-bold mb-2" id="id-number-detail">--</p>
								</div>
							</div>
							<div class="row mt-2">
								<div class="col-md-6">
									<small class="text-muted">Added By</small>
									<p class="mb-0" id="added-by">--</p>
								</div>
								<div class="col-md-6">
									<small class="text-muted">Payment Plan</small>
									<p class="mb-0 text-capitalize" id="payment-plan-detail">--</p>
								</div>
							</div>
						</div>
					</div>

					<div>
						<div class="spacy-sm w3-center">
							<small class="text-muted">
								<i class="fas fa-clock mr-1"></i>
								Profile loaded at <b id="load-time" class="themetxt">--</b>
							</small>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="blank">
		<div class="spacy-lg w3-center">
			<span class="h3">Info</span>
			<p>loading client record ...</p>
		</div>
	</div>
</div>

<script>
	let clientData = {
        "id": 2,
        "added_by": 3,
        "client_name": "Michael Omondi",
        "client_contact": "+254798765432",
        "client_gender": "male",
        "payment_plan": "per_visit",
        "credit_balance": "0.00",
        "kra_pin": null,
        "id_number": "23456789",
        "extra_details": {
            "membership_type": "Standard",
            "referral_source": "Facebook"
        },
        "created_at": "2024-01-20T08:15:00.000000Z",
        "updated_at": "2024-01-20T08:15:00.000000Z"
    };

	window['runOnAwake'] = () => {
		to_clients_mgt_btn.querySelector('a').dataset.link = linkAtlas['view_clients'];

		if(!mypermissions.includes('MANAGE_CLIENTS')){
			to_clients_mgt_btn.remove();
		}

		if(window['cur_record'] !== undefined){
			if(window['cur_record'].rectype !== "client"){
				alert_danger('invalid action, try again');
				return;
			} else {
				clientData = window['cur_record'].data;
			}
		} else {
			if(clientData !== {}){
				alert_info('loading dummy data')
			} else {
				alert_danger('invalid request method, try again');
			}
		}

		initializeClientProfile(clientData);
		refreshUI(300);
	}

	window['runOnLeave'] = () => {
		// delete(window['runOnAwake']);
		delete(window['initializeClientProfile']);
		delete(window['edit_client']);
		delete(window['form_afterfx']);
	}

	window['initializeClientProfile'] = (data) => {
		// Hide placeholders
		blank.classList.add('w3-hide');
		document.querySelector('#client-profile-container').classList.remove('w3-hide');

		// Set basic info
		document.getElementById('client-id').textContent = data.id;
		document.getElementById('client-name').textContent = data.client_name;
		document.getElementById('client-initials').textContent = data.client_name.charAt(0).toUpperCase();
		document.getElementById('client-phone').textContent = data.client_contact || "---";
		document.getElementById('client-gender').textContent = data.client_gender;

		// Set badges
		document.getElementById('client-gender-badge').textContent = data.client_gender.charAt(0).toUpperCase() + data.client_gender.slice(1);
		const paymentBadge = document.getElementById('payment-plan-badge');
		credit_mgt_btn.classList.add('w3-hide');

		if(data.payment_plan === 'monthly') {
			paymentBadge.textContent = 'Monthly';
			paymentBadge.className = 'badge w3-green ml-2';

			if(mypermissions.includes('MANAGE_CLIENT_CREDIT')){
				credit_mgt_btn.classList.remove('w3-hide');
			}
		} else {
			paymentBadge.textContent = 'Per Visit';
			paymentBadge.className = 'badge w3-black ml-2';
			zeclass = 'add';
		}

		// Credit balance
		const creditBalance = parseFloat(data.credit_balance);
		const balanceSpan = document.getElementById('credit-balance');
		balanceSpan.textContent = creditBalance.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

		if(creditBalance > 0) {
			balanceSpan.parentElement.classList.remove('text-danger');
			balanceSpan.parentElement.classList.add('text-success');
		} else if(creditBalance < 0) {
			balanceSpan.parentElement.classList.remove('text-success');
			balanceSpan.parentElement.classList.add('text-danger');
		}

		// Format dates
		const createdDate = new Date(data.created_at);
		const updatedDate = new Date(data.updated_at);

		document.getElementById('member-since').textContent = formatDate0(createdDate);
		document.getElementById('last-updated').textContent = formatDate0(updatedDate);

		// Additional details
		document.getElementById('kra-pin-detail').textContent = data.kra_pin || 'Not provided';
		document.getElementById('id-number-detail').textContent = data.id_number || 'Not provided';
		document.getElementById('added-by').textContent = `${data.mycreator.name || '--'}`;
		document.getElementById('payment-plan-detail').textContent = data.payment_plan.replace('_', ' ');

		// Update load time
		document.getElementById('load-time').textContent = new Date().toLocaleTimeString();

		// pricing data
		let pr_dta = bizdata.pricing_data;
		let pr_use = {...pr_dta,...clientData.payment_data};

		let p_data = document.querySelector('#sampleDataContainer');
		p_data.innerHTML = '';

		Object.keys(pr_use).forEach((d,n) => {
			p_data.innerHTML += `
				<div class="samplebox border slide-up" style="${mekstagger(200,n)}">
					<div class="info">
						<b>${d}</b>
						<span>${formatNumber(pr_use[d],0)}</span>
					</div>
				</div>
			`;
		})
	}

	window['changePaymentPlan'] = () => {
		console.log('Change payment plan clicked');
		alert_danger('payment plan logic not implemented yet');
	}
	window['manageCreditBalance'] = () => {
		change_credit_form.recid.value = clientData.id;
		change_credit_form.client_name_2.value = clientData.client_name;
		change_credit_form.user_credit.value = clientData.credit_balance;

		change_credit_modal_trig.click();
	}
	window['deactivateClient'] = () => {
		confirmAction('Deactivate Client','Are you sure you want to deactivate this client account?');
	}

	window['edit_client'] = (e) => {
		// if(e === undefined){return;}

		roleop = "edit";
		rec_id = clientData.id;
		add_edit_form.recid.value = rec_id;

		let myn = clientData;

		// window['userBeingEdited'] = myn;

        add_edit_form.client_name.value = myn.client_name;
		add_edit_form.client_contact.value = myn.client_contact;
		add_edit_form.client_gender.value = myn.client_gender;
		add_edit_form.payment_plan.value = myn.payment_plan;
		add_edit_form.kra_pin.value = myn.kra_pin;
		add_edit_form.id_number.value = myn.id_number;

		// add_edit
		// add_edit_form.user_role_name.value = myn.my_roles.id;
		// add_edit_form.user_role_name.value = myn.role_name;

		add_edit_modal_trig.click();

		// alert_info(rec_id);
		// alert_info(e.textContent);
		add_edit_title.innerHTML = "Edit Client Details";
	}
	window['edit_client_pricing'] = (el) => {
		edit_paydata_form.recid.value = clientData.id;

		let pr_dta = bizdata.pricing_data;
		let pr_use = {...pr_dta,...clientData.payment_data};

		edit_paydata_form.price_soil.value = pr_use.Soil;
		edit_paydata_form.price_rock.value = pr_use.Rock;
		edit_paydata_form.price_solution.value = pr_use.Solution;
		edit_paydata_form.price_carbon.value = pr_use.Carbon;

		edit_paydata_trig.click();
	}
	window['form_afterfx'] = (m) => {
		// alert_dark('runnin afterfx');
		let caller = m.el;
		let trig = caller.dataset.trigger;
		let modal_trig = document.querySelector(`#${trig}`);

		if(modal_trig){
			modal_trig.click();
		} else {
			alert_danger(`invalid trigger for form [#${caller.id}]`);
		}

		caller.reset();

		// window['lastClientEditResult'] = m;
		clientData = m.data.sendme;
		initializeClientProfile(clientData);
	}

	// Quick Action handlers
	window['openinfo'] = (p,x) => {
		window[variableAtlas['client_id']] = clientData.id;
		window[variableAtlas['t_i_act']] = p || undefined;
		window[variableAtlas['t_i_args']] = x || undefined;

		// alert_dark(`will run: ${p}`);

		let senddeets = {
			template: linkAtlas['client_info'],
			sidenote: pageNames['client_info'],
		};

		loadPage(senddeets);
	}

	window['createNewTask'] = () => {
		window['task_client_data'] = clientData;

		loadPage({
			template: linkAtlas['add_task'],
			sidenote: pageNames['add_task'],
		});
	}
	window['viewTasks'] = () => {
		alert_info(`Viewing client's tasks`);
		openinfo('showthis','tasks');
		// Navigate to samples view
	}
	window['viewSamples'] = () => {
		alert_info(`Viewing client's samples`);
		openinfo('showthis','samples');
		// Navigate to samples view
	}

	window['viewPayments'] = () => {
		alert_info(`Viewing client's payment history`);
		openinfo('showthis','payments');
		// Navigate to payments view
	}

	window['viewReports'] = () => {
		alert_info(`Viewing client's reports`);
		openinfo('showthis','reports');
	}
</script>
