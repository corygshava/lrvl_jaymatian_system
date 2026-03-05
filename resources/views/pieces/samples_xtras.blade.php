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

	.status-pending {
		background-color: #ffc107;
		color: #000;
	}

	.status-complete {
		background-color: #28a745;
		color: white;
	}

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

<button class="mybtn primary w3-hide" id="add_result_modal_trig" data-toggle="modal" data-target="#add_edit_modal">
	<i class="fas fa-plus"></i>
	Add result
</button>

<div class="modal fade" id="add_edit_modal" tabindex="-1" role="dialog" aria-labelledby="add_edit_title" aria-hidden="true" style="z-index: 102;">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content themeround borderless">
			<div class="modal-header">
				<span class="h3 modal-title" id="add_edit_title">Add result</span>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="mybtn secondary" data-dismiss="modal">Cancel</button>
				<!-- <button type="button" class="mybtn primary" data-submitme="#add_edit_form">Save</button> -->
			</div>
		</div>
	</div>
</div>

<button class="mybtn primary w3-hide" id="view_samples_modal_trig" data-toggle="modal" data-target="#samples_info_modal">
	<i class="fas fa-plus"></i>
	View sample details
</button>

<div class="modal fade" id="samples_info_modal" tabindex="-1" role="dialog" aria-labelledby="samples_info_heading" aria-hidden="true" style="z-index: 102;">
	<div class="modal-dialog modal-mg" role="document">
		<div class="modal-content themeround borderless">
			<div class="modal-header">
				<span class="h3 modal-title" id="samples_info_heading">Sample Record Information</span>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div id="sampleDataContainer"></div>
			</div>
			<div class="modal-footer w3-hide">
				<button type="button" class="mybtn secondary" data-dismiss="modal">Cancel</button>
				<!-- <button type="button" class="mybtn primary" data-submitme="#add_edit_form">Save</button> -->
			</div>
		</div>
	</div>
</div>

<button class="mybtn primary w3-hide" id="add_result_trig" data-toggle="modal" data-target="#addResultModal">
	<i class="fas fa-plus"></i>
	add results
</button>

<!-- Add Result Modal -->
<div class="modal fade" id="addResultModal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content themeround borderless">
			<div class="modal-header">
				<span class="h4">Add Result</span>
				<button type="button" class="close" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="themegrad w3-text-white spacy-sm themeround distance-tn" id="sample_brief">
					<span class="h4">Sample: <b>2340324093284</b></span>
					<div>
						<span>Type: <b>Solution</b></span>
					</div>
					<div>
						<span>Status: <b>Pending</b></span>
					</div>
				</div>
				<form id="addResultForm" action="defined:add_result" data-onsubmit="sendform" data-blockdefault="yes" data-callback="refreshData">
					<input type="hidden" name="sample_id">
					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="au_ppm">AU ppm</label>
							<input type="number" step="0.0001" class="form-control-custom" id="au_ppm" name="au_ppm" placeholder="enter Au PPM result" required>
						</div>
						<div class="form-group">
							<label class="form-label" for="cu_ppm">CU ppm</label>
							<input type="number" step="0.0001" class="form-control-custom" id="cu_ppm" name="cu_ppm" placeholder="enter Au PPM result" required>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="mybtn secondary sm" data-dismiss="modal">Close</button>
				<button type="button" class="mybtn primary sm" data-submitme="#addResultForm">Save Result</button>
			</div>
		</div>
	</div>
</div>

