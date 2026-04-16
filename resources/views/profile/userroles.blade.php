<style>
	.data-header{
		padding: 20px;
		border-bottom: 1px solid var(--clr-border);
		display: flex;
		justify-content: space-between;
		align-items: center;
		flex-wrap: wrap;
		gap: 15px;
	}

	/* details cards */
	.data-body details.mycard {
		padding: 0;
		background: var(--clr-panelbg);
		border-radius: var(--roundness);
		border: 1px solid var(--clr-border);
		box-shadow:
			0 1px 3px color-mix(in srgb, var(--clr-text) 4%, transparent),
			0 4px 12px color-mix(in srgb, var(--clr-text) 2%, transparent);
		transition: box-shadow 0.2s ease, transform 0.1s ease;
		overflow: hidden;
		margin-bottom: 12px;
	}
	.data-body details.mycard[open] {
		border: 1px solid var(--themecolor);
	}
	.data-body details.mycard summary {
		padding: 16px 20px;
		font-size: 1.2rem;
		font-weight: 800;
		cursor: pointer;
		display: flex;
		align-items: center;
		justify-content: space-between;
		list-style: none;
		user-select: none;
		position: relative;
		transition: background-color 0.15s ease;
	}
	.data-body details.mycard summary:hover {
		/*background-color: var(--clr-bg);*/
	}

	/* Custom chevron indicator */
	.data-body details.mycard summary::after {
		content: '';
		width: 8px;
		height: 8px;
		border-right: 2px solid currentColor;
		border-bottom: 2px solid currentColor;
		transform: rotate(45deg);
		transition: 0.2s ease;
		opacity: 0.6;
	}
	.data-body details.mycard[open] summary::after {
		transform: rotate(-135deg);
		margin-top: 4px;
	}
	.data-body details.mycard > .flow {
		padding: 12px 20px;
	}

	.data-body .permissions {
		background: transparent;
		padding: 16px;
		border-radius: calc(var(--roundness) * 0.6);
		border: 1px solid color-mix(in srgb, var(--clr-text) 5%, transparent);
	}
	.data-body .permissions > i {
		display: block;
		font-size: 0.75rem;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.05em;
		opacity: 0.7;
		margin-bottom: 12px;
		font-style: normal;
	}
	.data-body .permissions ul {
		list-style: none;
		padding: 0;
		margin: 0;
		display: flex;
		flex-wrap: wrap;
		gap: 8px;
	}
	.data-body .permissions li {
		font-size: 0.85rem;
		font-weight: 500;
		padding: 6px 12px;
		background: color-mix(in srgb, var(--clr-panelbg) 60%, var(--clr-text) 5%);
		border-radius: calc(var(--roundness) * 0.4);
		border: 1px solid color-mix(in srgb, var(--clr-text) 8%, transparent);
		font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
		font-size: 0.8rem;
	}

	/* Metadata styling */
	.data-body .metadata {
		display: flex;
		gap: 20px;
		font-size: 0.875rem;
		opacity: 0.8;
		padding: 4px 0;
	}
	.data-body .metadata div {
		display: flex;
		align-items: center;
		gap: 6px;
	}
	.data-body .metadata div::before {
		content: '';
		width: 6px;
		height: 6px;
		border-radius: 50%;
		background: color-mix(in srgb, var(--clr-text) 30%, transparent);
	}

	/* Actions refinement */
	.data-body .myactions {
		padding-top: 8px;
		/*border-top: 1px solid color-mix(in srgb, var(--clr-text) 8%, transparent);*/
		margin-top: 4px;
	}

	.permissions_box{
		display: flex;
		/*align-items: center;*/
		/*justify-content: space-between;*/
		flex-wrap: wrap;
		gap: 12px;
	}
	.permissions_box>div{
        --w: 240px;
		flex: 1 0 var(--w);
		/* max-width: var(--w); */
		/*overflow: auto;*/
	}
</style>

