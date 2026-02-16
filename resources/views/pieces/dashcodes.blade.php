<script>
	var menubar = undefined;
	let themenu = undefined;
	let menulinks = undefined;

	let maincon = undefined;
	let dynacode = undefined;
	let menuItems = [];
    let permissions = {};

    /*
	const confirm_modal = document.querySelector('#confirmerBox');
	const confirm_toggler = document.querySelector('[data-role="toggleConfirmModal"]');
	const info_modal = document.querySelector('#dashmodal');
	const info_toggler = document.querySelector('[data-role="toggleDashModal"]');
    // */

	const fadein = [{opacity: 1},{opacity: 0}];

	menuItems = [
		{caption: "dashboard", url: './ui/console',icon: 'fa fa-th-large'},
		{caption: "User Roles", url: './ui/user_roles',icon: 'fa fa-users'},
		{caption: "get users", url: './ui/datatables',icon: 'fa fa-th'},
		{caption: "test permissions", url: './ui/get_menu',icon: 'fa fa-list'},
		{caption: "view permissions", url: './my_permissions',icon: 'fa fa-user'},
		{caption: "am i admin", url: './am_i_admin',icon: 'fa fa-question'},
		{caption: "get roles", url: './data/user_roles',icon: 'fa fa-question'},
		{caption: "get users", url: './data/users',icon: 'fa fa-users'},
	]

	function setUI() {
		if(menubar == undefined){
			menubar = document.querySelector('#menubar');
		}

		if(maincon == undefined){maincon = document.querySelector('#dynamic_ui');}
		if(dynacode == undefined){dynacode = document.querySelector('#dynamic_js');}
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
        const cont = (w) => {
            permissions = w.sendme;
        };

        window[fetch_bypass_fyls]('./data/allpermissions',{},'GET').then(d => {
            responseHandler(d,cont,d);
            initmenu();
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

	window['menuact'] = (d) => {
		if(menulinks === undefined){
			setUI();
			if(menubar === undefined){alert_danger('menu unavailable');return;}
			themenu = menubar.querySelector('.menubar-menu');
			if(themenu === undefined){alert_danger('invalid menu setup');return;}
			menulinks = themenu.querySelectorAll('a');
		}

		activateSidebar(false);
		menulinks.forEach(m => {m.classList.remove('active')});
		d.classList.add('active');

		alert_info(`loading ${d.dataset.sidenote}`);

		let timing_fast = {duration: 400, easing: "ease-out",fill: "forwards"};
		maincon.animate([...fadein],{...timing_fast})
		curArea.innerHTML = 'loading...';

		window['toggleSidenav'](menubar_toggle_btn,true);

		setTimeout(() => {
			maincon.innerHTML = '';

			window[fetch_ui](d.dataset.template,{},'get').then(dt => {
				document.querySelector('#curArea').innerHTML = d.dataset.sidenote;

				maincon.innerHTML = dt;
				maincon.animate([...fadein].reverse(),{duration: 400, easing: "ease-out",fill: "forwards"});
				ParseMyJS(maincon);
				// activateSidebar(true);
				refreshUI(300);
			}).finally(() => {
				activateSidebar(true);
			})
		},400);
	}

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
				alert_dark('callback: ' + m.dataset.callback);
				callback = window[m.dataset.callback.trim()];
			} else {
				console.log('no fallback to the callback');
				alert_dark('no fallback to the callback');
			}
		} else {
			if(typeof callback != "function"){
				console.log('trying to read from a window variable');
				alert_dark('trying to read from a window variable');
				callback = window[callback];
			}
		}

		if(typeof callback == "function"){
			console.log('valid callback')
			alert_dark('valid callback')
		} else {
			console.log('not a function')
			alert_dark('not a function')
		}

		// return;

		// callback = callback == undefined ? (m.dataset.callback ? window[m.dataset.callback] : undefined) : callback;

		callback = typeof callback == 'function' ? callback : () => {alert_info('invalid callback passed to sendform')};

		m.style.pointerEvents = "none";
		m.style.opacity = 0.2;
		m.dataset.submitting = true;

		let fdata = getFormdata(m);
		let myres = undefined;
		// let cango = true;

		// add_edit_form.style.pointerEvents = "all";
		window[fetch_bypass_fyls](m.action,fdata,'POST',true,true).then(d => {
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

	callOnDocLoad.push({act: initstuff});
</script>