<script>
	let sample = {};
	let state_atlas = [
		'pending',
		'incomplete',
		'complete',
	];

	let temp_results = [];

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
						<td>${result.sample_id}</td>
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

	function renderSampleData(dta) {
		// clear stuff from last session
		temp_results = [];

		// Validate rectype
		if (!dta || dta.rectype !== 'sample') {
			alert_danger('Invalid data: rectype must be "sample"');
			return;
		}

		// alert_info('rendering sample info');

		sample = dta.data;
		const container = document.getElementById('sampleDataContainer');

		container.innerHTML = 'loading...';

		// Helper function to format dates
		function formatDate(dateString) {
			return formatDate0(dateString);
		}

		// Helper function to get status badge
		function getStatusBadge(status) {
			return status === 0
				? '<span class="badge-status status-pending">Pending</span>'
				: '<span class="badge-status status-complete">Complete</span>';
		}

		// Helper function to get payment status badge
		function getPaymentBadge(isPaid) {
			return isPaid
				? '<span class="mybadge status-paid">Paid</span>'
				: '<span class="mybadge status-unpaid">Unpaid</span>';
		}

		// Helper function to get sample type badge
		function getSampleTypeBadge(type) {
			const typeClass = `type-${type}`;
			return `<span class="sample-type-badge ${typeClass}">${type}</span>`;
		}

		// return;
		// Build the interface
		const html = `
			<div class="sample-header themegrad w3-display-container">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span class="h4">Sample: ${sample.sample_no}</span>
						<div class="flowline overflow gap-sm left" style="margin-top: 8px;">
							<div class="distance-tn">Tag: <b>${sample.sample_tag}</b></div>
							<div class="distance-tn">Type: <b>${sample.sample_type}</b></div>
							<div class="distance-tn">amount: <b>${sample.sample_count}</b></div>
						</div>
					</div>
				</div>

				<div class="spacy-md w3-display-right"><b>${getStatusBadge(sample.status)}</b></div>
			</div>

			<!-- Action Buttons -->
			<div class="flowline centerline center spacy-sm w3-hide">
				<button class="mybtn primary w3-hide" data-toggle="collapse" data-target="#add_result_form">
					<i class="fas fa-plus-circle"></i> Add Result
				</button>
				<button class="mybtn secondary" onclick="viewTask(${sample.task_id})">
					<i class="fas fa-folder"></i> View Task
				</button>
				<button class="mybtn secondary" onclick="viewClient(${sample.client_id})">
					<i class="fas fa-users"></i> View Client
				</button>
			</div>

			<!-- Client Information (Primary) -->
			<div class="sample-info-card w3-hide">
				<h5><i class="fas fa-user-circle"></i> Client Information</h5>
				<div class="info-row">
					<span class="info-label">Client Name:</span>
					<span class="info-value">${sample.owner.client_name}</span>
				</div>
				<div class="info-row">
					<span class="info-label">Contact:</span>
					<span class="info-value">${sample.owner.client_contact}</span>
				</div>
				<div class="info-row">
					<span class="info-label">Gender:</span>
					<span class="info-value">${sample.owner.client_gender}</span>
				</div>
				<div class="info-row">
					<span class="info-label">Payment Plan:</span>
					<span class="info-value">${sample.owner.payment_plan}</span>
				</div>
				<div class="info-row">
					<span class="info-label">Credit Balance:</span>
					<span class="info-value">KES ${parseFloat(sample.owner.credit_balance).toFixed(2)}</span>
				</div>
			</div>

			<div class="distance-sm border themeround collapser" style="border-color: var(--themecolor) !important;" id="add_section">
				<a class="text-decoration-none spacy-sm flowline spread centerline themetxt" href="#add_result_form" data-toggle="collapse" data-target="#add_result_form">
					<span class="text-uppercase themetxt font-weight-bold"><i class="fas fa-plus-circle"></i> Add result</span>
					<i class="fa fa-chevron-down myicon"></i>
				</a>
				<div class="collapse spacy-sm" id="add_result_form">
					<form id="addNewResultForm" data-onsubmit="add_result" data-blockdefault="yes">
						<div class="input-group-custom row">
							<div class="form-group col-md-4 col-sm-6">
								<label class="form-label" for="sample_id">Sample id (optional)</label>
								<input type="text" class="form-control-custom" id="sample_id" name="sample_id" placeholder="(will be auto generated if blank)">
							</div>
							<div class="form-group col-md-4 col-sm-6">
								<label class="form-label" for="au_ppm">AU ppm</label>
								<input type="number" step="0.0001" class="form-control-custom" id="au_ppm" name="au_ppm" placeholder="enter Au PPM result" required>
							</div>
							<div class="form-group col-md-4 col-sm-6">
								<label class="form-label" for="cu_ppm">CU ppm</label>
								<input type="number" step="0.0001" class="form-control-custom" id="cu_ppm" name="cu_ppm" placeholder="enter Cu PPM result" required>
							</div>
						</div>
						<div>
							<button type="button" class="mybtn secondary sm w3-hide" data-toggle="collapse" data-target="#add_result_form">Close</button>
							<button type="button" class="mybtn primary sm" data-submitme="#addNewResultForm"><i class="fa fa-plus"></i> Add result</button>
						</div>
					</form>
				</div>
			</div>

			<!-- Results Section -->
			<div class="sample-info-card">
				<h5><i class="fas fa-chart-line"></i> Test Results</h5>
				<div id="results_summary">
					<span><b>${sample.results ? sample.results.length : 0} / ${sample.sample_count} </b> sample results saved</span>
				</div>
				<div id="resultsTable">
					${renderResultsTable(sample.results,temp_results)}
				</div>
				<div>
					<div class="distance-tn">
						<span><b>NOTE: </b> once results are <b>saved</b> they cannot be edited, ensure you enter accurate Information</span>
					</div>
					<button type="button" class="mybtn primary sm w3-hide" data-runme="save_results" id="save_results_btn"><i class="fa fa-save"></i> save results</button>
				</div>
			</div>

			<div class="distance-sm border themeround collapser">
				<a class="text-decoration-none spacy-sm flowline spread centerline" href="#sampleInfo" data-toggle="collapse" data-target="#sampleInfo">
					<span class="text-uppercase text-muted font-weight-bold"><i class="fas fa-flask"></i> Sample Information</span>
					<i class="fa fa-chevron-down myicon"></i>
				</a>
				<div class="collapse panelbg distance-sm spacy-sm" id="sampleInfo">
					<div class="info-row">
						<span class="info-label">Client Name:</span>
						<span class="info-value">${sample.owner.client_name}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Sample Number:</span>
						<span class="info-value">${sample.sample_no}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Sample Tag:</span>
						<span class="info-value">${sample.sample_tag}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Type:</span>
						<span class="info-value">${getSampleTypeBadge(sample.sample_type)}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Sample Count:</span>
						<span class="info-value">${sample.sample_count}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Created:</span>
						<span class="info-value">${formatDate(sample.created_at)}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Updated:</span>
						<span class="info-value">${formatDate(sample.updated_at)}</span>
					</div>
				</div>
			</div>

			<div class="distance-sm border themeround collapser">
				<a class="text-decoration-none spacy-sm flowline spread centerline" href="#taskInfo" data-toggle="collapse" data-target="#taskInfo">
					<span class="text-uppercase text-muted font-weight-bold"><i class="fas fa-folder"></i> Task Information</span>
					<i class="fa fa-chevron-down myicon"></i>
				</a>
				<div class="collapse panelbg distance-sm spacy-sm" id="taskInfo">
					<div class="info-row">
						<span class="info-label">Task Number:</span>
						<span class="info-value">${sample.task.task_no}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Status:</span>
						<span class="info-value">${getStatusBadge(sample.task.status)}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Payment Status:</span>
						<span class="info-value">${getPaymentBadge(sample.task.is_paid)}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Created:</span>
						<span class="info-value">${formatDate(sample.task.created_at)}</span>
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
						<span class="info-value">${sample.mycreator.name}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Email:</span>
						<span class="info-value">${sample.mycreator.email}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Phone:</span>
						<span class="info-value">${sample.mycreator.phone_number}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Last Login:</span>
						<span class="info-value">${formatDate(sample.mycreator.last_login_at)}</span>
					</div>
				</div>
			</div>
		`;

		container.innerHTML = html;

		refreshUI(300);
	}

	// Toggle collapsible sections
	function toggleCollapse(sectionId) {
		const body = document.getElementById(sectionId);
		const icon = document.getElementById(sectionId + '-icon');

		if (body.classList.contains('show')) {
			body.classList.remove('show');
			icon.classList.remove('fa-chevron-up');
			icon.classList.add('fa-chevron-down');
		} else {
			body.classList.add('show');
			icon.classList.remove('fa-chevron-down');
			icon.classList.add('fa-chevron-up');
		}
	}

	// Action button handlers (implement these based on your needs)
	function addResult(sampleId) {
		console.log('Add result for sample:', sampleId);
		// view_samples_modal_trig.click();

		sample_brief.innerHTML = `
			<span class="h4">Sample: <b>${sample.sample_no}</b></span>
			<div>
				<span>Type: <b>${sample.sample_type}</b></span>
			</div>
			<div>
				<span>Status: <b>${state_atlas[sample.status]}</b></span>
			</div>
		`;

		setTimeout(() => {
			// add_result_trig.click();
		},300);
	}

	function viewTask(taskId) {
		console.log('View task:', taskId);
	}

	function viewClient(clientId) {
		console.log('View client:', clientId);
	}

	window['showmodal'] = (f) => {
		let wot = !f ? 'hide' : 'show';
		$('#samples_info_modal').modal(wot);
	}

	window['render_results_data'] = () => {
		resultsTable.innerHTML = renderResultsTable(sample.results,temp_results);

		let samples = sample.sample_count;
		let results = sample.results ? sample.results.length : 0;
		let added = temp_results.length;
		let meth = (results + added) >= samples ? 'add' : 'remove';
		let save_btn_meth = added == 0 ? 'add' : 'remove';

		add_section.classList[meth]('is_inactive');
		save_results_btn.classList[save_btn_meth]('w3-hide');
		results_summary.innerHTML = `
			<span><b>${sample.results ? sample.results.length : 0} / ${sample.sample_count} </b> sample results saved</span><br>
			<span><b>${temp_results.length}</b> results added</span>
		`;

		if(meth == 'add'){
			$('#add_result_form').collapse('hide');
		}
		refreshUI();
	}

	window['add_result'] = (f) => {
		alert_info('adding sample');

		let n = temp_results.length + 1;
		let date = new Date();
		// let dadate = date.toISOString();

		if(sample.results){
			n = sample.results.length + temp_results.length;
		}

		let sample_n = f.sample_id.value == '' ? `sample: ${n}` : f.sample_id.value

		let newrec = {
			id : n,
			sample_id : sample_n,
			au_ppm : f.au_ppm.value,
			cu_ppm : f.cu_ppm.value,
			date_added : date,
		};

		// console.log("rec: ",newrec,"sample id: ",f.sample_id.value);
		temp_results.push(newrec);

		f.reset();
		render_results_data();
	}

	window['delete_result'] = (el) => {
		let recid = el.dataset.myid || -1;

		if(recid == -1){alert_danger('invalid procedure method');return;}
		if(recid >= temp_results.length){alert_danger('invalid result id');return;}
		if(temp_results[recid] == undefined){alert_danger('invalid result');return;}

		showmodal(false);

		const reshowmodal = () => {
			setTimeout(() => {
				showmodal(true);
			},300);
		}

		setTimeout(() => {
			confirmAction(mekHeading('delete Result','h4'),'are you sure you want to remove this result entry?',() => {
				ridme(recid);
				reshowmodal();
			},false,() => {
				reshowmodal();
			})
		}, 300);
	}

	window['ridme'] = (n) => {
		let target = temp_results[n] || undefined;

		if(target == undefined){
			alert_danger('invalid target for ridme');
			return;
		}
		let newarr = [...temp_results].filter(f => {return f.id !== target.id});
		temp_results = newarr;
		render_results_data();
	}

	window['edit_result'] = (el) => {
		let sel = '#add_result_form';
		let f = document.querySelector(sel).querySelector('form');
		let recid = el.dataset.myid || -1;

		if(recid == -1){alert_danger('invalid procedure method');return;}
		if(recid >= temp_results.length){alert_danger('invalid result id');return;}
		if(temp_results[recid] == undefined){alert_danger('invalid result');return;}

		// toggle the collapse
		$(sel).collapse('show');

		// assign the inputs the appropriate values
		f.au_ppm.value = temp_results[recid].au_ppm;
		f.cu_ppm.value = temp_results[recid].cu_ppm;
		f.sample_id.value = temp_results[recid].sample_id;

		// delete from the array
		ridme(recid);
	}

	window['save_results'] = (el) => {
		showmodal(false);

		// confirm sending as it is undoable
		const reshowmodal = () => {
			setTimeout(() => {
				showmodal(true);
			},200);
		}

		// send the temp array
		const step2 = () => {
			window[fetch_bypass](linkAtlas['submit_results'],{sample_id: sample.id,results: temp_results},'POST').then(d => {
				responseHandler(d,step3,d);
			})
			.catch((e) => {
				alert_danger('there was an error saving the records');
				reshowmodal();
			})
		}

		setTimeout(() => {
			confirmAction(mekHeading('Save Results','h4'),'are you sure you want to save the entered results?<br><br>Once saved you wont be able to edit the results',() => {
				step2();
			},false,() => {
				reshowmodal();
			})
		}, 200);

		// if it worked get the latest sample data
		// refresh ui using the fresh sample details
		// enable the samples entry form
	}

	/*
	// Save result handler
	document.addEventListener('DOMContentLoaded', function() {
		const saveButton = document.getElementById('saveResult');
		if (saveButton) {
			saveButton.addEventListener('click', function() {
				const auPpm = document.getElementById('au_ppm').value;
				const cuPpm = document.getElementById('cu_ppm').value;

				console.log('Saving result:', { au_ppm: auPpm, cu_ppm: cuPpm });
				// Implement your save logic here

				$('#addResultModal').modal('hide');
				document.getElementById('addResultForm').reset();
			});
		}
	});
	*/

	// Usage example:
	// renderSampleData(yourDataObject);
</script>
