<button class="mybtn primary w3-hide" id="add_edit_modal_trig" data-toggle="modal" data-target="#add_edit_modal">
	<i class="fas fa-plus"></i>
	Add New
</button>

<div class="modal fade" id="add_edit_modal" tabindex="-1" role="dialog" aria-labelledby="add_edit_title" aria-hidden="true" style="z-index: 102;">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content themeround borderless">
			<div class="modal-header">
				<span class="h3 modal-title" id="add_edit_title">Add New client</span>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<!-- Multiple inputs -->
				<form id="add_edit_form" data-trigger="add_edit_modal_trig" action="./op/clients" data-onsubmit="sendform" data-blockdefault="yes" data-callback="form_afterfx" method="post">
					<input type="hidden" name="recid" value="">
					<input type="hidden" name="opname" value="add">

					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="client_name">Client Name</label>
							<input type="text" class="form-control-custom" id="client_name" name="client_name" placeholder="enter client name" required>
						</div>
					</div>

					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="client_contact">Contact Number</label>
							<input type="tel" class="form-control-custom" id="client_contact" name="client_contact" placeholder="enter contact number" required>
						</div>
					</div>

					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="client_gender">Gender</label>
							<select class="form-control-custom" id="client_gender" name="client_gender" required>
								<option value="" selected>Select gender</option>
								<option value="male">Male</option>
								<option value="female">Female</option>
							</select>
						</div>
					</div>

					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="payment_plan">Payment Plan</label>
							<select class="form-control-custom" id="payment_plan" name="payment_plan" required>
								<option value="per_visit" selected>Per Visit</option>
								<option value="monthly">Monthly</option>
							</select>
						</div>
					</div>

					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="kra_pin">KRA PIN <span style="opacity: 0.6;">(optional)</span></label>
							<input type="text" class="form-control-custom" id="kra_pin" name="kra_pin" placeholder="enter KRA PIN">
						</div>
					</div>

					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="id_number">ID Number <span style="opacity: 0.6;">(optional)</span></label>
							<input type="text" class="form-control-custom" id="id_number" name="id_number" placeholder="enter ID number">
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

<button class="mybtn primary w3-hide" id="change_credit_modal_trig" data-toggle="modal" data-target="#change_credit_modal">
	<i class="fas fa-plus"></i>
	Modal 2
</button>

<div class="modal fade" id="change_credit_modal" tabindex="-1" role="dialog" aria-labelledby="change_credit_title" aria-hidden="true" style="z-index: 102;">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content themeround borderless">
			<div class="modal-header">
				<span class="h3 modal-title" id="change_credit_title">Manage Credit Balance</span>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<p><b>NOTE: </b> credit balance can only be edited for users with a <b class="themetxt">monthly</b> payment plan</p>
				<hr>
				<form id="change_credit_form" data-trigger="change_credit_modal_trig" action="./op/change_credit" data-onsubmit="sendform" data-blockdefault="yes" data-callback="form_afterfx">
					<input type="hidden" name="recid" value="">
					<input type="hidden" name="opname" value="add">
					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="client_name_2">Client Name</label>
							<input type="text" class="form-control-custom" id="client_name_2" name="client_name_2" placeholder="client name" readonly required>
						</div>
					</div>

					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="user_credit">Current Credit Balance</label>
							<input type="number" class="form-control-custom" id="user_credit" name="user_credit" placeholder="credit balance" readonly required>
						</div>
					</div>

					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="admin_password">Your Password</label>
							<input type="password" class="form-control-custom" id="admin_password" name="admin_password" placeholder="enter your password for authorization" required>
						</div>
					</div>

					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="credit_amt">Amount</label>
							<input type="number" class="form-control-custom" id="credit_amt" name="credit_amt" placeholder="enter the amount" required>
						</div>
					</div>

					<div class="input-group-custom" id="user_role_field_input">
						<div class="form-group">
							<label class="form-label" for="credit_action">Action</label>
							<select class="form-control-custom" id="credit_action" name="credit_action" placeholder="what is their assined role" required>
								<option value="" disabled selected>pick an action below</option>
								<option value="set">set as current balance</option>
								<option value="subtract">remove from client balance</option>
								<option value="add">add to client balance</option>
							</select>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="mybtn secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="mybtn primary" data-submitme="#change_credit_form">Save</button>
			</div>
		</div>
	</div>
</div>

<script>
	function updateUI(w) {
		add_edit_modal.style.pointerEvents = 'all';
		add_edit_modal.style.opacity = '1';
		return true;
	}

	function setup_form() {
		add_edit_modal.style.pointerEvents = 'none';
		add_edit_modal.style.opacity = '0.2';
		return updateUI();
	}
</script>
