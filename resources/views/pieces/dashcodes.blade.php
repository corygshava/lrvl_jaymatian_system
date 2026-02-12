<script>
	var menubar = undefined;
	let themenu = undefined;
	let menulinks = undefined;

	let maincon = undefined;
	let dynacode = undefined;
	var menuItems = [];

	const fadein = [{opacity: 1},{opacity: 0}];

	menuItems = [
		{caption: "dashboard", url: './ui_view/console',icon: 'fa fa-th-large'},
		{caption: "User Roles", url: './ui_view/user_roles',icon: 'fa fa-users'},
		{caption: "test permissions", url: './get_menu',icon: 'fa fa-list'},
		{caption: "view permissions", url: './my_permissions',icon: 'fa fa-user'},
	]

	/*
		menuItems = [
			{caption: "dashboard",url: './pieces/dash',icon: 'fas fa-th-large'},
			{caption: "admin dashboard",url: './pieces/admin_dash',icon: 'fas fa-th-large'},
			{caption: "login",url: './pieces/login',icon: 'fa fa-cog'},
			{caption: "client",url: './pieces/empty',icon: 'fa fa-user'},
		];
	// */

	function setUI() {
		if(menubar == undefined){
			menubar = document.querySelector('#menubar');
		}

		if(maincon == undefined){maincon = document.querySelector('#dynamic_ui');}
		if(dynacode == undefined){dynacode = document.querySelector('#dynamic_js');}
	}

	window['toggleSidenav'] = (me) => {
		setUI();

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

	window['menuact'] = (d) => {
		if(menulinks === undefined){
			setUI();
			if(menubar === undefined){alert_danger('menu unavailable');return;}
			themenu = menubar.querySelector('.menubar-menu');
			if(themenu === undefined){alert_danger('invalid menu setup');return;}
			menulinks = themenu.querySelectorAll('a');
		}

		menulinks.forEach(m => {m.classList.remove('active')});
		d.classList.add('active');

		alert_info(`loading ${d.dataset.sidenote}`);

		maincon.animate([...fadein],{duration: 400, easing: "ease-out",fill: "forwards"})
		curArea.innerHTML = 'loading...';

		setTimeout(() => {
			maincon.innerHTML = '';

			window[fetch_ui](d.dataset.template,{},'get').then(dt => {
				document.querySelector('#curArea').innerHTML = d.dataset.sidenote;

				maincon.innerHTML = dt;
				maincon.animate([...fadein].reverse(),{duration: 400, easing: "ease-out",fill: "forwards"});
				ParseMyJS(maincon);
				refreshUI(300);

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
	}

	callOnDocLoad.push({act: initmenu});
</script>
