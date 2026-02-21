<style>
	#client-profile-container{
		--width: 900px;
	}
	.profile_card{
		background: var(--clr-panelbg);
		border-radius: var(--roundness);
		max-width: 900px;
		margin: 0 auto;
	}
	.profile_card .card-header{
		position: relative;
		width: 100%;
		border-radius: var(--roundness) var(--roundness) 0 0;
		color: #fff;
	}
	#client-profile-container .avatar-circle {
		--w: 48px;
		width: var(--w);
		height: var(--w);
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 1.75rem;
		font-weight: 700;
		box-shadow: 0 4px 15px rgba(0,0,0,0.15);
		border: 3px solid rgba(255,255,255,0.3);
	}

	.profile_card .client_stats .client_stat{
		flex: 1 0 175px;
		padding: 12px 0;
	}

	.profile_card .media{
		display: flex;
		flex-wrap: nowrap;
		flex-direction: row;
		width: 100%;
		padding: 12px 0;
		flex: 1 0 calc(var(--width) * 0.4);
	}
	.profile_card .icon-circle {
		--w: 48px;
		width: var(--w);
		height: var(--w);
		border-radius: 50%;
		color: var(--themecolor);
		background-color: #eef;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 1rem;
	}

	.action-card {
		transition: all 0.3s ease;
		cursor: pointer;
		border: 2px solid transparent;
	}
	.action-card:hover {
		transform: translateY(-2px);
		box-shadow: 0 4px 12px rgba(0,0,0,0.1);
		border-color: var(--themecolor);
	}
	.action-card .action-icon {
		width: 56px;
		height: 56px;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 1.5rem;
		margin-bottom: 12px;
	}
</style>

<div id="to_clients_mgt_btn">
	<a href="#" class="backbtn" data-runme="load_page" data-label="Client management"><i class="fa fa-arrow-left"></i> back to <b>client management</b></a>
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
							<a class="dropdown-item spacy-tn" href="#" onclick="editClient()">
								<i class="fas fa-edit mr-2 text-muted"></i>Edit Profile
							</a>
							<a class="dropdown-item spacy-tn w3-hide" href="#" onclick="changePaymentPlan_()">
								<i class="fas fa-money-check-alt mr-2 text-muted"></i>Change Payment Plan
							</a>
							<a class="dropdown-item spacy-tn" href="#" onclick="manageCreditBalance()">
								<i class="fas fa-wallet mr-2 text-muted"></i>Manage Credit
							</a>
							<a class="dropdown-item text-danger border-top spacy-tn w3-hide" id="deact_client_btn" href="#" onclick="deactivateClient()">
								<i class="fas fa-user-slash mr-2"></i>Deactivate
							</a>
						</div>
					</div>
				</div>
			</div>

			<div class="card-body">
				<div class="flowline overflow w3-center client_stats">
					<div class="client_stat">
						<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Client ID</small>
						<b class="text-primary font-weight-bold"><span id="client-id">2</span></b>
					</div>
					<div class="client_stat">
						<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Credit Balance</small>
						<span class="text-success font-weight-bold">KES <span id="credit-balance">0.00</span></span>
					</div>
					<div class="client_stat">
						<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Member Since</small>
						<span class="text-dark font-weight-bold" id="member-since">--</span>
					</div>
					<div class="client_stat">
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
									<i class="fas fa-plus"></i>
								</div>
								<h6 class="mb-0 font-weight-bold">New Task</h6>
								<small class="text-muted">Create task</small>
							</div>
						</div>
						<div class="col-md-3 col-sm-6 mb-3">
							<div class="action-card card themeround text-center p-3" onclick="viewJobs()">
								<div class="action-icon bg-success text-white mx-auto">
									<i class="fas fa-briefcase"></i>
								</div>
								<h6 class="mb-0 font-weight-bold">View Jobs</h6>
								<small class="text-muted">All client jobs</small>
							</div>
						</div>
						<div class="col-md-3 col-sm-6 mb-3">
							<div class="action-card card themeround text-center p-3" onclick="viewSamples()">
								<div class="action-icon bg-warning text-white mx-auto">
									<i class="fas fa-flask"></i>
								</div>
								<h6 class="mb-0 font-weight-bold">View Samples</h6>
								<small class="text-muted">Sample history</small>
							</div>
						</div>
						<div class="col-md-3 col-sm-6 mb-3">
							<div class="action-card card themeround text-center p-3" onclick="viewReports()">
								<div class="action-icon bg-info text-white mx-auto">
									<i class="fas fa-chart-bar"></i>
								</div>
								<h6 class="mb-0 font-weight-bold">View Reports</h6>
								<small class="text-muted">Client reports</small>
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
								<small class="text-muted">Added By (User ID)</small>
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

