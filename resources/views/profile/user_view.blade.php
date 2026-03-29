<?php
	// require_once __DIR__.'/piece_add_user_form.php';
?>

{{-- <!-- --}}
@include('pieces.forms_userops')
{{-- --> --}}

<style>
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

<div class="container-fluid">
	<div class="page-header">
		<div class="page-title">
			<span class="h4" data-role="pagename">--</span>
			<p>This page shows a user's saved profile details</p>
		</div>
		<div id="to_user_mgt_btn">
			<a href="#" class="mybtn primary sm" data-runme="load_page" data-label="defined:user_mgt">
				<i data-myicon="clients"></i> user management
			</a>
		</div>
	</div>

	<!-- User Profile Card Component -->
	<div id="user-profile-container" class="container-fluid p-0 w3-hide">
		<div class="profile_card">
			<div class="card themeround border-0 panelbg shadow-md">
				<div class="card-header panelbg themegrad flowline overflow left centerline w3-display-container">
					<div class="avatar-circle bg-white text-primary">
						<span id="user-initials">??</span>
					</div>
					<div>
						<strong class="h3" id="user-name"></strong>
						<b>Role: </b><span id="user-role-badge">test</span>
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
								<a class="dropdown-item text-danger border-top spacy-tn" id="deact_user_btn" href="#" data-runme="deactivateAccount">
									<i class="fas fa-user-slash mr-2"></i>Deactivate
								</a>
							</div>
						</div>
					</div>
					<div class="w3-display-topright slide-up" id="account_active_tag">
						<div class="spacy-tn w3-red">account Inactive</div>
					</div>
				</div>

				<div class="card-body">
					<div class="flowline overflow w3-center stats">
						<div class="stat w3-hide">
							<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">User ID</small>
							<b class="text-primary font-weight-bold"><span id="user-id">2</span></b>
						</div>
						<div class="stat">
							<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Registered Since</small>
							<span class="text-dark font-weight-bold" id="member-since">--</span>
						</div>
						<div class="stat">
							<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Last Login</small>
							<span class="text-dark font-weight-bold" id="last-login">--</span>
						</div>
						<div class="stat">
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
</div>

<script>
	let userData = {};

	window['killOnLeave'] = [

	];

	window['runOnAwake'] = () => {
		to_user_mgt_btn.querySelector('a').dataset.link = linkAtlas['view_users'];

		let cls = !(mypermissions.includes('MANAGE_USERS')) ? 'add' : 'remove';
		to_user_mgt_btn.classList[cls]('w3-hide');

		cls = !(mypermissions.includes('ACTIVATE_USER')) ? 'add' : 'remove';
		deact_user_btn.classList[cls]('w3-hide');

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

	window['initializeUserProfile'] = (data) => {
		let con = (data.is_active)
		let cls = !con ? 'remove' : 'add';
		account_active_tag.classList[cls]('w3-hide');

		cls = `dropdown-item border-top spacy-tn ${con ? 'text-danger' : 'text-success'}`;
		let content = !con ? `<i class="fas fa-check mr-2"></i>Activate account` : `<i class="fas fa-user-slash mr-2"></i>Deactivate`;
		deact_user_btn.innerHTML = content;
		deact_user_btn.className = cls;
		deact_user_btn.dataset.act = con ? 'deactivate' : 'activate';

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

		// Format dates
		const createdDate = new Date(data.created_at);
		const updatedDate = new Date(data.updated_at);
		const loginDate = new Date(data.last_login_at);

		document.getElementById('member-since').textContent = formatDate(createdDate);
		document.getElementById('last-updated').textContent = formatDate(updatedDate);
		document.getElementById('last-login').textContent = loginDate.getTime() == 0 ? '--' : formatDate(loginDate);

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
	window['renderPermissions'] = (perms) => {
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

	function formatDate(date) {
		return formatDate0(date);

		return date.toLocaleDateString('en-US', {
			month: 'short',
			day: 'numeric',
			year: 'numeric'
		});
	}

	function formatDateTime(date) {
		return formatDate0(date);

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
	function editProfile() {
		console.log('Edit profile clicked');
		// Implement your edit logic here
		// alert('Edit profile functionality would open here');
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

	window['deactivateAccount'] = (el) => {
		if(el == undefined){
			alert_danger('invalid procedure');
			return;
		}

		let con = userData.is_active;
		let exp = con ?
			'the user will be unable to log in after this' :
			'the user will be able to log in after this';

		const deactaccount = (ps) => {
			const data = {
				recid: userData.id,
				...ps
			};

			const showres = (f) => {
				initializeUserProfile(f.sendme);
			}

			window[fetch_bypass](linkAtlas['toggle_account_active'],data,'POST').then((d) => {
				responseHandler(d,showres,d);
			})
		}

		confirmAction(`${el.dataset.act} Account`,`Are you sure you want to <b>${el.dataset.act}</b> this user account?<br></br>${exp}`,() => {
			setTimeout(() => {
				awaitConfirmPass(deactaccount);
			},200)
		});
	}

	window['edit_user'] = (e) => {
		// if(e === undefined){return;}

		roleop = "edit";
		rec_id = userData.id;
		add_edit_form.recid.value = rec_id;

		// let myn = window['curUserData'].filter(f => {return f.id.toString().toLowerCase() == ("" + rec_id).toLowerCase();})[0];
		let myn = userData;

		// window['userBeingEdited'] = myn;

		add_edit_form.name.value = myn.name;
		add_edit_form.user_name.value = myn.name;
		add_edit_form.user_email.value = myn.email;
		add_edit_form.user_password.value = "*************************";
		add_edit_form.user_password.setAttribute('readonly',true);

		// add_edit
		add_edit_form.user_role_name.value = myn.my_roles.id;
		// add_edit_form.user_role_name.value = myn.role_name;

		add_edit_modal_trig.click();

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

		// let myn = window['curUserData'].filter(f => {return f.id.toString().toLowerCase() == ("" + rec_id).toLowerCase();})[0];
		let myn = userData;

		// window['userBeingEdited'] = myn;

		// maform.setAttribute('readonly',false);
		maform.user_name.value = myn.name;
		// maform.setAttribute('readonly',true);
		// maform.user_role_name.value = myn.my_roles.id;
		// add_edit_form.user_role_name.value = myn.role_name;

		change_pw_modal_trig.click();

		// alert_info(rec_id);
		// alert_info(e.textContent);
		add_edit_title.innerHTML = "Edit user Details";
	}

	window['form_afterfx'] = (m) => {
		// alert_dark('data sent back');
		// console.log(m);
		let udata = m.data.sendme;
		userData = udata;
		initializeUserProfile(udata);
		compareUserData(udata);

		add_edit_modal_trig.click();
	}

	window['form_pw_afterfx'] = () => {
		// alert_danger('runnin afterfx');
		change_pw_modal_trig.click();
	}
</script>