<div class="container-fluid">
	<div class="page-header">
		<div class="page-title">
			<span class="text-muted h4">User Role management</span>
			<p>Here you can view and modify all user roles and their permissions</p>
		</div>
		<div class="flowline centerline gap-tn">
			<button class="mybtn secondary" data-runme="refresh_stuff"><i class="fa fa-sync"></i> Refresh</button>
			<button class="mybtn primary" data-runme="add_role"><i class="fa fa-plus"></i> add Role</button>
			<button class="mybtn primary w3-hide" id="add_role_btn" data-toggle="modal" data-target="#editAddModal"><i class="fa fa-plus"></i> add Role</button>
		</div>
	</div>

	<section class="distance-sm panelbg spacy-sm themeround">
		<div class="data-panel w3-transparent animate-fade-in">
			<!-- Data Header -->
			<div class="data-header">
				<div class="d-flex align-items-center gap-md">
					<h4 class="data-title"><b>All User roles</b></h4>
				</div>
				<div class="data-actions w3-hide">
					<div class="search-box">
						<input type="text" placeholder="Search clients, jobs and operations" id="quickSearch">
						<button><i class="fas fa-search"></i></button>
					</div>
				</div>
			</div>

			<div class="data-body distance-sm" id="rolesHolder">
			</div>
		</div>
	</section>
</div>

<!-- Add items Modal -->
<div class="modal fade" id="editAddModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" style="z-index: 102;">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content themeround borderless">
			<div class="modal-header">
				<span class="h4 modal-title" id="add_edit_title">Add User Role</span>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<!-- Multiple inputs -->
				<form id="add_edit_form" action="./op/user_roles" data-onsubmit="sendform" data-blockdefault="yes" data-callback="form_afterfx">
					@csrf
					<input type="hidden" name="recid" value="">
					<input type="hidden" name="opname" value="add">
					<div class="input-group-custom w3-hide">
						<div class="form-group">
							<!-- <label class="form-label" for="user_role_name">Role name</label> -->
							<input type="text" class="form-control-custom" id="permissions" name="permissions" placeholder="permissions">
						</div>
					</div>
					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="user_role_name">Role name</label>
							<input type="text" class="form-control-custom" id="user_role_name" name="user_role_name" placeholder="what will we call this role" required>
						</div>
					</div>

					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="email">Permissions</label>
						</div>
					</div>

					<div style="padding: 12px 32px;" class="border themeround distance-tn permissions_box">
						<div class="custom-control custom-switch">
							<input type="checkbox" class="custom-control-input" id="ADD_CLIENT" name="ADD_CLIENT">
							<label class="custom-control-label" for="ADD_CLIENT"> ADD_CLIENT</label>
						</div>
						<div class="custom-control custom-switch">
							<input type="checkbox" class="custom-control-input" id="VIEW_CLIENT" name="VIEW_CLIENT">
							<label class="custom-control-label" for="VIEW_CLIENT"> VIEW_CLIENT</label>
						</div>
					</div>

					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="email">Quick actions</label>

							<div>
								<button class="btn btn-sm btn-outline-danger" type="button" onclick="form_op_giveall(false)" data-value="false">revoke all permissions</button>
								<button class="btn btn-sm btn-outline-success" type="button" onclick="form_op_giveall(true)" data-value="true">add all permissions</button>
							</div>
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

