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

<button class="mybtn primary w3-hide" id="view_report_modal_trig" data-toggle="modal" data-target="#report_info_modal">
	<i class="fas fa-plus"></i>
	View Report details
</button>

<div class="modal fade" id="report_info_modal" tabindex="-1" role="dialog" aria-labelledby="report_info_heading" aria-hidden="true" style="z-index: 102;">
	<div class="modal-dialog modal-mg" role="document">
		<div class="modal-content themeround borderless">
			<div class="modal-header">
				<span class="h3 modal-title" id="report_info_heading">Payment Information</span>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div id="DataContainer"></div>
			</div>
			<div class="modal-footer w3-hide">
				<button type="button" class="mybtn secondary" data-dismiss="modal">Cancel</button>
				<!-- <button type="button" class="mybtn primary" data-submitme="#add_edit_form">Save</button> -->
			</div>
		</div>
	</div>
</div>

<script>
	let report = {};

	// Render results table
	function renderResultsTable(results,xtra_results) {
		let tableHTML = `
			<table class="table table-bordered results-table">
				<thead>
					<tr>
						<th class="w3-hide">ID</th>
						<th>Sample ID</th>
						<th>AU (ppm)</th>
						<th>CU (ppm)</th>
						<th class="w3-hide">Date Added</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody id="results_table_data">
		`;

		if (!results || results.length === 0) {
			tableHTML += '<tr><td class="w3-center spacy-sm" colspan="6"><i>No saved results</i></td></tr>';
		} else {
			results.forEach(result => {
				tableHTML += `
					<tr>
						<td class="w3-hide">${result.id}</td>
						<td>${result.sample_name}</td>
						<td>${result.au_ppm}</td>
						<td>${result.cu_ppm}</td>
						<td class="w3-hide">${formatDate(result.date_added)}</td>
						<td></td>
					</tr>
				`;
			});
		}

		if(xtra_results !== undefined || xtra_results.length > 0){
			xtra_results.forEach((r, n) => {
				tableHTML += `
					<tr class="secondary_rows">
						<td class="w3-hide">${r.id}</td>
						<td>${r.sample_id}</td>
						<td>${r.au_ppm}</td>
						<td>${r.cu_ppm}</td>
						<td class="w3-hide">${formatDate(r.date_added)}</td>
						<td>
							<button type="button" class="mybtn secondary sm" data-runme="edit_result" data-myid="${n}">edit</button>
							<button type="button" class="mybtn bg-danger w3-text-white sm" data-runme="delete_result" data-myid="${n}">delete</button>
						</td>
					</tr>
				`;
			});
		}

		tableHTML += '</tbody></table>';
		return tableHTML;
	}

	function renderData(dta) {
		let rectype = 'report';

		// Validate rectype
		if (!dta || dta.rectype !== rectype) {
			alert_danger(`Invalid data: rectype must be ${rectype}`);
			return;
		}

		report = dta.data;
		let payment = dta.data;
		const container = document.getElementById('DataContainer');

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

		let report_info = {
			'company_contact' : report.company_contact,
			'sample_type' : report.sample_type,
			'sample_count' : report.sample_count,
			'date_received' : formatDate(report.date_received),
			'date_compiled' : formatDate(report.date_compiled),
			'task_no' : report.task_no,
			'technical staff' : report.technical_staff_name,
			'Authorizer' : report.authorizer,
			'attendant' : report.attendant,
		};
		let report_outht = '';
		Object.keys(report_info).forEach(f => {
			report_outht += `
				<div class="info-row">
					<span class="info-label">${f}:</span>
					<span class="info-value">${report_info[f]}</span>
				</div>
			`;
		});

		console.log('report sample: ',report.sample);

		let sample_info = {
            "sample no": report.sample.sample_no,
            "sample tag": report.sample.sample_tag,
            "sample type": report.sample.sample_type,
            "sample count": report.sample.sample_count,
            "brough in": formatDate(report.sample.created_at),
            "status": `<b>${sample_state_atlas[2]}</b>`
		};
		let sample_outht = '';
		Object.keys(sample_info).forEach(f => {
			sample_outht += `
				<div class="info-row">
					<span class="info-label">${f}:</span>
					<span class="info-value">${sample_info[f]}</span>
				</div>
			`;
		});

		// return;
		// Build the interface
		const html = `
			<div class="sample-header themegrad w3-display-container">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span class="h4">Report: ${report.report_no}</span>
						<div class="flowline overflow gap-sm left" style="margin-top: 8px;">
							<div class="distance-tn">Created: <b>${formatDate(report.created_at)}</b></div>
						</div>
					</div>
				</div>
			</div>

			<!-- Action Buttons -->
			<div class="flowline centerline center spacy-sm w3-hide_">
				<button class="mybtn secondary" onclick="viewTask_payments(${report.task.id})" data-openthis="taskinfo">
					<i class="fas fa-folder"></i> View Task
				</button>
				<button class="mybtn secondary" onclick="viewClient_payments(${report.owner.id})" data-openthis="clientinfo">
					<i class="fas fa-users"></i> View Client
				</button>
				<button class="mybtn secondary" data-runme="regen_report">
					<i class="fas fa-file-export"></i> Regenerate
				</button>
			</div>
			<div class="flowline centerline center spacy-sm">
				<a class="mybtn secondary sm" data-runme="downloadPdf" target="blank" href="${linkAtlas['getreport_img']}/${report.image_link}"><i class="fa fa-download"></i> download as image</a>
				<a class="mybtn secondary sm" data-runme="downloadPdf" target="blank" href="${linkAtlas['getreport_pdf']}/${report.pdf_link}"><i class="fa fa-download"></i> download as pdf</a>
				<button class="mybtn secondary sm" data-runme="printPdf"><i class="fa fa-print"></i> print report</button>
			</div>

			<div class="sample-info-card w3-hide_">
				<h5><i class="fas fa-money-bill"></i> Report Information</h5>
				<div>
					${report_outht}
				</div>
			</div>

			<div class="distance-sm border themeround collapser">
				<a class="text-decoration-none spacy-sm flowline spread centerline" href="#sampleInfo" data-toggle="collapse" data-target="#sampleInfo">
					<span class="text-uppercase text-muted font-weight-bold"><i class="fas fa-flask"></i> Sample Information</span>
					<i class="fa fa-chevron-down myicon"></i>
				</a>
				<div class="collapse panelbg distance-sm spacy-sm" id="sampleInfo">
					${sample_outht}
					<div class="info-row">
						<button class="mybtn secondary sm" onclick="viewTask_payments()" data-openthis="taskinfo">
							<i class="fas fa-folder"></i> View Task
						</button>
					</div>
				</div>
			</div>

			<div class="distance-sm border themeround collapser">
				<a class="text-decoration-none spacy-sm flowline spread centerline" href="#takInfo" data-toggle="collapse" data-target="#takInfo">
					<span class="text-uppercase text-muted font-weight-bold"><i class="fas fa-folder"></i> Task Information</span>
					<i class="fa fa-chevron-down myicon"></i>
				</a>
				<div class="collapse panelbg distance-sm spacy-sm" id="takInfo">
					<div class="info-row">
						<span class="info-label">Task Number:</span>
						<span class="info-value">${report.task.task_no}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Status:</span>
						<span class="info-value"><b class="mybadge w3-black">${task_state_atlas[report.task.status]}</b></span>
					</div>
					<div class="info-row">
						<span class="info-label">Payment Status:</span>
						<span class="info-value">${getPaymentBadge(report.task.is_paid)}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Created:</span>
						<span class="info-value">${formatDate(report.task.created_at)}</span>
					</div>
				</div>
			</div>

			<div class="distance-sm border themeround collapser">
				<a class="text-decoration-none spacy-sm flowline spread centerline" href="#taskInfo" data-toggle="collapse" data-target="#taskInfo">
					<span class="text-uppercase text-muted font-weight-bold"><i class="fas fa-users"></i> Client Information</span>
					<i class="fa fa-chevron-down myicon"></i>
				</a>
				<div class="collapse panelbg distance-sm spacy-sm" id="taskInfo">
					<div class="info-row">
						<span class="info-label">Client Name:</span>
						<span class="info-value">${report.owner.client_name}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Contact:</span>
						<span class="info-value">${report.owner.client_contact}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Gender:</span>
						<span class="info-value">${report.owner.client_gender}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Payment Plan:</span>
						<span class="info-value">${report.owner.payment_plan}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Credit Balance:</span>
						<span class="info-value">KES ${parseFloat(report.owner.credit_balance).toFixed(2).toLocaleString()}</span>
					</div>
					<div class="info-row">
						<button class="mybtn secondary" onclick="viewClient_payments(${report.owner.id})" data-openthis="clientinfo">
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
						<span class="info-value">${report.mycreator.name}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Email:</span>
						<span class="info-value">${report.mycreator.email}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Phone:</span>
						<span class="info-value">${report.mycreator.phone_number}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Last Login:</span>
						<span class="info-value">${formatDate(report.mycreator.last_login_at)}</span>
					</div>
				</div>
			</div>
		`;

		container.innerHTML = html;

		// setTimeout(d => {render_results_data();},100);
		refreshData(300);
	}

	function viewTask_payments() {
		alert_info('opening task');
		window[variableAtlas['task_id']] = report.task.id;

		let payld = {
			template: linkAtlas['task_info'],
			sidenote: pageNames['task_info'],
		};
		loadPage(payld);
	}

	function viewClient_payments_payments(clientId) {
		alert_info('opening client Information');
		window[variableAtlas['client_id']] = report.owner.id;

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

	window['regen_report'] = (el) => {
		alert_info('heading to report creation page');
		window[variableAtlas['sample_id']] = report.sample.id;

		loadPage({
			template: linkAtlas['gen_report'],
			sidenote: pageNames['gen_report'],
		});
	}
	window['printPdf'] = () => {
		alert_info('printing report');

		setTimeout(e => {
			// run print ops
			alert_warning('printing');
			// Get the canvas element

		const printWindow = window.open('', '_blank');
		// Get the canvas as a data URL
		const dataUrl = `${linkAtlas['getreport_img']}/${report.image_link}`;

		// Write HTML to the print window
		printWindow.document.write(`
			<!DOCTYPE html>
			<html>
				<head>
					<title>Print Canvas</title>
					<style>
						body {
							margin: 0;
							padding: 0;
							display: flex;
							justify-content: center;
							align-items: center;
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
