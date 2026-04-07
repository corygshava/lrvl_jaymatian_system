<!-- add new modal setup -->
<button class="mybtn primary w3-hide" id="add_edit_modal_trig" data-toggle="modal" data-target="#add_edit_modal">
	<i class="fas fa-plus"></i>
	Add New
</button>

<div class="modal fade" id="add_edit_modal" tabindex="-1" role="dialog" aria-labelledby="add_edit_title" aria-hidden="true" style="z-index: 102;">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content themeround borderless">
			<div class="modal-header">
				<span class="h3 modal-title" id="add_edit_title">Add Expense</span>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<form id="add_edit_form" action="defined:add_expense" data-onsubmit="sendform" data-blockdefault="yes" data-callback="add_form_afterfx" method="post">
					<input type="hidden" name="recid" value="">
					<input type="hidden" name="opname" value="add">
					<input type="hidden" name="bizdata_pricing" value="add">

					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="admin_password">Your Password</label>
							<input type="password" class="form-control-custom" id="admin_password" name="admin_password" placeholder="enter your password" required>
						</div>
					</div>

					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="purpose">Purpose</label>
							<input type="text" class="form-control-custom" id="purpose" name="purpose" placeholder="what was it used for" required>
						</div>
					</div>

					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="amount">Amount spent</label>
							<input type="number" class="form-control-custom" id="amount" name="amount" placeholder="how much was sent" required>
						</div>
						<div class="form-group">
							<label class="form-label" for="desc">Description (optional)</label>
							<textarea class="form-control-custom" id="desc" name="desc" placeholder="brief description of what the cash was used for"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="mybtn secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="mybtn primary" data-submitme="#add_edit_form">Save</button>
			</div>
		</div>
	</div>
</div>

<!-- Expense record viewer -->
<button class="mybtn primary w3-hide" id="view_expense_modal_trig" data-toggle="modal" data-target="#expense_info_modal">
	<i class="fas fa-plus"></i>
	View Report details
</button>

<div class="modal fade" id="expense_info_modal" tabindex="-1" role="dialog" aria-labelledby="report_info_heading" aria-hidden="true" style="z-index: 102;">
	<div class="modal-dialog modal-mg" role="document">
		<div class="modal-content themeround borderless">
			<div class="modal-header">
				<span class="h3 modal-title" id="report_info_heading">Expense Information</span>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div id="expense_DataContainer"></div>
			</div>
			<div class="modal-footer w3-hide">
				<button type="button" class="mybtn secondary" data-dismiss="modal">Cancel</button>
				<!-- <button type="button" class="mybtn primary" data-submitme="#add_edit_form">Save</button> -->
			</div>
		</div>
	</div>
</div>

<script>
	let expense = {};

	purpose_classes = {
		'expenditure' : 'alert-primary w3-text-black',
		'bank transfer' : 'alert-warning w3-text-black',
	};

	window['renderExpenseData'] = (dta) => {
		if (!dta || dta.rectype !== 'expense') {
			alert_danger('Invalid data: rectype must be "expense"');
			return;
		}

		expense = dta.data;
		const container = expense_DataContainer;

		container.innerHTML = 'loading...';
		let outht = '';
		let deets_outht = '';

		let toshow = {
			'internal serial' : expense.expenditure_no,
			'purpose' : `<strong class="mybadge themeround ${purpose_classes[expense.purpose.toLowerCase()]}">${expense.purpose}</strong>`,
			'Added by' : expense.mycreator.name,
			'Amount' : `<strong>${formatNumber(expense.amount,2)}</strong>`,
			'Added on' : `<span>${formatDate0(expense.created_at)}</span>`,
			'Desc' : `<p>${expense.desc}</p>`,
		};
		Object.keys(toshow).forEach(f => {
			deets_outht += `
				<div class="info-row">
					<span class="info-label">${f.split("_").join(' ')}:</span>
					<span class="info-value">${toshow[f]}</span>
				</div>
			`;
		});

		outht = `
			<div class="sample-info-card w3-hide_">
				<h5><i class="${iconAtlas['expenses']}"></i> Expense Information</h5>
				${deets_outht}
			</div>

			<div class="distance-sm border themeround collapser">
				<a class="text-decoration-none spacy-sm flowline spread centerline" href="#userInfo" data-toggle="collapse" data-target="#userInfo">
					<span class="text-uppercase text-muted font-weight-bold"><i class="${iconAtlas['users']}"></i> Creator Information</span>
					<i class="fa fa-chevron-down myicon"></i>
				</a>
				<div class="collapse panelbg distance-sm spacy-sm" id="userInfo">
					<div class="info-row">
						<span class="info-label">Name:</span>
						<span class="info-value">${expense.mycreator.name}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Email:</span>
						<span class="info-value">${expense.mycreator.email}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Phone:</span>
						<span class="info-value">${expense.mycreator.phone_number}</span>
					</div>
					<div class="info-row">
						<span class="info-label">Last Login:</span>
						<span class="info-value">${formatDate0(expense.mycreator.last_login_at)}</span>
					</div>
				</div>
			</div>
		`;

		container.innerHTML = outht;
	}
</script>
