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

	.sample-info-card {
		background: white;
		border: 1px solid var(--themecolor);
		border-radius: 8px;
		padding: 20px;
		margin-bottom: 20px;
		box-shadow: 0 2px 4px rgba(0,0,0,0.05);
	}

	.sample-info-card h5 {
		color: var(--themecolor);
		font-weight: 600;
		margin-bottom: 15px;
		border-bottom: 1px solid var(--themecolor);
		padding-bottom: 8px;
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

<button class="mybtn primary w3-hide" id="view_invoice_modal_trig" data-toggle="modal" data-target="#invoice_info_modal">
	<i data-myicon="invoices"></i>
	View Invoice details
</button>

<div class="modal fade" id="invoice_info_modal" tabindex="-1" role="dialog" aria-labelledby="invoice_info_heading" aria-hidden="true" style="z-index: 102;">
	<div class="modal-dialog modal-mg" role="document">
		<div class="modal-content themeround borderless">
			<div class="modal-header">
				<span class="h3 modal-title" id="invoice_info_heading">Invoice Information</span>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div id="InvoiceDataContainer"></div>
			</div>
			<div class="modal-footer w3-hide">
				<button type="button" class="mybtn secondary" data-dismiss="modal">Cancel</button>
				<!-- <button type="button" class="mybtn primary" data-submitme="#add_edit_form">Save</button> -->
			</div>
		</div>
	</div>
</div>


<button class="mybtn primary w3-hide" id="add_invoice_payment_trig" data-toggle="modal" data-target="#add_invoice_payment_modal">
	<i class="fas fa-plus"></i>
	Add payment
</button>

<div class="modal fade" id="add_invoice_payment_modal" tabindex="-1" role="dialog" aria-labelledby="add_invoice_payment_title" aria-hidden="true" style="z-index: 102;">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content themeround borderless">
			<div class="modal-header">
				<span class="h3 modal-title" id="add_invoice_payment_title">Add Payment</span>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div>
					<p>Enter the details to add a payment entry for this invoice</p>
				</div>
				<div data-subrole="cur_invoice_report"></div>
				<form id="add_invoice_payment_form" action="defined:add_payment_invoice" data-onsubmit="sendform" data-blockdefault="yes" data-callback="add_pay_form_afterfx" method="post">
					<input type="hidden" name="invoice_id" value="">
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
				<button type="button" class="mybtn primary" data-submitme="#add_invoice_payment_form">Add payment</button>
			</div>
		</div>
	</div>
</div>

<script>
	let invoice = {};
	let modalon = false;

	// Render results table
	function renderInvoiceData(dta) {
		console.log('invoice: ',dta);
		let rectype = 'invoice';

		// Validate rectype
		if (!dta || dta.rectype !== rectype) {
			alert_danger(`Invalid data: rectype must be ${rectype}`);
			return;
		}

		invoice = dta.data;
		let payment = dta.data;
		const container = document.getElementById('InvoiceDataContainer');

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

		let invoice_info = {
			invoice_no : invoice.invoice_no,
			total : formatNumber(invoice.total),
			total_paid : formatNumber(invoice.total_paid),
			balance : formatNumber(invoice.balance),
			payment_progress : `<b class="themetxt">${formatNumber(invoice.total_paid)}</b> / <b>${formatNumber(invoice.total)}</b>`,
			number_of_tasks : invoice.tasks.length,
			// task_ids : invoice.invoice_no,
			// invoice_data : invoice.invoice_no,
			status : `<span>${invoice_state_atlas[invoice.status]}</span>`,
			date_of_issue : `<span>${formatDate0(invoice.created_at)}</span>`,
		};
		let invoice_outht = '';
		Object.keys(invoice_info).forEach(f => {
			invoice_outht += `
				<div class="info-row">
					<span class="info-label">${f.split("_").join(' ')}:</span>
					<span class="info-value">${invoice_info[f]}</span>
				</div>
			`;
		});

		let invoice_setup_outht = setup_simpleTable('data-role="tasks_table"');

		let mekpaybtn = invoice.balance > 0 ? `
			<button class="mybtn primary" data-runme="trymekpayment">
				<i class="${iconAtlas['payments']}"></i> Make a Payment
			</button>
		` : '';

		// return;
		// Build the interface
		const html = `
			<div class="sample-header themegrad w3-display-container">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span class="h4">invoice: ${invoice.invoice_no}</span>
						<div class="flow overflow left" style="margin-top: 8px;gap: 8px;">
							<div>Created: <b>${formatDate(invoice.created_at)}</b></div>
							<div>Payment Progress: <b>${formatNumber(invoice.total_paid)}</b> / <b>${formatNumber(invoice.total)}</b></div>
							<div>Balance: <b>${formatNumber(invoice.balance)}</b></div>
						</div>
					</div>
				</div>
			</div>

			<!-- Action Buttons -->
			<div class="flowline overflow spacy-md">
				<div class="flowline centerline center spacy-sm w3-hide_">
					<button class="mybtn secondary" onclick="viewClient_invoices(${invoice.client.id})" data-openthis="clientinfo">
						<i class="${iconAtlas['clients']}"></i> View Client
					</button>
					<button class="mybtn secondary" onclick="regen_invoice()" data-openthis="clientinfo">
						<i class="${iconAtlas['export_file']}"></i> Regenerate Invoice
					</button>
					${mekpaybtn}
				</div>
				<div class="border-left w3-hide-small"></div>
				<div class="flowline centerline center spacy-sm">
					<a class="mybtn secondary sm" data-runme="downloadPdf" target="blank" href="${linkAtlas['getinvoice_img']}/${invoice.image_link}"><i class="fa fa-download"></i> download as image</a>
					<!--
					<a class="mybtn secondary sm" data-runme="downloadPdf" target="blank" href="${linkAtlas['getinvoice_pdf']}/${invoice.pdf_link}"><i class="fa fa-download"></i> download as pdf</a>
					// -->
					<button class="mybtn primary sm" data-runme="printPdf"><i class="fa fa-print"></i> print invoice</button>
					<button class="mybtn primary sm" onclick="openWhatsApp('${invoice.client.client_contact}')" data-openthis="clientinfo">
						<i class="fab fa-whatsapp"></i> Client whatsapp
					</button>
				</div>
			</div>

			<div class="sample-info-card w3-hide_">
				<h5><i class="${iconAtlas['invoices']}"></i> invoice Information</h5>
				<div>
					${invoice_outht}
				</div>
			</div>

			<div class="distance-sm border themeround collapser">
				<a class="text-decoration-none spacy-sm flowline spread centerline" href="#invoice_info" data-toggle="collapse" data-target="#invoice_info">
					<span class="text-uppercase text-muted font-weight-bold"><i class="fa fa-list"></i> Invoice Breakdown</span>
					<i class="fa fa-chevron-down myicon"></i>
				</a>
				<div class="collapse panelbg distance-sm spacy-sm" id="invoice_info">
					${invoice_setup_outht}
					<div class="info-row mt-4">
						<span class="info-label">Total paid:</span>
						<span class="info-value">${formatNumber(invoice.total_paid)}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Total Required:</span>
						<span class="info-value">${formatNumber(invoice.total)}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Balance:</span>
						<span class="info-value">${formatNumber(invoice.balance)}</span>
					</div>
				</div>
			</div>

			<div class="distance-sm border themeround collapser">
				<a class="text-decoration-none spacy-sm flowline spread centerline" href="#taskInfo" data-toggle="collapse" data-target="#taskInfo">
					<span class="text-uppercase text-muted font-weight-bold"><i class="${iconAtlas['clients']}"></i> Client Information</span>
					<i class="fa fa-chevron-down myicon"></i>
				</a>
				<div class="collapse panelbg distance-sm spacy-sm" id="taskInfo">
					<div class="info-row">
						<span class="info-label">Client Name:</span>
						<span class="info-value">${invoice.client.client_name}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Contact:</span>
						<span class="info-value">${invoice.client.client_contact}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Gender:</span>
						<span class="info-value">${invoice.client.client_gender}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Payment Plan:</span>
						<span class="info-value">${invoice.client.payment_plan}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Credit Balance:</span>
						<span class="info-value">KES ${formatNumber((+invoice.client.credit_balance).toFixed(2))}</span>
					</div>
					<div class="info-row">
						<button class="mybtn secondary" onclick="viewClient_invoices(${invoice.client.id})" data-openthis="clientinfo">
							<i class="${iconAtlas['clients']}"></i> View Client
						</button>
					</div>
				</div>
			</div>

			<div class="distance-sm border themeround collapser">
				<a class="text-decoration-none spacy-sm flowline spread centerline" href="#userInfo" data-toggle="collapse" data-target="#userInfo">
					<span class="text-uppercase text-muted font-weight-bold"><i class="${iconAtlas['user']}"></i> Creator Information</span>
					<i class="fa fa-chevron-down myicon"></i>
				</a>
				<div class="collapse panelbg distance-sm spacy-sm" id="userInfo">
					<div class="info-row">
						<span class="info-label">Name:</span>
						<span class="info-value">${invoice.creator.name}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Email:</span>
						<span class="info-value">${invoice.creator.email}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Phone:</span>
						<span class="info-value">${invoice.creator.phone_number}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Last Login:</span>
						<span class="info-value">${formatDate(invoice.creator.last_login_at)}</span>
					</div>
				</div>
			</div>
		`;

		container.innerHTML = html;

		let inv_reports = document.querySelectorAll('[data-subrole="cur_invoice_report"]');

		inv_reports.forEach(i => {
			i.innerHTML = `
				<div class="info-row">
					<span class="info-label">Invoice:</span>
					<span class="info-value">${invoice.invoice_no}</span>
				</div>
				<div class="info-row">
					<span class="info-label">Total paid:</span>
					<span class="info-value">${formatNumber(invoice.total_paid)}</span>
				</div>
				<div class="info-row">
					<span class="info-label">Total Required:</span>
					<span class="info-value">${formatNumber(invoice.total)}</span>
				</div>
				<div class="info-row">
					<span class="info-label">Balance:</span>
					<span class="info-value">${formatNumber(invoice.balance)}</span>
				</div>
			`;
			i.classList.add('border-bottom');
			i.classList.add('mb-4');
		})

		setTimeout(d => {
			render_invoice_setup();
		},200);
		refreshUI(300);
	}

	function render_invoice_setup() {
		console.log('invoice: ',invoice);
		const data = {
			data: invoice.tasks,
			cols: [
				{
					cap: 'ID',
					field: 'id',
					width: '50px',
				},
				{
					cap: 'Date added',
					field: 'created_at',
					show: function(value) {
						return formatDate0(value);
						// return `<span class="badge ${value}">${value}</span>`;
					}
				},
				{
					cap: 'Task Number',
					field: 'task_no',
					show: function(value) {
						return `<strong>${value}</strong>`;
					}
				},
				{
					cap: 'Samples Delivered',
					field: 'sample_count',
					show: function(value) {
						return `<b>${value}</b>`;
					}
				},
				{
					cap: 'payment',
					field: 'balance',
					show: function(value,r) {
						return `<b class="themetxt">${formatNumber(r.paid)}</b> / <b>${formatNumber(r.cost)}</b>`;
					}
				},
				{
					cap: 'Balance',
					field: 'balance',
					show: function(value,r) {
						return `<b>${formatNumber(value)}</b>`;
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
			tablename: 'tasks_table',
			caption: 'Invoice tasks',
			viewcall: 'viewTask_invoices',
			rowprops: 'data-rectype="invoice"',
		};

		console.log('invoice setup data: ',data);
		render_simpleTable(data);
	}

	window['viewTask_invoices'] = (el) => {
		alert_info('opening task');
		window[variableAtlas['task_id']] = el.myid;

		let payld = {
			template: linkAtlas['task_info'],
			sidenote: pageNames['task_info'],
		};
		loadPage(payld);
	}
	window['viewClient_invoices'] = (clientId) => {
		alert_info('opening client Information');
		window[variableAtlas['client_id']] = invoice.client.id;

		let payld = {
			template: linkAtlas['client_info'],
			sidenote: pageNames['client_info'],
		};
		loadPage(payld);
	}
	window['regen_invoice'] = () => {
		alert_info('opening invoice creation page');
		window[variableAtlas['invoice_id']] = invoice.id;
		window[variableAtlas['client_id']] = invoice.client.id;

		let payld = {
			template: linkAtlas['mek_invoice'],
			sidenote: pageNames['mek_invoice'],
		};
		loadPage(payld);
	}

	window['_inv_showmodal'] = (f) => {
		let wot = !f ? 'hide' : 'show';
		modalon = f;
		$('#invoice_info_modal').modal(wot);
	}

	window['trymekpayment'] = (el) => {
		setup_payment_form(invoice.id,'_inv_resetstuff');
		_inv_showmodal(false);

		setTimeout(() => {
			add_invoice_payment_trig.click();
		},300);
	}
	window['setup_payment_form'] = (t_id,cbk) => {
		add_invoice_payment_form.invoice_id.value = t_id;

		if(cbk !== undefined){
			add_invoice_payment_form.dataset.lastcallback = cbk;
		}
	}
	window['_inv_resetstuff'] = (d) => {
		let cur = {
			rectype: 'invoice',
			data: d.data.sendme,
		}

		renderInvoiceData(cur);
		if(!modalon){
			_inv_showmodal(true);
		}

		if(typeof window['refreshData'] == 'function'){
			refreshData();
		}
	}

	window['add_pay_form_afterfx'] = (d) => {
		let el = d.el;
		let cbk = el.dataset.lastcallback;

		add_invoice_payment_trig.click();
		el.reset();
		el.pay_method.value = '';

		if(cbk !== undefined){
			window[cbk](d);
		}
	}

	window['printPdf'] = () => {
		alert_info('printing invoice');

		setTimeout(e => {
			// run print ops
			alert_warning('printing');
			const printWindow = window.open('./print_invoice', '_blank');
			// Get the canvas as a data URL
			const dataUrl = `${linkAtlas['getinvoice_img']}/${invoice.image_link}`;

			// Write HTML to the print window
			// printWindow.location.href = './print_invoice';

			printWindow.document.write(`
				<!DOCTYPE html>
				<html>
					<head>
						<title>Invoice ${invoice.invoice_no}</title>
						<style>
							body {
								margin: 0;
								padding: 0;
								display: flex;
								justify-content: center;
								align-items: flex-start;
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
		},400);
	}
</script>
