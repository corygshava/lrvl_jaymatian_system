<style>
	/* Progress Indicator */
		.progress-indicator {
			border-radius: 15px;
			padding: 20px 30px;
			position: sticky;
			top: 8px;
		}
		.progress-indicator .progress-steps {
			display: flex;
			justify-content: space-between;
			position: relative;
			--l: 65px;
		}
		.progress-indicator .progress-steps::before {
			content: '';
			position: absolute;
			width: calc(96% - var(--l));
			top: 20px;
			left: var(--l);
			right: 0;
			height: 2px;
			background-color: var(--clr-border);
			opacity: 0.3;
			z-index: 0;
		}
		.progress-indicator .progress-line {
			position: absolute;
			width: calc(96% - var(--l));
			top: 20px;
			left: var(--l);
			height: 2px;
			/*background: var(--themecolor);*/
			z-index: 1;
			transition: width 0.3s ease;
		}

		.progress-indicator .step {
			display: flex;
			flex-direction: column;
			align-items: center;
			z-index: 2;
			/*background-color: var(--clr-text-muted);*/
			padding: 0 10px;
			cursor: pointer;
		}
		.progress-indicator .step-circle {
			width: 40px;
			height: 40px;
			border-radius: 50%;
			background-color: var(--clr-fade);
			display: flex;
			align-items: center;
			justify-content: center;
			margin-bottom: 8px;
			transition: all 0.3s ease;
		}
		.progress-indicator .step.active .step-circle {
			background: var(--themecolor);
			color: #fff;
			scale: 1.4;
		}
		.progress-indicator .step.completed .step-circle {
			background: var(--success);
			color: #fff;
		}
		.progress-indicator .step.completed .step-label {
			color: var(--success);
		}
		.progress-indicator .step-label {
			font-size: 13px;
			color: var(--clr-text-muted);
			font-weight: 500;
		}
		.progress-indicator .step.active .step-label {
			color: var(--themecolor);
		}

	/* page specific stuff */
		.search-box_2{
			display: flex;
			flex-direction: row-reverse;
			gap: 8px;
			margin: 16px 0;
			align-items: center;
			justify-content: start;
			border-radius: var(--roundness);
			overflow: hidden;
			border: 1px solid var(--clr-border);
		}
		.search-box_2 i{
			padding: 0 16px;
		}
		.search-box_2 input{
			border: none;
			display: block;
			width: 98%;
			padding: 12px 16px 12px 0;
		}
		.search-box_2 input:focus{
			border: none;
			outline: none;
		}
		.search-box_2:has(input:focus){
			border-color: var(--themecolor);
		}
		.search-box_2 input:focus + i{
			color: var(--themecolor);
		}

	/* client container */
		.client_container{
			--w: 300px;
			border: 1px solid var(--clr-border);
			padding: var(--size-sm);
			border-radius: var(--roundness);
			cursor: pointer;
			flex: 1 0 var(--w);
			max-width: 500px;
			position: relative;
		}
		.client_container:hover{
			border: 1px solid var(--themecolor);
			box-shadow: 0 0 24px var(--clr-shadow);
			padding: var(--size-sm);
			border-radius: var(--roundness);
		}
		.client_container .thename{
			display: block;
			font-size: 24px;
		}
		.client_container i{
			display: inline-block;
			padding: 7px 4px;
		}

	/* misc */
		.stats_area{
			font-size: 20px;
		}
		.table-modern{overflow: hidden;}

	@media(max-width: 600px){
		.progress-line{
			opacity: 0;
		}
	}
</style>

