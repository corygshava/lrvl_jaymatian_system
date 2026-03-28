
	<script>
		// state atlases
		window['pstate_classes'] = [
			'w3-text-red',
			'w3-text-grey',
			'w3-text-green'
		];
		window['sample_state_atlas'] = {
			0 : 'pending',
			1 : 'incomplete',
			2 : 'complete',
		};
		window['task_state_atlas'] = [
			'Pending',
			'awaiting payment',
			'Complete',
			'Cancelled',
		];
		window['state_atlas'] = {
			0 : 'pending',
			1 : 'awaiting_payment',
			2 : 'complete',
		};
		window['payment_state_atlas'] = {
			0 : 'pending',
			1 : 'incomplete',
			2 : 'paid',
		};

		const min_loadtime = 400;
	</script>

	<script>
		// runtime variables (move to something else later)
		const task_data_var = 'runtime_var_' + mekRandomString(5);

		var menubar = undefined;
		let themenu = undefined;
		let menulinks = undefined;

		let maincon = undefined;
		let dynacode = undefined;
		let menuItems = [];
		let permissions = {};
		let mypermissions = [];
		let browseHistory = [];
		let fullHistory = [];
		let crumbs = [];
		const crumbDepth = 4;

		const linkAtlas = {
			// SSR ui stuff
				'admin_dash' : './ui/admin_console',
				'staff_dash' : './ui/console',
				'view_users' : './ui/view_user',
				'view_user' : './ui/view_user',
				'view_client' : './ui/view_client',
				'view_clients' : './ui/client_mgt',
				'view_samples' : './ui/samples_mgt',
				'view_reports' : './ui/reports_mgt',
				'view_task' : './ui/view_task',
				'view_tasks' : './ui/task_mgt',
				'view_profile' : './ui/view_user',
				'view_users' : './ui/user_mgt',
				'client_info' : './ui/client_info',
				'task_info' : './ui/task_info',
				'add_task' : './ui/add_task',
				'gen_report' : './ui/gen_report',
				'biz_data' : './ui/biz_data',
				'samples_mgt' : './ui/samples_mgt',
				'reports_mgt' : './ui/reports_mgt',
				'ui_tester' : './ui/_ui_creat0r',

			// operations
				'toggle_account_active' : './op/toggle_active',
				'add_new_task' : './op/add_task',
				'add_result' : './op/add_result',
				'submit_results' : './op/add_result',
				'add_payment' : './op/add_payment',
				'add_task_note' : './op/add_task_note',
				'update_bizdata' : './op/update_bizdata',
				'update_bizdata_fyls' : './op/update_bizdata_images',
				'save_report' : './op/save_report',
				'update_payment' : './op/update_client_payment',
				'save_worksheet' : './op/save_worksheet',

			// datalinks
				'search_clients' : './data/search_clients',
				'get_task' : './data/get_task',
				'get_sample' : './data/get_sample',
				'get_client' : './data/get_client',
				'get_samples' : './data/get_samples',
				'get_payments' : './data/get_payments',
				'get_results' : './data/get_results',
				'get_reports' : './data/get_reports',
				'get_bizdata' : './data/bizdata',
				'get_user' : './data/cur_user',
				'get_allpermissions' : './data/allpermissions',
				'get_mypermissions' : './data/my_permissions',
				'get_staff_stats' : './data/staffstats',
				'get_incomplete_tasks' : './data/tasks/incomplete',
				'get_incomplete_samples' : './data/samples/incomplete',
				'get_admin_stats' : './data/adminstats',
				'get_sales_chart_data' : './data/sales_chart_data',
				'get_samples_chart_data' : './data/samples_chart_data',
				'get_visits_chart_data' : './data/visits_chart_data',
				'get_income_chart_data' : './data/income_chart_data',
				'get_worksheets' : './data/get_worksheets',

			// misc
				'bizimages' : './media/bizimg',
				'getreport_img' : './media/getreport/imgs',
				'getreport_pdf' : './media/getreport/pdfs',
		};
		const pageNames = {
			'view_users' : 'user information',
			'view_user' : 'user summary',
			'view_client' : 'Client summary',
			'view_clients' : 'Client management',
			'client_info' : 'Client Information',
			'view_profile' : 'User Profile',
			'view_users' : 'user management',
			'add_task' : 'Add new task',
			'view_task' : 'Task summary',
			'view_tasks' : 'Task management',
			'task_info' : 'Task Information',
			'gen_report' : 'Generate Report',
			'samples_mgt' : 'Samples management',
			'view_samples' : 'Samples management',
			'reports_mgt' : 'reports management',
			'view_reports' : 'reports management',
			'view_payments' : 'payments management',

			'ui_tester' : 'UI testing grounds',
		};
		const variableAtlas = {
			'initstuff' : `sys_init_${mekRandomString(8)}`,
			'task_id' : `cur_task_id`,
			'client_id' : `cur_client_id`,
			'sample_id' : `cur_sample_id`,
			'task_info_act' : `task_info_next_act`,
			'task_info_act_args' : 'task_info_next_act_args',
		};

		// shortener ops
			variableAtlas['t_i_act'] = variableAtlas['task_info_act'];
			variableAtlas['t_i_args'] = variableAtlas['task_info_act_args'];

		let pref_prefix = 'jaymatt_MIS_';
		let prefsAtlas = {
			'last_saved_task' : `${pref_prefix}saved_task`,
		};

		const iconAtlas = {
			'admin_dash' : 'fa fa-home',
			'staff_dash' : 'fa fa-house-user',
			'user_roles' : 'fa fa-user-lock',
			'user_mgt' : 'fa fa-user-cog',
			'user_profile' : 'fa fa-user-tie',
			'clients' : 'fa fa-users',
			'client' : 'fa fa-user',
			'tasks' : 'fa fa-folder',
			'add_task' : 'fa fa-folder-plus',
			'samples' : 'fa fa-flask',
			'payments' : 'fa fa-dollar-sign',
			'reports' : 'fa fa-file-contract',
			'bizdata' : 'fa fa-briefcase',
			'xtras' : 'fa fa-file-code',
			'xtras_2' : 'fa fa-file-excel',
			'worksheets' : 'fa fa-file-signature',
			'invoices' : 'fa fa-file-invoice',
		};

		// will be set via server call later
			let encsetup = {
				offset: 4,
				salt: 'cwaizy',
				chunks: 19
			};

		/*
		const confirm_modal = document.querySelector('#confirmerBox');
		const confirm_toggler = document.querySelector('[data-role="toggleConfirmModal"]');
		const info_modal = document.querySelector('#dashmodal');
		const info_toggler = document.querySelector('[data-role="toggleDashModal"]');
		// */

		const fadein = [{opacity: 1},{opacity: 0}];

		menuItems = [
			{caption: "dashboard", url: linkAtlas['admin_dash'],icon: iconAtlas['admin_dash']},
			{caption: "dashboard", url: linkAtlas['staff_dash'],icon: iconAtlas['staff_dash']},
			{caption: "User Roles", url: './ui/user_roles',icon: iconAtlas['user_roles']},
			{caption: "User Management", url: './ui/user_mgt',icon: iconAtlas['user_mgt']},
			{caption: "View Profile", url: './ui/view_user',icon: iconAtlas['user_profile']},
			{caption: "client management", url: './ui/client_mgt',icon: iconAtlas['clients']},
			{caption: "Task Management", url: './ui/task_mgt',icon: iconAtlas['tasks']},
			{caption: "Add new task", url: './ui/add_task',icon: iconAtlas['add_task']},
			{caption: "Sample management", url: './ui/samples_mgt',icon: iconAtlas['samples']},
			{caption: "Payment management", url: './ui/payments_mgt',icon: iconAtlas['payments']},
			{caption: "Report management", url: './ui/reports_mgt',icon: iconAtlas['reports']},
			{caption: "Business data", url: './ui/biz_data',icon: iconAtlas['bizdata']},
			/*
			{caption: "test permissions", url: './ui/get_menu',icon: 'fa fa-code'},
			{caption: "view permissions", url: './my_permissions',icon: 'fa fa-code'},
			{caption: "am i admin", url: './am_i_admin',icon: 'fa fa-code'},
			{caption: "get roles", url: './data/user_roles',icon: 'fa fa-code'},
			*/
		];

		function setUI() {
			if(menubar == undefined){
				menubar = document.querySelector('#menubar');
			}

			if(maincon == undefined){maincon = document.querySelector('#dynamic_ui');}
			if(dynacode == undefined){dynacode = document.querySelector('#dynamic_js');}

			updateSessionUI();
		}

		function updateSessionUI(p = undefined){
			let namedisplays = document.querySelectorAll('[data-role="user_name_display"]');

			if(namedisplays.length == 0){
				// alert_danger('no name displays found');
			}
			namedisplays.forEach(e => {
				e.innerHTML = cur_user.name || "??";
			})

			// setup all icons where needed
			let _icons = document.querySelectorAll('[data-myicon]');
			_icons.forEach(_i => {
				let iclass = iconAtlas[_i.dataset.myicon.toLowerCase()];
				// let iclass = iconAtlas[_i.dataset.myicon.toLowerCase()] || 'fa-question';
				_i.classList.add('fa');
				_i.classList.add(iclass.split(" ")[1]);
			})

			if(p == undefined){
				return;
			}

			// render all pagenames where needed
			let pnames = document.querySelectorAll('[data-role="pagename"]');
			pnames.forEach(pn => {pn.innerHTML = p.sidenote;});

			// alert_warning('updating user');
			// console.log(cur_user);
		}

		window['toggleSidenav'] = (me,override = undefined) => {
			setUI();

			if(override !== undefined && typeof override == 'boolean'){
				menubar.dataset.small = override ? "no" : "yes"
			}

			if(menubar === undefined){
				alert_danger('menu unavailable')
				return;
			}

			if(menubar.dataset.small === "yes"){
				menubar.classList.remove('squish');
				menubar.dataset.small = "no";
				me.style.rotate = "0deg";
				me.querySelector('i').className = "fa fa-chevron-left";
			} else {
				menubar.classList.add('squish');
				menubar.dataset.small = "yes";
				me.style.rotate = "180deg";
				me.querySelector('i').className = "fa fa-bars";
			}

			// alert_info(menubar.dataset.small);
		};

		window[variableAtlas['initstuff']] = () => {
			const step1 = (w) => {
				// sets permissions
				permissions = w.sendme;

				window[fetch_bypass_fyls](linkAtlas['get_mypermissions'],{},'GET').then(d => {
					responseHandler(d,step2,d);
				}).catch(err => {
					dynamic_ui.innerHTML = mekError('Error',`error getting user permissions: ${err.message}`,true);
				})
			};

			const step2 = (w) => {
				// sets permissions
				mypermissions = w.sendme;

				window[fetch_bypass_fyls](linkAtlas['get_user'],{},'GET').then(d => {
					responseHandler(d,step3,d);
				}).catch(err => {
					dynamic_ui.innerHTML = mekError('Error',`error getting user information: ${err.message}`,true);
				})
			};

			const step3 = (w) => {
				// sets current user
				window['cur_user'] = w.sendme;
				// mypermissions = w.sendme.role_permissions;

				// initmenu();
				// step4({});
				// return;
				// /*
				window[fetch_bypass_fyls](linkAtlas['get_bizdata'],{},'POST').then(d => {
					responseHandler(d,step4,d);
				})
				// */
			};

			const step4 = (d) => {
				window['bizdata'] = parseBizdata(d.sendme);

				/*
				window['bizdata'] = {
					pricing_data: {
						soil: 1000,
						rock: 1000,
						solution: 1500,
						carbon: 1500,
					}
				};
				*/

				initmenu();
				Object.freeze(encsetup);
			}

			window[fetch_bypass_fyls](linkAtlas['get_allpermissions'],{},'GET').then(d => {
				responseHandler(d,step1,d);
				// initmenu();
			})
		}

		window['parseBizdata'] = (m) => {
			alert_dark('parsing bizdata');
			window['debug_var_bizdata'] = m;

			let outobj = {};

			m.forEach(el => {
				let obj = {};
				let dta = el.item_data;

				if(el.item_name == 'pricing_data'){
					dta.forEach(d => {
						let k = Object.keys(d)[0];
						obj[k] = d[k];
					})
				} else {
					obj = dta;
				}

				outobj[el.item_name] = obj;
			})

			return outobj;
		}

		function initmenu() {
			alert_info('loading menu items');

			setUI();
			if(menubar === undefined){alert_danger('menu unavailable');return;}

			themenu = menubar.querySelector('.menubar-menu');
			if(themenu === undefined){alert_danger('invalid menu setup');return;}

			themenu.innerHTML = '';
			let outht = '';

			menuItems.forEach((m,i) => {
				outht += `
					<div>
						<a href="#" class="menulink" data-runme="menuact" data-template='${m.url}' data-sidenote="${m.caption}">
							<i class="${m.icon} slide-r" style="${mekstagger(200,i)}"></i>
							<span>${m.caption}</span>
						</a>
					</div>
				`;
			});

			themenu.innerHTML = outht;

			menulinks = themenu.querySelectorAll('a');
			menulinks[0].classList.add('active');

			setTimeout(() => {
				window['menuact'](menulinks[0]);
			},400);

			refreshUI(200);
		}

		function activateSidebar(con = true) {
			setUI();

			if(menubar == undefined){return;}

			if(!con){
				menubar.classList.add('unavailable');
				// alert_dark('deactivating sidebar')
			} else {
				menubar.classList.remove('unavailable');
				// alert_dark('activating sidebar')
			}
		}

		window['menuact'] = (d, after) => {
			if(menulinks === undefined){
				setUI();
				if(menubar === undefined){alert_danger('menu unavailable');return;}
				themenu = menubar.querySelector('.menubar-menu');
				if(themenu === undefined){alert_danger('invalid menu setup');return;}
				menulinks = themenu.querySelectorAll('a');
			}

			menulinks.forEach(m => {m.classList.remove('active')});
			d.classList.add('active');

			let passed = {
				template: d.dataset.template,
				sidenote: d.dataset.sidenote,
				fromBtn: true
			}
			let aft = d.dataset.afterfx || after || undefined;

			if(aft !== undefined){
				passed['afterfx'] = aft;
				d.removeAttribute('data-afterfx');
			}

			// alert_info('sidemenu clicked');
			// console.log('passing: ',passed);

			loadPage(passed);
		};

		window['loadPage'] = (p,after) => {
			let dft = {
				template: './ui/error',
				sidenote: 'new page',
				afterfx: undefined
			}
			p = {...dft,...p};

			// alert_dark(p.template);

			if(!p.fromBtn){
				let navbtn = menubar.querySelector(`[data-template="${p.template}"]`);
				if(navbtn){
					navbtn.dataset.afterfx = p.afterfx;
					navbtn.click();
					// alert_dark('btn found');
					return;
				} else {
					// alert_dark('no btn found');
				}
			}

			if(window['runOnLeave'] !== undefined){
				window['runOnLeave']();
				delete(window['runOnLeave']);
			}

			if(window['killOnLeave'] !== undefined){
				killghosts(window['killOnLeave']);
				delete(window['killOnLeave']);
			}

			activateSidebar(false);
			window['toggleSidenav'](menubar_toggle_btn,true);
			alert_info(`loading ${p.sidenote}`);

			let timing_fast = {duration: 400, easing: "ease-out",fill: "forwards"};
			maincon.animate([...fadein],{...timing_fast})
			maincon.style.pointerEvents = "none";
			curArea.innerHTML = 'loading...';

			document.body.classList.remove('modal-open');

			setTimeout(() => {
				// TODO: add a loader logic here
				maincon.innerHTML = '';

				window[fetch_ui](p.template,{},'get').then(dt => {
					document.querySelector('#curArea').innerHTML = p.sidenote;

					maincon.innerHTML = dt;
					maincon.style.pointerEvents = "all";
					maincon.animate([...fadein].reverse(),{duration: 400, easing: "ease-out",fill: "forwards"});

					// reset run on awake
					window['runOnAwake'] = () => {};

					ParseMyJS(maincon);
					updateSessionUI(p);
					refreshUI(300);

					if(p.afterfx !== undefined || p.afterfx !== null){
						setTimeout(() => {
							console.log('last after fx: ',p.afterfx);
							console.log('last payld: ',p);

							if(typeof window[p.afterfx] == 'function'){
								window[p.afterfx]();
							} else {
								// alert_dark('invalid afterfx');
							}
						},400);
					} else {
						// alert_dark('no afterfx defined');
					}

					browseHistory.push(p);
					fullHistory.push(p);
					renderCrumbs();
					return;

					// was quite buggy and i had already found a workaround so had to ignore it somehow
					if(typeof after == 'function'){
						setTimeout(() => {
							after();
						},400);
					}
				}).finally(() => {
					activateSidebar(true);
				});
			},400);
		}
		window['load_page'] = (el,topass) => {
			// let fun = el.dataset.afterfx !== undefined ? window[el.dataset.afterfx] : undefined;
			let passme = {
				template: el.dataset.link || './ui/error',
				sidenote: el.dataset.label || 'error',
				afterfx: el.dataset.afterfx || undefined,
			};

			if(passme.template.includes('defined:')){
				let i = passme.template.split(":")[1];
				passme.template = linkAtlas[i];
			}
			if(passme.sidenote.includes('defined:')){
				let i = passme.sidenote.split(":")[1];
				passme.sidenote = pageNames[i] || i;
			}

			window['loadPage'](passme);
		}
		window['refresh_']

		window['view_profile'] = () => {
			alert_info('loading full profile')

			// window[fetch_bypass]('./data/')
			window['cur_record'] = {
				rectype : "user",
				data : window['cur_user'],
			};

			let mbtn = menubar.querySelector(`[data-template="${linkAtlas['view_profile']}"]`);
			mbtn.click();
		};

		// breadcrumbs ops
		function renderCrumbs(){
			let ui = breadcrumb_trail;

			if(ui == undefined){
				alert_danger('breadcrumbs component missing');
				return;
			}

			ui.classList.add('w3-hide');
			ui.innerHTML = '';
			ui.style.opacity = 1;

			setTimeout(() => {
				let bh = [...browseHistory];
				alert_silent('browseHistory: ',bh);

				crumbs = [];

				[...bh].reverse().forEach((el,id) => {
					if(id < crumbDepth){
						crumbs.push(el);
					}
				})

				crumbs = [...crumbs].reverse();
				alert_silent('crumbs: ',crumbs);

				if(crumbs.length < 2){
					return;
				}

				outht = '';

				crumbs.forEach((el,id,arr) => {
					let islast = (id == (arr.length - 1));
					let href = !islast ? 'href="#" ' : '';
					let op = !islast ? `data-runme="history_crawl" ` : '';
					let active = islast ? 'active' : '';

					outht += `
						<li class="breadcrumb-item ${active}"><a ${href} ${op} data-level="${id}">${el.sidenote}</a></li>
					`;
				})

				ui.innerHTML = outht;
				ui.classList.remove('w3-hide');
				ui.style.opacity = 1;
				refreshUI(300);
			},200);
		}

		function hidecrumbs() {
			let ui = breadcrumb_trail;
			ui.style.opacity = 0;

			setTimeout(() => {
				ui.classList.add('w3-hide');
			},400);
		}

		window['history_crawl'] = (l) => {
			let lvl = l.dataset.level || crumbs.length - 2;
			let killfrom = browseHistory.indexOf(crumbs[lvl]);
			let bh = [...browseHistory];

			// browseHistory = [...bh].splice(0,bh.length - (crumbDepth - lvl));

			loadPage({...crumbs[lvl],fromBtn: false});
		}

		// utility ops
		function ParseMyJS(who) {
			let oldscs = who.querySelectorAll('script');

			if(oldscs.length == 0){
				// alert_info('no scripts present');
				alert_silent('no scripts present');
				return;
			}

			dynacode.innerHTML = '';
			let code = '';
			oldscs.forEach(c => {
				code += c.textContent;
				who.removeChild(c);
			});

			// return;

			let script = document.createElement('script');
			script.textContent = `
				try{
					${code}
				} catch (err) {
					console.log(err);
					alert_danger(err.message);
				}

				// end of parsed
				// alert_success('loading successful');
			`;
			dynacode.appendChild(script);

			if(window['runOnAwake'] !== undefined){
				if(typeof window['runOnAwake'] == 'function'){
					window['runOnAwake']();
				}
			}

			if(window['killOnLeave'] !== undefined){
				// prevents killing variables outside
				// also coincidentally prevents it from being deleted so dont uncomment unless you know a workaround
				// Object.freeze(window['killOnLeave']);
			}
		}

		function sendform(m,callback,onfail,tosend = undefined) {
			let hasencrypted = false;
			let ps = '';

			if(m.dataset.submitting === true){
				alert_danger('already submitting...');
				return;
			} else {
				alert_info('submitting form...')
			}

			// callback mgt
			if(callback == undefined){
				if(m.dataset.callback){
					console.log('callback: ',m.dataset.callback);
					// alert_dark('callback: ' + m.dataset.callback);

					callback = window[m.dataset.callback.trim()];
				} else {
					console.log('no fallback to the callback');
					// alert_dark('no fallback to the callback');
				}
			} else {
				if(typeof callback != "function"){
					console.log('trying to read from a window variable');
					// alert_dark('trying to read from a window variable');
					callback = window[callback];
				}
			}

			if(typeof callback == "function"){
				console.log('valid callback')
				// alert_dark('valid callback')
			} else {
				callback = (a) => {
					alert_success('process successful');
					console.log('default callback results: ',a);
				};
				console.log('not a function')
				// alert_dark('not a function')
			}

			// onfail mgt
			if(onfail == undefined){
				if(m.dataset.onfail){
					console.log('onfail: ',m.dataset.onfail);
					// alert_dark('onfail: ' + m.dataset.onfail);

					onfail = window[m.dataset.onfail.trim()];
				} else {
					console.log('no fallback to the onfail');
					// alert_dark('no fallback to the onfail');
				}
			} else {
				if(typeof onfail != "function"){
					console.log('trying to read from a window variable');
					// alert_dark('trying to read from a window variable');
					onfail = window[onfail];
				}
			}

			if(typeof onfail == "function"){
				console.log('valid onfail')
				// alert_dark('valid onfail')
			} else {
				onfail = (a) => {alert_danger('process failed')};
				console.log('not a function')
				// alert_dark('not a function')
			}

			// return;

			// callback = callback == undefined ? (m.dataset.callback ? window[m.dataset.callback] : undefined) : callback;

			callback = typeof callback == 'function' ? callback : () => {alert_info('invalid callback passed to sendform')};

			m.style.pointerEvents = "none";
			m.style.opacity = 0.2;
			m.dataset.submitting = true;

			if(m.admin_password !== undefined){
				hasencrypted = true;
				ps = m.admin_password.value;
				m.admin_password.value = encryptme(ps,encsetup.offset,encsetup.salt,encsetup.chunks);
			}

			let fdata = tosend == undefined ? getFormdata(m) : tosend;
			const sender = tosend == undefined ? window[fetch_bypass_fyls] : window[fetch_bypass];
			const as_is = tosend == undefined;
			let myres = undefined;
			let myaction = m.action;
			// console.log('fdata: ',fdata,'use as is: ',as_is);

			if(myaction.includes('defined:')){
				myaction = linkAtlas[myaction.split(':')[1]];
			}

			// add_edit_form.style.pointerEvents = "all";
			sender(myaction,fdata,'POST',true,as_is).then(d => {
				myres = d;

				responseHandler(d,callback,{'el': m,'data': d});

				if(!d.success || !d.result){
					onfail(d);
				}
				// console.log("finally, the result is: ",myres);
				// callback(m);
			})
			.catch(err => {
				alert_danger(err.message);

				if(onfail !== undefined){
					onfail(err);
				}
			})
			.finally(() => {
				m.style.pointerEvents = "all";
				m.style.opacity = 1;
				m.dataset.submitting = false;

				if(hasencrypted){
					m.admin_password.value = ps;
				}
			})
		}

		function killghost(who) {
			// kills ghost processes and functions
			// NOTE: this shit is OP as fukari so use it sparingly
			if(window[who] !== undefined){
				delete(window[who]);
			} else {
				alert_danger('invalid killghost pass');
			}
		}

		function killghosts(stuff = []) {
			if(typeof stuff == 'object'){
				if(stuff.forEach === undefined){
					alert_danger('invalid killghosts pass')
					return;
				}
				stuff.forEach(s => {killghost(s);})
			} else {
				alert_dark('invalid killghosts pass');
			}
		}

		function compareUserData(udata) {
			let cur_u = cur_user;

			if(udata.id == cur_u.id){
				cur_user = udata;
				mypermissions = cur_user.role_permissions;

				updateSessionUI();
			}
		}

		// confirm password subsys
			function awaitConfirmPass(callback) {
				if(typeof callback !== "function"){
					alert_danger('invalid callback');
					return;
				}

				// create a ghost process to call when password is confirmed
				window['confirmPasswordCallback'] = callback;
				password_confo_trig.click();
			}
			function confirmPassword(m){
				// alert_info('doxing password: ' + m.admin_password.value);
				password_confo_trig.click();

				let topass = encryptme(m.admin_password.value,encsetup.offset,encsetup.salt,encsetup.chunks);

				window['confirmPasswordCallback']({admin_password: topass});
				killghost('confirmPasswordCallback');
			}
			window['password_confo_clearghost'] = () => {
				killghost('confirmPasswordCallback');
			}

		// logout operations
			window['logout'] = (el) => {
				logoutform.submit();
			}
			window['logout_fail'] = (d) => {
				alert_danger('logout fail');
			}
			window['run_post_logout'] = (d) => {
				alert_danger('run post logout');
				window.location.reload();
			}

		// reusables (this project only)

		// print ops
			async function printElement(selector,printImmediately = false) {
				const element = document.querySelector(selector);

				if (!element) {
					console.error(`Element not found: ${selector}`);
					return;
				}

				try {
					let printtxt = printImmediately ? `
						onload="window.print()"
					` : ``;
					const options = {
						allowTaint: true,
						backgroundColor: null,
						logging: false,
						scale: 2,
						useCORS: false
					};
					const canvas = await html2canvas(element,options);

					// Convert canvas to data URL
					const imageDataUrl = canvas.toDataURL('image/png');

					// Open in new tab
					const newTab = window.open();
					newTab.document.write(`
						<html>
							<head>
								<title>Printed Element</title>
								<style>
									body { margin: 0;}
									img { max-width: 100%; height: auto;}
								</style>
							</head>
							<body>
								<img src="${imageDataUrl}" alt="Captured element" ${printtxt}/>
							</body>
						</html>
					`);
					newTab.document.close();
				} catch (error) {
					console.error('Error capturing element:', error);
				}
			}
			window['print_me'] = (el) => {
				if(el == undefined){alert_danger('invalid call');return;}
				if(el.dataset.target == undefined){alert_danger('invalid target');return;}

				let sel = el.dataset.target;
				let con = el.dataset.immediate == "yes" || el.dataset.immediate == "true";

				printElement(sel,con).then(e => {
					alert_success('document printed successfully');
				});
			}

		callOnDocLoad.push({act: window[variableAtlas['initstuff']]});
	</script>

