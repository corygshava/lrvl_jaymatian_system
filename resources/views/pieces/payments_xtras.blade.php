<style>
	.sample-header {
		color: white;
		padding: 16px 24px;
		border-radius: var(--roundness);
		margin-bottom: 20px;
	}

	.sample-header h3 {
		margin: 0;
		font-weight: 600;
	}

	.info-row {
		display: flex;
		padding: 8px 0;
		border-bottom: 1px solid #f0f0f0;
	}

	.info-row:last-child {
		border-bottom: none;
	}

	.info-label {
		font-weight: 600;
		color: #555;
		width: 180px;
		flex-shrink: 0;
	}

	.info-value {
		color: #333;
		flex-grow: 1;
	}

	.badge-status {
		padding: 6px 12px;
		border-radius: 20px;
		font-size: 12px;
		font-weight: 600;
	}

	.status-0 {background-color: #ffc107;color: #000;}
	.status-1 {background-color: #444;color: #fff;}
	.status-2 {background-color: #28a745;color: white;}

	.status-paid {
		background-color: #17a2b8;
		color: white;
	}

	.status-unpaid {
		background-color: #dc3545;
		color: white;
	}

	.action-buttons {
		display: flex;
		gap: 10px;
		margin-bottom: 20px;
	}

	.collapsible-section {
		background: white;
		border: 1px solid #e0e0e0;
		border-radius: 8px;
		margin-bottom: 15px;
		overflow: hidden;
	}

	.collapsible-header {
		background: #f8f9fa;
		padding: 15px 20px;
		cursor: pointer;
		display: flex;
		justify-content: space-between;
		align-items: center;
		transition: background 0.2s;
	}

	.collapsible-header:hover {
		background: #e9ecef;
	}

	.collapsible-header h6 {
		margin: 0;
		font-weight: 600;
		color: #333;
	}

	.collapsible-body {
		padding: 20px;
		display: none;
	}

	.collapsible-body.show {
		display: block;
	}

	.results-table {
		width: 100%;
		margin-top: 20px;
	}

	.results-table th {
		/*font-weight: 600;*/
		padding: 12px;
	}

	.results-table td {
		padding: 10px 12px;
	}

	.results-table tbody tr.secondary_rows{
		background: #777;
		color: #fff;
		/*border-top: 1px solid var(--themecolor) !important;*/
	}
	.results-table tbody tr.secondary_rows:hover{
		color: #444;
	}
	.results-table tbody tr:nth-child(even) {
		/*background-color: #f8f9fa;*/
	}

	.results-table tbody tr:hover {
		background-color: #e9ecef;
	}

	.sample-type-badge {
		display: inline-block;
		padding: 4px 10px;
		border-radius: 4px;
		font-size: 12px;
		font-weight: 600;
		text-transform: uppercase;
	}

	.type-solution {
		background-color: #e3f2fd;
		color: #1976d2;
	}

	.type-soil {
		background-color: #fff3e0;
		color: #f57c00;
	}

	.type-carbon {
		background-color: #f3e5f5;
		color: #7b1fa2;
	}
</style>

<button class="mybtn primary w3-hide" id="add_payment_trig" data-toggle="modal" data-target="#add_payment_modal">
	<i class="fas fa-plus"></i>
	Add payment
</button>

<div class="modal fade" id="add_payment_modal" tabindex="-1" role="dialog" aria-labelledby="add_edit_title" aria-hidden="true" style="z-index: 102;">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content themeround borderless">
			<div class="modal-header">
				<span class="h3 modal-title" id="add_edit_title">Add Payment</span>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<form id="add_payment_form" action="defined:add_payment" data-onsubmit="sendform" data-blockdefault="yes" data-callback="add_pay_form_afterfx" method="post">
					<input type="hidden" name="taskid" value="">
					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="admin_password">Your Password</label>
							<input type="password" class="form-control-custom" id="admin_password" name="admin_password" placeholder="enter password for verification" required>
						</div>
						<div class="form-group">
							<label class="form-label" for="pay_method">Payment Method</label>
							<select class="form-control-custom" id="pay_method" name="pay_method" required>
								<option disabled value="">select a payment method</option>
								<option>Mpesa</option>
								<option>Cash</option>
							</select>
						</div>
						<div class="form-group">
							<label class="form-label" for="amount">Amount</label>
							<input type="number" class="form-control-custom" id="amount" name="amount" placeholder="how much was paid" required>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="mybtn secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="mybtn primary" data-submitme="#add_payment_form">Add payment</button>
			</div>
		</div>
	</div>
</div>

<button class="mybtn primary w3-hide" id="view_payment_modal_trig" data-toggle="modal" data-target="#payment_info_modal">
	<i class="fas fa-plus"></i>
	View Payment details
</button>

<div class="modal fade" id="payment_info_modal" tabindex="-1" role="dialog" aria-labelledby="payment_info_heading" aria-hidden="true" style="z-index: 102;">
	<div class="modal-dialog modal-mg" role="document">
		<div class="modal-content themeround borderless">
			<div class="modal-header">
				<span class="h3 modal-title" id="payment_info_heading">Payment Information</span>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div id="paymentDataContainer"></div>
			</div>
			<div class="modal-footer w3-hide">
				<button type="button" class="mybtn secondary" data-dismiss="modal">Cancel</button>
				<!-- <button type="button" class="mybtn primary" data-submitme="#add_edit_form">Save</button> -->
			</div>
		</div>
	</div>
</div>

<script>
	// alert_dark('what the act')
	let payment = {};
	let pay_type_classes = {
		'invoice' : 'alert-primary w3-text-black',
		'task' : 'alert-warning w3-text-black',
		'debt_payment' : 'w3-cobalt w3-text-white',
	};

	window['task_state_atlas'] = [
		'Pending',
		'awaiting payment',
		'Complete',
		'Cancelled',
	];

	window['renderPayData'] = (dta) => {
		// clear stuff from last session

		// Validate rectype
		if (!dta || dta.rectype !== 'payment') {
			alert_danger('Invalid data: rectype must be "payment"');
			return;
		}

		payment = dta.data;
		const container = document.getElementById('paymentDataContainer');

		container.innerHTML = 'loading...';

		// Helper function to format dates
		function formatDate(dateString) {
			return formatDate0(dateString);
		}

		// Helper function to get status badge
		function getStatusBadge(status) {
			return `<span class="badge-status status-${status}">${status}</span>`;
		}

		function getPaymentBadge(isPaid) {
			return isPaid
				? '<span class="mybadge status-paid">Paid</span>'
				: '<span class="mybadge status-unpaid">Unpaid</span>';
		}

		let toshow = {};
		let assoc = payment.task != null ? 'Task' : 'Invoice';
		let my_icon = assoc.toLowerCase() + 's';
		let assocBtn = payment.task != null ? `
			<button class="mybtn secondary" onclick="viewTask_payments(${payment.task.id})" data-openthis="taskinfo">
				<i class="fas fa-folder"></i> View Task
			</button>
		` : '';
		let assocSerial = payment.task != null ? payment.task.task_no : payment.invoice.invoice_no;

		if(payment.task != null){
			let mytask = payment.task;
			toshow = {
				task_number : mytask.task_no,
				status : `<b class="mybadge w3-black">${task_state_atlas[payment.task.status]}</b>`,
				payment_status : getPaymentBadge(payment.task.is_paid),
				created_at : formatDate(payment.task.created_at),
			};
		} else if(payment.invoice != null){
			let invoice = payment.invoice;
			toshow = {
				invoice_no : invoice.invoice_no,
				total : formatNumber(invoice.total),
				total_paid : formatNumber(invoice.total_paid),
				balance : formatNumber(invoice.balance),
				payment_progress : `<b class="themetxt">${formatNumber(invoice.total_paid)}</b> / <b>${formatNumber(invoice.total)}</b>`,
				number_of_tasks : invoice.tasks_count,
				status : `<span>${invoice_state_atlas[invoice.status]}</span>`,
				date_of_issue : `<span>${formatDate0(invoice.created_at)}</span>`,
			};
		}

		let tdd = 'assocInfo';
		let task_outht = `
			<div class="distance-sm border themeround collapser">
				<a class="text-decoration-none spacy-sm flowline spread centerline" href="#${tdd}" data-toggle="collapse" data-target="#${tdd}">
					<span class="text-uppercase text-muted font-weight-bold"><i class="${iconAtlas[my_icon]}"></i> ${assoc} Information</span>
					<i class="fa fa-chevron-down myicon"></i>
				</a>
				<div class="collapse panelbg distance-sm spacy-sm" id="${tdd}">
		`;
		Object.keys(toshow).forEach(f => {
			task_outht += `
				<div class="info-row">
					<span class="info-label">${f.split("_").join(' ')}:</span>
					<span class="info-value">${toshow[f]}</span>
				</div>
			`;
		});

		task_outht += `
					<div class="info-row">
						${assocBtn}
					</div>
				</div>
			</div>
		`;

		// return;
		// Build the interface
		const html = `
			<div class="sample-header themegrad w3-display-container">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span class="h4">Payment: ${assocSerial}</span>
						<div class="flowline overflow gap-sm left" style="margin-top: 8px;">
							<div class="distance-tn">Method: <b>${payment.method}</b></div>
							<div class="distance-tn">amount: <b>${Number(payment.amount).toLocaleString()}</b></div>
						</div>
					</div>
				</div>

				<div class="spacy-md w3-display-right w3-hide"><b>pending</b></div>
			</div>

			<!-- Action Buttons -->
			<div class="flowline centerline center spacy-sm w3-hide_">
				<button class="mybtn primary w3-hide" data-toggle="collapse" data-target="#add_result_form">
					<i class="fas fa-plus-circle"></i> Add Result
				</button>
				${assocBtn}
				<button class="mybtn secondary" onclick="viewClient_payments(${payment.owner.id})" data-openthis="clientinfo">
					<i class="fas fa-users"></i> View Client
				</button>
			</div>

			<div class="sample-info-card w3-hide_">
				<h5><i class="fas fa-money-bill"></i> Payment Information</h5>
				<div class="info-row">
					<span class="info-label">Payment Purpose:</span>
					<span class="info-value"><b class="mybadge text-sm ${pay_type_classes[payment.payment_type]}">${payment.payment_type}</b></span>
				</div>
				<div class="info-row">
					<span class="info-label">Client Name:</span>
					<span class="info-value">${payment.owner.client_name}</span>
				</div>
				<div class="info-row">
					<span class="info-label">Amount:</span>
					<span class="info-value">KES ${parseFloat(payment.amount).toLocaleString()}</span>
				</div>
				<div class="info-row">
					<span class="info-label">Method Used:</span>
					<span class="info-value">${payment.method}</span>
				</div>
				<div class="info-row">
					<span class="info-label">Created:</span>
					<span class="info-value">${formatDate(payment.created_at)}</span>
				</div>
			</div>

			${task_outht}

			<div class="distance-sm border themeround collapser">
				<a class="text-decoration-none spacy-sm flowline spread centerline" href="#taskInfo" data-toggle="collapse" data-target="#taskInfo">
					<span class="text-uppercase text-muted font-weight-bold"><i class="fas fa-users"></i> Client Information</span>
					<i class="fa fa-chevron-down myicon"></i>
				</a>
				<div class="collapse panelbg distance-sm spacy-sm" id="taskInfo">
					<div class="info-row">
						<span class="info-label">Client Name:</span>
						<span class="info-value">${payment.owner.client_name}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Contact:</span>
						<span class="info-value">${payment.owner.client_contact}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Gender:</span>
						<span class="info-value">${payment.owner.client_gender}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Payment Plan:</span>
						<span class="info-value">${payment.owner.payment_plan}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Credit Balance:</span>
						<span class="info-value">KES ${parseFloat(payment.owner.credit_balance).toFixed(2).toLocaleString()}</span>
					</div>
					<div class="info-row">
						<button class="mybtn secondary" onclick="viewClient_payments(${payment.owner.id})" data-openthis="clientinfo">
							<i class="fas fa-users"></i> View Client
						</button>
					</div>
				</div>
			</div>

			<div class="distance-sm border themeround collapser">
				<a class="text-decoration-none spacy-sm flowline spread centerline" href="#userInfo" data-toggle="collapse" data-target="#userInfo">
					<span class="text-uppercase text-muted font-weight-bold"><i class="fas fa-user-tie"></i> Creator Information</span>
					<i class="fa fa-chevron-down myicon"></i>
				</a>
				<div class="collapse panelbg distance-sm spacy-sm" id="userInfo">
					<div class="info-row">
						<span class="info-label">Name:</span>
						<span class="info-value">${payment.mycreator.name}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Email:</span>
						<span class="info-value">${payment.mycreator.email}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Phone:</span>
						<span class="info-value">${payment.mycreator.phone_number}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Last Login:</span>
						<span class="info-value">${formatDate(payment.mycreator.last_login_at)}</span>
					</div>
				</div>
			</div>
		`;

		container.innerHTML = html;

		// setTimeout(d => {render_results_data();},100);
	}

	window['viewTask_payments'] = () => {
		alert_info('opening task');
		window[variableAtlas['task_id']] = payment.task.id;

		let payld = {
			template: linkAtlas['task_info'],
			sidenote: pageNames['task_info'],
		};
		loadPage(payld);
	}

	window['viewClient_payments'] = (clientId) => {
		alert_info('opening client Information');
		window[variableAtlas['client_id']] = payment.owner.id;

		let payld = {
			template: linkAtlas['client_info'],
			sidenote: pageNames['client_info'],
		};
		loadPage(payld);
	}

	window['showmodal'] = (f) => {
		let wot = !f ? 'hide' : 'show';
		$('#samples_info_modal').modal(wot);
	}

	window['setup_payment_form'] = (t_id,cbk) => {
		add_payment_form.taskid.value = t_id;

		if(cbk !== undefined){
			add_payment_form.dataset.lastcallback = cbk;
		}
	}

	window['add_pay_form_afterfx'] = (d) => {
		let el = d.el;
		let cbk = el.dataset.lastcallback;

		add_payment_trig.click();
		el.reset();
		el.pay_method.value = '';

		if(cbk !== undefined){
			window[cbk]();
		}
	}
</script>