<div class="container-fluid">
	<div class="page-header">
		<div class="page-title">
			<span class="h4">Add task</span>
			<p>Add a new task to the database</p>
		</div>
	</div>

	<div class="panelbg themeround distance-tn" id="progress_panel">
		<div class="progress-indicator">
			<div class="progress-steps">
				<div class="progress-line themegrad" id="progressLine" style="width: 20%;"></div>
				<div class="step" data-step="0" data-runme="pick_step_2">
					<div class="step-circle"><i class="fas fa-user"></i></div>
					<div class="step-label">Client Selection</div>
				</div>
				<div class="step" data-step="1" data-runme="pick_step_2">
					<div class="step-circle"><i class="fas fa-flask"></i></div>
					<div class="step-label">Samples</div>
				</div>
				<div class="step" data-step="2" data-runme="pick_step_2">
					<div class="step-circle"><i class="fas fa-money-bill"></i></div>
					<div class="step-label">Payment</div>
				</div>
				<div class="step" data-step="3" data-runme="pick_step_2">
					<div class="step-circle"><i class="fas fa-stamp"></i></div>
					<div class="step-label">Finalize</div>
				</div>
			</div>
		</div>
	</div>

	<div class="tabs panelbg themeround spacy-sm slide-left" style="overflow: hidden;">
		<div class="tab tilt_in_left" id="client_data">
			<div>
				<h4><b>Select Client</b></h4>
				<p>enter their <b>name</b>, <b>phone number</b> or <b>ID number</b></p>
				<div class="search-box_2">
					<input type="Search" id="client_search" placeholder="search for client...">
					<i class="fa fa-search"></i>
				</div>
			</div>

			<div class="flowline overflow gap-sm" id="client_search_results">
				<div class="spacy-md flow centroid w3-center" style="flex: 1 1 1280px">
					<span class="h4">search for a client first</span>
					<p>the results will show here</p>
					<button class="mybtn primary" data-runme="load_page" data-label="Client management" data-link="defined:view_clients" data-afterfx="add_record"><i class="fa fa-plus"></i> add new</button>
				</div>
			</div>
		</div>

		<div class="tab tilt_in_left" id="samples_data" style="display: none">
			<div>
				<h4><b>Samples Entry</b></h4>
				<p>Here you can enter the samples the client has brought for analysis</p>
			</div>

			<div data-role="client_card" class="themegrad spacy-sm themeround">
				<strong class="h4 w3-text-white" data-subrole="thename">${w.client_name}</strong>
				<div>
					<span class="badge badge-primary" data-subrole="client_gender">${w.client_gender}</span>
					<span class="badge w3-black" data-subrole="payment_plan">${w.payment_plan}</span>
				</div>
			</div>

			<div data-role="samples_stats_card" class="row spacy-sm themeround stats_area">
				<div class="col-md-4">
					Total Samples:<br> <b class="themetxt" data-subrole="total_samples">5</b>
				</div>
				<div class="col-md-4">
					Sample Entries:<br> <b class="themetxt" data-subrole="total_sample_entries">5</b>
				</div>
				<div class="col-md-4">
					Total cost:<br> <b class="text-success" data-subrole="total_cost">--</b>
				</div>
			</div>

			<div class="spacy-sm">
				<form class="row border spacy-sm themeround" id="add_sample_form" data-onsubmit="add_sample" data-blockdefault="yes">
					<div class="col-md-4">
						<div class="input-group-custom">
							<div class="form-group">
								<label class="form-label" for="sample_type">Sample Type</label>
								<select class="form-control-custom" id="sample_type" name="sample_type" required>
									<option value="">Select sample type</option>
									<option value="male">Soil</option>
									<option value="female">Carbon</option>
									<option value="female">Solution</option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="input-group-custom">
							<div class="form-group">
								<label class="form-label" for="sample_count">Sample count</label>
								<input type="number" class="form-control-custom" id="sample_count" name="sample_count" placeholder="enter number of samples" required>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="input-group-custom">
							<div class="form-group">
								<label class="form-label" for="sample_tag">Sample Tag (optional)</label>
								<input type="tel" class="form-control-custom" id="sample_tag" name="sample_tag" placeholder="enter a name to easily mark it">
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<button class="mybtn primary"><i class="fa fa-plus"></i> add sample</button>
					</div>
				</form>
			</div>

			<div>
				<h4 class="spacy-sm">Samples</h4>
				<div class="table-responsive">
					<table class="table-modern border themeround">
						<thead>
							<tr class="themeround">
								<th width="40" class="w3-hide">
									<input type="checkbox" class="checkbox-custom" id="selectAll" onchange="toggleSelectAll()">
								</th>
								<th></th>
								<th>Sample Type</th>
								<th>Sample Tag</th>
								<th>Sample Count</th>
								<th class="w3-hide">cost</th>
								<th width="120">Actions</th>
							</tr>
						</thead>
						<tbody id="sample_tablebody"></tbody>
					</table>
				</div>
			</div>

			<div class="distance-md border-top">
				<div class="distance-md flowline spread gap-sm">
					<button class="mybtn outline" data-step="0" data-runme="pick_step_2"><i class="fa fa-arrow-left"></i> pick a different client</button>
					<button class="mybtn primary" data-step="2" data-runme="pick_step_2">proceed to payment <i class="fa fa-arrow-right"></i></button>
				</div>
			</div>
		</div>

		<div class="tab tilt_in_left" id="payment_data" style="display: none">
			<div>
				<h4><b>Payment Information</b></h4>
				<p>Here you can enter the payment information from the client</p>
			</div>

			<div data-role="client_card" class="themegrad spacy-sm themeround">
				<strong class="h4 w3-text-white" data-subrole="thename">${w.client_name}</strong>
				<div>
					<span class="badge badge-primary" data-subrole="client_gender">${w.client_gender}</span>
					<span class="badge w3-black" data-subrole="payment_plan">${w.payment_plan}</span>
				</div>
			</div>

			<div data-role="payment_stats_card" class="row spacy-sm themeround stats_area">
				<div class="col-md-3" data-subrole="pays_made">
					Payments made:<br> <b class="themetxt">--</b>
				</div>
				<div class="col-md-3" data-subrole="amount_due">
					Amount Due:<br> <b class="themetxt">--</b>
				</div>
				<div class="col-md-3" data-subrole="amount_paid">
					Amount Paid:<br> <b class="themetxt">--</b>
				</div>
				<div class="col-md-3" data-subrole="balance">
					Balance:<br> <b class="text-success">--</b>
				</div>
				<div class="col-md-3" data-subrole="client_credit">
					Client Credit:<br> <b class="themetxt">--</b>
				</div>
			</div>

			<div class="spacy-sm">
				<form class="row border spacy-sm themeround" id="add_payment_form" data-onsubmit="add_payment" data-blockdefault="yes">
					<div class="col-md-6">
						<div class="input-group-custom">
							<div class="form-group">
								<label class="form-label" for="payment_method">Payment Method</label>
								<select class="form-control-custom" id="payment_method" name="payment_method" required>
									<option value="" disabled selected>Select payment method</option>
									<option value="Mpesa">Mpesa</option>
									<option value="Cash">Cash</option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group-custom">
							<div class="form-group">
								<label class="form-label" for="payment_amount">Payment Amount</label>
								<input type="number" class="form-control-custom" id="payment_amount" name="payment_amount" placeholder="how much was paid" required>
							</div>
						</div>
					</div>
					<div class="col-md-12 flowline gap-sm centerline left">
						<button class="mybtn primary"><i class="fa fa-plus"></i> add payment</button>
						<button class="mybtn secondary" type="button" id="deduct_from_credit_btn" data-runme="deduct_from_credit">deduct from credit</button>
					</div>
				</form>
			</div>

			<div>
				<h4 class="spacy-sm">Samples</h4>
				<div class="table-responsive">
					<table class="table-modern border themeround">
						<thead>
							<tr class="themeround">
								<th width="40" class="w3-hide">
									<input type="checkbox" class="checkbox-custom" id="selectAll" onchange="toggleSelectAll()">
								</th>
								<th></th>
								<th>Payment Method</th>
								<th>Amount</th>
								<th>added at</th>
								<th class="w3-hide">cost</th>
								<th width="120">Actions</th>
							</tr>
						</thead>
						<tbody id="payments_tablebody">
							<tr><td colspan="12" class="w3-center"><i>no payments attached</i></td></tr>
						</tbody>
					</table>
				</div>
			</div>

			<div class="distance-md border-top">
				<div class="distance-md flowline spread gap-sm">
					<button class="mybtn outline" data-step="1" data-runme="pick_step_2"><i class="fa fa-arrow-left"></i> confirm samples</button>
					<button class="mybtn primary" data-step="3" data-runme="pick_step_2">proceed to finalize <i class="fa fa-arrow-right"></i></button>
				</div>
			</div>
		</div>

		<div class="tab tilt_in_left" id="finalize_data" style="display: none">
			<div>
				<h4><b>Finalize</b></h4>
				<p>Any other information or instructions the client may have left</p>
			</div>

			<div data-role="client_card" class="themegrad spacy-sm themeround">
				<strong class="h4 w3-text-white" data-subrole="thename">${w.client_name}</strong>
				<div>
					<span class="badge badge-primary" data-subrole="client_gender">${w.client_gender}</span>
					<span class="badge w3-black" data-subrole="payment_plan">${w.payment_plan}</span>
				</div>
			</div>

			<div data-role="final_stats_card" class="row spacy-sm themeround stats_area">
				<!-- show samples summary -->
				<div class="col-md-3" data-subrole="total_samples">
					Samples delivered:<br> <b class="themetxt" data-copyme='[data-role="samples_stats_card"] [data-subrole="total_samples"]' data-ignorepicker="yes">--</b>
				</div>
				<div class="col-md-3" data-subrole="total_sample_entries">
					Sample entries:<br> <b class="themetxt" data-copyme='[data-role="samples_stats_card"] [data-subrole="total_sample_entries"]' data-ignorepicker="yes">--</b>
				</div>
				<!-- show payments summary -->
				<div class="col-md-3" data-subrole="total_paid">
					Total paid:<br> <b class="themetxt" data-copyme='[data-role="payment_stats_card"] [data-subrole="amount_paid"] b' data-ignorepicker="yes">--</b>
				</div>
				<div class="col-md-3" data-subrole="balance">
					Balance:<br> <b class="themetxt" data-copyme='[data-role="payment_stats_card"] [data-subrole="balance"] b' data-ignorepicker="yes">--</b>
				</div>

				<!-- show note textarea for extra info -->
				<!-- submit job (add logic for requesting password entry if the payment is incomplete) -->
			</div>

			<div class="spacy-sm">
				<form class="row border spacy-sm themeround" id="finalize_task_form" data-onsubmit="finalize_task_creation" data-blockdefault="yes">
					<div class="col-md-12">
						<div class="input-group-custom">
							<div class="form-group">
								<label class="form-label" for="task_note">Task Note:</label>
								<textarea type="number" class="form-control-custom" id="task_note" name="task_note" placeholder="any information the client may want to include for the task" rows="5"></textarea>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<button class="mybtn primary" type="submit">submit Task<i class="fa fa-paper-plane"></i></button>
					</div>
				</form>
			</div>

			<div class="distance-md border-top">
				<div class="distance-md flowline spread gap-sm">
					<button class="mybtn outline" data-step="2" data-runme="pick_step_2"><i class="fa fa-arrow-left"></i> confirm payments</button>
					<button class="mybtn primary" style="opacity: 0;pointer-events: none;" data-step="2" data-runme="pick_step_2">finalize<i class="fa fa-arrow-right"></i></button>
				</div>
			</div>
		</div>

		<div class="tab tilt_in_left" id="submitter_loader">
			<div class="spacy-md w3-center" id="submitter_loader_ui">
				<p>submitting task, please wait</p>
				<div class="flow centroid">
					<div class="loader"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	// intervals
	let search_interval = undefined;
	let save_inter = undefined;

	let debounce = 700;								// delay for search to prevent overloading the server with requests
	let autosave_delay = 3000;						// delay in microseconds to prevent slowing down your browser
	let client_search_url = linkAtlas['search_clients'];
	let loadedClients = [];

	let tabs = document.querySelectorAll('.tab');
	let checks = document.querySelectorAll('.progress-steps>.step');
	const steps = 4;
	const spans = [0,33,63,90,90];

	window['task_runtime_data'] = {};
	let picked_client = {};

	window['runOnAwake'] = () => {
		setupUIs();
		setupListeners();
		init_task_creation_process();

		refreshUI(300);
	}

	window['runOnLeave'] = () => {
		if(search_interval !== undefined){clearTimeout(search_interval);}
		if(save_inter !== undefined){clearInterval(save_inter);}

		// kill all locally made functions and variables to prevent unused ghost variables
		delete(window['task_runtime_data']);
		delete(window['runOnLeave']);
		delete(window['pick_step_2']);
		delete(window['run_search_listener']);
		delete(window['run_search_client']);
		delete(window['pick_client']);
		delete(window['add_sample']);
		delete(window['edit_sample']);
		delete(window['remove_sample']);
		delete(window['add_payment']);
		delete(window['deduct_from_credit']);
		delete(window['edit_payment']);
		delete(window['remove_payment']);
	}

	function setupUIs() {
		let biz = window['bizdata'];

		if(biz == undefined){
			alert_danger('there was an error processing page setup, try again later',8);
			throw new Error('samples pricing data is invalied, reload the page');
		}
		let prdata = biz.pricing_data;

		sample_type.innerHTML = '';

		let st = document.createElement('option');
		st.disabled = true;
		st.selected = true;
		st.value = "";
		st.textContent = "pick a sample type";
		sample_type.appendChild(st);

		Object.keys(prdata).forEach(d => {
			let op = document.createElement('option');
			op.value = d;
			op.textContent = d;

			sample_type.appendChild(op);
		});

		sample_tablebody.innerHTML = `
			<tr>
				<td colspan="12" class="w3-center spacy-sm">
					<i>no samples added yet</i>
				</td>
			</tr>
		`;
	}
	function setupListeners() {
		// client tab
			// client search input
			client_search.addEventListener('input',(e) => {
				run_search_listener(e);
			})
			client_search.addEventListener('keydown',(e) => {
				if(e.key.toLowerCase() == 'enter'){
					run_search_listener(e);
				}
			})

		// samples tab

		// payment tab

		// finalize tab
	}

	function init_task_creation_process() {
		const step2 = () => {
			if(window['task_client_data'] !== undefined){
				alert_dark('found task client data');
				// return;
				select_client(window['task_client_data']);
				window['task_client_data'] = undefined;
				// last_step();
			} else {
				step3();
			}
		};

		const step3 = () => {
			alert_info('search clients to begin',8);
			last_step();
			pick_step(0);
		}

		const last_step = () => {
			saveTaskData();
		}

		let last_save = localStorage.getItem(prefsAtlas['last_saved_task']) || undefined;
		if(last_save !== undefined){
			if(last_save.clientData !== undefined){
				confirmAction('<span class="h4">Saved data found</span>','would you like to continue with creating the last task?',() => {
					// alert_danger('continuing');
					loadSavedTask();
					last_step();
				},false,() => {
					step2();
					localStorage.removeItem(prefsAtlas['last_saved_task']);
				});
			} else {
				pick_step(0);
			}
			step2();
			// pick_step(0);
		} else {
			step2();
		}
	}
	function loadSavedTask(){
		let last_save = localStorage.getItem(prefsAtlas['last_saved_task']) || undefined;

		if(last_save === undefined){
			console.log(last_save);
			alert_danger('saved data corrupted');
			pick_step(0);
			return;
		}

		task_runtime_data = JSON.parse(last_save);
		localStorage.removeItem(prefsAtlas['last_saved_task']);
		alert_success('saved data loaded');
		continueFromLast();

		// console.log("full: ",task_runtime_data,"saved: ",last_save);
	}
	function saveTaskData() {
		if(save_inter !== undefined){
			clearInterval(save_inter);
		}

		// localStorage.setItem(prefsAtlas['last_saved_task'],JSON.stringify(task_runtime_data));

		save_inter = setInterval(() => {
			localStorage.setItem(prefsAtlas['last_saved_task'],JSON.stringify(task_runtime_data));
			// alert_dark('running autosave');
		},autosave_delay);
	}
	function continueFromLast() {
		// alert_info('continuing from last');

		if(task_runtime_data.clientData == undefined){
			pick_step(0);
			alert_info('continuing from client mgt');
			return;
		}
		if(task_runtime_data.sampleData == undefined){
			pick_step(1);
			alert_info('continuing from samples');
			return;
		}
		if(task_runtime_data.paymentData == undefined){
			pick_step(2);
			alert_info('continuing from payments');
			return;
		}

		pick_step(3);
		alert_info('continuing from final stage');
	}

	// runtime ops
	function pick_step(n) {
		progressLine.style.width = `${spans[n]}%`;
		render_all_data();

		checks.forEach((w,id) => {
			if(id == n){
				w.classList.add('active');
			}else {
				w.classList.remove('active');
			}

			if(id < n){
				w.classList.add('completed');
			} else {
				w.classList.remove('completed');
			}
		})

		tabs.forEach(el => {
			el.style.display = 'none';
		})

		let it = tabs[n];
		if(it !== undefined){
			it.style.display = 'block';
		}
	}
	window['pick_step_2'] = (g) => {
		let n = Number(g.dataset.step);

		if(task_runtime_data.clientData == undefined && n > 0){
			alert_danger('pick a client first');
			return;
		}
		if(task_runtime_data.sampleData == undefined && n > 1){
			alert_danger('enter samples first');
			return;
		}
		if(task_runtime_data.paymentData == undefined && n > 2 && false){
			alert_danger('enter payment details first');
			return;
		}

		pick_step(n);
	}

	// client ops
	window['run_search_listener'] = (e) => {
		if(search_interval !== undefined){
			// alert_danger('search interval isnt empty');
			clearTimeout(search_interval);
		}

		search_interval = setTimeout(() => {
			run_search_client(e.target.value);
		},debounce);
	}
	window['run_search_client'] = (s) => {
		if(s == ''){
			alert_warning('type something first');
			return;
		}
		alert_info(`searching for ${s}`);

		window[fetch_bypass](`${client_search_url}?&per_page=10&search=${s}`,{q: s},'GET').then(w => {
			responseHandler(w,render_client_search,w);
			refreshUI(300);
		}).catch(w => {
			alert_danger('Error searching clients: ',w.message);
		});
	}
	function render_client_search(d) {
		alert_info('results recieved');

		if(d.sendme.data.length == 0){
			client_search_results.innerHTML = `
				<div class="spacy-md flow centroid w3-center" style="flex: 1 1 1280px">
					<span class="h4">None found</span>
					<p>try again, or</p>
					<button class="mybtn primary" data-runme="load_page" data-label="Client management" data-link="defined:view_clients" data-afterfx="add_record"><i class="fa fa-plus"></i> add new</button>
				</div>
			`;

			return;
		}

		let outht = '';

		loadedClients = d.sendme.data;
		d.sendme.data.forEach((w,id) => {
			outht += `
				<div class="client_container flow slide-up" data-runme="pick_client" data-id="${id}" style="${mekstagger(200,id)}">
					<strong class="thename">${w.client_name}</strong>
					<div>
						<span class="badge badge-primary">${w.client_gender}</span>
						<span class="badge w3-black">${w.payment_plan}</span>
					</div>
					<hr>
					<span><i class="fa fa-phone"></i> <span>${w.client_contact}</span></span>
					<span><i class="fa fa-id-card"></i> <span>${w.id_number || '--'}</span></span>
					<div class="distance-tn">
						<button class="mybtn primary sm"><i class="fa fa-check"></i> select client</button>
					</div>
				</div>
			`;
		})

		client_search_results.innerHTML = outht;
	}
	window['pick_client'] = (el) => {
		let id = el.dataset.id;

		// alert_info(`picking client ${id}`);
		picked_client = loadedClients[id] || undefined;
		window['select_client'](picked_client);
	}
	window['select_client']= (c) => {
		const select = () => {
			task_runtime_data.clientData = c;
			render_all_data();
			pick_step(1);
			alert_info('client selected successfully');
		}

		if(task_runtime_data.paymentData !== undefined && task_runtime_data.clientData !== undefined){
			if(c.id == task_runtime_data.clientData.id){
				// picked the same client twice
				select();
			} else if(task_runtime_data.paymentData.length > 0) {
				confirmAction(mekHeading('Payment Records found','h4'),'we have found some payment information saved, changing clients will result in the data being cleared',() => {
					select();
					task_runtime_data.paymentData = [];
				},false,() => {
					pick_step(2);
				});
			} else {
				// paymentData is empty
				select();
			}
		} else {
			select();
		}
	}

	// sample ops
	window['add_sample'] = (el) => {
		let saved_samples = task_runtime_data.sampleData || [];
		let sampleobj = {};

		let s_type = el.sample_type.value;
		let s_amt = el.sample_count.value;
		let s_tag = el.sample_tag.value || `NO ${saved_samples.length + 1}`;

		sampleobj = {
			typ: s_type,
			amt: s_amt,
			tag: s_tag,
		};
		saved_samples.push(sampleobj);
		task_runtime_data.sampleData = saved_samples;

		el.reset();
		el.sample_type.value = "";

		alert_success('sample added');
		render_all_data();
		console.log("saved: ",saved_samples,"sample: ",sampleobj);
	}
	window['edit_sample'] = (el) => {
		let id = el.dataset.myid;

		if(id == undefined){
			alert_danger('invalid request method');
			return;
		}

		if(id >= task_runtime_data.sampleData.length){
			alert_danger('invalid sample selection');
			return;
		}

		// populate the form
		let currec = task_runtime_data.sampleData[id];

		sample_type.value = currec.typ;
		sample_count.value = currec.amt;
		sample_tag.value = currec.tag;

		// remove from saved samples
		task_runtime_data.sampleData.splice(id,1);
		render_all_data();
	}
	window['remove_sample'] = (el) => {
		let id = el.dataset.myid;

		if(id == undefined){
			alert_danger('invalid request method');
			return;
		}

		if(id >= task_runtime_data.sampleData.length){
			alert_danger('invalid sample selection');
			return;
		}

		let currec = task_runtime_data.sampleData[id];

		confirmAction('<span class="h4">Remove Sample</span>','are you sure you want to remove this sample from the list?',() => {
			task_runtime_data.sampleData.splice(id,1);
			render_all_data();
			alert_success('sample removed');
		})
	}

	// payment ops
	window['add_payment'] = (el) => {
		let saved_payments = task_runtime_data.paymentData || [];
		let sampleobj = {};

		let p_type = el.payment_method.value;
		let p_amt = el.payment_amount.value;
		let timestamp = new Date();

		sampleobj = {
			method: p_type,
			amt: p_amt,
			timestamp: timestamp,
		};
		saved_payments.push(sampleobj);
		task_runtime_data.paymentData = saved_payments;

		el.reset();
		el.payment_method.value = "";

		alert_success('payment added');
		render_all_data();
		// console.log("saved: ",saved_payments,"sample: ",sampleobj);
	}
	window['deduct_from_credit'] = (el) => {
		// alert_info('payment will be deducted from their balance');

		let c_bal = task_runtime_data.clientData.credit_balance;
		let cost = task_runtime_data.sampleStats.cost;
		let a_bal = cost;

		if(task_runtime_data.paymentStats !== undefined){
			a_bal = task_runtime_data.paymentStats.balance || cost;
		}

		// first confirmation
		const step1 = () => {
			if(Math.floor(Number(c_bal)) == 0){
				confirmAction(mekHeading('Insufficient Credits','h4'),'The client doesnt have any Credit to deduct',() => {},false,() => {});
				return;
			}
			confirmAction(mekHeading('Deduct from Credit','h4'),`this will result in <b>${Math.floor(Number(a_bal)).toLocaleString()}</b> being deducted from the client's balance (balance: <b>${Math.floor(Number(c_bal)).toLocaleString()}</b>)<br>are you sure?`,() => {
				// alert_info('deducting');
				setTimeout(() => {
					step2();
				},700);
			})
		}

		const step2 = () => {
			if(c_bal < a_bal){
				confirmAction('<span class="h4">Insufficient Credits</span>',`the client's balance of <b>${Math.floor(Number(c_bal)).toLocaleString()}</b> is less than the required <b>${Math.floor(Number(a_bal)).toLocaleString()}</b><br><br>Do you still want to deduct?`,() => {
					// alert_info('deducting');
					step3();
				})
			} else {
				step3();
			}
		}

		const step3 = () => {
			// alert_info('running actual deduction procedure');
			let topay = c_bal;

			topay = topay > a_bal ? a_bal : topay;

			let inter = task_runtime_data.paymentData || [];
			inter.push({
				method: 'Client credit',
				amt: topay,
				timestamp: new Date(),
			});

			task_runtime_data.paymentData = inter;
			render_all_data();
			alert_success('payment added successfully');
			el.style.display = 'none';
		}

		step1();
	}
	window['edit_payment'] = (el) => {
		let id = el.dataset.myid;

		if(id == undefined){
			alert_danger('invalid request method');
			return;
		}

		if(id >= task_runtime_data.paymentData.length){
			alert_danger('invalid sample selection');
			return;
		}

		// populate the form
		let currec = task_runtime_data.paymentData[id];

		payment_method.value = currec.method;
		payment_amount.value = currec.amt;
		// sample_tag.value = currec.tag;

		// remove from saved samples
		task_runtime_data.paymentData.splice(id,1);
		render_all_data();
	}
	window['remove_payment'] = (el) => {
		let id = el.dataset.myid;

		if(id == undefined){
			alert_danger('invalid request method');
			return;
		}

		if(id >= task_runtime_data.paymentData.length){
			alert_danger('invalid sample selection');
			return;
		}

		let currec = task_runtime_data.paymentData[id];

		confirmAction('<span class="h4">Remove Payment Record</span>','are you sure you want to remove this payment from the list?',() => {
			task_runtime_data.paymentData.splice(id,1);
			render_all_data();
			alert_success('payment removed');
		})
	}

	// finalize ops
	window['finalize_task_creation'] = (el) => {
		//  || task_runtime_data.paymentData == undefined
		if(task_runtime_data.clientData == undefined || task_runtime_data.sampleData == undefined){
			alert_danger('invalid data found, confirm your information first');
			return;
		}

		let tosend = task_runtime_data;
		tosend.note = el.task_note.value;

		const step1 = () => {
			alert_info('verifying payments');

			if(tosend.paymentStats.balance > 0 || tosend.paymentStats.balance == undefined){
				confirmAction('<span class="h4">Balance Not cleared</span>','the client has not paid in full<br><br>do you still wish to proceed?',() => {
					awaitConfirmPass(step1b);
				});
			} else {
				step2();
			}
		}

		const step1b = (dta) => {
			tosend.admin_password = dta.admin_password;
			alert_info('continuing with incomplete balance');
			step2();
		}

		const step2 = () => {
			pick_step(4);
			alert_info('saving to database');
			progress_panel.classList.add('is_inactive');

			// set timeout to prevent request overload
			setTimeout(() => {
				// console.log('task data to pass: ',tosend);
				window[fetch_bypass](linkAtlas['add_new_task'],{'sentdata' : tosend},'POST').then((d) => {
					if(d.result == false){
						pick_step(3);
						progress_panel.classList.remove('is_inactive');
					}
					responseHandler(d,final_step,d);
				});
			},1000 + (Math.random() * 3000));
		}

		const final_step = (dta) => {
			window[task_data_var] = dta.tosend;
			localStorage.removeItem(prefsAtlas['last_saved_task']);

			confirmAction(mekHeading('Manage created task','h4'),'would you like to manage the created task',() => {
				// alert_info('going to task management UI');
				window['cur_task_id'] = dta.sendme.id;

				let senddeets = {
					template: linkAtlas['task_info'],
					sidenote: 'manage Task',
				};
				loadPage(senddeets);
			},false,() => {
				alert_info('you are adding another task');
			});

			task_runtime_data.sampleData = [];
			task_runtime_data.paymentData = [];

			render_all_data();

			task_runtime_data = {};

			pick_step(0);
			progress_panel.classList.remove('is_inactive');
		}

		step1();
	}

	// rendering ops
	function render_all_data() {
		if(task_runtime_data.clientData !== undefined){
			render_client_data();
		}
		if(task_runtime_data.sampleData !== undefined){
			render_samples_data();
		}
		if(task_runtime_data.paymentData !== undefined){
			render_payments_data();
		}

		// render stats
		render_samples_stats();
		render_payments_stats();
		refreshUI(300);
	}
	function render_client_data() {
		// alert_dark('rendering client data');
		let cld = task_runtime_data.clientData;

		if(cld == undefined){
			alert_danger('select a client first')
			return;
		}
		let clientboxes = document.querySelectorAll('[data-role="client_card"]');

		clientboxes.forEach(cb => {
			let name = cb.querySelector('[data-subrole="thename"]');
			let sex = cb.querySelector('[data-subrole="client_gender"]');
			let pay = cb.querySelector('[data-subrole="payment_plan"]');

			if(name !== undefined){name.innerHTML = cld.client_name;}
			if(sex !== undefined){sex.innerHTML = cld.client_gender;}
			if(pay !== undefined){pay.innerHTML = cld.payment_plan;}
		})
	}
	function render_samples_data() {
		// alert_dark('rendering samples');

		const showEmpty = () => {
			sample_tablebody.innerHTML = `
				<tr>
					<td colspan="12" class="w3-center spacy-sm">
						<i>no samples added yet</i>
					</td>
				</tr>
			`;
		};

		if(task_runtime_data.sampleData == undefined){
			showEmpty();
			return;
		}

		if(task_runtime_data.sampleData.length == 0){
			showEmpty();
			return;
		}

		let outht = '';

		task_runtime_data.sampleData.forEach((s,id) => {
			outht += `
				<tr>
					<td class="w3-hide">
						<input type="checkbox" class="checkbox-custom" onchange="toggleSelect('STU-1001')">
					</td>

					<td><span>${id + 1}</span></td>
					<td><span>${s.typ}</span></td>
					<td><span>${s.tag}</span></td>
					<td><span>${s.amt}</span></td>
					<td class="w3-hide"><span>5,000</span></td>

					<td class="w3-hide_">
						<div class="flowline gap-tn">
							<a class="mybtn outline" data-runme="edit_sample" data-myid="${id}" href="#add_sample_form" title="Edit">
								<i class="fas fa-edit"></i> edit
							</a>
							<button class="mybtn bg-danger w3-text-white" data-runme="remove_sample" data-myid="${id}" title="Delete">
								<i class="fas fa-trash"></i> remove
							</button>
						</div>
					</td>
				</tr>
			`;
		})

		sample_tablebody.innerHTML = outht;
	}
	function render_samples_stats() {
		let viewOverride = undefined;
		let showthis = [];
		let s_data = task_runtime_data.sampleData;

		if(s_data == undefined){
			viewOverride = 0;
		} else if(s_data.length == 0){
			viewOverride = 0;
		}

		// fetch UIs
		let sample_stats = document.querySelector('[data-role="samples_stats_card"]');
		let total_samples = undefined, total_sample_entries = undefined, total_cost = undefined;

		if(sample_stats !== undefined){
			total_samples = sample_stats.querySelector('[data-subrole="total_samples"]');
			total_sample_entries = sample_stats.querySelector('[data-subrole="total_sample_entries"]');
			total_cost = sample_stats.querySelector('[data-subrole="total_cost"]');

			// render total samples
			if(s_data !== undefined){
				let out_d = 0;
				s_data.forEach(d => {out_d += Number(d.amt);})
				showthis.push(out_d);

				out_d = s_data.length;
				showthis.push(out_d);

				out_d = 0;
				s_data.forEach(d => {
					let price = bizdata.pricing_data[d.typ];
					out_d += Number(d.amt) * price;
				})
				showthis.push(out_d);
			}
			total_samples.innerHTML = showthis[0] || 0;

			// render total entries
			total_sample_entries.innerHTML = showthis[1] || 0;

			// render total cost
			total_cost.innerHTML = showthis[2] == undefined ? 0 : showthis[2].toLocaleString();

			task_runtime_data.sampleStats = {
				total: showthis[0],
				entries: showthis[1],
				cost: showthis[2],
			}
		}
	}
	function render_payments_data() {
		// alert_dark('rendering payments');

		const showEmpty = () => {
			payments_tablebody.innerHTML = `
				<tr>
					<td colspan="12" class="w3-center spacy-sm">
						<i>no payments added yet</i>
					</td>
				</tr>
			`;
		};

		if(task_runtime_data.paymentData == undefined){
			showEmpty();
			return;
		}

		if(task_runtime_data.paymentData.length == 0){
			showEmpty();
			return;
		}

		let outht = '';

		task_runtime_data.paymentData.forEach((s,id) => {
			let editbtn = (s.method !== "Client credit") ?
				`
					<a class="mybtn outline" data-runme="edit_payment" data-myid="${id}" href="#add_sample_form" title="Edit">
						<i class="fas fa-edit"></i> edit
					</a>
				`
				: '';
			outht += `
				<tr>
					<td class="w3-hide">
						<input type="checkbox" class="checkbox-custom" onchange="toggleSelect('STU-1001')">
					</td>

					<td><span>${id + 1}</span></td>
					<td><span>${s.method}</span></td>
					<td><span>${s.amt}</span></td>
					<td><span>${formatDate0(s.timestamp)}</span></td>
					<td class="w3-hide"><span>5,000</span></td>

					<td class="w3-hide_">
						<div class="flowline gap-tn">
							${editbtn}
							<button class="mybtn bg-danger w3-text-white" data-runme="remove_payment" data-myid="${id}" title="Delete">
								<i class="fas fa-trash"></i> remove
							</button>
						</div>
					</td>
				</tr>
			`;
		})

		payments_tablebody.innerHTML = outht;

		refreshUI(300);
	}
	function render_payments_stats() {
		// alert_info('rendering pay stats');

		let viewOverride = undefined;
		let showthis = [];
		let _data = task_runtime_data.paymentData;

		if(_data == undefined){
			viewOverride = 0;
		} else if(_data.length == 0){
			viewOverride = 0;
		}

		// fetch UIs
		let stats = document.querySelector('[data-role="payment_stats_card"]');
		let s_stats = task_runtime_data.sampleStats;
		let c_data = task_runtime_data.clientData;

		let amount_due = undefined;
		let amount_paid = undefined;
		let balance = undefined;
		let client_credit = undefined;
		let pays_made = undefined;
		let is_monthly = task_runtime_data.clientData != undefined ? task_runtime_data.clientData.payment_plan == 'monthly' : false;

		if(stats !== undefined){
			amount_paid = stats.querySelector('[data-subrole="amount_paid"]');
			amount_due = stats.querySelector('[data-subrole="amount_due"]');
			balance = stats.querySelector('[data-subrole="balance"]');
			client_credit = stats.querySelector('[data-subrole="client_credit"]');
			pays_made = stats.querySelector('[data-subrole="pays_made"]');

			// calculate shit
			if(_data !== undefined){
				let out_d = _data.length;
				showthis.push(out_d);

				out_d = task_runtime_data.sampleStats.cost;
				showthis.push(out_d);

				out_d = 0;
				_data.forEach(d => {out_d += Number(d.amt);})
				showthis.push(out_d);

				out_d = showthis[1] - showthis[2];
				showthis.push(out_d);

				out_d = task_runtime_data.clientData.credit_balance;
				showthis.push(out_d);
			}

			pays_made.querySelector('b').innerHTML = showthis[0] == undefined ? 0 : showthis[0].toLocaleString();
			amount_due.querySelector('b').innerHTML = showthis[1] == undefined ? (s_stats.cost || 0) : showthis[1].toLocaleString();
			amount_paid.querySelector('b').innerHTML = showthis[2] == undefined ? 0 : showthis[2].toLocaleString();
			balance.querySelector('b').innerHTML = showthis[3] == undefined ? (s_stats.cost || 0) : showthis[3].toLocaleString();
			client_credit.querySelector('b').innerHTML = showthis[4] == undefined ? (c_data !== undefined ? c_data.credit_balance : 0) : Math.floor(showthis[4]).toLocaleString();

			// UI appearance stuff
			let balcon = showthis[3] <= 0;

			let items = is_monthly ? 5 : 4;
			let split = Math.floor(12 / items);
			let bal_class_add = balcon ? "w3-text-green" : "w3-text-red";
			let bal_class_remove = !balcon ? "w3-text-green" : "w3-text-red";

			pays_made.className = `col-sm-6 col-md-${split}`;
			amount_due.className = `col-sm-6 col-md-${split}`;
			amount_paid.className = `col-sm-6 col-md-${split}`;
			balance.className = `col-sm-6 col-md-${split}`;
			client_credit.className = `col-sm-6 col-md-${split}`;
			client_credit.style.display = is_monthly ? 'block' : 'none';
			balance.querySelector('b').classList.add(bal_class_add);
			balance.querySelector('b').classList.remove(bal_class_remove);
			deduct_from_credit_btn.style.display = is_monthly ? 'inline-flex' : 'none';

			if(balcon && showthis[3] !== undefined){
				add_payment_form.classList.add('is_inactive');
			} else {
				add_payment_form.classList.remove('is_inactive');
			}

			// render total cost
			// total_cost.innerHTML = showthis[2] == undefined ? 0 : showthis[2].toLocaleString();

			task_runtime_data.paymentStats = {
				pays_made : showthis[0],
				amount_due : showthis[1],
				amount_paid : showthis[2],
				balance : showthis[3],
				client_credit : showthis[4],
			};
		}
	}
</script>
