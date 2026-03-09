
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
			'view_users' : './ui/view_user',
			'view_user' : './ui/view_user',
			'view_client' : './ui/view_client',
			'view_clients' : './ui/client_mgt',
			'client_info' : './ui/client_info',
			'view_profile' : './ui/view_user',
			'view_users' : './ui/user_mgt',
			'add_task' : './ui/add_task',
			'view_task' : './ui/view_task',
			'view_tasks' : './ui/task_mgt',
			'task_info' : './ui/task_info',
			'gen_report' : './ui/gen_report',

			// operations
			'toggle_account_active' : './op/toggle_active',
			'add_new_task' : './op/add_task',
			'add_result' : './op/add_result',
			'submit_results' : './op/add_result',
			'add_payment' : './op/add_payment',
			'add_task_note' : './op/add_task_note',

			// datalinks
			'search_clients' : './data/search_clients',
			'get_task' : './data/get_task',
			'get_sample' : './data/get_sample',
			'get_samples' : './data/get_samples',
			'get_payments' : './data/get_payments',
			'get_results' : './data/get_results',
			'get_reports' : './data/get_reports',
		};

		const pageNames = {
			'view_users' : 'user information',
			'view_user' : 'user information',
			'view_client' : 'client information',
			'view_clients' : 'Client management',
			'client_info' : 'Client Summary',
			'view_profile' : 'User Profile',
			'view_users' : 'user management',
			'add_task' : 'Add task',
			'view_task' : 'Task Information',
			'view_tasks' : 'Task management',
			'task_info' : 'Task Summary',
			'gen_report' : 'Generate Report',
		}
		const variableAtlas = {
			'task_id' : `cur_task_id`,
			'client_id' : `cur_client_id`,
			'sample_id' : `cur_sample_id`,
			'task_info_act' : `task_info_next_act`,
			'task_info_act_args' : 'task_info_next_act_args',
		}

		// shortener ops
			variableAtlas['t_i_act'] = variableAtlas['task_info_act'];
			variableAtlas['t_i_args'] = variableAtlas['task_info_act_args'];

		let pref_prefix = 'jaymatt_MIS_';
		let prefsAtlas = {
			'last_saved_task' : `${pref_prefix}saved_task`,
		};

		/*
		const confirm_modal = document.querySelector('#confirmerBox');
		const confirm_toggler = document.querySelector('[data-role="toggleConfirmModal"]');
		const info_modal = document.querySelector('#dashmodal');
		const info_toggler = document.querySelector('[data-role="toggleDashModal"]');
		// */

		const fadein = [{opacity: 1},{opacity: 0}];

		menuItems = [
			{caption: "dashboard", url: './ui/console',icon: 'fa fa-th-large'},
			{caption: "User Roles", url: './ui/user_roles',icon: 'fa fa-user-lock'},
			{caption: "User Management", url: './ui/user_mgt',icon: 'fa fa-user-cog'},
			{caption: "View Profile", url: './ui/view_user',icon: 'fa fa-user-tie'},
			{caption: "client management", url: './ui/client_mgt',icon: 'fa fa-users'},
			{caption: "Task Management", url: './ui/task_mgt',icon: 'fa fa-folder'},
			{caption: "Add new task", url: './ui/add_task',icon: 'fa fa-folder-plus'},
			{caption: "Sample management", url: './ui/samples_mgt',icon: 'fa fa-flask'},
			{caption: "Payment management", url: './ui/payments_mgt',icon: 'fa fa-money-bill'},
			{caption: "test permissions", url: './ui/get_menu',icon: 'fa fa-code'},
			{caption: "view permissions", url: './my_permissions',icon: 'fa fa-code'},
			{caption: "am i admin", url: './am_i_admin',icon: 'fa fa-code'},
			{caption: "get roles", url: './data/user_roles',icon: 'fa fa-code'},
		];

		function setUI() {
			if(menubar == undefined){
				menubar = document.querySelector('#menubar');
			}

			if(maincon == undefined){maincon = document.querySelector('#dynamic_ui');}
			if(dynacode == undefined){dynacode = document.querySelector('#dynamic_js');}

			updateSessionUI();
		}

		function updateSessionUI(){
			let namedisplays = document.querySelectorAll('[data-role="user_name_display"]');

			if(namedisplays.length == 0){
				// alert_danger('no name displays found');
			}

			namedisplays.forEach(e => {
				e.innerHTML = cur_user.name || "??";
			})

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

		function initstuff(){
			const step1 = (w) => {
				// sets permissions
				permissions = w.sendme;

				window[fetch_bypass_fyls]('./data/my_permissions',{},'GET').then(d => {
					responseHandler(d,step2,d);
				}).catch(err => {
					dynamic_ui.innerHTML = 'error getting user permissions';
				})
			};

			const step2 = (w) => {
				// sets permissions
				mypermissions = w.sendme;

				window[fetch_bypass_fyls]('./data/cur_user',{},'GET').then(d => {
					responseHandler(d,step3,d);
				}).catch(err => {
					dynamic_ui.innerHTML = 'error getting user information';
				})
			};

			const step3 = (w) => {
				// sets current user
				window['cur_user'] = w.sendme;
				// mypermissions = w.sendme.role_permissions;

				// initmenu();
				step4();
				return;
				/*
				window[fetch_bypass_fyls]('./data/cur_user',{},'GET').then(d => {
					responseHandler(d,step4,d);
				})
				// */
			};

			const step4 = () => {
				window['bizdata'] = {
					pricing_data: {
						soil: 1000,
						rock: 1000,
						solution: 1500,
						carbon: 1500,
					}
				};

				initmenu();
			}

			window[fetch_bypass_fyls]('./data/allpermissions',{},'GET').then(d => {
				responseHandler(d,step1,d);
				// initmenu();
			})
		}

		function initmenu() {
			alert_info('loading menu items');

			setUI();
			if(menubar === undefined){alert_danger('menu unavailable');return;}

			themenu = menubar.querySelector('.menubar-menu');
			if(themenu === undefined){alert_danger('invalid menu setup');return;}

			themenu.innerHTML = '';
			let outht = '';

			menuItems.forEach(m => {
				outht += `
					<div>
						<a href="#" class="menulink" data-runme="menuact" data-template='${m.url}' data-sidenote="${m.caption}">
							<i class="${m.icon}"></i>
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
					ParseMyJS(maincon);
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

			window['loadPage'](passme);
		}

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
		}

		function sendform(m,callback) {
			if(m.dataset.submitting === true){
				alert_danger('already submitting...');
				return;
			} else {
				alert_info('submitting form...')
			}

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
				console.log('not a function')
				// alert_dark('not a function')
			}

			// return;

			// callback = callback == undefined ? (m.dataset.callback ? window[m.dataset.callback] : undefined) : callback;

			callback = typeof callback == 'function' ? callback : () => {alert_info('invalid callback passed to sendform')};

			m.style.pointerEvents = "none";
			m.style.opacity = 0.2;
			m.dataset.submitting = true;

			let fdata = getFormdata(m);
			let myres = undefined;
			let myaction = m.action;

			if(myaction.includes('defined:')){
				myaction = linkAtlas[myaction.split(':')[1]];
			}

			// add_edit_form.style.pointerEvents = "all";
			window[fetch_bypass_fyls](myaction,fdata,'POST',true,true).then(d => {
				// alert_danger('awkdhawkjdkawj')
				myres = d;

				responseHandler(d,callback,{'el': m,'data': d});

				// console.log("finally, the result is: ",myres);
				// callback(m);
			})
			.catch(err => {
				alert_danger(err.message);
			})
			.finally(() => {
				m.style.pointerEvents = "all";
				m.style.opacity = 1;
				m.dataset.submitting = false;
			})
		}

		function killghost(who) {
			// kills ghost processes and functions
			// NOTE: this shit is OP as fukari so use it sparingly
			delete(window[who]);
		}

		function compareUserData(udata) {
			let cur_u = cur_user;

			if(udata.id == cur_u.id){
				cur_user = udata;
				mypermissions = cur_user.role_permissions;
				updateSessionUI();
			}
		}

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

			window['confirmPasswordCallback']({admin_password: m.admin_password.value});
			killghost('confirmPasswordCallback');
		}

		window['password_confo_clearghost'] = () => {
			killghost('confirmPasswordCallback');
		}

		callOnDocLoad.push({act: initstuff});
	</script>