@include('pieces.forms_clientops')

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
			// to_clients_mgt_btn.remove();
		}

		if(!mypermissions.includes('DEACTIVATE_CLIENT') && false){
			deact_client_btn.remove();
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

	function initializeClientProfile(data) {
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
		if(data.payment_plan === 'monthly') {
			paymentBadge.textContent = 'Monthly';
			paymentBadge.className = 'badge badge-success ml-2';
		} else {
			paymentBadge.textContent = 'Per Visit';
			paymentBadge.className = 'badge badge-info ml-2';
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

		document.getElementById('member-since').textContent = formatDate(createdDate);
		document.getElementById('last-updated').textContent = formatDate(updatedDate);

		// Additional details
		document.getElementById('kra-pin-detail').textContent = data.kra_pin || 'Not provided';
		document.getElementById('id-number-detail').textContent = data.id_number || 'Not provided';
		document.getElementById('added-by').textContent = `User #${data.added_by}`;
		document.getElementById('payment-plan-detail').textContent = data.payment_plan.replace('_', ' ');

		// Update load time
		document.getElementById('load-time').textContent = new Date().toLocaleTimeString();
	}

	function formatDate(date) {
		return date.toLocaleDateString('en-US', {
			month: 'short',
			day: 'numeric',
			year: 'numeric'
		});
	}

	function formatDateTime(date) {
		return date.toLocaleString('en-US', {
			year: 'numeric',
			month: '2-digit',
			day: '2-digit',
			hour: '2-digit',
			minute: '2-digit',
			second: '2-digit',
			hour12: false
		}).replace(/(\d+)\/(\d+)\/(\d+),/, '$3-$1-$2');
	}

	// Action handlers
	function editClient() {
		console.log('Edit client clicked');
		// Implement your edit logic here
		window['edit_client']();
	}

	function changePaymentPlan() {
		console.log('Change payment plan clicked');
		// Implement payment plan change logic
		alert('Payment plan change functionality would open here');
	}

	function manageCreditBalance() {
		// console.log('Manage credit clicked');
		// alert('Credit balance management functionality would open here');

		change_credit_form.recid.value = clientData.id;
		change_credit_form.client_name_2.value = clientData.client_name;
		change_credit_form.user_credit.value = clientData.credit_balance;

		change_credit_modal_trig.click();
	}

	function deactivateClient() {
		confirmAction('Deactivate Client','Are you sure you want to deactivate this client account?');
	}

	// Quick Action handlers
	function createNewTask() {
		console.log('Create new task for client:', clientData.id);
		alert(`Creating new task for ${clientData.client_name}`);
		// Implement new task creation
	}

	function viewJobs() {
		console.log('View jobs for client:', clientData.id);
		alert(`Viewing jobs for ${clientData.client_name}`);
		// Implement jobs view
	}

	function viewSamples() {
		console.log('View samples for client:', clientData.id);
		alert(`Viewing samples for ${clientData.client_name}`);
		// Implement samples view
	}

	function viewReports() {
		console.log('View reports for client:', clientData.id);
		alert(`Viewing reports for ${clientData.client_name}`);
		// Implement reports view
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
		add_edit_title.innerHTML = "Edit user Details";
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

		// window['lastClientEditResult'] = m;
		clientData = m.data.sendme;
		initializeClientProfile(clientData);

		// TODO: make it that it sends back the modified data for this page to show again
		// window['refresh_stuff']();
		// refreshData();
	}
</script>