<script>
	let roleop = "add";
	let userRoles = [];
	let rec_id = null;

	// let afterfx_canrun = true;

	function populateEditForm() {
		const p_box = add_edit_form.querySelector('.permissions_box');

		let finht = [...Object.keys(permissions)].map(f => {
			return `
				<div class="custom-control custom-switch">
					<input type="checkbox" class="custom-control-input" id="${f}" data-value="${f}" onchange="form_op_update_perms(this)">
					<label class="custom-control-label" for="${f}"> ${permissions[f]}</label>
				</div>
			`;
		}).join('');

		p_box.innerHTML = finht;
	}

	function form_op_update_perms(who) {
		let prs = add_edit_form.permissions;

		/*
			if(!prs.value.includes(who.dataset.value)){
				let added = prs.value.split(',');
				added.push(who.dataset.value);

				prs.value = added.join(',');
			}
		*/

		if(who !== undefined){
			let items = prs.value == '' ? [] : prs.value.split(',');
			let pos = items.indexOf(who.dataset.value);
			let comp = who.checked;
			let con = comp ? pos === -1 : pos !== -1;

			if(comp){
				if(con){items.push(who.dataset.value)}
			} else {
				if(con){items.splice(pos,1)}
			}

			prs.value = items.join(',');
		}
	}

	function form_op_giveall(c,silent = false) {
		if(c == undefined){return;}

		// const con = c.dataset.value == "true";
		const con = c;
		const p_box = add_edit_form.querySelector('.permissions_box');
		const boxes = p_box.querySelectorAll('input');

		boxes.forEach(b => {
			b.checked = c;
			form_op_update_perms(b);
		});

		if(!silent){
			alert_info(c ? "all permissions applied" : "all permissions revoked");
		}
	}

	function renderRoles() {
		rolesHolder.innerHTML = '';

		if(userRoles.length == 0){
			rolesHolder.innerHTML = `
				<div class="spacy-md w3-center">
					<div class="text-muted"><i>no user roles found</i></div>
				</div>
			`;
		}

		userRoles.forEach((f,id) => {
			let roles_list = [...f.role_permissions].map(f => {return f == undefined || f == '' ? '<i>NONE</i>' : `<li data-wot="${f}">${permissions[f]}</li>`;})

			// console.log(roles_list);

			let c_date = formatDate(new Date(f.created_at));
			let u_date = formatDate(new Date(f.updated_at));
			let delht = f.users_count > 0 ? '' :
						`<button class="mybtn danger sm" data-runme="delete_role" data-id="${f.id}">
							<i class="fa fa-trash"></i>
							delete
						</button>`;

			rolesHolder.innerHTML += `
				<details class="mycard slide-up" data-id="${f.id}" style="${mekstagger(200,id)}">
					<summary>${f.role_name}</summary>
					<div class="flow gap-sm slide-down">
						<div>users assigned: <b>${f.users_count}</b></div>
						<div class="permissions">
							<i>User permissions</i>
							<ul>
								${roles_list.join(' ')}
							</ul>
						</div>
						<div class="metadata">
							<div>created: ${c_date}</div>
							<div>modified: ${u_date}</div>
						</div>
						<div class="myactions flowline left small" style="gap: var(--size-tn);">
							<button class="mybtn outline sm" data-runme="edit_role" data-id="${f.id}">
								<i class="fa fa-edit"></i>
								edit
							</button>
							${delht}
						</div>
					</div>
				</details>
			`;
		})
	}

	window['add_role'] = (e) => {
		roleop = "add";
		add_edit_form.recid.value = "";
		add_edit_form.reset();
		add_role_btn.click();

		add_edit_title.innerHTML = "Add user role";
	}

	window['edit_role'] = (e) => {
		if(e === undefined){return;}

		roleop = "edit";
		rec_id = e.dataset.id;
		add_edit_form.recid.value = rec_id;

		let myn = userRoles.filter(f => {return f.id.toString().toLowerCase() == rec_id.toLowerCase();})[0];
		add_edit_form.permissions.value = myn.role_permissions.join(",");
		add_edit_form.user_role_name.value = myn.role_name;

		// update checkboxes in add form
		let boxes = add_edit_form.querySelector('.permissions_box').querySelectorAll('[type="checkbox"]');

		boxes.forEach(b => {
			let con = myn.role_permissions.indexOf(b.dataset.value) !== -1;
			b.checked = con;
		})
		// alert_info(boxes.length);

		add_role_btn.click();

		// alert_info(rec_id);
		// alert_info(e.textContent);
		add_edit_title.innerHTML = "Edit user role";
	}

	window['delete_role'] = (e) => {
		roleop = "edit";
		rec_id = e.dataset.id;

		const delRole = () => {
			alert_danger('deleting role');

			const callme = () => {
				alert_danger('deleted successfully');
			}

			window[fetch_bypass]('./op/user_roles',{recid: rec_id},'DELETE').then(d => {
				responseHandler(d,callme,d);
			}).finally(() => {
				window['refresh_stuff']();
			});
		}

		let explain = `
			this will delete this user role from the database.
			<br><br>
			<b>NOTE:</b> if any users are assigned to this role, deleting it will fail
		`;

		confirmAction('Delete User Role',explain,delRole);
		// alert_info(e.textContent);
	}

	// autoruns
	window['runOnAwake'] = () => {
		alert_info('fetching user roles, please wait...',22);

		const setroles = (d) => {
			userRoles = d.sendme;
		}

		window[fetch_bypass_fyls]('./data/user_roles',{},'GET').then(d => {
			// alert_dark('its done getting user roles');
			clear_alerts();
			alert_success(d.message);
			responseHandler(d,setroles,d,true,true);
			renderRoles();
			populateEditForm();
			refreshUI(300);
		});
	};

	window['refresh_stuff'] = () => {
		rolesHolder.innerHTML = '';
		window['runOnAwake']();
	}

	window['form_afterfx'] = () => {
		// alert_danger('runnin afterfx');
		add_role_btn.click();

		window['refresh_stuff']();
	}
</script>
