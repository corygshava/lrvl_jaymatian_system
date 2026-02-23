<style>
	#user-profile-container{
		--width: 900px;
	}
	.profile_card{
		/*overflow: hidden;*/
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
	#user-profile-container .avatar-circle {
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

	.profile_card .user_stats .user_stat{
		flex: 1 0 175px;
		padding: 12px 0;
	}

	.profile_card .media{
		display: flex;
		flex-wrap: nowrap;
		flex-direction: row;
		width: 100%;
		/*background: red;*/
		padding: 12px 0;
		flex: 1 1 calc(var(--width) * 0.4);
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
</style>

<div id="to_user_mgt_btn">
	<a href="#" class="backbtn" data-runme="load_page" data-label="User management"><i class="fa fa-arrow-left"></i> back to <b>user management</b></a>
</div>

<!-- User Profile Card Component -->
<div id="user-profile-container" class="container-fluid p-0 w3-hide">
	<div class="profile_card">
		<div class="card themeround border-0 panelbg shadow-md">
			<div class="card-header panelbg themegrad flowline overflow left centerline">
				<div class="avatar-circle bg-white text-primary">
					<span id="user-initials">C</span>
					<span class="badge bg-danger w3-display-topright w3-text-white slide-up" id="inactive_account_marker">account inactive</span>
				</div>
				<div>
					<strong class="h3" id="user-name"></strong>
					<b>Role: </b><span class="badge badge-dark" id="user-role-badge">test</span>
				</div>
				<div class="w3-display-right spacy-md" style="z-index: 102">
					<div class="dropdown">
						<button class="btn btn-link text-white" type="button" data-toggle="dropdown">
							<i class="fas fa-ellipsis-v"></i>
						</button>
						<div class="dropdown-menu dropdown-menu-right themeround panelbg border-0 w3-card">
							<a class="dropdown-item spacy-tn" href="#" onclick="editProfile()">
								<i class="fas fa-edit mr-2 text-muted"></i>Edit Profile
							</a>
							<a class="dropdown-item spacy-tn" href="#" onclick="changePassword()">
								<i class="fas fa-key mr-2 text-muted"></i>Change Password
							</a>
							<!-- <div class="dropdown-divider"></div> -->
							<a class="dropdown-item text-danger border-top spacy-tn" id="deact_user_btn" href="#" onclick="deactivateAccount()">
								<i class="fas fa-user-slash mr-2"></i>Deactivate
							</a>
						</div>
					</div>
				</div>
			</div>

			<div class="card-body">
				<div class="flowline overflow w3-center user_stats">
					<div class="user_stat">
						<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">User ID</small>
						<b class="text-primary font-weight-bold"><span id="user-id">2</span></b>
					</div>
					<div class="user_stat">
						<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Member Since</small>
						<span class="text-dark font-weight-bold" id="member-since">--</span>
					</div>
					<div class="user_stat">
						<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Last Login</small>
						<span class="text-dark font-weight-bold" id="last-login">--</span>
					</div>
					<div class="user_stat">
						<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Last Updated</small>
						<span class="text-dark font-weight-bold" id="last-updated">--</span>
					</div>
				</div>

				<span class="text-uppercase text-muted font-weight-bold">Contact information</span>

				<div class="flowline gap-sm overflow distance-sm">
					<div class="media gap-sm">
						<div>
							<div class="icon-circle">
								<i class="fa fa-envelope"></i>
							</div>
						</div>
						<div class="media-body">
							<small class="text-muted text-uppercase d-block">Email Address</small>
							<span class="font-weight-medium" id="user-email">--</span>
							<span id="email-verified-badge" class="w3-hide badge badge-warning badge-pill ml-2" style="font-size: 0.65rem;">
								<i class="fas fa-exclamation-circle mr-1"></i>--
							</span>
						</div>
					</div>

					<div class="media gap-sm">
						<div>
							<div class="icon-circle">
								<i class="fa fa-phone"></i>
							</div>
						</div>
						<div class="media-body">
							<small class="text-muted text-uppercase d-block">Phone number</small>
							<span class="font-weight-medium" id="user-phone">--</span>
							<span id="email-verified-badge" class="w3-hide badge badge-warning badge-pill ml-2" style="font-size: 0.65rem;">
								<i class="fas fa-exclamation-circle mr-1"></i>--
							</span>
						</div>
					</div>
				</div>

				<span class="text-uppercase text-muted font-weight-bold">User permissions</span>

				<div class="distance-sm">
					<div class="d-flex justify-content-between align-items-center mb-3 w3-hide">
						<span class="badge badge-primary badge-pill" id="permission-count">0</span>
					</div>

					<div class="permissions">
						<ul id="permissions-container"></ul>
					</div>
				</div>

				<!-- <span class="text-uppercase text-muted font-weight-bold">User permissions</span> -->

				<div class="distance-sm border themeround">
					<a class="text-decoration-none spacy-sm flowline spread centerline" href="#roleDetails" data-toggle="collapse" data-target="#roleDetails">
						<span class="text-uppercase text-muted font-weight-bold">Role details</span>
						<i class="fa fa-chevron-down"></i>
					</a>
					<div class="collapse panelbg distance-sm spacy-sm" id="roleDetails">
						<div class="row">
							<div class="col-md-12">
								<small class="text-muted">Role Name</small>
								<p class="font-weight-bold mb-2" id="role-name-detail">test</p>
							</div>
						</div>
						<div class="row mt-2">
							<div class="col-md-6">
								<small class="text-muted">Created</small>
								<p class="mb-0 text-monospace small" id="role-created">2026-02-14 13:33:46</p>
							</div>
							<div class="col-md-6">
								<small class="text-muted">Last Modified</small>
								<p class="mb-0 text-monospace small" id="role-updated">2026-02-14 13:44:18</p>
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
		<p>loading user record ...</p>
	</div>
</div>

@include('pieces.forms_userops')

<script>
	let userData = {};

	window['runOnAwake'] = () => {
		to_user_mgt_btn.querySelector('a').dataset.link = linkAtlas['view_users'];

		if(!mypermissions.includes('MANAGE_USERS')){
			to_user_mgt_btn.remove();
		}

		if(window['cur_record'] !== undefined){
			if(window['cur_record'].rectype !== "user"){
				userData = window['cur_user'];
			} else {
				userData = window['cur_record'].data;
			}
		} else {
			userData = window['cur_user'];
		}

		initializeUserProfile(userData);
		// updateUI();
		setup_form();
		refreshUI(300);
	}

	function initializeUserProfile(data) {
		if(!mypermissions.includes('ACTIVATE_USER')){
			deact_user_btn.style.display = 'none';
			deact_user_btn.setAttribute('disabled', true);
		} else {
			deact_user_btn.style.display = 'block';
			deact_user_btn.removeAttribute('disabled');

            if(userData.is_active){
				deact_user_btn.classList.add('text-danger');
				deact_user_btn.classList.remove('text-success');
				deact_user_btn.innerHTML = `<i class="fas fa-user-slash mr-2"></i> Deactivate`;
			} else {
				deact_user_btn.classList.remove('text-danger');
				deact_user_btn.classList.add('text-success');
				deact_user_btn.innerHTML = `<i class="fas fa-user-check mr-2"></i> Activate`;
			}
        }

		// hide placeholders
		blank.classList.add('w3-hide');
		document.querySelector('#user-profile-container').classList.remove('w3-hide');

		// Set basic info
		document.getElementById('user-id').textContent = data.id;
		document.getElementById('user-name').textContent = data.name;
		document.getElementById('user-initials').textContent = data.name.charAt(0).toUpperCase();
		document.getElementById('user-email').textContent = data.email || "--";
		document.getElementById('user-phone').textContent = data.phone_number || "---";
		document.getElementById('user-role-badge').textContent = data.role_name;
		document.getElementById('inactive_account_marker').style.display = data.is_active ? 'none' : 'block';

		// Format dates
		const createdDate = new Date(data.created_at);
		const updatedDate = new Date(data.updated_at);
		const loginDate = data.last_login_at != null ? new Date(data.last_login_at) : null;

		document.getElementById('member-since').textContent = createdDate != null ? formatDate(createdDate) : '--';
		document.getElementById('last-updated').textContent = updatedDate != null ? formatDate(updatedDate) : '--';
		document.getElementById('last-login').textContent = loginDate != null ? formatDate(loginDate) : '--';

		// Email verification status
		const verifyBadge = document.getElementById('email-verified-badge');
		// const verifyBtn = document.getElementById('verify-btn');

		if (data.email_verified_at) {
			verifyBadge.className = 'badge badge-success badge-pill ml-2';
			verifyBadge.innerHTML = '<i class="fas fa-check-circle mr-1"></i>Verified';
			// verifyBtn.style.display = 'none';
		} else {
			// verifyBtn.style.display = 'inline-block';
		}

		// Role details
		// document.getElementById('role-id').textContent = data.my_roles.id;
		document.getElementById('role-name-detail').textContent = data.my_roles.role_name;
		document.getElementById('role-created').textContent = formatDateTime(new Date(data.my_roles.created_at));
		document.getElementById('role-updated').textContent = formatDateTime(new Date(data.my_roles.updated_at));

		// Render permissions
		renderPermissions(data.role_permissions);

		// Update load time
		document.getElementById('load-time').textContent = new Date().toLocaleTimeString();
	}

	function renderPermissions(perms) {
		const container = document.getElementById('permissions-container');
		const permissionIcons = {
			'VIEW_DASHBOARD': 'fa-chart-line',
			'ADD_ROLE': 'fa-user-plus',
			'VIEW_ANALYTICS': 'fa-chart-bar',
			'EDIT_USER': 'fa-user-edit',
			'DELETE_USER': 'fa-user-times',
			'MANAGE_SETTINGS': 'fa-cogs'
		};

		const permissionLabels = permissions;

		container.innerHTML = perms.map(perm => {
			// const icon = permissionIcons[perm] || 'fa-check';
			const label = permissionLabels[perm] || perm.replace(/_/g, ' ');

			return `<li>${label}</li>`;
		}).join('');

		document.getElementById('permission-count').textContent = perms.length;
	}

	// Action handlers
	function editProfile() {
		window['edit_user']();
	}

	function changePassword() {
		console.log('Change password clicked');
		window['change_password']();
	}

	function verifyEmail() {
		console.log('Verify email clicked');
		const btn = document.getElementById('verify-btn');
		btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
		btn.disabled = true;

		// Simulate API call
		setTimeout(() => {
			alert('Verification email sent!');
			btn.innerHTML = 'Verify';
			btn.disabled = false;
		}, 1000);
	}

	function deactivateAccount() {
		const step1 = () => {
			// allows entering the user's password
			awaitConfirmPass(step2);
		}

		const step2 = (arg) => {
			alert_info('password confirmed');

			window[fetch_bypass](linkAtlas['toggle_account_active'],{...arg,recid: userData.id},'POST').then(r => {
				responseHandler(r,step3,r);
			});
		}

		const step3 = (arg) => {
			// alert_info('user activated successfully');
			// alert_silent('process complete');
			userData = arg.sendme;
			compareUserData(userData);
			initializeUserProfile(userData);
		}

		const isa = userData.is_active;
		const op = isa ? 'deactivate' : 'activate';
		const exp = isa ? `
			The user will be <b class="themetxt">unable</b> to log in with their details after this.<br>
			Are you sure you want to ${op} this user account?
		` : `
			The user will be <b class="themetxt">able</b> to log in with their details after this.<br>
			Are you sure you want to ${op} this user account?
		`;

		confirmAction(`${op} Account`,exp,step1);
	}

	window['edit_user'] = (e) => {
        roleop = "edit";
		rec_id = userData.id;
		add_edit_form.recid.value = rec_id;

		let myn = userData;
		// window['userBeingEdited'] = myn;

		add_edit_form.name.value = myn.name;
		add_edit_form.user_name.value = myn.name;
		add_edit_form.user_email.value = myn.email;
		add_edit_form.user_contact.value = myn.phone_number || '';
		add_edit_form.user_password.value = "*************************";
		add_edit_form.user_password.setAttribute('readonly',true);
		add_edit_form.user_role_name.value = myn.my_roles.id;
		add_edit_modal_trig.click();

		if(!mypermissions.includes('MANAGE_USERS')){
			user_role_field_input.style.display = 'none';
		} else {
			user_role_field_input.style.display = 'block';
		}

		// alert_info(rec_id);
		// alert_info(e.textContent);
		add_edit_title.innerHTML = "Edit user Details";
	}

	window['change_password'] = (e) => {
		// if(e === undefined){return;}
		let maform = change_pw_form;

		roleop = "edit";
		rec_id = userData.id;
		maform.recid.value = rec_id;

		let myn = userData;

		// window['userBeingEdited'] = myn;

		maform.user_name.value = myn.name;

		change_pw_modal_trig.click();

		// alert_info(rec_id);
		// alert_info(e.textContent);
		add_edit_title.innerHTML = "Edit user Details";
	}

	window['form_afterfx'] = (d) => {
		// alert_danger('runnin afterfx');
		add_edit_modal_trig.click();

        userData = d.data.sendme;
		compareUserData(userData);
        initializeUserProfile(userData);
	}

	window['form_pw_afterfx'] = () => {
		// alert_danger('runnin afterfx');
		change_pw_modal_trig.click();
	}
</script>
