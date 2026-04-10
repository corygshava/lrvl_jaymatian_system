<button class="mybtn primary w3-hide" id="add_edit_modal_trig" data-toggle="modal" data-target="#add_edit_modal">
	<i class="fas fa-plus"></i>
	Add New
</button>

<div class="modal fade" id="add_edit_modal" tabindex="-1" role="dialog" aria-labelledby="add_edit_title" aria-hidden="true" style="z-index: 102;">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content themeround borderless">
			<div class="modal-header">
				<span class="h3 modal-title" id="add_edit_title">Add New User__</span>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<!-- Multiple inputs -->
				<form id="add_edit_form" action="./op/users" data-onsubmit="sendform" data-blockdefault="yes" data-callback="form_afterfx" method="post">
					<input type="hidden" name="recid" value="">
					<input type="hidden" name="opname" value="add">
					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="user_name">Username</label>
							<input type="text" class="form-control-custom" id="user_name" name="user_name" placeholder="what are their names" required>
						</div>
					</div>

					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="user_email">Email</label>
							<input type="email" class="form-control-custom" id="user_email" name="user_email" placeholder="what is their email" required>
						</div>
					</div>

					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="user_contact">Phone Number</label>
							<input type="tel" class="form-control-custom" id="user_contact" name="user_contact" placeholder="what is their phone number">
						</div>
					</div>

					<div class="input-group-custom" id="password_field_input">
						<div class="form-group">
							<label class="form-label" for="user_password">Password</label>
							<input type="password" class="form-control-custom" id="user_password" name="user_password" placeholder="enter password" required>
						</div>
					</div>

					<div class="input-group-custom" id="user_role_field_input">
						<div class="form-group">
							<label class="form-label" for="user_role_name">Role</label>
							<select class="form-control-custom" id="user_role_name" name="user_role_name" placeholder="what is their assined role" required></select>
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

<button class="mybtn primary w3-hide" id="change_pw_modal_trig" data-toggle="modal" data-target="#change_password_modal">
	<i class="fas fa-plus"></i>
	Change password
</button>

<div class="modal fade" id="change_password_modal" tabindex="-1" role="dialog" aria-labelledby="change_password_title" aria-hidden="true" style="z-index: 102;">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content themeround borderless">
			<div class="modal-header">
				<span class="h3 modal-title" id="change_password_title">Change Password</span>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<!-- Multiple inputs -->
				<form id="change_pw_form" action="./op/change_password" data-onsubmit="sendform" data-blockdefault="yes" data-callback="form_pw_afterfx">
					<input type="hidden" name="recid" value="">
					<input type="hidden" name="opname" value="add">
					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="user_name">Username</label>
							<input type="text" class="form-control-custom" id="user_name" name="user_name" placeholder="what are their names" readonly required>
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
							<label class="form-label" for="user_password">User's new Password</label>
							<input type="password" class="form-control-custom" id="user_password" name="user_password" placeholder="enter the user's new password" required>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="mybtn secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="mybtn primary" data-submitme="#change_pw_form">Save</button>
			</div>
		</div>
	</div>
</div>

<script>
	function updateUI(w) {
		add_edit_modal.style.pointerEvents = 'all';
		add_edit_modal.style.opacity = '1';
		user_roles = w.sendme;

		// update the role select to have all current roles
		if(user_role_name === undefined){
			return;
		}

		user_role_name.innerHTML = '';

		let dft = document.createElement('option');
		dft.value = "";
		dft.textContent = "Select a role";
		dft.selected = true;
		dft.disabled = true;

		user_role_name.appendChild(dft);

		/*
		Object.keys(user_roles).forEach(r => {
			let opt = document.createElement('option');
			opt.value = r;
			opt.textContent = user_roles[r];

			user_role_name.appendChild(opt);
		})
		return;
		*/

		[...user_roles].forEach((r) => {
			let opt = document.createElement('option');
			opt.value = r.id;
			opt.textContent = r.role_name;

			user_role_name.appendChild(opt);
		});

		alert_info('checking permissions');

		if(!mypermissions.includes(getitempermissions(linkAtlas['view_user_roles']))){
			user_role_name.classList.add('is_inactive');
		} else {
			user_role_name.classList.remove('is_inactive');
		}

		if(!mypermissions.includes('CHANGE_PASSWORDS')){
			change_pw_modal_trig.classList.add('is_inactive');
		} else {
			change_pw_modal_trig.classList.remove('is_inactive');
		}

		return;
	}

	function setup_form() {
		add_edit_modal.style.pointerEvents = 'none';
		add_edit_modal.style.opacity = '0.2';

		window[fetch_bypass]('./data/user_roles',{},'get').then((d) => {
			responseHandler(d,updateUI,d,true);
			// alert_info('render this');
		})
	}
</script>
